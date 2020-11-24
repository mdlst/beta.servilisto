<?php
$template = '<div class="infowindow" ><div class="post-item"><div class="place-wrapper">
    <a href="{{= permalink }}" class="img-place">
        <img src="{{= the_post_thumnail }}">
    </a>
    <div class="place-detail-wrapper">
        <h2 class="title-place"><a href="{{= permalink }}">{{= post_title }}</a></h2>
        <span class="address-place"><i class="fa fa-map-marker"></i> {{= et_full_location }}</span>
        <div class="rate-it" data-score="{{= rating_score }}"></div>
    </div>
</div></div></div>';

// $temaplte   =   '<div class="admap-content"> <img src="{{= the_post_thumnail }}" /> <p> <a href="{{= permalink }}" > {{= post_title }} </a> </p> <p> '.__("Location", ET_DOMAIN).': {{= et_full_location }} </p></div>';
echo '<script type="text/template" id="ae_info_content_template">' . apply_filters('ce_admap_template', $template) . '</script>';
echo '<div class="map-element" style="display:none"></div>';