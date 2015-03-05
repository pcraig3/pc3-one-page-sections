<?php
/**
 * The file that builds the Manage Sections page
 *
 * Makes no assumptions about Sections.  In fact, assumes that there won't be any.
 * If sections are found, Admin_PC3SectionManagerPage_Callbacks populates the form created on this page with information
 * about Sections.
 *
 * By default, form displays error message and alerts users that they need to create sections
 *
 * @since      0.3.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Admin_PC3SectionManagerPage extends PC3_AdminPageFramework
{
    /**
     * The slug used to uniquely identify this page, both in the code and in the URL
     *
     * @since   0.7.0
     * @var     string
     */
    private $sPageSlug;

    /**
     * The slug for our custom post type (Sections)
     *
     * @since   0.7.0
     * @var     string
     */
    private $sSectionSlug;

    /**
     * @since      0.7.0
     *
     * @var string Field id for the select drop-down list in our form
     */
    public $sSelectFieldId = '__sections_page';

    /**
     * @since      0.3.0
     *
     * @var string Field id for the sortable sections in our form
     */
    public $sSortableFieldId = '__sections';

    /**
     * @since      0.8.2
     *
     * @var string Field id for the editor field in our form
     */
    public $sEditorFieldId = '__editor';

    /**
     * @since      0.3.0
     *
     * @var string Field id for the submit button in our form
     */
    public $sSubmitFieldId = '__submit';

    /**
     * An array of setting fields to be added to this page.
     *
     * @since   0.9.0
     * @var     array
     */
    private $aSettingFields;

    private $bDebug;

    /**
     * @since   0.7.0
     *
     * @param string $sPageSlug         The slug used to uniquely identify this page, both in the code and in the URL
     * @param string $sSectionSlug      The slug for our custom post type (Sections)
     * @param string $sSelectFieldId    Field id for the select box in our form
     * @param string $sSortableFieldId  Field id for the sortable sections in our form
     * @param string $sEditorFieldId    Field id for the (CSS) editor field in our form
     * @param string $sSubmitFieldId    Field id for the submit button in our form
     * @param array $aSettingFields         Setting fields for our admin page
     * @param int $iDebug               Debug flag
     */
    public function __construct($sPageSlug, $sSectionSlug,
                                $sSelectFieldId='', $sSortableFieldId='', $sEditorFieldId='', $sSubmitFieldId='',
                                array $aSettingFields = array(), $iDebug = 0 ) {

        //string $sOptionKey = null, string $sCallerPath = null, string $sCapability = 'manage_options', string $sTextDomain = 'admin-page-framework'
        parent::__construct(
            null,
            null,
            'manage_options',
            'admin-page-framework'
        );

        $this->sPageSlug = $sPageSlug;
        $this->sSectionSlug = $sSectionSlug;

        $this->aSettingFields = $aSettingFields;

        //set to 'true' if $iDebug is not zero.
        $this->bDebug = intval( $iDebug ) !== 0;

        //@TODO this is a pretty ugly solution
        $this->sSelectFieldId = $sSelectFieldId ? $sSelectFieldId : $this->sSelectFieldId;
        $this->sSortableFieldId    = $sSortableFieldId ? $sSortableFieldId : $this->sSortableFieldId;
        $this->sEditorFieldId    = $sEditorFieldId ? $sEditorFieldId : $this->sEditorFieldId;
        $this->sSubmitFieldId    = $sSubmitFieldId ? $sSubmitFieldId : $this->sSubmitFieldId;
    }

    /**
     * The set-up method which is triggered automatically with the 'wp_loaded' hook.
     *
     * Here we define the setup() method to set how many pages, page titles and icons etc.
     *
     * @since      0.8.0
     */
    public function setUp()
    {
        // Create the root menu - specifies to which parent menu to add.
        if ( post_type_exists( $this->sSectionSlug ) )
            $this->setRootMenuPageBySlug( 'edit.php?post_type=' . $this->sSectionSlug );
        else
            $this->setRootMenuPage('Settings');

        // Add the sub menus and the pages.
        /*
        parameters: https://github.com/michaeluno/admin-page-framework/blob/c85cdc9051eeef09efa8668fd79c2f9d74999fce/development/factory/AdminPageFramework/AdminPageFramework_Menu_Controller.php
        */
        $this->addSubMenuItems(
            array(
                'title' => 'Manage Sections',  // page and menu title
                'page_slug' => $this->sPageSlug,     // page slug
                'order' => 5
            )
        );
    }

    /**
     * One of the pre-defined methods which is triggered when the registered page loads.
     * ie, load_{page slug}
     *
     * Here we add form fields.
     *
     * @since      0.8.0
     */
    public function load_manage_sections($oAdminPage)
    {
        $this->registerFieldTypes();

        $this->addSettingFields(
            array( // Single Drop-down List
                'field_id'      => $this->sPageSlug . '__sections_page',
                'title'         => __( 'One Page Sections Page', 'one-page-sections' ),
                'type'          => 'select',
                'label'         => array(
                    0 => __( '---', 'one-page-sections' ),
                ),
                'description' => __( 'This select field should be filled with the names of pages from your site.',
                        'one-page-sections' )
                    . ' ' . __( 'Please create at least one Page.', 'one-page-sections' ),
            ),
            array(
                'field_id'          => $this->sPageSlug . $this->sSortableFieldId,
                'title'             => __( 'Section Titles', 'one-page-sections' ),
                'type'              => 'hidden',
                'default'           => '',
                // 'hidden' =>    true // <-- the field row can be hidden with this option.
                'label'             =>
                    __( 'Sorry, but I couldn\'t find any sections.  <br>:(', 'one-page-sections' ),
                'description'       => __( 'Maybe try <a href="/wp-admin/post-new.php?post_type=pc3_section">adding a Section</a>?', 'one-page-sections' )
            ),
            array(  // Ace Custom Field
                'field_id'          => $this->sPageSlug . '__editor',
                'title'             => __('CSS Editor', 'one-page-sections' ),
                'description'       => __('Custom CSS goes here.', 'one-page-sections' ),
                'type'              => 'ace',
                //'default'           => '.abc { color: #fff; }',
                //'repeatable'        => true,
                // The attributes below are the defaults, i.e. if you want theses you don't have to set them
                'attributes' =>  array(
                    'cols'          =>  96,
                    'rows'          =>  14,
                ),
                // The options below are the  defaults, i.e. if you want theses you don't have to set them
                'options'    => array(
                    'language'      => 'css', // available languages https://github.com/ajaxorg/ace/tree/master/lib/ace/mode
                    'theme'         => 'dreamweaver', //available themes https://github.com/ajaxorg/ace/tree/master/lib/ace/theme
                    'gutter'        => true,
                    'readonly'      => false,
                    'fontsize'      => 14,
                )
            )
        );

        if( ! empty( $this->aSettingFields ) )
            foreach( $this->aSettingFields as $oSettingField )
                $this->addSettingField(
                    $oSettingField->setUpField()
                );

    }

    /**
     * Register custom field types.
     *
     * @since      0.8.0
     */
    private function registerFieldTypes() {

        if ( ! class_exists('AceCustomFieldType') )
            require_once ONE_PAGE_SECTIONS_DIR_PATH . 'vendor/AceCustomFieldType/AceCustomFieldType.php';

        new AceCustomFieldType();
    }

    /**
     * One of the pre-defined methods which is triggered when the page contents is going to be rendered.
     *
     * ie, do_{page slug}
     *
     * @since      0.8.0
     */
    public function do_manage_sections()
    {
        if( $this->bDebug ) {
            // Show the saved option value.
            echo '<h3>Show all the options as an array</h3>';
            echo $this->oDebug->getArray(PC3_AdminPageFramework::getOption(get_class($this)));
        }
    }
}