<?php
/**
 * Created by PhpStorm.
 * User: Paul
 * Date: 30/01/2015
 * Time: 20:48
 */

class PC3_SectionManagerPage extends PC3_AdminPageFramework
{

    /**
     * The set-up method which is triggered automatically with the 'wp_loaded' hook.
     *
     * Here we define the setup() method to set how many pages, page titles and icons etc.
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

    }

    /**
     * One of the pre-defined methods which is triggered when the registered page loads.
     * ie, load_{page slug}
     *
     * Here we add form fields.
     */
    public function load_manage_sections($oAdminPage)
    {

        $this->addSettingFields(
            array(    // Single text field
                'field_id' => 'my_text_field',
                'type' => 'text',
                'title' => 'Text',
                'description' => 'Type something here.',
            ),
            array(    // Text Area
                'field_id' => 'my_textarea_field',
                'type' => 'textarea',
                'title' => 'Single Text Area',
                'description' => 'Type a text string here.',
                'default' => 'Hello World! This is set as the default string.',
            ),
            array( // Submit button
                'field_id' => 'submit_button',
                'type' => 'submit',
            )
        );

    }

    /**
     * One of the pre-defined methods which is triggered when the page contents is going to be rendered.
     *
     * ie, do_{page slug}
     */
    public function do_manage_sections()
    {
        // Show the saved option value.
        // The extended class name is used as the option key. This can be changed by passing a custom string to the constructor.
        echo '<h3>Saved Fields</h3>';
        echo '<pre>my_text_field: ' . AdminPageFramework::getOption('PC3_SectionManagerPage', 'my_text_field', 'default text value') . '</pre>';
        echo '<pre>my_textarea_field: ' . AdminPageFramework::getOption('PC3_SectionManagerPage', 'my_textarea_field', 'default text value') . '</pre>';

        echo '<h3>Show all the options as an array</h3>';
        echo $this->oDebug->getArray(AdminPageFramework::getOption('PC3_SectionManagerPage'));


    }
}