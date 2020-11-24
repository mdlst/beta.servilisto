<?php header("Content-type: text/css"); ?>
<?php
$absolute_path = __FILE__;
$path_to_file = explode('wp-content', $absolute_path);
$path_to_wp = $path_to_file[0];
require_once($path_to_wp . '/wp-load.php');
?>
<?php echo 'a:hover, a:focus{color:#479edc;}
a, * a, a:hover{outline-style:none;text-decoration:none;}
::-moz-selection{background:#b3d4fc;text-shadow:none;}
::selection{background:#b3d4fc;text-shadow:none;}
::-webkit-input-placeholder{color:#5f6f81;font-size:13px;font-weight:300;}
:-moz-placeholder{color:#5f6f81;font-size:13px;font-weight:300;}
::-moz-placeholder{color:#5f6f81;font-size:13px;font-weight:300;}
:-ms-input-placeholder{color:#5f6f81;font-size:13px;font-weight:300;}
.list-option-left-wrapper .list-option-left, ul.top-menu, ul.top-menu-right, ul.top-menu li ul.top-info, .list-option-filter, .list-places, .list-places .place-wrapper .place-detail-wrapper .rate-it, .list-share-social, .description-place-wrapper .list-gallery, .list-lastest-news, .step-content-wrapper .list-form-login, .step-content-wrapper .list-price, .option-search, .list-categories, .edit-place-option, .list-place-review, .social-list-footer, .list-news-widget, .info-address-place ul, .list-user-page-info, .list-item-place-user{margin:0;padding:0;list-style:none;}
.list-option-left-wrapper .list-option-left li a, .list-share-social li a, #add-review input[type="submit"]:hover{background:#1c84d4;}
.list-option-left-wrapper .list-option-left li a:hover{background:#5f6f81}
.mask-color{background:#1b83d3;}
.categories-wrapper, .categories-wrapper:before, .list-categories li:hover .number-categories, .categories-wrapper .categories-name, .categories-wrapper .categories-name, .categories-wrapper .icon-categories i{-webkit-transition:all 0.3s ease;-moz-transition:all 0.3s ease;-o-transition:all 0.3s ease;-ms-transition:all 0.3s ease;transition:all 0.3s ease;}
';

// render cat color css
$cat = new AE_Category(array(
    'taxonomy' => 'place_category'
));
$category = $cat->getAll();

// print style for category
foreach ($category as $key => $value) {
    if ($value->color == '' || $value->color == '0') {
        $color = '#eb5256';
    } else {
        $color = $value->color;
    }
    $text_color = get_color_hexdec($color);
    echo '.cat-' . $value->term_id . ' .categories-wrapper{ border-color : ' . $color . '; }'; // chosens border
    echo '.cat-' . $value->term_id . ' .categories-wrapper:hover .categories-name,.cat-' . $value->term_id . ' .categories-wrapper:hover .icon-categories i{color : ' . $text_color . ' !important;}'; // hover text color with background color
    echo '.cat-' . $value->term_id . ' .categories-wrapper:hover .number-categories{background-color :' . $text_color . ' !important}';
    // menu category border
    echo '.list-places .place-wrapper .img-place .cat-' . $value->term_id . ' .ribbon:after {border : 10px solid ' . $color . ';right: -15px;border-left-width: 1.5em;border-right-color: rgba(0, 0, 0, 0);position:absolute;top:0;}';

    echo '.infowindow .img-place .cat-' . $value->term_id . ' .ribbon:after,.widget-features-wrapper .list-places .place-wrapper .img-place .cat-' . $value->term_id . ' .ribbon:after {border : 8px solid ' . $color . ';right: -15px;border-left-width: 1.5em;border-right-color: rgba(0, 0, 0, 0);}';
    echo '.cat-' . $value->term_id . '  .ribbon-event:after {content: "";border: 10px solid ' . $color . ';z-index: -1;bottom: 0;right: -15px;position: absolute;border-left-width: 1.5em;border-right-color: rgba(0, 0, 0, 0);}';
    echo 'ul.top-menu .gn-menu li.menu-place-category-' . $value->term_id . '::after{ background: ' . $color . '; }';
    echo '.cat-' . $value->term_id . ' .categories-wrapper:before,.chosen-container-multi .chosen-choices li.search-choice.cat-' . $value->term_id . ' {background: ' . $color . ' !important;color: ' . $text_color . ' !important;;border-radius: 0;-moz-border-radius: 0;-webkit-border-radius: 0;}';
    echo '.cat-' . $value->term_id . ' .categories-wrapper:before,.list-places .place-wrapper .img-place .cat-' . $value->term_id . ' .ribbon ,.cat-' . $value->term_id . ' .ribbon-event {background-color : ' . $color . ';color : ' . $text_color . ' !important;}';

    if ($value->parent == 0) {
        echo '.chosen-container-multi .chosen-drop .cat-' . $value->term_id . '{border-left: 3px ' . $color . ' solid;}';
    }

}

?>

