<?php
/**
 * new WordPress Widget format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class DE_Map_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function DE_Map_Widget() {
        $widget_ops = array( 'classname' => 'de-map', 'description' => __("DE Map", ET_DOMAIN) );
        parent::__construct( 'de-map', 'DE Map', $widget_ops );
    }

    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance ) {
        extract( $args, EXTR_SKIP );
        if(!is_singular('place')):
        ?>
        <section id="map-top-wrapper"></section>
        <?php
        endif;
    }

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance ) {

        // update logic goes here
        $updated_instance = $new_instance;
        return $updated_instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance ) {
        // $instance = wp_parse_args( (array) $instance, array() );
        _e("Directory map", ET_DOMAIN);
        // display field names here using:
        // $this->get_field_id( 'option_name' ) - the CSS ID
        // $this->get_field_name( 'option_name' ) - the HTML name
        // $instance['option_name'] - the option value
    }
}
/**
 * Categories widget class
 *
 * @since 1.0
 */
class DE_Categories_Widget extends WP_Widget {

    /**
     * construct DE_Categories_Widget
     */
    function __construct() {
        $widget_ops = array( 'classname' => 'de_widget_categories', 'description' => __( "A list of categories",ET_DOMAIN ) );
        parent::__construct('de_widget_categories', __('DE Categories',ET_DOMAIN), $widget_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $instance = wp_parse_args( $instance, array(
                'title'          => 'Default title' ,
            ));
        extract($instance);

        echo $before_widget;
        if ( $title )
            echo $before_title . apply_filters( 'widget_title' , $title ) . $after_title;

        $instance['parent'] = 0;
        // render list categories html
        de_categories_list( $instance );

        echo $after_widget;
    }
    // update widget settings
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['style'] = strip_tags($new_instance['style']);
        $instance['count'] = !empty($new_instance['count']) ? 1 : 0;
        $instance['hide_empty'] = !empty($new_instance['hide_empty']) ? 1 : 0;

        $instance['number'] = !empty($new_instance['number']) ? $new_instance['number'] : 10;
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }

    // render widget settings form
    function form( $instance ) {

        $instance = wp_parse_args( $instance, array(
                    'title' => '' ,
                    'style' => 'vertical' ,
                    'count' => false,
                    'hide_empty' => false,
                    'orderby' => 'name',
                    'number' => 15,
                )
            );
        extract($instance);

    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:',ET_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of categories:',ET_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e( 'Style:' , ET_DOMAIN ); ?></label>
            <select id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" class="widefat">
                <option <?php selected('vertical', $style); ?> value="vertical"><?php _e( 'Vertical' , ET_DOMAIN ); ?></option>
                <option <?php selected('horizontal', $style); ?> value="horizontal"><?php _e( 'Horizontal' , ET_DOMAIN ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Orderby:' , ET_DOMAIN ); ?></label>
            <select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" class="widefat">
                <option <?php selected('name', $orderby); ?> value="name"><?php _e( 'Name' , ET_DOMAIN ); ?></option>
                <option <?php selected('id', $orderby); ?> value="id"><?php _e( 'Id' , ET_DOMAIN ); ?></option>
                <option <?php selected('count', $orderby); ?> value="count"><?php _e( 'Count' , ET_DOMAIN ); ?></option>
                <option <?php selected('slug', $orderby); ?> value="slug"><?php _e( 'Slug' , ET_DOMAIN ); ?></option>
            </select>
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts',ET_DOMAIN ); ?></label><br />

            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>"<?php checked( $hide_empty ); ?> />
            <label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php _e( 'Hide empty',ET_DOMAIN ); ?></label><br />
        </p>
<?php
    }

}

/**
 * new WordPress Widget format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 * @author Dakachi
 */
class DE_ListPlace_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function DE_ListPlace_Widget() {
        $widget_ops = array( 'classname' => 'de-list-place', 'description' => __("DE List Places", ET_DOMAIN) );
        parent::__construct( 'de-list-place', __("DE List Places", ET_DOMAIN), $widget_ops );
    }

    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance ) {
        extract( $args, EXTR_SKIP );
        echo $before_widget;
        $instance = wp_parse_args( $instance, array(
                'title'             => '' ,
                'query'             => 'featured' ,
                'order'             => 'DESC' ,
                'showposts'         => 10,
                'style'             => 'vertical',
                'place_category'    => '',
                'location'          => '',
                'hide_empty'        => false,
                'paginate'          => '',
                'filterbar'         => '1',
                'defaultdisplay'    =>'0'
            ));
        extract($instance);
        global $ae_post_factory;
        $place_obj = $ae_post_factory->get('place');
        /**
         * show in top and bottom sidebar with fullwidth
        */
        $template = 'template/loop-place';
        $class    = "row list-wrapper";
        $thumnail = 'big_post_thumbnail';
        /**
         * show in left sidebar
        */
        if($style === 'vertical' || et_load_mobile()) {
            $style = 'vertical';
            $class    = 'widget-features-wrapper';
            $template = 'template/loop-place-vertical';
            $thumnail = 'small_post_thumbnail';
        }
        /**
         * setup order by
        */
        if($order != 'rand'){
            $orderby = 'date';
        }
        else{
            $orderby = 'rand';
        }
        /**
         * setup query args
        */
        $query_args = array(
            'orderby'   => $orderby,
            'order'     => $order,
            'showposts' => $showposts,
            'post_status' => 'publish',
            'place_category' => $place_category,
            'location' => $location
            );
        /**
         * check query featured ad
        */
        if($instance['query'] == 'featured') {
            $query_args['meta_key']  = 'et_featured';
            $query_args['meta_value']  = '1';
            $query_args['orderby']  = $orderby;
            $query_args['order']  = $order;
        }

        if($instance['query'] == 'event') {
            $query_args['meta_key']  = 'de_event_post';
            $query_args['meta_compare'] = '!=';
            $query_args['meta_value'] = '';
            $query_args['meta_type'] = 'NUMERIC';
            $query_args['orderby']  = $orderby;
            $query_args['order']  = $order;
        }
        $query = $place_obj->query($query_args);

        if($query->have_posts()) {

        ?>

        <div data-thumb="<?php echo $thumnail; ?>" class="block-posts " data-defaultdisplay="<?php echo $defaultdisplay?>" >
            <?php
                if(isset($instance['title']) && $instance['title'] != '') {
                    echo '<div class="filter-wrapper">';
                    echo $before_title  . $instance['title'] . $after_title;
                    echo '</div>';
                }
                if($style !== 'vertical' && $filterbar === '1' && $instance['order'] !== 'rand') {
                    echo '<div class="filter-wrapper">';
                    if(!$paginate)
                    {
                        global $post_in;
                        $post_in = "";
                        $count = 0;
                        while($query->have_posts()) { $query->the_post();
                            if($count)
                                $post_in .= ",".get_the_ID();
                            else
                                $post_in .= get_the_ID();
                            $count++;
                        }
                    }
                    include(locate_template('template/place-filter.php'));
                    echo '</div>';
                }
            ?>
            <div data-thumb="<?php echo $thumnail; ?>" class="<?php echo $class; ?>  "  >
                <ul data-thumb="<?php echo $thumnail; ?>" class=" list-places <?php if($style === 'vertical' || $defaultdisplay === '1') echo 'fullwidth'; if($style === 'vertical' ) echo ' vertical';?>"  data-list="<?php echo $instance['query']; ?>">
                <?php
                    global $post, $ae_post_factory;
                    $post_arr   =   array();
                    while($query->have_posts()) { $query->the_post();
                        $ae_post    =   $ae_post_factory->get('place');
                        $convert    =   $ae_post->convert($post, $thumnail);
                        $convert->defaultdisplay = $defaultdisplay;
                        $post_arr[] =   $convert;
                        get_template_part( $template );
                    }
                    echo '<script type="json/data" class="postdata" > ' . json_encode($post_arr) . '</script>';

                ?>
                </ul>
                    <?php
                    if( $style !== 'vertical' ) {
                        ?>
                        <div class="paginations-wrapper">
                            <?php
                                $meta_key = isset($query_args['meta_key']) ? $query_args['meta_key'] : '';
                                if($orderby == 'rand')
                                    de_refresh($query, 1 , $paginate,'',$meta_key);
                                else
                                    ae_pagination($query, 1 , $paginate );
                            ?>
                        </div>
                    <?php
                    }
                    ?>
            </div>
        </div>
            <?php

        }else{
            if(current_user_can('manage_options' )) {
                _e("Empty post with query", ET_DOMAIN);
            }
        }
        echo $after_widget;
        wp_reset_postdata();
    }

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance ) {

        // update logic goes here
        $updated_instance = $new_instance;
        return $updated_instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance ) {

        $instance = wp_parse_args( $instance, array(
                'title'          => '' ,
                'query'          => 'nearby' ,
                'showposts'      => 10,
                'style'          => 'vertical',
                'place_category' => '',
                'location'       => '',
                'hide_empty'     => false,
                'paginate'       => 'load_more'
            ));
        extract($instance);

        // display field names here using:
        // $this->get_field_id( 'option_name' ) - the CSS ID
        // $this->get_field_name( 'option_name' ) - the HTML name
        // $instance['option_name'] - the option value
    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:',ET_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e( 'Category:',ET_DOMAIN ); ?></label>
            <?php
            ae_tax_dropdown( 'place_category' ,
                            array(
                                    'class'           => '',
                                    'hide_empty'      => true,
                                    'hierarchical'    => true ,
                                    'id'              => $this->get_field_id('place_category'),
                                    'selected'        => $place_category,
                                    'name'            => $this->get_field_name('place_category'),
                                    'name_option'     => $this->get_field_name('place_category'),
                                    'show_option_all' => __("Select category", ET_DOMAIN),
                                    'value'           => 'slug'
                                )
                        ) ;
            ?>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('location'); ?>"><?php _e( 'Location:',ET_DOMAIN ); ?></label>
            <?php
            ae_tax_dropdown( 'location' ,
                            array(
                                    'class'           => '',
                                    'hide_empty'      => true,
                                    'hierarchical'    => true ,
                                    'id'              => $this->get_field_id('location'),
                                    'selected'        => $location,
                                    'name'            => $this->get_field_name('location'),
                                    'name_option'     => $this->get_field_name('location'),
                                    'show_option_all' => __("Select location", ET_DOMAIN),
                                    'value'           => 'slug'
                                )
                        ) ;
            ?>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e( 'Style:' , ET_DOMAIN ); ?></label>
            <select id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" class="widefat">
                <option <?php selected('vertical', $style); ?> value="vertical"><?php _e( 'Vertical' , ET_DOMAIN ); ?></option>
                <option <?php selected('horizontal', $style); ?> value="horizontal"><?php _e( 'Horizontal' , ET_DOMAIN ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('query'); ?>"><?php _e( 'Query:' , ET_DOMAIN ); ?></label>
            <select id="<?php echo $this->get_field_id('query'); ?>" name="<?php echo $this->get_field_name('query'); ?>" class="widefat">
                <option <?php selected('featured', $query); ?> value="featured"><?php _e( 'Featured Posts' , ET_DOMAIN ); ?></option>
                <option <?php selected('recent', $query); ?> value="recent"><?php _e( 'Recent Posts' , ET_DOMAIN ); ?></option>
                <option <?php selected('event', $query); ?> value="event"><?php _e( 'Has Event' , ET_DOMAIN ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('showposts'); ?>"><?php _e( 'Number of posts',ET_DOMAIN ); ?></label><br />
            <input type="text" value="<?php echo $showposts; ?>" class="widefat" id="<?php echo $this->get_field_id('showposts'); ?>" name="<?php echo $this->get_field_name('showposts'); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('paginate'); ?>"><?php _e( 'Paginate:' , ET_DOMAIN ); ?></label>
            <select id="<?php echo $this->get_field_id('paginate'); ?>" name="<?php echo $this->get_field_name('paginate'); ?>" class="widefat">
                <option <?php selected('', $paginate); ?> value="0"><?php _e( 'None' , ET_DOMAIN ); ?></option>
                <option <?php selected('page', $paginate); ?> value="page"><?php _e( 'Page paginate' , ET_DOMAIN ); ?></option>
                <option <?php selected('load_more', $paginate); ?> value="load_more"><?php _e( 'Load more' , ET_DOMAIN ); ?></option>
            </select>
        </p>

    <?php

    }
}

/**
 * new WordPress Widget format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class DE_Blog_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function DE_Blog_Widget() {
        $widget_ops = array( 'classname' => 'de-blog', 'description' => __("DE Recent Blog", ET_DOMAIN) );
        parent::__construct( 'de-blog', 'DE Recent Blog', $widget_ops );
    }

    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance ) {
         extract( $args, EXTR_SKIP );
        echo $before_widget;
        $instance = wp_parse_args( $instance, array(
                'title'       => '' ,
                'showposts'   => 10,
                'style'       => 'vertical',
                'category'    => '',
                'paginate'    => 'load_more',
                'post_type'   => 'post',
                'post_status' => 'publish'
            ));

        extract($instance);
        $instance['cat'] = $category;
        global $ae_post_factory, $post;
        $args = array(
            'showposts' => $instance['showposts'],
            'cat'    => $instance['category'],
            'post_type'   => 'post',
            'category_name' => $category,
            'post_status' => $instance['post_status']
        );

        $blog = new WP_Query($args);

        if(isset($instance['title']) && $instance['title']) {
            echo $before_title  . $instance['title'] . $after_title;
        }

        if($style== 'vertical') {
            $div_class = 'news-wrapper';
            $ul_class = 'list-news-widget';
            $template = 'template/loop-post-vertical';
        }else {
            $div_class = 'blog-wrapper';
            $ul_class = 'list-blog';
            $template = 'template/loop-post';
        }

    ?>

        <div class="<?php echo $div_class; ?>">
            <ul class="<?php echo $ul_class; ?>" data-thumb='thumbnail' >
            <?php
                $post_arr = array();
                if($blog->have_posts()) {
                    while($blog->have_posts()) { $blog->the_post();
                        $ae_post    =   $ae_post_factory->get('post');
                        $convert    =   $ae_post->convert($post, 'thumbnail');
                        $post_arr[] =   $convert;
                        get_template_part( $template );
                    }
            ?>
            </ul>
            <div class="paginations-wrapper">
                <?php
                        if($style !== 'vertical') {
                            ae_pagination($blog, 1, $paginate );
                        }
                        wp_reset_postdata();
                        echo '<script type="json/data" class="postdata" id="ae-posts-data"> ' . json_encode($post_arr) . '</script>';
                    }
                ?>
            </div>
        </div>

        <?php
        wp_reset_postdata();
        echo $after_widget;
    }

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance ) {

        // update logic goes here
        $updated_instance = $new_instance;
        return $updated_instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance ) {
        $instance = wp_parse_args( $instance, array(
                'title'          => '' ,
                'showposts'      => 10,
                'style'          => 'vertical',
                'category'       => '',
                'paginate'       => 'load_more'
            ));
        extract($instance);

    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:',ET_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e( 'Category:',ET_DOMAIN ); ?></label>
            <?php
            wp_dropdown_categories(array('selected' => $category, 'name' => $this->get_field_name('category')));
            ?>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e( 'Style:' , ET_DOMAIN ); ?></label>
            <select id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" class="widefat">
                <option <?php selected('vertical', $style); ?> value="vertical"><?php _e( 'Vertical' , ET_DOMAIN ); ?></option>
                <option <?php selected('horizontal', $style); ?> value="horizontal"><?php _e( 'Horizontal' , ET_DOMAIN ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('showposts'); ?>"><?php _e( 'Number of posts',ET_DOMAIN ); ?></label><br />
            <input type="text" value="<?php echo $showposts; ?>" class="widefat" id="<?php echo $this->get_field_id('showposts'); ?>" name="<?php echo $this->get_field_name('showposts'); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('paginate'); ?>"><?php _e( 'Paginate:' , ET_DOMAIN ); ?></label>
            <select id="<?php echo $this->get_field_id('paginate'); ?>" name="<?php echo $this->get_field_name('paginate'); ?>" class="widefat">
                <option <?php selected('', $paginate); ?> value="0"><?php _e( 'None' , ET_DOMAIN ); ?></option>
                <option <?php selected('page', $paginate); ?> value="page"><?php _e( 'Page paginate' , ET_DOMAIN ); ?></option>
                <option <?php selected('load_more', $paginate); ?> value="load_more"><?php _e( 'Load more' , ET_DOMAIN ); ?></option>
            </select>
        </p>

    <?php
        // display field names here using:
        // $this->get_field_id( 'option_name' ) - the CSS ID
        // $this->get_field_name( 'option_name' ) - the HTML name
        // $instance['option_name'] - the option value
    }
}


/**
 * new Directory Engine Review Widget
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class DE_Review_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function DE_Review_Widget() {
        $widget_ops = array( 'classname' => 'de-review', 'description' => __("De Review Widget", ET_DOMAIN) );
        parent::__construct( 'de-review', __("DE REVIEW", ET_DOMAIN), $widget_ops );
    }

    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance ) {
        $instance = wp_parse_args( (array) $instance, array('style' => 'vertical', 'number' => 4, 'paginate' => true , 'title' => __("REVIEWS", ET_DOMAIN) ) );
        extract( $args, EXTR_SKIP );
        extract($instance, EXTR_SKIP );

        echo $before_widget;
        echo $before_title;
        echo $instance['title']; // Can set this with a widget option, or omit altogether
        echo $after_title;

        echo '<div class="row comment-block" >';
            if($style == 'vertical') {
                $thumb_size = 'thumbnail';
                echo '<ul class="list-place-review vertical" >';
            }else{
                echo '<ul class="list-place-review" >';
                $thumb_size = 'review_post_thumbnail';
            }

                global $ae_post_factory;
                $review_object = $ae_post_factory->get('de_review'); // get review object

                $query_args = array(
                    'status' =>'approve',
                    'meta_key' => 'et_rate',
                    'type' => 'review',
                    'post_status' => 'publish',
                    'post_type' => 'place',
                    'meta_query' => array(
                        'relation' => 'AND',
                        array(
                            'key'       => 'et_rate',
                            'value'     => '0',
                            'compare'   => '>'
                        )
                    )
                );

                /**
                 * count all reivews
                */
                $total_args = $query_args;
                $all_cmts   = get_comments( $total_args );

                /**
                 * get page 1 reviews
                */
                // $query_args['number'] = $number;
                $query_args = wp_parse_args(  $instance, $query_args);
                $reviews = get_comments( $query_args );

                $paged = 1;
                $total_review = count($all_cmts);
                $comment_pages  =   ceil( $total_review/$number );

                $comment_arr = array();
                if( !empty($reviews) ){
                    foreach ($reviews as $comment) {
                        // convert review object
                        $convert = $review_object->convert($comment, $thumb_size);
                        $comment_arr[] = $convert;
                        // check view style to load the loop template
                        if($style == 'vertical'){
                            get_template_part( 'template/loop', 'review-vertical' );
                        }else {
                            get_template_part( 'template/loop', 'review' );
                        }
                    }
                    // reset author and post data array
                    $review_object->reset();
                }

            echo '</ul>';
            $query_args['total'] = $comment_pages;
            echo '<div class="paginations-wrapper">';
                if( $style != 'vertical' && $paginate ) { // render pagination
                    ae_comments_pagination( $comment_pages, $paged ,$query_args );
                }
                // render js data for use
                echo '<script type="json/data" class="postdata" > ' . json_encode($comment_arr) . '</script>';
            echo '</div>';
        echo '</div>';
        echo $after_widget;
        wp_reset_postdata();
        // remove_filter( 'comments_clauses' , array($this, 'groupby'), 10, 4 );
    }

    function groupby( $args ){
        global $wpdb;
        $args['groupby'] = ' ' .$wpdb->comments.'.comment_post_ID';
        return $args;
    }

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance ) {

        // update logic goes here
        $new_instance['paginate'] = !empty($new_instance['paginate']) ? 1 : 0;

        $updated_instance = $new_instance;
        return $updated_instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'style' => 'horizontal', 'number' => 4, 'paginate' => true , 'title' => __("REVIEWS", ET_DOMAIN) ) );

        extract($instance);
        // display field names here using:
        // $this->get_field_id( 'option_name' ) - the CSS ID
        // $this->get_field_name( 'option_name' ) - the HTML name
        // $instance['option_name'] - the option value
    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:',ET_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of posts',ET_DOMAIN ); ?></label><br />
            <input type="text" value="<?php echo $number; ?>" class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e( 'Style:' , ET_DOMAIN ); ?></label>
            <select id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" class="widefat">
                <option <?php selected('vertical', $style); ?> value="vertical"><?php _e( 'Vertical' , ET_DOMAIN ); ?></option>
                <option <?php selected('horizontal', $style); ?> value="horizontal"><?php _e( 'Horizontal' , ET_DOMAIN ); ?></option>
            </select>
        </p>

        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('paginate'); ?>" name="<?php echo $this->get_field_name('paginate'); ?>" <?php checked( $paginate ); ?> />
            <label for="<?php echo $this->get_field_id('paginate'); ?>"><?php _e( 'Show pagination',ET_DOMAIN ); ?></label><br />

        </p>
    <?php
    }
}


/**
 * new WordPress Widget format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class DE_Social_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function DE_Social_Widget() {
        $widget_ops = array( 'classname' => 'de-social', 'description' => __("DE Social Widget", ET_DOMAIN) );
        parent::__construct( 'de-social', 'DE Social', $widget_ops );
    }

    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance ) {
        extract( $args, EXTR_SKIP );
        echo $before_widget;
        echo $before_title;
        echo $instance['title']; // Can set this with a widget option, or omit altogether
        echo $after_title;
    ?>
        <ul class="social-list-footer">
            <?php if( ae_get_option('site_facebook') ) {
                echo '<li><a target="_blank" class="ico-social-link" href="'. ae_get_option('site_facebook') .'"><span><i class="fa fa-facebook"></i></span>'.__("Facebook", ET_DOMAIN).'</a></li>' ;
            } ?>
            <?php if( ae_get_option('site_twitter') ) {
                echo '<li><a target="_blank" class="ico-social-link" href="'. ae_get_option('site_twitter') .'"><span class="ico-twitter"><i class="fa fa-twitter"></i></span>'. __("Twitter", ET_DOMAIN).'</a></li>' ;
            } ?>

            <?php if( ae_get_option('site_google') ) {
                echo '<li><a target="_blank" class="ico-social-link" href="'. ae_get_option('site_google') .'"><span class="ico-google-plus"><i class="fa fa-google-plus"></i></span>'.__("Google+", ET_DOMAIN).'</a></li>' ;
            } ?>
            <!-- <li><a href="#"><span><i class="fa fa-twitter"></i></span></a></li>
            <li><a href="#"><span><i class="fa fa-google-plus"></i></span></a></li>
            <li><a href="#"><span><i class="fa fa-pinterest"></i></span>Pinterest</a></li>
            <li><a href="#"><span><i class="fa fa-linkedin"></i></span>LinkedIn</a></li> -->
        </ul>
    <?php
        echo $after_widget;
    }

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance ) {

        // update logic goes here
        $updated_instance = $new_instance;
        return $updated_instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => __("Keep in touch", ET_DOMAIN) ) );
        extract($instance);
    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:',ET_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
    <?php
    }
}


/**
 * new WordPress Widget format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class DE_Testimonial_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function DE_Testimonial_Widget() {
        $widget_ops = array( 'classname' => 'de-testimonial', 'description' => __("DE Testimonial Widget", ET_DOMAIN) );
        parent::__construct( 'de-testimonial', 'DE Testimonial', $widget_ops );
    }

    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance ) {
        extract( $args, EXTR_SKIP );
        // echo $before_widget;
        $instance = wp_parse_args( $instance, array(
                'title'  => '' ,
                'number' => 4,
            ));

        extract($instance);

        /**
         * setup query args
        */
        $query_args = array('orderby' => 'date' , 'showposts' => $number , 'post_status' => 'publish', 'post_type' => 'testimonial');

        $query = new WP_Query($query_args);
        if($query->have_posts()) {

        ?>

        <!-- <section class="carousel-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12"> -->
                        <div class="testimonial-wrapper">
                            <div class="customNavigation">
                              <a class="prev-testi"><i class="fa fa-chevron-left"></i></a>
                              <a class="next-testi"><i class="fa fa-chevron-right"></i></a>
                            </div>
                            <div id="testimonial" class="owl-carousel">
                                <?php
                                    global $post;
                                    while($query->have_posts()) {
                                        $query->the_post();
                                ?>
                                <div class="tes-wrapper">
                                    <div class="avatar-info">
                                        <span class="avatar"><?php the_post_thumbnail( 'thumbnail' ); ?></span>

                                    </div>
                                    <div class="quote-testi">
                                    	<h2 class="name"><?php the_title(); ?></h2>
                                        <!--27_10_2016 Id : 1 S-->
                                        <img class="oculto" src="<?php echo get_template_directory_uri() ?>/img/quote.png" alt="quote">
                                        <?php echo $post->post_content; ?>
                                        <!--27_10_2016 Id : 1 E-->
                                    </div>
                                </div>
                                <?php
                                    }
                                    wp_reset_query();
                                ?>
                            </div>
                        </div>
                    <!-- </div>
                </div>
            </div>
        </section> -->
            <?php
            // echo $after_widget;
        }else{
            if(current_user_can('manage_options' )) _e("Empty post with query", ET_DOMAIN);
        }
        wp_reset_postdata();
    }

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance ) {

        // update logic goes here
        $updated_instance = $new_instance;
        return $updated_instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance ) {
        $instance = wp_parse_args( $instance, array(
                'title'          => '' ,
                'showposts'      => 4
            ));

        extract($instance);

    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:',ET_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('showposts'); ?>"><?php _e( 'Number of posts',ET_DOMAIN ); ?></label><br />
            <input type="text" value="<?php echo $showposts; ?>" class="widefat" id="<?php echo $this->get_field_id('showposts'); ?>" name="<?php echo $this->get_field_name('showposts'); ?>" />
        </p>
    <?php

        // display field names here using:
        // $this->get_field_id( 'option_name' ) - the CSS ID
        // $this->get_field_name( 'option_name' ) - the HTML name
        // $instance['option_name'] - the option value
    }
}


/**
 * this file contain widgets support by DirectoryEngine
 * Widget_List_Categories
*/

add_action('widgets_init', 'de_register_sidebars');
function de_register_sidebars() {

    register_widget( 'DE_Categories_Widget' );
    register_widget( 'DE_ListPlace_Widget' );
    register_widget( 'DE_Review_Widget' );
    register_widget( 'DE_Social_Widget' );
    register_widget( 'DE_Blog_Widget' );
    register_widget( 'DE_Testimonial_Widget' );
    register_widget( 'DE_Map_Widget' );
    register_widget( 'DE_Nearby_Widget' );
    //register_widget( 'DE_Areas_Widget' );
    /**
    * Creates a sidebar Main List
    * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
    */
    $args = array(
        'name'          => __( 'Main Sidebar', ET_DOMAIN ),
        'id'            => 'de-main',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<div id="%1$s" class="widget-wrapper widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<p class="widgettitle">',
        'after_title'   => '</p>'
    );
    register_sidebar( $args );

    /**
    * Creates a sidebar on top List
    * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
    */
    $args = array(
        'name'          => __( 'Top Sidebar', ET_DOMAIN ),
        'id'            => 'de-top',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<div id="%1$s" class="widget-wrapper widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>'
    );
    register_sidebar( $args );

    /**
    * Creates a sidebar on bottom List
    * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
    */
    $args = array(
        'name'          => __( 'Bottom Sidebar', ET_DOMAIN ),
        'id'            => 'de-bottom',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<div id="%1$s" class="widget-wrapper widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<p class="widgettitle">',
        'after_title'   => '</p>'
    );
    register_sidebar( $args );


    /**
    * Creates a sidebar on top List
    * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
    */
    $args = array(
        'name'          => __( 'Top Fullwidth Sidebar', ET_DOMAIN ),
        'id'            => 'de-fullwidth-top',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<div id="%1$s" class=" widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>'
    );
    register_sidebar( $args );


    /**
    * Creates a sidebar on bottom List
    * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
    */
    $args = array(
        'name'          => __( 'Bottom Fullwidth Sidebar', ET_DOMAIN ),
        'id'            => 'de-fullwidth-bottom',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<div id="%1$s" class=" widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>'
    );
    register_sidebar( $args );

     /**
    * Creates a sidebar on mobile top List
    * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
    */
    $args = array(
        'name'          => __( 'Mobile Top Sidebar', ET_DOMAIN ),
        'id'            => 'de_mobile_top',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<div id="%1$s" class="widget-wrapper widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>'
    );
    register_sidebar( $args );

    /**
    * Creates a sidebar Single Place
    * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
    */
    $args = array(
        'name'          => __( 'Single', ET_DOMAIN ),
        'id'            => 'de-single',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<div id="%1$s" class="widget-wrapper widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>'
    );

    register_sidebar( $args );

   /**
    * Creates a sidebar Footer 1
    * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
    */
    $args = array(
        'name'          => __( 'Footer 1', ET_DOMAIN ),
        'id'            => 'de-footer-1',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<p class="widgettitle">',
        'after_title'   => '</p>'
    );

    register_sidebar( $args );

    /**
    * Creates a sidebar Footer 2
    * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
    */
    $args = array(
        'name'          => __( 'Footer 2', ET_DOMAIN ),
        'id'            => 'de-footer-2',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<p class="widgettitle">',
        'after_title'   => '</p>'
    );

    register_sidebar( $args );


    /**
    * Creates a sidebar Footer 3
    * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
    */
    $args = array(
        'name'          => __( 'Footer 3', ET_DOMAIN ),
        'id'            => 'de-footer-3',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<p class="widgettitle">',
        'after_title'   => '</p>'
    );

    register_sidebar( $args );


    /**
    * Creates a sidebar Footer 4
    * @param string|array  Builds Sidebar based off of 'name' and 'id' values.
    */
    $args = array(
        'name'          => __( 'Footer 4', ET_DOMAIN ),
        'id'            => 'de-footer-4',
        'description'   => '',
        'class'         => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<p class="widgettitle">',
        'after_title'   => '</p>'
    );

    register_sidebar( $args );

}
/**
 * The nearby widget for Directoryengine
 *
 * @since Directoryengine-v1.8.4
 * @author Tambh
 */
class DE_Nearby_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
    function DE_Nearby_Widget() {
        $widget_ops = array( 'classname' => 'de-nearby', 'description' => __("DE Nearby", ET_DOMAIN) );
        parent::__construct( 'de-nearby', __("DE Nearby", ET_DOMAIN), $widget_ops );
    }

    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     * @return void Echoes it's output
     **/
    function widget( $args, $instance ) {
        extract( $args, EXTR_SKIP );
        echo $before_widget;
        $instance = wp_parse_args( $instance, array(
                'title'          => '' ,
                'query'          => 'featured' ,
                'showposts'      => 10,
                'style'          => 'vertical',
                'place_category' => '',
                'location'       => '',
                'hide_empty'     => false,
                'paginate'       => 'load_more',
                'filterbar'=> '1',
                'defaultdisplay'=>'0'
            ));

        extract($instance);

        /**
         * show in top and bottom sidebar with fullwidth
        */

        $class    = "row list-wrapper";
        $thumnail = 'big_post_thumbnail';
        /**
         * show in left sidebar
        */
        if($style === 'vertical') {
            $class    = 'widget-features-wrapper';
            $thumnail = 'small_post_thumbnail';
        } ?>

        <div data-thumb="<?php echo $thumnail; ?>" class="nearby-block" data-defaultdisplay="<?php echo $defaultdisplay?>" >
            <div data-thumb="<?php echo $thumnail; ?>" class="<?php echo $class; ?>  "  >
                <h2 class="widgettitle"><?php echo $title; ?></h2>
                <ul data-thumb="<?php echo $thumnail; ?>" class=" list-places <?php if($style === 'vertical' || $defaultdisplay === '1') echo 'fullwidth'; ?>"  data-list="<?php echo $instance['query']; ?>">

                        <input type="hidden" value="<?php echo $radius; ?>" name="radius" />
                        <input type="hidden" value="<?php echo $showposts; ?>" name="showposts" />
                        <input type="hidden" value="<?php echo $style; ?>" name="style" />
                <span class="first_text">
                    <?php _e( 'Please share your location to view this widget content', ET_DOMAIN ); ?>
                </span>
                </ul>

            </div>
        </div>

<?php
        echo $after_widget;
    }

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
    function update( $new_instance, $old_instance ) {

        // update logic goes here
        $updated_instance = $new_instance;
        return $updated_instance;
    }

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
    function form( $instance ) {

        $instance = wp_parse_args( $instance, array(
                'title'          => '' ,
                'query'          => 'nearby' ,
                'showposts'      => 10,
                'style'          => 'vertical',
                'place_category' => '',
                'location'       => '',
                'hide_empty'     => false,
                'paginate'       => 'load_more',
                'radius'         => 10
            ));
        extract($instance);

        // display field names here using:
        // $this->get_field_id( 'option_name' ) - the CSS ID
        // $this->get_field_name( 'option_name' ) - the HTML name
        // $instance['option_name'] - the option value
    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:',ET_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e( 'Style:' , ET_DOMAIN ); ?></label>
            <select id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" class="widefat">
                <option <?php selected('vertical', $style); ?> value="vertical"><?php _e( 'Vertical' , ET_DOMAIN ); ?></option>
                <option <?php selected('horizontal', $style); ?> value="horizontal"><?php _e( 'Horizontal' , ET_DOMAIN ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('showposts'); ?>"><?php _e( 'Number of posts',ET_DOMAIN ); ?></label><br />
            <input type="text" value="<?php echo $showposts; ?>" class="widefat" id="<?php echo $this->get_field_id('showposts'); ?>" name="<?php echo $this->get_field_name('showposts'); ?>" />
        </p>
          <p>
            <label for="<?php echo $this->get_field_id('radius'); ?>"><?php _e( 'Radius',ET_DOMAIN ); ?></label><br />
            <input type="text" value="<?php echo $radius; ?>" class="widefat" id="<?php echo $this->get_field_id('radius'); ?>" name="<?php echo $this->get_field_name('radius'); ?>" />
        </p>

    <?php

    }
}

/**
 * Areas widget for Directoryengine
 *
 * @since Directoryengine-v1.8.4
 * @author Tuandq
 */
class DE_Areas_Widget extends WP_Widget {
    /**
     * construct DE_Place_Tag_Widget
     */
    function __construct() {
        $widget_ops = array( 'classname' => 'de_widget_area', 'description' => __( "A list of areas",ET_DOMAIN ) );
        parent::__construct('de_widget_areas', __('DE Areas',ET_DOMAIN), $widget_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $instance = wp_parse_args( $instance, array(
                'title'          => 'Default title' ,
            ));
        echo $before_widget;
        if ( $title )
            echo $before_title . apply_filters( 'widget_title' , $title ) . $after_title;
        $loca =  new AE_Category( array( 'taxonomy' => 'location') );
        if($instance['orderby'] == 'name')
            $instance['order'] = 'ASC';
        $areas = $loca->getAll($instance);
        extract( $instance );
        echo '<div class="filter-wrapper">';
        echo '<h2 class="widgettitle">'.$title.'</h2>';
        echo '</div>';
        echo '<div class="row list-wrapper">';
        echo   '<ul class="list-areas vertical">';
        foreach ($areas as $key => $loca) {
            ?>
            <li class="col-md-12">
                <a href="<?php echo get_term_link($loca, 'place_category') ?>" title="<?php echo $loca->name; ?>">
                    <span class="area-name"><?php echo $loca->name; ?></span>
                    <?php
                        if($instance['count'])
                            echo '<span class="area-number">'.$loca->count.'</span>';
                    ?>
                </a>
            </li>
            <?php
        }
        echo '</ul></div>';
        echo $after_widget;
    }
    // update widget settings
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['count'] = !empty($new_instance['count']) ? 1 : 0;
        $instance['hide_empty'] = !empty($new_instance['hide_empty']) ? 1 : 0;
        $instance['number'] = !empty($new_instance['number']) ? $new_instance['number'] : 10;
        $instance['orderby'] = $new_instance['orderby'];
        return $instance;
    }

    // render widget settings form
    function form( $instance ) {

        $instance = wp_parse_args( $instance, array(
                    'title' => '' ,
                    'count' => false,
                    'hide_empty' => false,
                    'orderby' => 'name',
                    'number' => 15,
                )
            );
        extract($instance);

    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:',ET_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of areas:',ET_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Orderby:' , ET_DOMAIN ); ?></label>
            <select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" class="widefat">
                <option <?php selected('name', $orderby); ?> value="name"><?php _e( 'Name' , ET_DOMAIN ); ?></option>
                <option <?php selected('id', $orderby); ?> value="id"><?php _e( 'Id' , ET_DOMAIN ); ?></option>
                <option <?php selected('count', $orderby); ?> value="count"><?php _e( 'Count' , ET_DOMAIN ); ?></option>
                <option <?php selected('slug', $orderby); ?> value="slug"><?php _e( 'Slug' , ET_DOMAIN ); ?></option>
            </select>
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts',ET_DOMAIN ); ?></label><br />

            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('hide_empty'); ?>" name="<?php echo $this->get_field_name('hide_empty'); ?>"<?php checked( $hide_empty ); ?> />
            <label for="<?php echo $this->get_field_id('hide_empty'); ?>"><?php _e( 'Hide empty',ET_DOMAIN ); ?></label><br />
        </p>
    <?php
    }

}
