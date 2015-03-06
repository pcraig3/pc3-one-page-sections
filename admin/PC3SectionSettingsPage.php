<?php
/**
 * The class that builds the Settings page.
 *
 * Settings page allows user to do a debug flag and also remove/include libraries
 *
 * @since      0.9.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Admin_PC3SectionSettingsPage extends Lib_PC3AdminPage
{
    /**
     * The set-up method which is triggered automatically with the 'wp_loaded' hook.
     *
     * Here we define the setup() method to set how many pages, page titles and icons etc.
     *
     * @since      0.9.0
     */
    public function setUp()
    {
        // Create the root menu - specifies to which parent menu to add.
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
     * One of the pre-defined methods which is triggered when the page contents is going to be rendered.
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
}