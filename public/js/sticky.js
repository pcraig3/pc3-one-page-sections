/**
 * Short bit of JavaScript which will stick any element with a #jquery-sticky id to the top of the screen.
 *
 * @since      0.9.0
 *
 * @package    One_Page_Sections
 * @subpackage One_Page_Sections/public/js
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
