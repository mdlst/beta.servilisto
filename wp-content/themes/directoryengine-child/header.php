<?php $template_directory_uri = get_template_directory_uri(); ?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="es-ES" prefix="og: http://ogp.me/ns#">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="es-ES" prefix="og: http://ogp.me/ns#">
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html lang="es-ES" prefix="og: http://ogp.me/ns#">
<!--<![endif]-->
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1.0">

    <?php if (is_singular('place')) {
        global $post;
        if (isset($post)) {
            $url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
            ?>
            <meta property="og:title" content="<?php the_title(); ?>"/>
            <meta property="og:image" content="<?= $url; ?>">
            <meta property="og:url" content="<?php the_permalink(); ?>">
            <meta property="og:type" content="place"/>
        <?php }

    } ?>
    <title><?php wp_title('|', true, 'right'); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="alternate" hreflang="es" href="https://servilisto.com/"/>

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <!--[if lt IE 9]>
    <script src="<?= $template_directory_uri . '/js/html5.js' ?></script>
    <![endif]-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="application/javascript">
        var url22 = '<?= admin_url('admin-ajax.php'); ?>';
        var theme_url = '<?= get_stylesheet_directory_uri() ?>';
        var radius = '<?= (ae_get_option('nearby_distance')) ? ae_get_option('nearby_distance') : 10; ?>';
        var getIfApp = <?= getIfApp() ?>;
        var site_url = '<?= site_url() ?>';

    </script>

    <?php

    $re = false;
    $re = de_check_register();

    ae_favicon();
    wp_head();

    ?>

    <?php if(is_single()) : ?>
<script type="application/ld+json">
  {
   "@context":"http://schema.org",
      "@type":"BlogPosting",
      "headline":"<?php echo get_the_title(); ?>",
      "datePublished":"<?php echo get_the_date('c'); ?>",
      "author":"<?php the_author(); ?>",
      "description":"<?php echo get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true); ?>",
      "publisher":{
          "@type": "Organization",
          "name": "Servilisto",
          "url": "<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>",
          "logo":{
            "@type": "imageObject",
            "url": "https://servilisto.com/wp-content/uploads/2017/05/Logo-Servilisto-2-blanco-largo-300x50-2.png"
        }
        },
        "sameAs": ["https://www.facebook.com/Servilistocom-1817615135230309/",
           "https://twitter.com/servilisto_com",
          "https://www.youtube.com/channel/UC92UNo3L_conmz7ys3kih8Q",
          "https://plus.google.com/104359010687451603427"],
        "image": {
            "@type": "imageObject",
            "url": "<?php echo get_the_post_thumbnail_url();?>",
            "height": "200",
            "width": "400"
        }
  }
</script>
<?php endif; ?>
</head>
<body <?php body_class(); ?> >


<!--// new change by glowsol start -->
<?php
$added_redeem_arr = added_redeem_fun();
$points = $added_redeem_arr[0]->tpoint;
?>
<script>
    <?php    if ( $points > 1000 && is_user_logged_in() ) {  ?>
    var points = true;
    var my_bool = true;
    <?php    } else {  ?>
    var points = false;
    var my_bool = false;
    <?php } ?>
</script>
<!--// new change by glowsol end -->


<div id="sticky-holder"></div>

<!-- Header -->
<header id="header-wrapper">

    <!--COOKIES-->
    <div class="eupopup eupopup-container eupopup-container-block">
        <div class="eupopup-markup">
            <div class="eupopup-head">Este sitio web está usando cookies</div>
            <div class="eupopup-body">Este sitio web utiliza cookies propias y de terceros para optimizar su navegación,
                adaptarse a sus preferencias y realizar labores analíticas. Al continuar navegando acepta nuestra
                política de cookies.
            </div>
            <div class="eupopup-buttons">
                <a title="Cerrar aviso cookies" href="#" class="eupopup-button eupopup-button_1">Continuar</a>
                <a href="<?= site_url('politica-de-cookies/') ?>" target="_blank" title="Ver más"
                   class="eupopup-button eupopup-button_2">Ver más</a>
            </div>
            <div class="clearfix"></div>
            <a href="#" title="Cerrar aviso cookies" class="eupopup-closebutton">x</a>
        </div>
    </div>


    <?php
    /* NOTIFICATION */
    if (is_user_logged_in() && ae_user_can('edit_others_posts')) {
        if (isset($_COOKIE['view-notification']) && $_COOKIE['view-notification'] == 1) {
            $pending_place = new WP_Query(array('post_type' => 'place', 'post_status' => 'pending', 'showposts' => -1));
            if ($pending_place->found_posts > 0) {
                get_template_part('template/notification');
            }
        }
    }
    /* NOTIFICATION */


    ?>
    <?php
    global $user_ID;
    $confirm = get_user_meta($user_ID, 'register_status', true);
    if ($confirm) { ?>
        <section class="activation-notification">
            <div class="activation-notification-content">
                <p>
					<span>
						<i class="fa fa-exclamation-circle"></i>
                        ¡Tu cuenta aún no ha sido confirmada! Comprueba tu buzón para activar tu cuenta.
					</span>
                    <a href="#" class="activation-notification-close">
                        <i class="fa fa-times pull-right"></i>
                    </a>
                </p>
                <a href="#" class="resend-activation-code">Reenviar un código de activación</a>
            </div>
        </section>
    <?php } ?>


    <div id="menu-top" class="container">
        <ul class="top-menu gn-menu-main" id="gn-menu">

            <li class="content_logo">
                <a href="<?= home_url(); ?>" class="logo">
                    <?php ae_logo(); ?>
                </a>

            </li>
            <?php //de_support_info(); ?>
        </ul>
        <ul class="top-menu-right visible-md-up">
            <li class="top-active top-search" data-name="search">
                <a href="javascript:void(0)" class="search-btn">
                    <i class="fa fa-search"></i>
                </a>
            </li>

            <?php
            if (!isset($_COOKIE['current_user_lat']) || $_COOKIE['current_user_lat'] == "") {
                ?>

                <li class="top-geolocation" data-name="geolocation">
                    <a href="javascript:void(0)" id="startGeolocation" title="Utiliza mi geolocalización"
                       class="<?php
                       if (is_search()) echo 'search';
                       if (is_single()) echo 'single-place';
                       if (is_category()) echo 'category';
                       ?>">
                        <i class="fa fa-map-marker"></i>
                    </a>
                </li>
            <?php } ?>

            <?php if ($re) {


                if (is_user_logged_in()) {
                    global $current_user;

                    $tiene_notificaciones_chat = tieneChatNoLeidos($current_user->ID);

                    if (ae_user_can('edit_others_posts')) {
                        $pending_post = new WP_Query(array('post_type' => 'place', 'post_status' => 'pending', 'showposts' => -1));
                    }
                    ?>
                    <li class="top-user dropdown">

                        <a class="dropdown-toggle display-name" data-toggle="dropdown" href="#">

                            <?php
                            if ($tiene_notificaciones_chat) {
                                echo "<i class='fa fa-bell campana' aria-hidden='true' title='Tiene mensajes nuevos'></i>";
                            }
                            echo $current_user->display_name;
                            if (ae_user_can('edit_others_posts') && $pending_post->found_posts > 0) {
                                echo '<span style="color:#EE671B;"> (' . $pending_post->found_posts . ')</span>';
                            }
                            ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?= et_get_page_link('profile'); ?>">
                                    <i class="fa fa-user"></i><?php _e("Profile", ET_DOMAIN) ?>
                                </a>
                            </li>
                            <?php
                            if (ae_user_can('edit_others_posts')) {
                                if ($pending_post->have_posts()) {
                                    ?>
                                    <li>
                                        <a href="<?php echo get_post_type_archive_link('place'); ?>">
                                            <i class="fa fa-flash"></i><?php printf(__("%s Pending", ET_DOMAIN), $pending_post->found_posts); ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                            } ?>
                            <?php if ($tiene_notificaciones_chat) { ?>
                                <li>
                                    <a class="notifications_has_link"
                                       href="<?= et_get_page_link('profile') . "?tab-chat" ?>">
                                        <i class="fa fa-bell" aria-hidden="true"></i>Tiene mensajes<br>nuevos sin
                                        leer</a>
                                </li>
                            <?php } ?>
                            <li>
                                <a href="<?php echo wp_logout_url(home_url()); ?>">
                                    <i class="fa fa-power-off"></i><?php _e("Log Out", ET_DOMAIN) ?>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="top-avatar oculto">
                        <a href="<?= et_get_page_link('profile'); ?>">
                            <?php echo get_avatar($current_user->ID, 60); ?>
                        </a>
                    </li>
                <?php } else { ?>
                    <li class="non-login">
                        <a id="authenticate" class="<?php if (!is_page_template('page-login.php')) {
                            echo 'authenticate';
                        } ?> " href="#">
                            <?php _e("SIGN IN", ET_DOMAIN); ?>
                        </a>
                    </li>
                    <?php
                }
            } ?>
            <li class="menu-item publica-tu-anuncio">
                <a class="hover" href="<?= site_url('publica-tu-anuncio/') ?>">
                    Publica tu anuncio
                </a>
            </li>
        </ul>
        <div class="top-menu-center visible-lg-up">
            <?php de_header_top_menu(); ?>
            <div class="clear"></div>
        </div>

        <script type="text/template" id="header_login_template">
            <li class="top-user dropdown">
                <a class="dropdown-toggle display-name" data-toggle="dropdown" href="#">
                    {{= display_name }} <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>

                        <a href="<?= et_get_page_link('profile'); ?>">
                            <i class="fa fa-user"></i><?php _e("Profile", ET_DOMAIN) ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo wp_logout_url(home_url()); ?>">
                            <i class="fa fa-power-off"></i><?php _e("Log Out", ET_DOMAIN) ?>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="top-avatar oculto">
                <a href="{{= author_url }}">
                    <img alt="Avatar usuario" src="{{= et_avatar_url }}" class="avatar avatar-60 photo avatar-default" height="60"
                         width="60">
                </a>
            </li>
        </script>
        <script type="text/template" id="header_signin_template">
            <li class="non-login">
                <a id="authenticate" class="authenticate" href="#">
                    <?php _e("SIGN IN", ET_DOMAIN); ?>
                </a>
            </li>
        </script>

        <i class="menu-toogle fa fa-bars jamburguer"></i>

        <div class="clearfix"></div>
    </div>

    <!-- Opition Search Form -->
    <div id="option-search-form" class="option-search-form-wrapper option-contact-search">
        <div class="container">

            <p class="title">¿Que estás buscando?</p>

            <form action="<?php echo home_url(); ?>" method="get">
                <div class="row">
                    <div class="col-md-4">
                        <span class="titulos">Busca por palabra clave</span>
                        <input value="<?php echo(isset($_REQUEST['s']) ? $_REQUEST['s'] : '') ?>" type="text"
                               name="s" class="option-search-textfield"
                               placeholder="¿Que quieres buscar?"/>
                    </div>
                    <div class="col-md-4">
                        <span class="titulos">o busca por provincia</span>
                        <?php ae_tax_dropdown('location',
                            array('class' => 'chosen-single tax-item',
                                'hide_empty' => true,
                                'hierarchical' => true,
                                // 'id' => 'location' ,
                                'show_option_all' => __("Select your location", ET_DOMAIN),
                                'value' => 'slug',
                                'name' => 'l',
                                'id' => 'location-advanced-search',
                                'name_option' => 'l',
                                'selected' => (isset($_REQUEST['l']) && $_REQUEST['l']) ? $_REQUEST['l'] : ''
                            )
                        ); ?>
                    </div>
                    <div class="col-md-4">
                        <span class="titulos">o busca por categoría</span>
                        <div class="select-category-with-clear-button">
                            <i title="Limpiar resultados" class="fa fa-times hover" aria-hidden="true"></i>
                            <?php ae_tax_dropdown('place_category',
                                array('class' => 'chosen-single_category tax-item',
                                    'show_option_all' => 'Selecciona tu categoría',
                                    'hide_empty' => true,
                                    'hierarchical' => true,
                                    'id' => 'place_category_single',
                                    'value' => 'slug',
                                    'name' => 'c',
                                    'name_option' => 'c',
                                    'selected' => (isset($_REQUEST['c']) && $_REQUEST['c']) ? $_REQUEST['c'] : ''
                                )
                            ); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="centered">
                        <input class="btn-search hover" type="submit" value="<?php _e("Search", ET_DOMAIN); ?>"/>
                    </div>
                </div>
            </form>
        </div>
    </div>

</header>
<!-- Header / End -->

<!-- Marsk -->
<div class="marsk-black"></div>
<!-- Marsk / End -->

<div id="page">
