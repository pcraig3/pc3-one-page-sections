<?php
/**
 * The file that builds the Manage Sections page
 *
 * Makes no assumptions about Sections.  In fact, assumes that there won't be any.
 * If sections are found, PC3_SectionManagerPage_Callbacks populates the form created on this page with information
 * about Sections.
 *
 * By default, form displays error message and alerts users that they need to create sections
 *
 * @since      0.3.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/includes
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class PC3_SectionManagerPage extends PC3_AdminPageFramework
{
    /**
     * The slug used to uniquely identify this page, both in the code and in the URL
     *
     * @since   0.7.0
     * @var     string
     */
    private $sPageSlug = 'manage_sections';

    /**
     * The slug for our custom post type (Sections)
     *
     * @since   0.7.0
     * @var     string
     */
    private $sPostTypeSlug = 'pc3_section';

    /**
     * The field_id for our sortable field (hopefully to be filled with Sections).
     *
     * @since   0.7.0
     * @var     string
     */
    private $sSortableFieldId = 'manage_sections__sections';

    /**
     * The meta key name to keep track of the order in which our sortable fields should be rendered
     *
     * @since   0.7.0
     * @var     string
     */
    private $sMetaKey = 'order';

    /**
     * The name of this class
     *
     * @since   0.7.0
     * @var     string
     */
    private $sPageClass;

    /**
     * @since   0.7.0
     *
     * @param string $sPageSlug         The slug used to uniquely identify this page, both in the code and in the URL
     * @param string $sPostTypeSlug     The slug for our custom post type (Sections)
     * @param string $sSortableFieldId  The field_id for our sortable field
     * @param string $sMetaKey          The meta key name to keep track of the order in which our sortable fields should be rendered
     */
    function __construct($sPageSlug='', $sPostTypeSlug='', $sSortableFieldId='', $sMetaKey='') {

        //string $sOptionKey = null, string $sCallerPath = null, string $sCapability = 'manage_options', string $sTextDomain = 'admin-page-framework'
        parent::__construct(
            null,
            null,
            'manage_options',
            'admin-page-framework'
        );

        $this->sPageSlug        = $sPageSlug ? $sPageSlug : $this->sPageSlug;
        $this->sPostTypeSlug    = $sPostTypeSlug ? $sPostTypeSlug : $this->sPostTypeSlug;
        $this->sSortableFieldId = $sSortableFieldId ? $sSortableFieldId : $this->sSortableFieldId;
        $this->sMetaKey         = $sMetaKey ? $sMetaKey : $this->sMetaKey;

        $this->sPageClass       = get_class($this);
    }

    /**
     * The set-up method which is triggered automatically with the 'wp_loaded' hook.
     *
     * Here we define the setup() method to set how many pages, page titles and icons etc.
     *
     * @since      0.7.0
     */
    public function setUp()
    {

        // Create the root menu - specifies to which parent menu to add.

        //wish there was some way to make this a global variable
        //@var pc3_section
        if (post_type_exists( $this->sPostTypeSlug ))
            $this->setRootMenuPageBySlug( 'edit.php?post_type=' . $this->sPostTypeSlug );
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

        new PC3_SectionManagerPage_Callbacks(
            $this->sPageClass,
            $this->sPageSlug,
            $this->sPageSlug . $this->sSortableFieldId,
            $this->sPageSlug . '__submit'
        );

        //($sPostTypeSlug='', $sPageClass='', $sSortableFieldId='', $sMetaKey='') {
        new PC3_SectionPostType_MetaLayer(
            $this->sPostTypeSlug,
            $this->sPageClass,
            $this->sPageSlug . $this->sSortableFieldId,
            $this->sMetaKey
        );
    }

    /**
     * One of the pre-defined methods which is triggered when the registered page loads.
     * ie, load_{page slug}
     *
     * Here we add form fields.
     *
     * @since      0.7.0
     */
    public function load_manage_sections($oAdminPage)
    {

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
            array( // Submit button
                'field_id'      => $this->sPageSlug . '__submit',
                'type'          => 'submit',
                'attributes'    => array(
                    'disabled'  => 'disabled',
                    'class'     => 'button'
                )
            )
        );
    }

    /**
     * One of the pre-defined methods which is triggered when the page contents is going to be rendered.
     *
     * ie, do_{page slug}
     *
     * @since      0.3.0
     */
    public function do_manage_sections()
    {
        // Show the saved option value.
        // The extended class name is used as the option key. This can be changed by passing a custom string to the constructor.
        echo '<h3>Saved Fields</h3>';
        //echo '<pre>callback_example: ' . PC3_AdminPageFramework::getOption('PC3_SectionManagerPage', 'callback_example', 'default value') . '</pre>';
        echo '<pre>Whole thing: ';
        var_dump(PC3_AdminPageFramework::getOption('PC3_SectionManagerPage'));
        echo '</pre>';

        echo '<h3>Show all the options as an array</h3>';
        echo $this->oDebug->getArray(PC3_AdminPageFramework::getOption('PC3_SectionManagerPage'));
    }
}