<?php
/**
 * Template Name: Page Profile
 * version 1.0
 * @author: enginethemes
 */

if (!is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}

global $wp_query, $wp_rewrite, $current_user, $de_place_query, $user_ID, $post;

et_get_mobile_header();

$user = get_user_by('id', $user_ID);
$ae_users = AE_Users::get_instance();
$user = $ae_users->convert($user);


/**
 * get author current section
 */
$review_url = ae_get_option('author_review_url', 'reviews');
$togo_url = ae_get_option('author_togo_url', 'togos');

$current_section = "places";


// set current section to reviews lists
if (isset($wp_query->query_vars['author_tab'])) {
    switch ($wp_query->query_vars['author_tab']) {
        case 'reviews':
            $current_section = 'reviews';
            break;
        case 'togos' :
            $current_section = 'togos';
            break;
        case 'pending' :
            $current_section = 'pending';
            if ($user_ID != $user->ID) wp_redirect(home_url());
            break;
        default:
            break;
    }
}
?>
    <!-- Top bar -->
    <section id="top-bar" class="section-wrapper profile">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-9">
                            <h1 class="title-page">
                                Mi Perfil
                            </h1>
                        </div>
                        <div class="col-xs-3">
                            <a class="logout" href="<?php echo wp_logout_url(home_url()); ?>">
                                Salir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Top bar / End -->

    <!-- List News -->
    <section id="info-wrapper" class="section-wrapper">
        <div class="container fullwidth">
            <ul class="nav nav-tabs list-profile-tabs align-centered">
                <li class="active col-xs-12">
                    <a href="#user-info" data-toggle="tab">
                        Información
                    </a>
                </li>
                <li class="col-xs-6 oculto">
                    <a href="#package-info" data-toggle="tab">
                        Plan
                    </a>
                </li>
            </ul>
        </div>
        <div class="container fixed-height-mobile-profile">
            <div class="row">
                <div class="col-xs-12">
                    <div class="tab-content">
                        <div id="user-info" class="tab-pane fade in active">
                            <div class="info-user-wrapper">
                                <div class="cabecera-info">
                                    <h2 class="name-user"><?= $user->display_name; ?></h2>

                                    <div class="avatar-user <?= (getIfApp() ? 'aviso-movil-upload page-profile-imagen' : '')?>"><?= get_avatar($user->ID, 150); ?></div>
                                </div>
                                <div class="clearfix"></div>
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
                                <div class="clearfix"></div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- List News / End -->

    <!-- Tabs -->
    <section id="tabs-user-review-wrapper" class="section-wrapper">
        <ul class="nav nav-tabs list-user-info" role="tablist" id="myTab">


                <li class="places <?= ($current_section == 'places') ? 'active' : ''; ?>">
                    <a href="#user_place" data-toggle="tab">
                        <i class="fa fa-file-image-o"></i>
                        <?php
                        $array_total_place = array('author' => $user->ID, 'post_status' => array('publish', 'pending', 'reject', 'archive', 'draft'), 'post_type' => 'place', 'posts_per_page' => -1);
                        $query_total_place = new WP_Query($array_total_place);
                        $post_total = $query_total_place->found_posts;
                        if ($post_total > 1) {
                            echo "<span class='text'>Anuncios </span><span class='number'>($post_total)</span>";
                        } else {
                            echo "<span class='text'>Anuncios</span>";
                        }
                        ?>
                    </a>
                </li>

                <li class="reviews <?= ($current_section == 'reviews') ? 'active' : ''; ?>">
                    <a href="#user_review" data-toggle="tab">
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
                        if ($review_count > 1) {
                            printf(__('<span class="text">Opiniones</span><span class="number">(%s)</span>', ET_DOMAIN), $review_count);
                        } else {
                            printf(__('<span class="text">Opiniones</span>', ET_DOMAIN));
                        }
                        ?>
                    </a>
                </li>

            <li class="togos <?= ($current_section == 'togos') ? 'active' : ''; ?>">
                <a href="#user_togo" data-toggle="tab">
                    <i class="fa fa-heart"></i>
                    <?php
                    $reviews = get_comments(array(
                        'user_id' => $user->ID,
                        'type' => 'favorite',
                        'status' => 'approve'
                    ));
                    ?>
                    <span class="text">Favoritos</span>
                </a>
            </li>

            <li class="reservas <?= ($current_section == 'reservas') ? 'active' : ''; ?>">
                <a href="#tab-reservas" data-toggle="tab">
                    <i class="fa fa-ticket"></i>
                    <span class="text">Reservas</span>
                </a>
            </li>
            <li class="puntos <?= ($current_section == 'puntos') ? 'active' : ''; ?>">
                <a href="#tab-puntos" data-toggle="tab">
                   <span class="text">
                      <i class="fa fa-euro"></i>Puntos
                   </span>
                </a>
            </li>

            <li class="chat <?= ($current_section == 'chat') ? 'active' : ''; ?>">
                <a href="#tab-chat" data-toggle="tab">
                    <i class="fa fa-comments"></i>
                    <span class="text">Mensajes</span>
                </a>
            </li>
        </ul>


        <div class="tab-content">
            <?php
            get_template_part('mobile/template/profile', 'place');
            get_template_part('mobile/template/profile', 'review');
            get_template_part('mobile/template/profile', 'togo');
            get_template_part('mobile/template/author', 'reservas');
            get_template_part('template/author', 'puntos');
            get_template_part('template/author', 'chat');
            ?>
        </div>


    </section>
    <!-- Tabs / End -->

<?php
et_get_mobile_footer();