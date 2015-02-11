/**
 * @TODO
 *
 * @since      0.8.2
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/templates
 */

(function( $ ) {
    'use strict';

    $(document).ready(function() {

        var offset = 0;

        var $admin_bar = $('#wpadminbar');

        if ( $admin_bar )
            offset += $admin_bar.height();

        $("#jquery-sticky").sticky({topSpacing:offset});

    });

})( jQuery );
