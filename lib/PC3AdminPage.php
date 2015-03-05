<?php
/**
 * Generalized AdminPage class.  Other AdminPage classes should extend this one.
 *
 * @since      0.9.0
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/lib
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Lib_PC3AdminPage extends PC3_AdminPageFramework
{
    /**
     * The slug used to uniquely identify this page, both in the code and in the URL
     *
     * @since   0.7.0
     * @var     string
     */
    protected $sPageSlug;

    /**
     * An array of setting fields to be added to this page.
     *
     * @since   0.9.0
     * @var     array
     */
    protected $aSettingFields;

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
     * @param array $aSettingFields         Setting fields for our admin page
     * @param int $iDebug                   Debug flag
     */
    public function __construct($sPageSlug, array $aSettingFields, $iDebug = 0 ) {

        //string $sOptionKey = null, string $sCallerPath = null, string $sCapability = 'manage_options', string $sTextDomain = 'admin-page-framework'
        parent::__construct(
            null,
            null,
            'manage_options',
            'admin-page-framework'
        );

        $this->sPageSlug = $sPageSlug;
        $this->aSettingFields = $aSettingFields;

        //set to 'true' if $iDebug is not zero.
        $this->bDebug = intval( $iDebug ) !== 0;
    }

    /**
     * Adding the form fields from the `aSettingFields` array
     *
     * @since      0.9.0
     */
    protected function add_setting_fields()
    {

        if( ! empty( $this->aSettingFields ) )
            foreach( $this->aSettingFields as $oSettingField )
                $this->addSettingField(
                    $oSettingField->setUpField()
                );
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
}