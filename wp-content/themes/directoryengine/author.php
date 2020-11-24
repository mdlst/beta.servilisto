<?php
/**
 * Template: Author Page
 * version 1.0
 * @author: enginethemes
 */

global $wp_query, $wp_rewrite, $current_user,$de_place_query , $user_ID;
$user       = get_user_by( 'id', get_query_var( 'author' ) );
$ae_users   = AE_Users::get_instance();
$user       = $ae_users->convert($user);
$query_vars = $wp_query->query_vars;

/**
 * get author current section
*/
$review_url =   ae_get_option('author_review_url', 'reviews');
$togo_url   =   ae_get_option('author_togo_url', 'togos');

$current_section  = 'places';
if(isset($wp_query->query_vars['author_tab']) ) {
    switch ($wp_query->query_vars['author_tab']) {
        case 'collections':
            $current_section = 'collections';
            break;
        case 'reviews':
            $current_section = 'reviews';
            break;
        case 'togos':
            $current_section = 'togos';
            break;
        case 'events':
            $current_section = 'events';
            break;
        case 'pendinglist' : 
            $current_section = 'pending';
            if($user_ID != $user->ID ){
                wp_redirect( home_url() );
                exit;
            }
            break;
        default:       
            $current_section  = 'places';     
            break;
    }
}
get_header();
?>

<!-- Breadcrumb Blog -->
<div class="section-detail-wrapper breadcrumb-blog-page">
    <ol class="breadcrumb">
        <li><a href="<?php echo home_url() ?>" title="<?php echo get_bloginfo( 'name' ); ?>" ><?php _e("Home", ET_DOMAIN); ?></a></li>
        <li><a href="#" title="<?php echo $user->display_name ?>"><?php printf(__( 'Profile of %s' , ET_DOMAIN ), $user->display_name); ?></a></li>
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
                        <?php if(is_user_logged_in() && $current_user->ID == get_query_var( 'author' ) ){ ?>
                        <a href="#" class="edit-profile">
                            <div class="triagle-setting-top">
                                <i class="fa fa-pencil"></i>
                            </div>
                        </a>
                        <?php } ?>
                        <div id="user_avatar_container">
                            <span class="img-author">
                                <span class="author-avatar image" id="user_avatar_thumbnail">
                                    <?php echo get_avatar($user->ID, 135) ?>
                                </span>
                                <?php if(is_user_logged_in() && $current_user->ID == get_query_var( 'author' ) ){ ?>
                                <a href="#" class="new-look" id="user_avatar_browse_button">
                                    <i class="fa fa-pencil"></i>
                                    <?php _e('New look', ET_DOMAIN) ?>
                                </a>
                                <?php } ?>
                            </span>
                            <span class="et_ajaxnonce" id="<?php echo wp_create_nonce( 'user_avatar_et_uploader' ); ?>"></span>
                        </div>
                        <div class="info-author-wrapper">
                            <h1 class="name-author"><?php echo $user->display_name; ?></h1>
                            <!-- author basic info -->
                            <ul class="info-author-left" id="author_info">
                                <li class="location">
                                    <i class="fa fa-map-marker"></i>
                                    <span>
                                    <?php echo $user->location ? $user->location : __('Earth', ET_DOMAIN) ?>
                                    </span>
                                </li>
                                <li class="phone">
                                    <i class="fa fa-phone"></i>
                                    <span>
                                        <?php echo $user->phone ? $user->phone : __('No phone', ET_DOMAIN) ?>
                                    </span>
                                </li>
                                <li class="facebook">
                                    <i class="fa fa-facebook"></i>
                                    <span>
                                        <?php echo $user->facebook ? '<a target="_blank" href="'.$user->facebook.'">'.$user->facebook.'</a>' : '<a href="#">'.__('No facebook', ET_DOMAIN).'</a>' ?>
                                    </span>
                                </li>
                            </ul>
                            <!--// author basic info -->

                            <!-- author places, reviews, photos info -->
                            <ul class="info-author-left">
                                <li>
                                    <i class="fa fa-tree"></i> 
                                    <?php 
                                        $total_place =  ae_count_user_posts_by_type($user->ID, 'place');
                                        if($total_place > 1 )  {
                                            printf(__('Owned %d places', ET_DOMAIN), $total_place);
                                        }else {
                                            printf(__('Owned %d place', ET_DOMAIN), $total_place);
                                        }
                                    ?> 
                                </li>
                                <li>
                                    <i class="fa fa-star"></i> 
                                    <?php
                                        $total_review = get_comments(array(
                                            'user_id' => get_query_var( 'author' ), 
                                            'type' => 'review', 
                                            'status' => 'approve',
                                            'meta_key' => 'et_rate',
                                            'meta_query' =>  array(
                                                'relation' => 'AND',
                                                array(
                                                    'key'       => 'et_rate',
                                                    'value'     => '0',
                                                    'compare'   => '>'
                                                )
                                            )
                                        ));
                                        $review_count = count($total_review);
                                        if($review_count > 1 )  {
                                            printf(__('%d reviews', ET_DOMAIN), $review_count);
                                        }else {
                                            printf(__('%d review', ET_DOMAIN), $review_count);
                                        }
                                        
                                    ?>
                                </li>
                                <li>
                                    <i class="fa fa-photo"></i>
                                    <?php  
                                        add_filter( 'posts_orderby', 'order_by_post_status' );
                                        $pending = new WP_Query(array(
                                            'post_type'   => array('place','post'),
                                            'post_status' => array('publish'),
                                            'author' => get_query_var( 'author' ),
                                            'posts_per_page' => -1,
                                        ));
                                        remove_filter( 'posts_orderby', 'order_by_post_status' );

                                        $array_id = array();
                                        foreach ($pending->posts as $key => $value) {
                                            $array_id[] = $value->ID;
                                        }
                                        $query_img_args = array(
                                            'author'=> $user->ID,
                                            'post_type' => 'attachment',
                                            'post_mime_type' =>array(
                                                            'jpg|jpeg|jpe' => 'image/jpeg',
                                                            'gif' => 'image/gif',
                                                            'png' => 'image/png',
                                                            ),
                                            'post_status' => 'inherit',
                                            'posts_per_page' => -1,
                                            'post_parent__in' => $array_id
                                            );
                                        query_posts( $query_img_args );
                                        $total_picture = $wp_query->found_posts;
                                        wp_reset_query();
                                        if($total_picture > 1 )  {
                                            printf(__('%d pictures', ET_DOMAIN), $total_picture);
                                        }else {
                                            printf(__('%d picture', ET_DOMAIN), $total_picture);
                                        }
                                    ?> 
                                </li>
                            </ul>
                            <!--// author places, reviews, photos info -->
                        </div>
                        <?php if( is_user_logged_in() && $current_user->ID != get_query_var( 'author' ) ) {?>
                        <a data-user="<?php echo get_query_var( 'author' ); ?>" href="#" class="btn-contact-author <?php if(is_user_logged_in()) { echo 'contact-owner'; }else { echo 'authenticate'; } ?>" data-id="<?php echo get_query_var( 'author' ); ?>">
                            <?php _e("Contact This User", ET_DOMAIN) ?>
                        </a>
                        <?php } ?>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <?php
                if($current_user->ID == get_query_var( 'author' )){
                    $col = ' col-md-3 col-xs-3';
                }else{
                    $col = ' col-md-4 col-xs-4';
                }
                if(has_action('collection_tab')){
                    $col = ' col-md-3';
                }
                if($current_user->ID == get_query_var( 'author' ) && has_action('collection_tab')) {
                    $col = ' col-md-2';
                }elseif(has_action('collection_tab')){
                    $col = ' col-md-3';
                }

                ?>
                <div class="col-md-12">
                    <div class="tab-info-wrapper">
                        <ul class="nav nav-tabs list-info-user-tab">
                            <?php if(has_action('collection_tab')){?>
                                <li class="col-md-offset-1"></li>
                            <?php } ?>
                            <li class="<?php if($current_section  == 'places'){ echo  'active'; } echo $col; ?> ">
                                <a href="<?php echo get_author_posts_url( get_query_var( 'author' ) ); ?>">
                                    <i class="fa fa-tree"></i>
                                    <?php _e("Places", ET_DOMAIN) ;?>
                                </a>
                            </li>
                            <li class="<?php if( $current_section  == 'events'){ echo  'active';  } echo $col; ?> ">
                                <a href="<?php echo get_author_posts_url( get_query_var( 'author' ) ).'events/'; ?>">
                                    <i class="fa fa-ticket"></i><?php _e("Events", ET_DOMAIN) ?>
                                </a>
                            </li>
                            <li class="<?php if( $current_section  == 'reviews'){ echo  'active';  } echo $col; ?> ">
                                <a href="<?php echo get_author_posts_url( get_query_var( 'author' ) ).'reviews/'; ?>">
                                    <i class="fa fa-star"></i><?php _e("Reviews", ET_DOMAIN) ?>
                                </a>
                            </li>
                            <?php if($current_user->ID == get_query_var( 'author' )){ ?>
                            <li class="<?php if($current_section  == 'togos'){ echo  'active';  } echo $col; ?> ">
                                <a href="<?php echo get_author_posts_url( get_query_var( 'author' ) ).'togos/'; ?>">
                                    <i class="fa fa-heart"></i><?php _e("Togos", ET_DOMAIN) ?>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if(has_action('collection_tab')){
                                do_action('collection_tab', $col,$current_section);
                            } ?>
                        </ul>
                        <div class="tab-content row">
                            <!-- Tabs 1 / Start -->
                            <div class="tab-pane fade active body-tabs in" id="list-places-wrapper" >
                                <?php
                                    if($current_section == 'collections'){
                                        do_action('collection_content',$user->ID);
                                    }else{
                                        get_template_part( 'template/author', $current_section );
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column left / End --> 
        </div>
    </div>
</section>
<!-- Page Author / End -->   
<?php
get_footer();

