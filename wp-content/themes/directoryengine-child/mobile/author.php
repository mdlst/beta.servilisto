<?php
$user = get_user_by('id', get_query_var('author'));
$ae_users = AE_Users::get_instance();
$user = $ae_users->convert($user);


/**
 * get author current section
 */
$review_url = ae_get_option('author_review_url', 'reviews');
$togo_url = ae_get_option('author_togo_url', 'togos');

$current_section = 'places';
// set current section to reviews lists
if (isset($wp_query->query_vars['author_tab'])) {
    switch ($wp_query->query_vars['author_tab']) {
        case 'reviews':
            $current_section = 'reviews';
            break;
        case 'togos' :
            $current_section = 'togos';
            break;
        //////////heree//////////
        case 'puntos':
            $current_section = 'puntos';
            break;
        case 'reservas':
            $current_section = 'reservas';
            break;
        //////////heree//////////
        case 'pending' :
            $current_section = 'pending';
            if ($user_ID != $user->ID) wp_redirect(home_url());
            break;
        default:
            break;
    }
}

et_get_mobile_header();
?>

    <!-- Top bar -->
    <section id="top-bar" class="section-wrapper profile">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-6">
                            <h1 class="title-page">
                                <?= $user->display_name; ?>
                            </h1>
                        </div>
                        <div class="col-xs-6">
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
    <section id="info-wrapper" class="section-wrapper page-mi-perfil">
        <div class="container fullwidth">
            <div class="row">
                <div class="col-xs-12">
                    <div class="info-user-wrapper">
                        <div class="avatar-user"><?php echo get_avatar($user->ID, 70); ?></div>
                        <ul class="info-user">
                            <li><h2 class="name-user"><?php echo $user->display_name; ?></h2></li>
                            <li>
                                <i class="fa fa-map-marker"></i><?php echo ($user->location) ? $user->location : __("Earth", ET_DOMAIN) ?>
                            </li>
                            <li>
                                <i class="fa fa-phone"></i><?php echo ($user->phone) ? $user->phone : __("No phone", ET_DOMAIN) ?>
                            </li>
                            <li>
                                <i class="fa fa-tree"></i>
                                <?php
                                $total_place = ae_count_user_posts_by_type($user->ID, 'place');
                                printf(_n('owned %s place', 'own %s places', $total_place, ET_DOMAIN), $total_place);

                                ?>
                            </li>
                            <li>
                                <i class="fa fa-sta"></i>
                                <?php

                                $total_review = get_comments(array('post_author' => get_query_var('author'), 'type' => 'review', 'status' => 'approve', 'meta_key' => 'et_rate'));
                                printf(_n('%s review', '%s reviews', count($total_review), ET_DOMAIN), count($total_review));

                                ?>


                            </li>
                            <li>
                                <i class="fa fa-photo"></i>
                                <?php
                                $total_picture = count(get_posts(array('author' => $user->ID,
                                            'fields' => 'ids',
                                            'post_mime_type' => 'image',
                                            'post_type' => 'attachment'
                                        )
                                    )
                                );
                                wp_reset_query();
                                printf(_n('%s picture', '%s pictures', $total_picture, ET_DOMAIN), $total_picture);
                                ?>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- List News / End -->
    <!-- Tabs -->
    <section id="tabs-user-review-wrapper" class="section-wrapper">
        <ul class="nav nav-tabs list-user-info" role="tablist" id="myTab">
            <li class="nawaz<?php if ($current_section == 'places') {
                echo 'active';
            } ?> ">
                <a href="#user_place" role="tab" data
                   -toggle="tab">
                    <i class="fa fa-tree"></i>
                    <?php

                    _e("Places", ET_DOMAIN); ?>
                </a>
            </li>
            <li class="nawaz<?php if ($current_section == 'events') {
                echo 'active';
            } ?>">
                <a href="#user_events" role="tab" data-toggle="tab">
                    <i class="fa fa-ticket"></i><?php _e("Events", ET_DOMAIN); ?>
                </a>
            </li>

            <li class="nawaz<?php if ($current_section == 'reviews') {
                echo 'active';
            } ?>">
                <a href="#user_review" role="tab" data-toggle="tab">
                    <i class="fa fa-star"></i><?php _e("Reviews", ET_DOMAIN); ?>
                </a>
            </li>

            <li class="nawaz<?php if ($current_section == 'togos') {
                echo 'active';
            } ?>">
                <a href="#user_togo" role="tab" data-toggle="tab">
                    <i class="fa fa-star"></i><?php _e("Togos", ET_DOMAIN); ?>
                </a>
            </li>
            <?php //////////heree//////////?>
            <li class="nawaz<?php if ($current_section == 'reservas') {
                echo 'active';
            } ?>">
                <a href="#user_reservas" role="tab" data-toggle="tab">
                    <!--<a href="<?php //echo get_author_posts_url( get_query_var( 'author' ) ).'reservas/'; ?>">-->
                    <i class="fa fa-ticket"></i><?php _e("Reservas", ET_DOMAIN) ?>
                </a>
            </li>
            <li class=" nawaz<?php if ($current_section == 'puntos') {
                echo 'active';
            } ?>">
                <a href="#user_puntos" role="tab" data-toggle="tab">
                    <!--<a href="<?php //echo get_author_posts_url( get_query_var( 'author' ) ).'puntos/'; ?>">-->
                    <i class="fa fa-gift"></i><?php _e("&euro; Puntos", ET_DOMAIN) ?>
                </a>
            </li>
            <?php //////heree//////////?>
        </ul>

        <div class="tab-content">
            <!-- Tabs 1 / Start -->
            <div class="tab-pane fade active body-tabs in" id="user_place">
                <div class="container" id="place-list-wrapper">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            get_template_part('mobile/template/publish', 'places');
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabs 1 / End -->
            <!-- Tabs 2 -->
            <div class="tab-pane fade body-tabs" id="user_events">
                <div class="container" id="">
                    <div class="row" id="">

                        <?php
                        query_posts(array(
                            'post_type' => 'place',
                            'post_status' => 'publish',
                            'author' => get_query_var('author'),
                            'paged' => get_query_var('paged'),
                            'meta_query' => array(
                                'meta_key' => 'de_event_post',
                                'meta_type' => 'NUMERIC'
                            )

                        ));
                        ?>
                        <div class="tab-pane body-tabs" id="events-list-wrapper">
                            <div class="section-detail-wrapper" id="list-events">
                                <?php if (have_posts()) {
                                    get_template_part('mobile/template/list', 'events');
                                } else { ?>
                                    <div class="event-active-wrapper">
                                        <div class="col-md-12">
                                            <div class="event-wrapper tab-style-event">
                                                <h2 class="title-envent"><?php _e("Currently, there are not event yet.", ET_DOMAIN); ?></h2>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php de_mobile_event_pagination($wp_query, 1, 'load_more'); ?>
                        </div>
                        <?php wp_reset_query(); ?>
                    </div>
                </div>
            </div>
            <!--// Tabs 2 -->
            <!-- Tabs 3 / Start -->
            <div class="tab-pane fade body-tabs" id="user_review">
                <div class="container" id="reviews-list-wrapper">
                    <div class="row">
                        <ul class="list-place-review" id="list-reviews">
                            <?php
                            global $ae_post_factory;
                            $review_object = $ae_post_factory->get('de_review');
                            $number = get_option('posts_per_page', 10);
                            $all_cmts = get_comments(array(
                                'user_id' => get_query_var('author'),
                                'type' => 'review',
                                'meta_key' => 'et_rate',
                                'status' => 'approve'
                            ));
                            $query_args = array(
                                'user_id' => get_query_var('author'),
                                'type' => 'review',
                                'meta_key' => 'et_rate',
                                'number' => $number,
                                'status' => 'approve',
                                'paginate' => 'load_more'
                            );
                            $reviews = get_comments($query_args);

                            $comment_pages = ceil(count($all_cmts) / $number);

                            foreach ($reviews as $comment) {
                                $de_review = $review_object->convert($comment);
                                get_template_part('mobile/template/loop', 'review');
                            }

                            ?>
                        </ul>
                        <?php ae_comments_pagination($comment_pages, 1, $query_args); ?>
                    </div>
                </div>
            </div>
            <!-- Tabs 3 / End -->
            <!-- Tabs 4 / Start -->
            <div class="tab-pane fade body-tabs" id="user_togo">
                <div class="container" id="list-favorite">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-places fullwidth" id="list-favorite">
                                <?php
                                global $wp_query, $wp_rewrite, $ae_post_factory;
                                $post_object = $ae_post_factory->get('place');

                                $number = get_option('posts_per_page', 10);
                                // $paged      = (get_query_var('paged')) ? get_query_var('paged') : 1;  
                                // $offset     = ($paged - 1) * $number;  

                                $all_cmts = get_comments(array(
                                    'user_id' => get_query_var('author'),
                                    'type' => 'favorite',
                                    'status' => 'approve'
                                ));
                                $query_args = array(
                                    'user_id' => get_query_var('author'),
                                    'type' => 'favorite',
                                    'number' => $number,
                                    'status' => 'approve',
                                    'paginate' => 'load_more'
                                );

                                $reviews = get_comments($query_args);
                                $comment_pages = ceil(count($all_cmts) / $number);

                                foreach ($reviews as $comment) {
                                    $de_review = $review_object->convert($comment);
                                    get_template_part('mobile/template/loop', 'place');
                                }
                                ?>
                            </ul>
                            <?php ae_comments_pagination($comment_pages, 1, $query_args); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabs 4 / End -->
            <!-- Tabs reservas -->
            <div class="tab-pane fade body-tabs" id="user_reservas">
                <div class="container" id="">
                    <div class="row" id="">
                        <?php //echo "---------------------------user_reservas-----";

                        $path = dirname(__FILE__);
                        $fulpath = $path . "/template/author-reservas-mobile.php";
                        include($fulpath);
                        ?>

                        <?php ///////////end/////////////////?>
                    </div>
                </div>
            </div>
            <!--// Tabs reservas/ End -->
            <!-- Tabs puntos -->
            <div class="tab-pane fade body-tabs" id="user_puntos">
                <div class="container" id="">
                    <div class="row" id="">
                        <?php //echo "------------------------user_puntos";

                        ////////////start///////////////?>
                        <?php
                        global $wp_query, $current_user, $DOPBSP_pluginSeries_translation;
                        $user_total_points = $wpdb->get_row("SELECT (COALESCE(sum(points_added),0) - COALESCE(sum(points_redeem),0)) as total_points FROM " . $wpdb->prefix . "tbl_points_log where user_id = '" . $current_user->ID . "' group by user_id  ");

                        $table_pt = $wpdb->prefix . 'tbl_point_settings';
                        $results = $wpdb->get_results('SELECT * FROM ' . $table_pt . '');

                        $points_worth = $results[0]->points_worth;

                        $total_points = $user_total_points->total_points == '' ? 0 : $user_total_points->total_points;
                        $total_price = $total_points * $points_worth
                        ?>
                        <style>
                            .points {
                                padding: 50px 60px;
                                background-color: #FFF;
                                margin: 0 auto;
                                width: 100%;
                            }

                            .points p.pts {
                                font-size: 20px;
                                font-weight: bold;
                                color: #000;
                                text-align: center;
                            }

                            .points p.val {
                                font-size: 16px;
                                font-weight: bold;
                                color: #000;
                                text-align: center;
                                padding-top: 10px;
                            }

                        </style>
                        <ul class="list-place-review list-posts list-places" id="publish-places" data-list="publish"
                            data-thumb="big_post_thumbnail">

                            <li class="points ppp">
                                <p class="pts">Puntos actuales: <?php echo $total_points; ?></p>

                                <p class="val"><?php echo $total_points ?> Puntos
                                    = <?php echo $total_price; ?> &euro;</p>
                            </li>

                        </ul>

                        <?php ///////end/////////////////?>
                    </div>
                </div>
            </div>
            <!--// Tabs puntos/ End -->
        </div>
    </section>
    <!-- Tabs / End -->

<?php et_get_mobile_footer(); ?>