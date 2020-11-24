<?php
/**
 * Template Name: Page List Users
*/
global $post, $wp_query,$ae_post_factory, $user_ID;
get_header();
$number         =   get_option('posts_per_page');
$args = array ('number' => $number, 'count_total' => true, 'role'=> 'author', 'page_list_user' => 1 );
$users_query    =   new WP_User_Query($args);

$total_users  =   $users_query->total_users;
$users  =   $users_query->results;

$total_pages  =   ceil($total_users/$number);

$ae_users   =   AE_Users::get_instance();
$found_posts = '<span class="found_post">'.$total_users.'</span>';
if($total_users <=1){
   $count_user =  sprintf(__('%s User', ET_DOMAIN) , $found_posts );
}
else{
    $count_user =  sprintf(__('%s Users', ET_DOMAIN) , $found_posts);
}
?>
<!-- Breadcrumb List users -->
<div class="section-detail-wrapper breadcrumb-blog-page">
    <ol class="breadcrumb">
        <li><a href="<?php echo home_url() ?>" title="<?php echo get_bloginfo( 'name' ); ?>" ><?php _e("Home", ET_DOMAIN); ?></a></li>
        <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
    </ol>
</div>
<!-- Breadcrumb List users / End -->

<!-- Page List Users -->
<section id="list-user-page">
    <div class="container">
        <div class="row">
            <!-- Column left -->
            <div class="col-md-9 col-xs-12">
                <div class="list-user-page-wrapper">
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <span class="number-user-list">
                            <?php echo $count_user;?>
                            </span>
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <div class="search-list-user-page">
                                <label><?php _e('Search By Name:', ET_DOMAIN);?></label>
                                <input class="search" type="text" name="user_search" id="user_search" placeholder="<?php _e('Keyword', ET_DOMAIN); ?>" value=""  />
                            </div>
                        </div>
                    </div>
                    <ul class="list-user-page-info">
                        <?php
                            if($users){
                                foreach ($users as $key => $value) {
                                    $user       = $ae_users->convert($value);
                                    $user_arr[] = $user;
                                    get_template_part('template/user', 'item');
                                }
                            wp_reset_query();
                            echo '<script type="json/data" class="userdata" > ' . json_encode($user_arr) . '</script>';
                            }
                            else{
                                _e('0 user are found', ET_DOMAIN);
                            }
                        ?>
                    </ul>
                    <div class="paginations-wrapper">
                        <?php ae_user_pagination($args, $total_pages);?>
                    </div>
                </div>
            </div>
            <!-- Column left / End -->

            <!-- Column right -->
            <?php get_sidebar( 'single' ); ?>
            <!-- Column right / End -->
        </div>
    </div>
</section>
<!-- Page List Users / End -->   

<?php
get_footer();

