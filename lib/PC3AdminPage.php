<?php
/**
 * abstract AdminPage class.  Other AdminPage classes should extend this one.
 *
 * @since      0.9.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/lib
 * @author     Paul Craig <paul@pcraig3.ca>
 */
abstract class Lib_PC3AdminPage extends PC3_AdminPageFramework
{
    /**
     * The slug used to uniquely identify this page, both in the code and in the URL
     *
     * @since   0.7.0
     * @var     string
     */
    protected $sPageSlug;

    /**
     * An array of tabs (containing setting fields) to be added to this page.
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

    /**
     * @since   0.9.0
     *
     * @param string $sPageSlug             The slug used to uniquely identify this page, both in the code and in the URL
     * @param array $aTabs         Tabs (containing setting fields) for our admin page
     * @param int $iDebug                   Debug flag
     */
    public function __construct( $sPageSlug, array $aTabs, $iDebug = 0 ) {

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
    }

    /**
     * Adding the tabs in turn, and then each of their form fields
     * contained in their respective `aSettingFields` arrays
     *
     * @since      0.9.0
     */
    protected function add_setting_fields()
    {

        if( count( $this->aTabs ) > 0 )
            foreach( $this->aTabs as $oTab )
                $this->addInPageTabs(
                    $this->sPageSlug,
                    $oTab->setUpTab()
                );

        if (isset($_GET['page']) && $this->sPageSlug === $_GET['page']) {

            $current_tab = $this->get_current_tab();

            if( ! is_null( $current_tab ) )
                $this->add_setting_fields_from_tab($current_tab);

            else
                $this->add_setting_fields_from_tab($this->aTabs[0]);
        }

        $this->setPageHeadingTabsVisibility( false, $this->sPageSlug );    // disables the page heading tabs by passing false.
        $this->setInPageTabTag( 'h2' );        // sets the tag used for in-page tabs
    }

    private function add_setting_fields_from_tab( $oTab ) {

        $tabSettingFields = $oTab->getSettingFields();

        if (count($tabSettingFields) > 0)
            foreach ($tabSettingFields as $oSettingField)
                $this->addSettingField( $oSettingField->setUpField() );
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

    /**
     * Check if the current tab we're on has a non-empty array of setting fields.
     *
     * @return bool
     */
    protected function get_current_tab()
    {
        if (isset($_GET['page']) && $this->sPageSlug === $_GET['page']) {

            if( count( $this->aTabs ) > 0 )
                foreach( $this->aTabs as $oTab )
                    if (isset($_GET['tab']) && $oTab->getTabID() === $_GET['tab'])
                        return $oTab;

            return $this->aTabs[0];
        }

        return null;
    }
}