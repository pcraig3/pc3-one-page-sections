<?php
/**
 * Provide an interface with WP_Query so as to query the WP database in various ways
 *
 * @since      0.7.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/lib
 */
class Lib_PC3WPQueryFacade {

    private $sSectionSlug;
    private $sSectionMetaKey;

    function __construct( $sSectionSlug, $sSectionMetaKey ) {

        $this->sSectionSlug = $sSectionSlug;
        $this->sSectionMetaKey = $sSectionMetaKey;
    }

    /**
     * Function returns one sections by post.ID or post.post_title or post.post_name
     *
     * In case of a collision between ID, post_title, and post_names:
     * - matching post_name is returned first
     * - matching post_title is returned second
     * - matching post_id is returned last
     *
     * Probably this is an unlikely practical scenario, however
     *
     * @see: http://wordpress.stackexchange.com/questions/18703/wp-query-with-post-title-like-something
     *
     * @since    0.8.0
     *
     * @param string $_sSectionTitleOrID    a post ID or name or title
     *
     * @return array                        an array with one post if one is matched, else an empty array
     */
    public function getSectionByTitleOrID( $_sSectionTitleOrID ) {

        $args = array(
            'post_type' => $this->sSectionSlug,
            'posts_per_page' => 1,
            'pc3_section__post_title' => $_sSectionTitleOrID,
            'pc3_section__post_name' => $_sSectionTitleOrID,
            'post_status' => 'any',
        );

        //if numeric, check postID AND title
        if( is_numeric( $_sSectionTitleOrID ) )
            $args['pc3_section__ID'] = $_sSectionTitleOrID;

        add_filter( 'posts_where', array( $this, 'whereClauseFilter'), 10, 2 );
        $wp_query = new WP_Query($args);
        remove_filter( 'posts_where', array( $this, 'whereClauseFilter'), 10, 2 );

        return $wp_query->posts;
    }

    /**
     * Function modifies the WHERE clause of WP_Query so that we can return one
     * Section by post.ID or post.post_title or post.post_name
     *
     * @see: http://wordpress.stackexchange.com/questions/18703/wp-query-with-post-title-like-something
     *
     * @since    0.8.0
     *
     * @param string $where     where clause for wp_query before execution
     * @param object $wp_query  object executes queries on WP database
     *
     * @return string           modified or original where clause
     */
    public function whereClauseFilter($where, &$wp_query){

        $aSectionQueryKeys = array(
            'pc3_section__ID',
            'pc3_section__post_title',
            'pc3_section__post_name'
        );

        $aWhereClauseExtensions = array();

        foreach( $aSectionQueryKeys as $sQueryKey )
            //if a value has been assigned to our custom key (ie, in the getSectionByTitleOrID method above)
            if( $sValue = $wp_query->get( $sQueryKey ) )
                //either returns a where clause or an empty string
                if( $sWhereClause = $this->returnWhereClauseExtension( $sQueryKey, $sValue ) )
                    array_push( $aWhereClauseExtensions, $sWhereClause );

        //extend where clause if $aWhereClauseExtensions is not empty
        if( ! empty( $aWhereClauseExtensions ) )
            $where .= ' AND ( ' . implode( ' OR ', $aWhereClauseExtensions ) . ' ) ';

        return $where;
    }

    /**
     * Function takes a custom-set WP_Query array key and the value returned by the key, and
     * returns a string we can use to extend our WHERE clause.
     * If either parameter is empty, function returns an empty string.
     * If $_sQueryKey isn't formatted {post_type}__{post_attr}, function either returns empty string
     * or a meaningless where clause extension
     *
     * @since    0.8.0
     *
     * @param string $_sQueryKey    a custom WP_Query array key, formatted {post_type}__{post_attr}
     * @param string $_sValue       value assigned to WQ_Query[$_sQueryKey]
     *
     * @return string               an properly formatted extension of the WHERE clause
     */
    private function returnWhereClauseExtension( $_sQueryKey, $_sValue ) {

        global $wpdb;

        //if either of the passed-in values are empty strings, then exit
        if( $_sQueryKey === '' or $_sValue === '' )
            return '';

        $_aQueryKey = explode('__', $_sQueryKey);

        //if passed_in $_sSectionQueryKey isn't formatted like 'pc3_section__ID', then exit
        if( count( $_aQueryKey ) < 2 )
            return '';

        $_sPostAttribute = array_pop( $_aQueryKey );

        $_sValue = esc_sql( $_sValue );
        $_sValue = ' \'' . $_sValue . '\'';

        return $wpdb->posts . '.' . $_sPostAttribute  . ' = '. $_sValue;
    }

    /**
     * Determines if a post, identified by the specified ID, exists
     * within the WordPress database.
     **
     * @see https://tommcfarlin.com/wordpress-post-exists-by-id/
     *
     * @since    0.7.0
     *
     * @param    int    $_iPostID   The ID of the post to check
     * @return   bool               True if the post exists; otherwise, false.
     */
    public function isPostExists( $_iPostID ) {

        return is_string( get_post_status( $_iPostID ) );
    }

    /**
     * Function returns array of Sections by meta_key 'order' from least to greatest.
     *
     * @since    0.8.0
     *
     * @return array    array of Sections
     */
    public function getSectionsByOrderASC() {

        return $this->getPosts( array(
            'post_type' => $this->sSectionSlug,
            'orderby'   => 'meta_value_num',
            'meta_key'  => $this->sSectionMetaKey,
            'order'     => 'ASC',
            'post_status' => 'any',
            'posts_per_page' => -1
        ));
    }

    /**
     * Function returns Section with highest numeric 'order' value is returned.
     * Section is returned in an array
     *
     * @since    0.8.0
     *
     * @return array    array with one Section
     */
    public function getSectionWithLargestOrder() {

        return $this->getPosts( array(
            'post_type' => $this->sSectionSlug,
            'orderby'   => 'meta_value_num',
            'meta_key'  => $this->sSectionMetaKey,
            'order'     => 'DESC',
            'post_status' => 'any',
            'posts_per_page' => 1
        ) );
    }

    /**
     * Returns posts from WQ_Query.
     * Takes as a parameter an array of arguments which override the default arguments
     *
     * @since    0.7.0
     *
     * @param    array $_aUserArgs  User-specified arguments which override the default arguments
     * @return   array              Posts returned by our query
     */
    public function getPosts( array $_aUserArgs ) {

        $_iDefaultPostsPerPage = get_option( 'posts_per_page' );

        if( ! $_iDefaultPostsPerPage )
            $_iDefaultPostsPerPage = 6;

        $_aDefaultArgs = array(
            'posts_per_page'   => $_iDefaultPostsPerPage,
            'offset'           => 0,
            'category'         => '',
            'category_name'    => '',
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'include'          => '',
            'exclude'          => '',
            'meta_key'         => '',
            'meta_value'       => '',
            'post_type'        => 'post',
            'post_mime_type'   => '',
            'post_parent'      => '',
            'post_status'      => 'publish',
            'suppress_filters' => true
        );

        $_oResults = new WP_Query( array_merge( $_aDefaultArgs, $_aUserArgs ) );

        return $_oResults->posts;
    }
}