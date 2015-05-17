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
class Admin_PC3SectionManagerPage extends Lib_PC3AdminPage
{
    /**
     * The slug for our page
     *
     * @since   0.9.0
     * @var     string
     */
    protected $sSectionSlug;

    /**
     * @since   0.9.3
     *
     * @param string $sPageSlug         The slug used to uniquely identify this page, both in the code and in the URL
     * @param array $aTabs              Tabs (containing Setting fields) for our admin page
     * @param int $iDebug               Debug flag
     * @param string $sSectionSlug      The slug for our custom post type (Sections)
     */
    public function __construct($sPageSlug, array $aTabs = array(), $iDebug = 0,
                                $sSectionSlug ) {

        parent::__construct($sPageSlug, $aTabs, $iDebug);

        $this->sSectionSlug = $sSectionSlug;
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
        //parameters: https://github.com/michaeluno/admin-page-framework/blob/c85cdc9051eeef09efa8668fd79c2f9d74999fce/development/factory/AdminPageFramework/AdminPageFramework_Menu_Controller.php
        $this->addSubMenuItems(
            array(
                'title' => 'One Page Sections Settings',  // page and menu title
                'page_slug' => $this->sPageSlug,     // page slug
                'order' => 5
            )
        );
    }

    /**
     * One of the pre-defined methods which is triggered when the registered page loads.
     * ie, load_{page slug}
     *
     * Here a method in the parent class will add our form fields
     *
     * @since      0.9.0
     */
    public function load_pc3_settings( $oAdminPage )
    {
       $this->add_setting_fields();
    }

    /**
     * One of the pre-defined methods which is triggered when the page contents are going to be rendered.
     *
     * ie, do_{page slug}
     *
     * @since      0.9.0
     */
    public function do_pc3_settings()
    {
        if( $this->bDebug )
            $this->print_debug_information_to_screen();
    }

    /**
     * One of the pre-defined methods allowing us to add to / edit page contents.
     *
     * ie, content_{page slug}
     *
     * @since      0.9.3
     */
    public function content_pc3_settings( $sContent ) {

        $current_tab = $this->get_current_tab();

        //if the current tab has setting fields, add a submit button to the page
        if ( count( $current_tab->getSettingFields() ) > 0 )
            return $sContent . get_submit_button();

        return $sContent;
    }
}