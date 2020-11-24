<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,300,400,600,700'
          rel='stylesheet' type='text/css'>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <script type="text/javascript">
        var base_url = '<?php echo get_site_url(); ?>';
        var getIfApp = <?= getIfApp() ?>;
    </script>

    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php
    global $user_ID, $wp_query;

    $ruta_tema = get_stylesheet_directory_uri();

    ae_favicon();
    wp_head();

    wp_enqueue_script('jquery');
    wp_enqueue_script('modernizr', get_template_directory_uri() . '/js/modernizr.min.js', array(), true, false);
    wp_enqueue_script('dl-menu', get_stylesheet_directory_uri() . '/mobile/js/dl-menu.js', array('jquery'), true);
    if (is_page_template('page-profile.php')) {
        wp_enqueue_script('profile', get_stylesheet_directory_uri() . '/mobile/js/profile.js', array('appengine', 'dl-menu', 'chosen'), ET_VERSION, true);
    } else {
        wp_enqueue_script('main', get_stylesheet_directory_uri() . '/mobile/js/page-front.js', array('appengine', 'dl-menu', 'chosen'), ET_VERSION, true);
    }
    wp_enqueue_script('front', get_template_directory_uri() . '/mobile/js/front.js', array('jquery', 'backbone', 'marker', 'appengine'), true, false);

    $place_active = '';
    $review_active = '';
    $nearby_active = '';
    $blog_active = '';

    if (is_post_type_archive('place') || is_page_template('page-front.php')) $place_active = 'active';
    if (is_page('blog')) $blog_active = 'active';
    if (is_page_template('page-list-reviews.php')) $review_active = 'active';
    if (isset($_REQUEST['center'])) {
        $nearby_active = 'active';
        $place_active = '';
    }
    $post_place_active = '';
    if (is_page('publica-tu-anuncio')) $post_place_active = 'active';
    if (!has_nav_menu('et_mobile_header')) {
        echo '<style>#menu-footer ul li { width : 25% !important; }</style>';
    }

    // render cat color css
    $cat = new AE_Category(array(
        'taxonomy' => 'place_category'
    ));
    $category = $cat->getAll();
    et_mobile_render_less_style();

    //con esto ponemos en su lugar las categorias y las location en sus filtros correspondientes.
    if (isset($place_category)) {
        $_REQUEST['c'] = $place_category;
    }
    if (isset($_REQUEST['en'])) {
        $_REQUEST['l'] = $_REQUEST['en'];
    }


    ?>


    <style type="text/css">
        <?php foreach ($category as $key => $value) { ?>
        .place-wrapper .img-place .cat-<?php echo $value->term_id;  ?> .ribbon {
            background: <?php echo $value->color; ?>;
        }

        .place-wrapper .img-place .cat-<?php echo $value->term_id;  ?> .ribbon:after {
            content: "";
            position: absolute;
            display: block;
            border: 9px solid <?php echo $value->color; ?>;
            z-index: -1;
            bottom: 0;
        }

        .place-wrapper .img-place .cat-<?php echo $value->term_id;  ?> .ribbon:after {
            right: -15px;
            border-left-width: 1.5em;
            border-right-color: transparent;
        }

        <?php } ?>
        <?php if(!has_nav_menu('et_mobile_header')) { ?>
        #menu-footer ul li {
            width: 25%;
        }

        <?php } ?>
        .carousel-list .moxie-shim.moxie-shim-html5 {
            z-index: 1000;
            width: 70px !important;
            height: 70px !important;
        }
    </style>
</head>
<body <?php body_class(); ?> >
<div class="marsk-black"></div>
<!-- Menu Bottom -->
<section id="menu-footer" data-size="big">
    <ul>
        <li>
            <a class="<?php echo $place_active; ?>" href="<?php echo get_post_type_archive_link('place'); ?>"><i
                        class="fa fa-map-marker"></i><?php _e("Places", ET_DOMAIN); ?></a>
        </li>
        <li>
            <a class="<?php echo $nearby_active; ?>" href="#" id="search-nearby"><i
                        class="fa fa-compass"></i><?php _e("Nearby", ET_DOMAIN); ?></a>

            <form id="nearby" action="<?php echo get_post_type_archive_link('place') ?>" method="get">
                <input type="hidden" name="center" id="center_nearby"/>
            </form>
        </li>
        <li>
            <a class="<?php echo $post_place_active; ?>" href="<?= site_url() . "/publica-tu-anuncio/" ?>"><i
                        class="fa fa-plus"></i><?php _e('Anúnciate', ET_DOMAIN) ?></a>
        </li>
        <li>
            <a class="<?php echo $review_active; ?>" href="<?= site_url() . "/opiniones/" ?>">
                <i class="fa fa-comment"></i><?php _e("Reviews", ET_DOMAIN); ?>
            </a>
        </li>
        <!-- <li><a class="<?php echo $blog_active; ?>" href="<?php echo et_get_page_link('blog') ?>"><i class="fa fa-comments-o"></i><?php _e("Blog", ET_DOMAIN); ?></a></li> -->
        <?php if (has_nav_menu('et_mobile_header')) { ?>
            <li><a href="#" class="dl-trigger"><i class="fa fa-list"></i><?php _e("More", ET_DOMAIN); ?></a></li>
        <?php } ?>
    </ul>
</section>
<!-- Menu Bottom / End -->

<!-- Topbar -->
<?php if (!AE_Users::is_activate($user_ID)) { ?>
    <div class="top-bar-wrapper">
        <span class="icon-top-bar"><i class="fa fa-bullhorn"></i></span>

        <p class="content-top-bar"><?php _e("Please confirm your email address to complete your registration process.", ET_DOMAIN); ?></p>

        <div class="clearfix"></div>
    </div>
<?php } ?>
<!-- Topbar / End -->

<!-- Header -->

<header>
    <div class="container">
        <div class="row">
            <div class="col-xs-3">
                <a href="#" class="search-btn"><i class="fa fa-search"></i></a>
                <?php
                if (!isset($_COOKIE['current_user_locality']) || $_COOKIE['current_user_locality'] = "") {
                    ?>

                    <a href="javascript:void(0)" id="startGeolocation"
                       class="mobile">
                        <i class="fa fa-map-marker"></i>
                    </a>
                <?php } ?>

            </div>
            <div class="col-xs-6">
                <a href="<?php echo home_url(); ?>" class="logo"><img
                            src="<?= $ruta_tema . "/images/logo-blanco-movil.png" ?>"/></a>
            </div>

            <div class="col-xs-3">
                <?php if ($user_ID) { ?>
                    <a href="<?= home_url("mi-perfil") ?>"
                       class="avatar-author-header"><?php echo get_avatar($user_ID, 30); ?></a>
                <?php } else {
                    $re = false;
                    $re = de_check_register();
                    if ($re) {
                        ?>
                        <a title="Entrar" href="<?= home_url("entrar") ?>" class="avatar-author-header"><i
                                    class="fa fa-user"></i></a>
                        <?php
                    }
                } ?>
            </div>

        </div>
    </div>
</header>
<!-- Header / End -->
<section class="search-form-wrapper" id="version_mobile">
    <form action="<?php echo home_url(); ?>" method="get">
        <div class="search-form">
            <a href="#" class="btn-close-form"><i class="fa fa-times"></i></a>

            <div class="container padding-top-15">
                <div class="row">
                    <div class="col-xs-6" style="padding: 0 3px;">
                        <div class="cat_left">
                            <?php
                            ae_tax_dropdown('place_category',
                                array('hierarchical' => true,
                                    'hide_empty' => true,
                                    'id' => 'place_category-single',
                                    'show_option_all' => __("Categories", ET_DOMAIN),
                                    'value' => 'slug',
                                    'selected' => (isset($_REQUEST['c']) && $_REQUEST['c']) ? $_REQUEST['c'] : ''
                                )); ?>
                        </div>
                    </div>
                    <div class="col-xs-6" style="padding: 0 3px;">
                        <div class="loc_right">
                            <?php
                            ae_tax_dropdown('location',
                                array('hierarchical' => true,
                                    'hide_empty' => true,
                                    'id' => 'location-advanced-search-home',
                                    'show_option_all' => __("Location", ET_DOMAIN),
                                    'value' => 'slug',
                                    'selected' => (isset($_REQUEST['l']) && $_REQUEST['l']) ? $_REQUEST['l'] : ''
                                )); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <input type="submit" value="<?php _e("Search", ET_DOMAIN); ?>"
                               class="btn-search"/>
                    </div>
                </div>
            </div>
    </form>
</section>

<?php
if (has_nav_menu('et_mobile_header')) {
    wp_nav_menu(array('theme_location' => 'et_mobile_header',
            'menu_class' => 'dl-menu',
            'container' => 'div',
            'container_class' => 'dl-menuwrapper',
            'container_id' => 'dl-menu',
            'walker' => new DE_Menu_Walker()
        )
    );
}
?>
<!-- /dl-menuwrapper -->
<?php
if (is_active_sidebar('de_mobile_top')) {
    dynamic_sidebar('de_mobile_top');
}
