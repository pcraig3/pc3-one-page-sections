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
     * The set-up method which is triggered automatically with the 'wp_loaded' hook.
     *
     * Here we define the setup() method to set how many pages, page titles and icons etc.
     *
     * @since      0.6.0
     */
    public function setUp()
    {

        // Create the root menu - specifies to which parent menu to add.

        //wish there was some way to make this a global variable
        //@var pc3_section
        if (post_type_exists('pc3_section'))
            $this->setRootMenuPageBySlug('edit.php?post_type=pc3_section');
        else
            $this->setRootMenuPage('Settings');

        // Add the sub menus and the pages.
        /*
        parameters: https://github.com/michaeluno/admin-page-framework/blob/c85cdc9051eeef09efa8668fd79c2f9d74999fce/development/factory/AdminPageFramework/AdminPageFramework_Menu_Controller.php
        */
        $this->addSubMenuItems(
            array(
                'title' => 'Manage Sections',  // page and menu title
                'page_slug' => 'manage_sections',     // page slug
                'order' => 5
            )
        );

        new PC3_SectionManagerPage_Callbacks(
            'PC3_SectionManagerPage',
            'manage_sections',
            'manage_sections__sections',
            'manage_sections__submit'
        );

        new PC3_SectionPostType_MetaLayer();
    }

    /**
     * One of the pre-defined methods which is triggered when the registered page loads.
     * ie, load_{page slug}
     *
     * Here we add form fields.
     *
     * @since      0.3.0
     */
    public function load_manage_sections($oAdminPage)
    {

        $this->addSettingFields(
            array(
                'field_id'          => 'manage_sections__sections',
                'title'             => __( 'Section Titles', 'one-page-sections' ),
                'type'              => 'hidden',
                'default'           => '',
                // 'hidden' =>    true // <-- the field row can be hidden with this option.
                'label'             =>
                    __( 'Sorry, but I couldn\'t find any sections.  <br>:(', 'one-page-sections' ),
                'description'       => __( 'Maybe try <a href="/wp-admin/post-new.php?post_type=pc3_section">adding a Section</a>?', 'one-page-sections' )
            ),
            array( // Submit button
                'field_id'      => 'manage_sections__submit',
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