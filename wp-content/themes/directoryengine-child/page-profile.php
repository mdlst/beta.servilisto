<?php
/**
 * Template Name: Page Profile
 * version 1.0
 * @author: enginethemes
 */

/*********************************************************************************/
if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}
/*********************************************************************************/

global $wp_query, $wp_rewrite, $current_user, $de_place_query, $user_ID, $post, $ae_post_factory;
$user = get_user_by('id', $user_ID);
$ae_users = AE_Users::get_instance();
$user = $ae_users->convert($user);
$query_vars = $wp_query->query_vars;
$post_arr = array();

add_filter('posts_orderby', 'order_by_post_status');

$args = array(
    'author' => $current_user->ID, // I could also use $user_ID, right?
    'post_type' => 'place',
    'post_status' => array('publish', 'pending', 'draft', 'archive', 'reject'),
);
$current_user_posts = get_posts($args);

/****************************************************************************/
if ($current_user_posts) {
    $l = 0;

    foreach ($current_user_posts as $anuncios_user) {
        global $post, $ae_post_factory;
        $post = $anuncios_user;

        $ae_post = $ae_post_factory->get('place');
        $convert = $ae_post->convert($post, 'medium_post_thumbnail');
        $post_arr[] = $convert;

        $place_obj = $ae_post_factory->get('place');
        $post = $place_obj->current_post;

        if ($post->post_author != $user_ID) continue;

        $package_data = AE_Package::get_package_data($user_ID);

        $pk_det[$l]['post_title'] = $post->post_title;
        $pk_det[$l]['post_author'] = $post->post_author;
        $pk_det[$l]['ID'] = $post->ID;
        $pk_det[$l]['sku'] = $anuncios_user->et_payment_package;
        $pk_det[$l]['post_status'] = $post->post_status;
        $pk_det[$l]['guid'] = $post->guid;
        $pk_det[$l]['renew_place'] = $post->renew_place;

        $l++;
    }
    $ae_pack = $ae_post_factory->get('pack');
    $packs = $ae_pack->fetch();

    foreach ($packs as $key => $value) {
        $pkset[$value->sku] = $value;
    }
}

/****************************************************************************/

$rol = get_rol_useractual();

get_header();

?>

    <!-- Breadcrumb Blog -->
    <div class="section-detail-wrapper breadcrumb-blog-page">
        <ol class="breadcrumb">
            <li>
                <a href="<?= home_url() ?>" title="<?= get_bloginfo('name'); ?>">Home</a>
            </li>
            <li>
                <a href="#" title="<?= $user->display_name ?>">Perfil de <?= $user->display_name ?></a>
            </li>
        </ol>
    </div>
    <!-- Breadcrumb Blog / End -->

    <div class="wrapper_profile">
        <div class="container">
            <div class="row">
                <div class="col-md-3 left-pad-right">
                    <div class="col-left-profile">
                        <div class="left-profile">
                            <div class="user-info">
                                <div class="bar-info">
                                    <h3 class="visible-md visible-lg visible-sm visible-xs">Información usuario</h3>
                                    <?php if (is_user_logged_in()) { ?>
                                        <a href="#" class="edit-profile">
                                        <span id="edit-user">
                                            <i title="Editar perfil" class="fa fa-pencil"></i>
                                        </span>
                                        </a>
                                    <?php } ?>
                                </div>
                                <div class="content-info" id="user_avatar_container">
                                    <div class="username visible-md visible-lg">
                                        <h4><?= $user->display_name ?></h4>
                                    </div>

                                    <div class="avatar img-author">
                                    <span class="author-avatar image" id="user_avatar_thumbnail">
                                        <?= get_avatar($user->ID, 58); ?>
                                    </span>
                                        <?php if (is_user_logged_in()) { ?>
                                            <a href="#" class="new-look" id="user_avatar_browse_button">
                                            <span>
                                                <i class="fa fa-upload"></i>
                                            </span>
                                            </a>
                                        <?php } ?>
                                        <span class="et_ajaxnonce"
                                              id="<?= wp_create_nonce('user_avatar_et_uploader'); ?>"></span>
                                    </div>

                                    <div class="detail-info">

                                        <p class="location">
                                            <a href="#" class="edit-profile">
                                                <i class="fa fa-map-marker"></i>
                                                <span>
                                                 <?= $user->location ? $user->location : '<< Añadir localización >>' ?>
                                                </span>
                                            </a>
                                        </p>


                                        <p class="email">
                                            <i class="fa fa-envelope"></i>
                                            <span>
                                                <?= $user->user_email ? $user->user_email : '<< Añadir email >>' ?>
                                            </span>
                                        </p>

                                        <p class="phone">
                                            <a href="#" class="edit-profile">
                                                <i class="fa fa-phone"></i>
                                                <span>
                                            <?= $user->phone ? $user->phone : '<< Añadir teléfono >>' ?>
                                        </span>
                                            </a>
                                        </p>

                                        <p class="facebook">
                                            <a href="#" class="edit-profile">
                                                <i class="fa fa-facebook-square"></i>
                                                <span>
                                            <?= $user->facebook ? '<a target="_blank" href="' . $user->facebook . '">' . $user->facebook . '</a>' : '<< Añadir Facebook >>' ?>
                                        </span>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php if ($rol == "author") { ?>
                                <div class="pakage-info">

                                    <div class="bar-info">
                                        <h3 class="">Plan de anuncio actual</h3>
                                    </div>


                                    <?php
                                    if (isset($pk_det)) {
                                        foreach ($pk_det as $pk => $pv) {

                                            ?>
                                            <div class="content-package">
                                                <h3>
                                                    <?= (isset($pkset[$pv['sku']]->post_title)) ? sizeof($pv['sku']) . " " . $pkset[$pv['sku']]->post_title : __('No Package', ET_DOMAIN); ?>

                                                    <?php if (isset($pkset[$pv['sku']]->et_price)): ?>
                                                        <span>  <?= (!$pkset[$pv['sku']]->et_price) ? "Gratis" : $pkset[$pv['sku']]->et_price . '€/mes'; ?></span>
                                                    <?php endif; ?>

                                                </h3>
                                                <div class="clear"></div>
                                                <p>
                                                    <a href="<?= $pv['renew_place'] ?>">
                                                        <i class="fa fa-level-up"></i>
                                                        Mejorar Anuncio
                                                    </a>
                                                </p>
                                            </div>

                                        <?php }
                                    } ?>

                                </div>
                            <?php } ?>


                        </div>
                        <div class="post-place-profile">
                            <a href="<?= site_url('publica-tu-anuncio/') ?>" class="post-place-profile-btn hover">
                                Publica tu anuncio
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 right-pad-left">
                    <div class="col-right-profile">
                        <div class="right-profile">
                            <div class="tabs-rigth-profile">
                                <ul class="nav nav-tabs list-info-user-tabs">

                                    <!--TAB PLACES-->
                                    <li class="places active">
                                        <a href="#tab-place" data-toggle="tab">
                                            <i class="fa fa-file-image-o"></i>
                                            <?php
                                            $array_total_place = array('author' => $user->ID, 'post_status' => array('publish', 'pending', 'reject', 'archive', 'draft'), 'post_type' => 'place', 'posts_per_page' => -1);
                                            $query_total_place = new WP_Query($array_total_place);
                                            $post_total = $query_total_place->found_posts;
                                            if ($post_total > 0) {
                                                if ($post_total == 1) {
                                                    echo "<span title='Tienes $post_total anuncio' class='text'>Anuncios</span><span class='number'>($post_total)</span>";
                                                } else {
                                                    echo "<span title='Tienes $post_total anuncios' class='text'>Anuncios</span><span class='number'>($post_total)</span>";
                                                }
                                            } else {
                                                echo "<span title='Aún no has publicado anuncios' class='text'>Anuncios</span>";
                                            }
                                            ?>
                                        </a>
                                    </li>

                                    <!--TAB REVIEWS-->
                                    <li class="reviews">
                                        <a href="#tab-review" data-toggle="tab">
                                            <i class="fa fa-star"></i>
                                            <?php
                                            $total_review = get_comments(array(
                                                'user_id' => $user->ID,
                                                'type' => 'review',
                                                'status' => 'approve',
                                                'meta_query' => array(
                                                    'relation' => 'AND',
                                                    array(
                                                        'key' => 'et_rate',
                                                        'value' => '0',
                                                        'compare' => '>'
                                                    )
                                                )
                                            ));
                                            $review_count = count($total_review);
                                            if ($review_count > 0) {
                                                if ($review_count == 1) {
                                                    echo "<span title='Has publicado $review_count opinión' class='text'>Opiniones</span><span class='number'>($review_count)</span>";
                                                } else {
                                                    echo "<span title='Has publicado $review_count opiniones' class='text'>Opiniones</span><span class='number'>($review_count)</span>";
                                                }
                                            } else {
                                                echo '<span title="Aún no has publicado ninguna opinión" class="text">Opiniones</span>';
                                            }
                                            ?>
                                        </a>
                                    </li>

                                    <!--TAB FAVORITOS-->
                                    <li class="togos">
                                        <a href="#tab-togo" data-toggle="tab">
                                            <i class="fa fa-heart"></i>
                                            <?php
                                            $favoritos = get_comments(array(
                                                'user_id' => $user->ID,
                                                'type' => 'favorite',
                                                'status' => 'approve'
                                            ));
                                            $favoritos_count = count($favoritos);

                                            if ($favoritos_count > 0) {
                                                if ($favoritos_count == 1) {
                                                    echo "<span title='Has guardado $favoritos_count anuncio como favorito' class='text'>Favoritos</span><span class='number'>($favoritos_count)</span>";
                                                } else {
                                                    echo "<span title='Has guardado $favoritos_count anuncios como favoritos' class='text'>Favoritos</span><span class='number'>($favoritos_count)</span>";
                                                }
                                            } else {
                                                echo '<span title="Aún no has guardado ningún anuncio como favorito" class="text">Favoritos</span>';
                                            }
                                            ?>

                                        </a>
                                    </li>

                                    <!--TAB RESERVAS-->
                                    <li class="reservas">
                                        <a href="#tab-reservas" data-toggle="tab">
                                            <i class="fa fa-ticket"></i>

                                            <?php

                                            $query_base = "SELECT * FROM " . $wpdb->base_prefix . "dopbsp_reservations AS dr INNER JOIN " . $wpdb->base_prefix . "dopbsp_calendars AS dc ON dr.calendar_id=dc.id  and dc.user_id=" . $current_user->ID . "";
                                            $count_result = $wpdb->get_results($query_base);
                                            $total_reservas = count($count_result);

                                            if ($total_reservas > 0) {
                                                if ($total_reservas == 1) {
                                                    echo "<span title='Tienes $favoritos_count reserva' class='text'>Reservas</span><span class='number'>($total_reservas)</span>";
                                                } else {
                                                    echo "<span title='Tienes $favoritos_count reservas' class='text'>Reservas</span><span class='number'>($total_reservas)</span>";
                                                }
                                            } else {
                                                echo '<span title="Aún no tienes reservas para tus anuncios" class="text">Reservas</span>';
                                            }

                                            ?>
                                        </a>
                                    </li>

                                    <!--TAB PUNTOS-->
                                    <li class="puntos">
                                        <a href="#tab-puntos" data-toggle="tab">
                                            <i class="fa fa-euro"></i>
                                            <?php
                                            $user_total_points = $wpdb->get_row("SELECT (COALESCE(sum(points_added),0) - COALESCE(sum(points_redeem),0)) as total_points FROM " . $wpdb->prefix . "tbl_points_log where user_id = '" . $current_user->ID . "' group by user_id  ");

                                            if ($user_total_points && $user_total_points->total_points > 0) {
                                                echo "<span title='Tienes $user_total_points->total_points puntos' class='text'>Puntos</span><span class='number'>($user_total_points->total_points)</span>";
                                            } else {
                                                echo '<span title="Aún no tienes puntos" class="text">Puntos</span>';
                                            }

                                            ?>
                                        </a>
                                    </li>

                                    <!--TAB CHAT-->
                                    <li class="chat">
                                        <a href="#tab-chat" data-toggle="tab">
                                            <i class="fa fa-comments"></i>
                                            <span class="text">Mensajes</span>
                                        </a>
                                    </li>


                                </ul>
                            </div>

                            <div class="content-tabs-right-profile tab-content">
                                <?php
                                get_template_part('template/profile-places');
                                //get_template_part('template/profile-events');
                                get_template_part('template/profile-reviews');
                                get_template_part('template/profile-togos');
                                // get_template_part('template/profile-pictures');
                                get_template_part('template/profile-reservas');
                                get_template_part('template/profile-puntos');
                                get_template_part('template/profile-chat');
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer(); ?>