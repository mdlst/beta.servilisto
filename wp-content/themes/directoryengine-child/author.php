<?php

/**
 * Template: Author Page
 * version 1.0
 * @author: enginethemes
 */


global $wp_query, $wp_rewrite, $current_user, $de_place_query, $user_ID;

$user = get_user_by('id', get_query_var('author'));

$ae_users = AE_Users::get_instance();

$user = $ae_users->convert($user);

$query_vars = $wp_query->query_vars;


/**
 * get author current section
 */

$review_url = ae_get_option('author_review_url', 'reviews');

$togo_url = ae_get_option('author_togo_url', 'togos');


$current_section = 'places';


if (isset($wp_query->query_vars['author_tab'])) {

    switch ($wp_query->query_vars['author_tab']) {

        case 'reviews':

            $current_section = 'reviews';

            break;

        case 'togos':

            $current_section = 'togos';

            break;

        case 'events':

            $current_section = 'events';

            break;
        case 'reservas':
            $current_section = 'reservas';

            break;
        case 'puntos':
            $current_section = 'puntos';

            break;
        case 'pendinglist' :

            $current_section = 'pending';

            if ($user_ID != $user->ID) {

                wp_redirect(home_url());

                exit;

            }

            break;

        default:

            $current_section = 'places';

            break;

    }

}


get_header();

// set current section to togos list

//if(array_key_exists( $togo_url, $wp_query->query_vars ) ) $current_section = 'togos';


?>


    <!-- Breadcrumb Blog -->

    <div class="section-detail-wrapper breadcrumb-blog-page">

        <ol class="breadcrumb">

            <li><a href="<?php echo home_url() ?>"
                   title="<?php echo get_bloginfo('name'); ?>"><?php _e("Home", ET_DOMAIN); ?></a></li>

            <li><a href="#"
                   title="<?php echo $user->display_name ?>"><?php printf(__('Profile of %s', ET_DOMAIN), $user->display_name); ?></a>
            </li>

        </ol>

    </div>

    <!-- Breadcrumb Blog / End -->


    <!-- Page Author -->

    <section id="author-page">

        <div class="container">

            <div class="row">


                <!-- Column left -->

                <div class="col-md-12">

                    <div class="profile-wrapper">

                        <?php if (is_user_logged_in() && $current_user->ID == get_query_var('author')) { ?>

                            <a href="#" class="edit-profile">

                                <div class="triagle-setting-top">

                                    <i class="fa fa-pencil"></i>

                                </div>

                            </a>

                        <?php } ?>

                        <div id="user_avatar_container">

                            <span class="img-author">

                                <span class="author-avatar image" id="user_avatar_thumbnail">

                                    <?php echo get_avatar($user->ID, 58) ?>

                                </span>

                                <?php if (is_user_logged_in() && $current_user->ID == get_query_var('author')) { ?>

                                    <a href="#" class="new-look" id="user_avatar_browse_button">

                                        <i class="fa fa-pencil"></i>

                                        <?php _e('New look', ET_DOMAIN) ?>

                                    </a>

                                <?php } ?>

                            </span>

                            <span class="et_ajaxnonce"
                                  id="<?php echo wp_create_nonce('user_avatar_et_uploader'); ?>"></span>

                        </div>

                        <div class="info-author-wrapper">

                            <h1 class="name-author"><?php echo $user->display_name; ?></h1>

                            <ul class="info-author-left" id="author_info">

                                <li class="location">

                                    <i class="fa fa-map-marker"></i><span><?php echo $user->location ? $user->location : __('Earth', ET_DOMAIN) ?></span>

                                </li>

                                <li class="phone">

                                    <i class="fa fa-phone"></i><span><?php echo $user->phone ? $user->phone : __('No phone', ET_DOMAIN) ?></span>

                                </li>

                                <li class="facebook">

                                    <i class="fa fa-facebook"></i><span><?php echo $user->facebook ? '<a target="_blank" href="' . $user->facebook . '">' . $user->facebook . '</a>' : '<a href="#">' . __('No facebook', ET_DOMAIN) . '</a>' ?></span>

                                </li>

                            </ul>

                            <ul class="info-author-left">

                                <li>

                                    <i class="fa fa-tree"></i>

                                    <?php

                                    $total_place = ae_count_user_posts_by_type($user->ID, 'place');

                                    if ($total_place > 1) {

                                        printf(__('owned %d places', ET_DOMAIN), $total_place);

                                    } else {

                                        printf(__('owned %d place', ET_DOMAIN), $total_place);

                                    }

                                    // printf(_n('owned %d place', , $total_place, ET_DOMAIN),$total_place) ;

                                    ?>

                                </li>

                                <li>

                                    <i class="fa fa-star"></i>

                                    <?php

                                    $total_review = get_comments(array('post_author' => get_query_var('author'), 'type' => 'review', 'status' => 'approve', 'meta_key' => 'et_rate'));

                                    $review_count = count($total_review);

                                    if ($review_count > 1) {

                                        printf(__('%d reviews', ET_DOMAIN), $review_count);

                                    } else {

                                        printf(__('%d review', ET_DOMAIN), $review_count);

                                    }

                                    // printf(_n('%d review', '%d reviews', , ET_DOMAIN),count($total_review) ) ;


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

                                    if ($total_picture > 1) {

                                        printf(__('%d pictures', ET_DOMAIN), $total_picture);

                                    } else {

                                        printf(__('%d picture', ET_DOMAIN), $total_picture);

                                    }

                                    // printf(_n('%d picture', '%d pictures', $total_picture, ET_DOMAIN),$total_picture) ;

                                    ?>

                                </li>

                            </ul>

                        </div>

                        <?php if (is_user_logged_in() && $current_user->ID != get_query_var('author')) { ?>

                            <a data-user="<?php echo get_query_var('author'); ?>" href="#"
                               class="contact-author-btn <?php if (is_user_logged_in()) {
                                   echo 'contact-owner';
                               } else {
                                   echo 'authenticate';
                               } ?>" data-id="<?php echo get_query_var('author'); ?>">

                                <?php _e("Contact This User", ET_DOMAIN) ?>

                            </a>

                        <?php } ?>

                        <div class="clearfix"></div>

                    </div>

                </div>


                <?php

                $col = ' col-md-4';

                if ($current_user->ID == get_query_var('author')) {

                    $col = ' col-md-4';

                }


                ?>

                <div class="col-md-12">

                    <div class="tab-info-wrapper row">
                        <?php ////heree////?>
                        <ul class="nav nav-tabs list-info-user-tab">

                            <!-- <li class="<?php if ($current_section == 'pending') {
                                echo 'active';
                            } ?> col-md-3">

                                    <a href="<?php echo get_author_posts_url(get_query_var('author')) . 'pendinglist/'; ?>">

                                        <i class="fa fa-ticket"></i><?php _e("Pending", ET_DOMAIN) ?>

                                    </a>

                                </li>     -->

                                <li class="<?php if ($current_section == 'places') {
                                    echo 'active';
                                }
                                echo $col; ?> ">

                                    <a href="<?php echo get_author_posts_url(get_query_var('author')); ?>">

                                        <i class="fa fa-tree"></i>

                                        <?php if ($current_user->ID == get_query_var('author')) {

                                            _e("Your listing", ET_DOMAIN);

                                        } else {

                                            _e("Places", ET_DOMAIN);

                                        }

                                        ?>

                                    </a>

                                </li>


                            <li class="<?php if ($current_section == 'events') {
                                echo 'active';
                            }
                            echo $col; ?> ">

                                <a href="<?php echo get_author_posts_url(get_query_var('author')) . 'events/'; ?>">

                                    <i class="fa fa-ticket"></i><?php _e("Events", ET_DOMAIN) ?>

                                </a>

                            </li>

                            <li class="<?php if ($current_section == 'reservas') {
                                echo 'active';
                            }
                            echo $col; ?> ">

                                <a href="<?php echo get_author_posts_url(get_query_var('author')) . 'reservas/'; ?>">

                                    <i class="fa fa-ticket"></i><?php _e("Reservas", ET_DOMAIN) ?>

                                </a>

                            </li>



                                <li class="<?php if ($current_section == 'reviews') {
                                    echo 'active';
                                }
                                echo $col; ?> ">

                                    <a href="<?php echo get_author_posts_url(get_query_var('author')) . 'reviews/'; ?>">

                                        <i class="fa fa-star"></i><?php _e("Reviews", ET_DOMAIN) ?>

                                    </a>

                                </li>



                            <?php if ($current_user->ID == get_query_var('author')) { ?>

                                <li class="<?php if ($current_section == 'togos') {
                                    echo 'active';
                                }
                                echo $col; ?> ">

                                    <a href="<?php echo get_author_posts_url(get_query_var('author')) . 'togos/'; ?>">

                                        <i class="fa fa-heart"></i><?php _e("Favorites", ET_DOMAIN) ?>

                                    </a>

                                </li>
                            <?php } ?>

                            <li class="<?php if ($current_section == 'puntos') {
                                echo 'active';
                            }
                            echo $col; ?> ">
                                <a href="<?php echo get_author_posts_url(get_query_var('author')) . 'puntos/'; ?>">
                                    <i class="fa fa-gift"></i><?php _e("&euro; Puntos", ET_DOMAIN) ?>
                                </a>
                            </li>

                        </ul>

                        <div class="tab-content">

                            <!-- Tabs 1 / Start -->

                            <div class="tab-pane fade active body-tabs in" id="list-places-wrapper">

                                <?php

                                if ($current_section == 'places' && $current_user->ID == get_query_var('author')) {

                                    get_template_part('template/author', 'pending');

                                } else {

                                    get_template_part('template/author', $current_section);

                                }

                                ?>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Column left / End -->


                <!-- Column right -->

                <?php //get_sidebar( 'single' ); ?>

                <!-- Column right / End -->

            </div>

        </div>

    </section>

    <!-- Page Author / End -->


<?php


get_footer();



