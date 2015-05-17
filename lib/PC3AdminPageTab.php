<?php

/**
 * Tabs can be passed to `Lib_PC3AdminPage` objects and they (as well as their
 * setting fields) will be added to the page.
 *
 * @since      0.9.3
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/admin
 * @author     Paul Craig <paul@pcraig3.ca>
 */
class Lib_PC3AdminPageTab {

    /**
     * The ID used to used to uniquely identify this field within a page.
     *
     * @since   0.9.3
     * @var     string
     */
    protected $sTabID;

    /**
     * Title of the tab that shows up in the user interface.
     *
     * @since   0.9.3
     * @var     array
     */
    protected $sTabTitle;

    /**
     * An array of setting fields to be added to this page.
     *
     * @since   0.9.3
     * @var     array
     */
    protected $aSettingFields;

    /**
     * @since   0.9.3
     *
     * @param string $sTabID                the field_id of this tab
     * @param string $sTabTitle             the title of this tab
     * @param array $aSettingFields         Fields appearing under this tab.
     */
    public function __construct( $sTabID, $sTabTitle, array $aSettingFields = array() ) {

        $this->sTabID = $sTabID;
        $this->sTabTitle = $sTabTitle;
        $this->aSettingFields = $aSettingFields;
    }

    /**
     * @since   0.9.3
     *
     * @return string
     */
    public function getTabID() {

        return $this->sTabID;
    }

    /**
     * @since   0.9.3
     *
     * @return array
     */
    public function setUpTab() {

        return array(
            'tab_slug' =>   $this->sTabID,
            'title' =>      $this->sTabTitle
        );
    }

    /**
     *
     * @since   0.9.3
     *
     * @return array
     */
    public function getSettingFields() {

        return $this->aSettingFields;
    }
}