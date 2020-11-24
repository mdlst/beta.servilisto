<?php

/**
 * Mobile Footer Template
 */
?>
<!-- Footer -->
<div style="height:60px;"></div>  <!--le damos espacio al menu fixed de abajo-->

   <?php
//   if( is_active_sidebar( 'de_mobile_bottom' ) ) {
// 	dynamic_sidebar( 'de_mobile_bottom' );
// }
?>
</footer>
<!-- Footer / End -->
<?php
get_template_part('mobile/template/js-loop', 'place');
get_template_part('mobile/template/js-loop', 'review');
get_template_part('mobile/template/js-loop', 'blog');

// user logged in and in author or single place
if (is_user_logged_in() && (is_author() || is_singular('place'))) {
    get_template_part('template/modal', 'contact');
}
wp_footer();

?>
<div id="replace"></div>

<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri(); ?>/mobile/css/ban.css">
<img class="loader_full_mobile" src="<?= get_stylesheet_directory_uri(); ?>/mobile/images/loader.gif" />
<script>

    jQuery(document).ready(function ($) {

        $(window).scroll(function(){
            if ($(this).scrollTop() > 55) {
                $('#top-bar').addClass('fixed-up-mobile');
            } else {
                $('#top-bar').removeClass('fixed-up-mobile');
            }
        });

       /* if ($.cookie("smartbanner") != 1) {
            checkDevice();
            $('body').css('margin-top', 78);
            $('.sb-close').click(function () {
                $('#replace').fadeOut();
                $('body').css('margin-top', 0);
                $.cookie('smartbanner', 1,{ expires: 7, path: '/', secure: true });
            });
        }*/
        function checkDevice() {
            var isiPhone = navigator.userAgent.toLowerCase().indexOf("iphone");
            var isiPad = navigator.userAgent.toLowerCase().indexOf("ipad");
            var isiPod = navigator.userAgent.toLowerCase().indexOf("ipod");

            var isAndroid = /android/i.test(navigator.userAgent.toLowerCase());

            var isBlackberry = navigator.userAgent.toLowerCase().indexOf("BlackBerry");

            var isOpera = /Opera/i.test(navigator.userAgent.toLowerCase());

            var isIE = navigator.userAgent.toLowerCase().indexOf("IEMobile");

            if (isiPhone > -1) {
                $('#replace').html('<div id="smartbanner" class="android no-icon shown" style="position: absolute; top: 0px;"><div class="sb-container"><a href="javascript:void(0);" class="sb-close">×</a><span class="sb-icon"></span><div class="sb-info"><strong>Servilisto App</strong><span>Servilisto.com</span><span>FREE - In App Store</span></div><a href="https://itunes.apple.com/us/app/servilisto-servicios-cerca/id1106090063?mt=8" class="sb-button"><span>Download</span></a></div></div>');
            }
            else if (isAndroid) {
                $('#replace').html('<div id="smartbanner" class="android no-icon shown" style="position: absolute; top: 0px;"><div class="sb-container"><a href="javascript:void(0);" class="sb-close">×</a><span class="sb-icon"></span><div class="sb-info"><strong>Servilisto App</strong><span>Servilisto.com</span><span>FREE - In Google Play</span></div><a href="https://play.google.com/store/apps/details?id=com.app_servilisto.layout" class="sb-button"><span>Download</span></a></div></div>');
            }


        }
    });


    jQuery(document).ready(function () {
        jQuery('input#timepick').click(function (e) {
            e.preventDefault();
        });
    });

</script>

</body>
</html>