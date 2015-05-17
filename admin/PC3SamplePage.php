<?php

class Admin_PC3SamplePage extends PC3_AdminPageFramework
{
    /**
     * The slug used to uniquely identify this page, both in the code and in the URL
     *
     * @since   0.7.0
     * @var     string
     */
    protected $sPageSlug;

    /**
     * An array of tabs and their contained fields to be added to this page.
     *
     * @since   0.9.0
     * @var     array
     */
    protected $aTabs;

    /**
     * In 'Debug' mode, this page prints to screen its settings
     *
     * @since   0.9.0
     * @var     boolean
     */
    protected $bDebug;

    protected $container;

    /**
     * @since   0.9.0
     *
     * @param string $sPageSlug             The slug used to uniquely identify this page, both in the code and in the URL
     * @param array $aTabs                  Tabs (containing setting fields) for our admn page
     * @param int $iDebug                   Debug flag
     */
    public function __construct($sPageSlug, array $aTabs, $iDebug = 0, $container ) {

        //string $sOptionKey = null, string $sCallerPath = null, string $sCapability = 'manage_options', string $sTextDomain = 'admin-page-framework'
        parent::__construct(
            null,
            null,
            'manage_options',
            'admin-page-framework'
        );

        $this->sPageSlug = $sPageSlug;
        $this->aTabs = $aTabs;

        //set to 'true' if $iDebug is not zero.
        $this->bDebug = intval( $iDebug ) !== 0;

        $this->container = $container;
    }

    /**
     * Adding the form fields from the `aSettingFields` array
     *
     * @since      0.9.0
     */
    protected function add_setting_fields()
    {
        if( ! empty( $this->aTabs ) )
            foreach( $this->aTabs as $oTab ) {
                    $this->addInPageTabs(
                    $this->sPageSlug,
                    $oTab->setUpTab()
                );

                if (isset($_GET['tab']) && $oTab->getTabID() === $_GET['tab']) {

                    $tabSettingFields = $oTab->getSettingFields();

                    if( count( $tabSettingFields ) > 0 )
                        foreach( $tabSettingFields as $oSettingField )
                            $this->addSettingField(
                                $oSettingField->setUpField()
                            );
                }
            }

        $this->setPageHeadingTabsVisibility( false, $this->sPageSlug );    // disables the page heading tabs by passing false.
        $this->setInPageTabTag( 'h2' );        // sets the tag used for in-page tabs
    }

    /**
     * Prints information about this page's keys and values to the screen.
     *
     * @since      0.9.0
     */
    protected function print_debug_information_to_screen()
    {
        // Show the saved option value.
        echo '<h3>Show all the options as an array</h3>';
        echo $this->oDebug->getArray( PC3_AdminPageFramework::getOption( get_class( $this ) ) );
    }

    protected function is_current_tab_has_setting_fields()
    {
        if( ! empty( $this->aTabs ) )
            foreach( $this->aTabs as $oTab )
                if (isset($_GET['tab']) && $oTab->getTabID() === $_GET['tab'])
                    if( count( $oTab->getSettingFields() ) > 0 )
                        return true;

        return false;
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
            $this->setRootMenuPageBySlug( 'edit.php?post_type=' . $this->sPageSlug );
        else
            $this->setRootMenuPage('Settings');

        // Add the sub menus and the pages.
        //parameters: https://github.com/michaeluno/admin-page-framework/blob/c85cdc9051eeef09efa8668fd79c2f9d74999fce/development/factory/AdminPageFramework/AdminPageFramework_Menu_Controller.php
        $this->addSubMenuItems(
            array(
                'title'         => 'My Tabs',        // page title
                'page_slug'     => $this->sPageSlug,    // page slug
            )
        );
    }

    public function load_my_tabs($oAdminPage)
    {
        $this->add_setting_fields();
    }

    /**
     * One of the predefined callback method.
     *
     * @remark      content_{page slug}
     */
    public function content_my_tabs( $sContent ) {

        if ( $this->is_current_tab_has_setting_fields() )
            $sContent .= get_submit_button();

        $sContent .= '<h3>Page Content Filter</h3>'
        . '<p>This is inserted by the the page <em>content_</em> filter, set in the <b><i>\'content_ + page slug\'</i></b> method.</p>';

        if (isset($_GET['tab'])) {
            $sContent .= '<h4>Page: ' . $_GET['page'] . '</h4>';
            $sContent .= '<h4>Tab: ' . $_GET['tab'] . '</h4>';
        }

        return $sContent;
    }

    /**
     * One of the predefined callback method.
     *
     * @remark      content_{page slug}_{tab slug}
     */
    public function content_my_tabs_third_tab( $sContent ) {
        $sContent .= '<h3>Variables</h3>';

        $sContent .= '<h4>new editor: ' . $this->container->getParameter('field__new_editor') . '</h4>';
        $sContent .= '<h4>new binary: ' . $this->container->getParameter('field__binary') . '</h4>';

        return $sContent;
    }
}