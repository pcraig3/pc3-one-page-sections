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
class Admin_PC3SectionSettingsPage extends PC3_AdminPageFramework
{
    /**
     * The slug used to uniquely identify this page, both in the code and in the URL
     *
     * @since   0.7.0
     * @var     string
     */
    private $sPageSlug;

    /**
     * @since      0.7.0
     *
     * @var string Field id for the debug radio buttons in our form
     */
    public $sDebugFieldId = '__debug';

    /**
     * @since      0.3.0
     *
     * @var string Field id for the submit button in our form
     */
    public $sSubmitFieldId = '__submit';

    private $bDebug;

    /**
     * @since   0.7.0
     *
     * @param string $sPageSlug         The slug used to uniquely identify this page, both in the code and in the URL
     * @param string $sDebugFieldId     Field id for the debug radio buttons in our form
     * @param string $sSubmitFieldId    Field id for the submit button in our form
     * @param int $iDebug               Debug flag
     */
    public function __construct($sPageSlug,
                                $sDebugFieldId='', $sSubmitFieldId='' ,
                                $iDebug = 0 ) {

        //string $sOptionKey = null, string $sCallerPath = null, string $sCapability = 'manage_options', string $sTextDomain = 'admin-page-framework'
        parent::__construct(
            null,
            null,
            'manage_options',
            'admin-page-framework'
        );

        $this->sPageSlug = $sPageSlug;

        //set to 'true' if $iDebug is not zero.
        $this->bDebug = intval( $iDebug ) !== 0;

        //@TODO this is a pretty ugly solution
        $this->sDebugFieldId    = $sDebugFieldId ? $sDebugFieldId : $this->sDebugFieldId;
        $this->sSubmitFieldId   = $sSubmitFieldId ? $sSubmitFieldId : $this->sSubmitFieldId;
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
        $this->setRootMenuPage('Settings');

        // Add the sub menus and the pages.
        /*
        parameters: https://github.com/michaeluno/admin-page-framework/blob/c85cdc9051eeef09efa8668fd79c2f9d74999fce/development/factory/AdminPageFramework/AdminPageFramework_Menu_Controller.php
        */
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
     * Here we add form fields.
     *
     * @since      0.8.0
     */
    public function load_pc3_settings($oAdminPage)
    {

        $this->addSettingFields(
            array( // Repeatable radio buttons
                'field_id'      => $this->sPageSlug . $this->sDebugFieldId,
                'title'         => __( 'Debug Flag', 'one-page-sections' ),
                'type'          => 'radio',
                'label'         => array(
                    0 => 'No',
                    1 => 'Yes'
                ),
                'default' => 0, // set the key of the label array
            ),
            array( // Submit button
                'field_id'      => $this->sPageSlug . '__submit',
                'type'          => 'submit',
                'attributes'    => array(
                    'class'     => 'button button-primary'
                )
            )
        );
    }

    /**
     * One of the pre-defined methods which is triggered when the page contents is going to be rendered.
     *
     * ie, do_{page slug}
     *
     * @since      0.8.0
     */
    public function do_pc3_settings()
    {
        if( $this->bDebug ) {
            // Show the saved option value.
            echo '<h3>Show all the options as an array</h3>';
            echo $this->oDebug->getArray(PC3_AdminPageFramework::getOption(get_class($this)));
        }
    }
}