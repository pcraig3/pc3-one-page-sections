/**
 * @TODO
 *
 * @since      0.5.0
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

        var $jquery_sticky = $('#jquery-sticky');

        if ( $jquery_sticky )
            offset += $jquery_sticky.height();

        /**
         * using mahlihu's scroll-to-id plugin
         *
         * @see  http://manos.malihu.gr/page-scroll-to-id
         */
        //@var pc3_section
        $(".pc3_section__wrapper a[href^='#']").mPageScroll2id({
            scrollSpeed: 300,
            scrollEasing: "easeInOutQuad",
            pageEndSmoothScroll: true,
            offset: offset
        });
    });

})( jQuery );
