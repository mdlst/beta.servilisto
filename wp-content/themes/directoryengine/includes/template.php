<?php

/**
 * this file contain all function render template for theme
 */

if (!function_exists('de_categories_list')) {

    /**
     * render place categories list with associate color and icon
     * @param array|the $args the args for query term
     * @author Dakachi
     * @verion 1.0
     */
    function de_categories_list($args = array('hide_empty' => false))
    {

        $args = wp_parse_args($args, array(
                'style' => 'vertical',
                'count' => false,
                'hide_empty' => false
            )
        );
        if (!isset($args['orderby'])) {
            $args['order'] = 'ASC';
            $args['orderby'] = 'name';
        }
        $cat = new AE_Category(array('taxonomy' => 'place_category'));
        $category = $cat->getAll($args);

        $col = 'col-md-3 col-xs-6';
        if ($args['style'] == 'horizontal') $col = 'col-md-12 col-xs-6';

        ?>
        <!-- Categories -->

        <div class="row">
            <ul class="list-categories">
                <?php
                foreach ($category as $key => $cat) {
                    ?>
                    <li class="<?php echo $col; ?> cat-<?php echo $cat->term_id; ?>">
                        <a href="<?php echo get_term_link($cat, 'place_category') ?>" class="categories-wrapper">
                            <span class="icon-categories"><i class="fa <?php echo $cat->icon; ?>"></i></span>
                            <span class="categories-name"><?php echo $cat->name; ?></span>
                            <?php if ($args['count']) { ?>
                                <span class="number-categories"><?php echo $cat->count; ?></span>
                            <?php } ?>
                        </a>
                    </li>
                    <?php
                } ?>
            </ul>
        </div>

        <!-- Categories / End -->
        <?php
    }
}
/**
 * print server day in text
 * @param Array $serve_day 0 -> every day, 1 : MonDay, 2 : TuesDay ...
 * @return void , print a string
 * @author Dakachi
 */
function de_serve_day($serve_day)
{

    if (empty($serve_day)) {
        echo __("No specify serve day", ET_DOMAIN);
        return;
    }
    // $typedata  = gettype($serve_day);
    if (!is_array($serve_day)) {
        $new = array();
        array_push($new, $serve_day);
        $serve_day = $new;
    }
    $max = max($serve_day);
    $min = min($serve_day);
    $week = array(__('Monday', ET_DOMAIN),
        __('Tuesday', ET_DOMAIN),
        __('Wednesday', ET_DOMAIN),
        __('Thursday', ET_DOMAIN),
        __('Friday', ET_DOMAIN),
        __('Saturday', ET_DOMAIN),
        __('Sunday', ET_DOMAIN)
    );

    if ($min == 0) {
        _e("Every day", ET_DOMAIN);
    } else {
        if (count($serve_day) > 1) {
            if ($max == count($serve_day)) {
                printf(__("From %s to %s", ET_DOMAIN), $week[$min - 1], $week[$max - 1]);
            } else {
                $day = '';
                foreach ($serve_day as $value) {
                    $day .= $week[$value - 1];
                    $day .= ', ';
                }
                echo trim($day, ', ');
            }
        } else {
            echo $week[$serve_day[0] - 1];
        }

    }
}

/**
 * print place category icon fa class
 * @param object $cat
 * @used AE_Category
 * @author Dakachi
 * @return string Get category icon
 */
function de_category_icon($cat)
{
    return AE_Category::get_category_icon($cat->term_id, 'place_category');
}

/**
 * print place category color code
 * @param object $cat
 * @used AE_Category
 * @author Dakachi
 */
function de_category_color($cat)
{
    echo AE_Category::get_category_color($cat->term_id, 'place_category');
}


/**
 * Create HTML list of nav menu input items.
 *
 * @package DirectoryEngine
 * @uses Walker_Nav_Menu
 */
class DE_Menu_Walker extends Walker_Nav_Menu
{


    /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int $depth Depth of menu item. Used for padding.
     * @param array $args An array of arguments. @see wp_nav_menu()
     */
    function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        if ($depth < 1) {
            $output .= "\n$indent<ul class=\"gn-submenu sub-menu " . $depth . "\">\n";
        } else {
            $output .= "\n$indent<ul class=\"sub-menu\">\n";
        }
    }

    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param array $args An array of arguments. @see wp_nav_menu()
     * @param int $id Current item ID.
     */
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $class_names = '';

        $icon_class = $item->classes[0];
        if (preg_match('/fa-/', $icon_class)) {
            unset($item->classes[0]);
        } else {
            $icon_class = 'fa-th';
        }

        $classes = empty($item->classes) ? array() : (array)$item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if ($item->type == 'taxonomy' && $item->object == 'place_category') {
            $icon_class = AE_Category::get_category_icon($item->object_id, 'place_category');
            if ($depth < 2) {
                $classes[] = 'menu-place-category-' . $item->object_id;
            }
        }

        /**
         * Filter the CSS class(es) applied to a menu item's <li>.
         *
         * @since 3.0.0
         *
         * @see wp_nav_menu()
         *
         * @param array $classes The CSS classes that are applied to the menu item's <li>.
         * @param object $item The current menu item.
         * @param array $args An array of wp_nav_menu() arguments.
         */
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filter the ID applied to a menu item's <li>.
         *
         * @since 3.0.1
         *
         * @see wp_nav_menu()
         *
         * @param string $menu_id The ID that is applied to the menu item's <li>.
         * @param object $item The current menu item.
         * @param array $args An array of wp_nav_menu() arguments.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        $atts['href'] = !empty($item->url) ? $item->url : '';

        /**
         * Filter the HTML attributes applied to a menu item's <a>.
         *
         * @since 3.6.0
         *
         * @see wp_nav_menu()
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's <a>, empty strings are ignored.
         *
         * @type string $title Title attribute.
         * @type string $target Target attribute.
         * @type string $rel The rel attribute.
         * @type string $href The href attribute.
         * }
         * @param object $item The current menu item.
         * @param array $args An array of wp_nav_menu() arguments.
         */
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        $item_output .= '<a class="gn-icon" ' . $attributes . '><i class="fa ' . $icon_class . '"></i>';
        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        if (!et_load_mobile()) {
            if ($depth < 1) {
                $item_output .= '<span class="arrow-submenu"><i class="fa fa-chevron-right"></i></span>';
            }
        }
        $item_output .= $args->after;

        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes $args->before, the opening <a>,
         * the menu item's title, the closing </a>, and $args->after. Currently, there is
         * no filter for modifying the opening and closing <li> for a menu item.
         *
         * @since 3.0.0
         *
         * @see wp_nav_menu()
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item Menu item data object.
         * @param int $depth Depth of menu item. Used for padding.
         * @param array $args An array of wp_nav_menu() arguments.
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

}

/**
 * Create HTML list of nav menu input items.
 *
 * @package DirectoryEngine
 * @uses Walker_Nav_Menu
 */
class DE_Header_Top_Walker_Nav_Menu extends Walker_Nav_Menu
{
    /**
     * start the level
     * @param string $output
     * @param int $depth
     * @param array $args
     */
    function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
    }

    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item Menu item data object.
     * @param int $depth Depth of menu item. Used for padding.
     * @param array $args An array of arguments. @see wp_nav_menu()
     * @param int $id Current item ID.
     */
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $class_names = '';
        $icon_class = '';
        // $icon_class = $item->classes[0];
        // if(preg_match('/fa-/', $icon_class)) {
        //     unset($item->classes[0]);
        // }else {
        //     $icon_class = 'fa-th';
        // }

        $classes = empty($item->classes) ? array() : (array)$item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // if($item->type == 'taxonomy' && $item->object == 'place_category') {
        //     $icon_class = AE_Category::get_category_icon($item->object_id, 'place_category');
        //     $classes[] = 'menu-place-category-'. $item->object_id;
        // }

        /**
         * Filter the CSS class(es) applied to a menu item's <li>.
         *
         * @since 3.0.0
         *
         * @see wp_nav_menu()
         *
         * @param array $classes The CSS classes that are applied to the menu item's <li>.
         * @param object $item The current menu item.
         * @param array $args An array of wp_nav_menu() arguments.
         */
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filter the ID applied to a menu item's <li>.
         *
         * @since 3.0.1
         *
         * @see wp_nav_menu()
         *
         * @param string $menu_id The ID that is applied to the menu item's <li>.
         * @param object $item The current menu item.
         * @param array $args An array of wp_nav_menu() arguments.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        $atts['href'] = !empty($item->url) ? $item->url : '';


        /**
         * Filter the HTML attributes applied to a menu item's <a>.
         *
         * @since 3.6.0
         *
         * @see wp_nav_menu()
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's <a>, empty strings are ignored.
         *
         * @type string $title Title attribute.
         * @type string $target Target attribute.
         * @type string $rel The rel attribute.
         * @type string $href The href attribute.
         * }
         * @param object $item The current menu item.
         * @param array $args An array of wp_nav_menu() arguments.
         */
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $item_output = $args->before;
        if ($classes && in_array('menu-item-has-children', $classes)) {
            $style = 'style="display:inline-block"';
        } else {
            $style = '';
        }
        $item_output .= '<a class="dropdown-toggle"' . $attributes . '><i class="fa ' . $icon_class . '"></i>';
        /** This filter is documented in wp-includes/post-template.php */
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        if (!et_load_mobile()) {
            if ($classes && in_array('menu-item-has-children', $classes)) {
                $item_output .= '<span class="arrow-submenu" ' . $style . '>';

                if ($depth <= 2) {
                    $item_output .= '<i class="caret"></i></span>';
                } else {
                    $item_output .= '</span>';
                }
            }
        }


        $item_output .= $args->after;

        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes $args->before, the opening <a>,
         * the menu item's title, the closing </a>, and $args->after. Currently, there is
         * no filter for modifying the opening and closing <li> for a menu item.
         *
         * @since 3.0.0
         *
         * @see wp_nav_menu()
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item Menu item data object.
         * @param int $depth Depth of menu item. Used for padding.
         * @param array $args An array of wp_nav_menu() arguments.
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

add_action('wp_footer', 'de_footer_template');
function de_footer_template()
{
    global $user_ID;

    get_template_part('template-js/loop', 'area');
    // user not login in render template modal authencation
    if (!is_user_logged_in()) {
        get_template_part('template-js/modal', 'authenticate');
    }

    // user login and on template author
    if (is_user_logged_in() && is_author() || is_page_template('page-profile.php')) {
        get_template_part('template-js/modal', 'edit-profile');
    }

    // user logged in and in author or single place
    if (is_user_logged_in() && (is_author() || is_singular('place'))) {
        get_template_part('template-js/modal', 'contact');
    }

    // print report modal
    if (is_singular('place')) {
        get_template_part('template-js/modal', 'report');
        get_template_part('template-js/modal', 'claim');
    }

    global $post;

    if (is_user_logged_in() && ae_user_can('edit_others_posts')) {
        get_template_part('template-js/loop', 'notification');
    }

    if (!is_page_template('page-post-place.php') && ae_user_can('edit_others_posts')  // user can edit others post
        || (is_singular('place') && $user_ID == $post->post_author) // user owned the post in single page
        || (is_author() && $user_ID == get_query_var('author')
            || is_page_template('page-profile.php')) // current user visit his profile
    ) {
        // render template modal edit place
        get_template_part('template-js/modal', 'edit-place');
    }

    if (ae_user_can('edit_others_posts')) {
        // render modal reject template
        get_template_part('template-js/modal', 'reject');
    }
    // ce_categories_json();

    get_template_part('template-js/modal', 'create-event');
    get_template_part('template-js/event', 'item');

    if (is_page_template('page-profile.php')) {
        get_template_part('template-js/author', 'loop-place');
        get_template_part('template-js/author', 'loop-review');
        get_template_part('template-js/author', 'loop-togo');
        get_template_part('template-js/author', 'loop-picture');
        get_template_part('template-js/author', 'loop-event');
    } elseif (is_author()) {
        get_template_part('template-js/author', 'loop-review');
        get_template_part('template-js/author', 'loop-togo');
        get_template_part('template-js/loop', 'place');
        get_template_part('template-js/author', 'loop-event');
    } else {
        get_template_part('template-js/loop', 'place');
    }
    get_template_part('template-js/loop', 'place-nearby');
    get_template_part('template-js/loop', 'review');
    get_template_part('template-js/loop', 'post');

    if (is_page_template('page-list-user.php')) {
        get_template_part('template-js/user', 'item');
    }
    ?>
    <script type="text/template" id="ae_carousel_template">
        <li class="image-item" id="{{= attach_id }}"><span class="img-gallery">
            <img title="" data-id="{{= attach_id }}" src="{{= thumbnail[0] }}"/>
            <a href="" title="<?php _e("Delete", ET_DOMAIN); ?>" class="delete-img delete"><i
                    class="fa fa-times"></i></a>
            </span>

            <div class="inputRadio">
                <input class="checkbox-field" name="featured_image" value="{{= attach_id }}"
                       title="<?php _e("click to select a featured image", ET_DOMAIN); ?>"
                       id="check-image-{{= attach_id }}" type="radio"
                <# if(typeof is_feature !== "undefined" ) { #> checked="true"
                    <# } #> />
                        <label for="check-image-{{= attach_id }}"></label>
            </div>
        </li>
    </script>
    <script type="text/template" id="ae_carousel_comment_template">
        <li class="image-item" id="{{= attach_id }}"><span class="img-gallery">
            <img title="" data-id="{{= attach_id }}" src="{{= thumbnail[0] }}"/>
            <a href="" title="<?php _e("Delete", ET_DOMAIN); ?>" class="delete-img delete"><i
                    class="fa fa-times"></i></a>
            </span>
        </li>
    </script>
    <?php
}


/**
 * Retrieve taxonomy parents with separator.
 *
 * @since 1.2.0
 *
 * @param int $id Tax ID.
 * @param bool $link Optional, default is false. Whether to format with link.
 * @param string $separator Optional, default is '/'. How to separate categories.
 * @param bool $nicename Optional, default is false. Whether to use nice name for display.
 * @param array $visited Optional. Already linked to categories to prevent duplicates.
 * @return string|WP_Error A list of category parents on success, WP_Error on failure.
 */
if (!function_exists('de_get_tax_parents')) {

    /**
     * Get direct children of this term (only terms whose explicit parent is this value)
     * @param int $id The ID Term
     * @param string $tax
     * @param bool|false $link
     * @param string $separator
     * @param bool|false $nicename
     * @param array $visited
     * @return mixed|null|string|WP_Error $chain
     */
    function de_get_tax_parents($id, $tax = 'category', $link = false, $separator = '/', $nicename = false, $visited = array())
    {
        $chain = '';
        $parent = get_term($id, $tax);
        $separator = '<i class="fa ' . de_category_icon($parent) . '"></i>';
        if (is_wp_error($parent))
            return $parent;

        if ($nicename)
            $name = $parent->slug;
        else
            $name = $parent->name;

        if ($parent->parent && ($parent->parent != $parent->term_id) && !in_array($parent->parent, $visited)) {
            $visited[] = $parent->parent;
            // $separator = '<i class="fa '.de_category_icon($parent->parent).'"></i>';
            $chain .= de_get_tax_parents($parent->parent, $tax, $link, $separator, $nicename, $visited);
        }

        if ($link) {
            if (!$parent->parent) {
                $chain .= '<li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">' . $separator . ' <a href="' . esc_url(get_term_link($parent->term_id, $tax)) . '" itemprop="url"><span itemprop="title">' . $name . '</span></a></li>';
            } else {
                $chain .= '<li itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">' . $separator . ' <a href="' . esc_url(get_term_link($parent->term_id, $tax)) . '" itemprop="url"><span itemprop="title">' . $name . '</span></a></li>';
            }
        } else
            $chain .= $name . $separator;
        return $chain;
    }
}

/**
 * render place location list with image,title and count places
 * @param array|the $args the args for query term
 * @author Tuandq
 * @verion 2.0
 */
if (!function_exists('de_areas_list')) {

    function de_areas_list($args)
    {
        $args = wp_parse_args($args, array(
                'count' => false,
                'hide_empty' => false
            )
        );
        if (!isset($args['orderby'])) {
            $args['order'] = 'ASC';
            $args['orderby'] = 'name';
        } else
            $args['order'] = 'DESC';
        /*If query by ID, remove number*/
        if (isset($args['include'])) {
            $args['number'] = 0;
            $load_more = false;
        } else {
            unset($args['include']);
            $load_more = true;
        }
        $loca = new AE_Category(array('taxonomy' => 'location'));
        $areas = $loca->getAll($args);
        $col = 'col-md-4 col-sm-4 col-xs-6';
        if (isset($args['style']) && $args['style'] == 'horizontal') {
            ?>
            <!-- Areas -->
            <div class="filter-wrapper">
                <h2 class="widgettitle"><?php echo $args['title']; ?></h2>
            </div>
            <div class=" block-area">
                <div class="row list-wrapper">
                    <ul class="list-areas">
                        <?php
                        foreach ($areas as $key => $loca) {
                            ?>
                            <li class="area-item <?php echo $col; ?>">
                                <div class="area-wrapper">
                                    <a href="<?php echo get_term_link($loca, 'place_category') ?>"><img
                                            src="<?php echo et_taxonomy_image_url($loca->term_id, 'medium', TRUE); ?>"
                                            alt=""></a>

                                    <div class="area-info">
                                        <h2><?php echo $loca->name; ?></h2>
                                        <?php
                                        if ($args['count'])
                                            echo '<span class="place-number">' . $loca->count . ' ' . __("Places", ET_DOMAIN) . '</span>';
                                        ?>
                                    </div>
                                </div>
                            </li>
                            <?php
                        } ?>
                    </ul>
                </div>
                <?php if (count($areas) >= $args['number'] && $load_more) { ?>
                    <div class="paginations-wrapper">
                        <script type="application/json" class="ae_query">


                        </script>
                        <div class="paginations">
                            <a class="inview load-more-post"><?php _e('Load more', ET_DOMAIN); ?></a>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!-- Areas / End -->
            <?php
        } else {
            echo '<div class="filter-wrapper">';
            echo '<h2 class="widgettitle">' . $args['title'] . '</h2>';
            echo '</div>';
            echo '<div class="row list-wrapper">';
            echo '<ul class="list-areas vertical">';
            foreach ($areas as $key => $loca) {
                ?>
                <li class="col-md-12">
                    <a href="<?php echo get_term_link($loca, 'place_category') ?>" title="<?php echo $loca->name; ?>">
                        <span class="area-name"><?php echo $loca->name; ?></span>
                        <?php
                        if ($args['count'])
                            echo '<span class="area-number">' . $loca->count . '</span>';
                        ?>
                    </a>
                </li>
                <?php
            }
            echo '</ul></div>';
        }
    }
}

/**
 * render posts with refresh
 * @param $wp_query The WP_Query object for post list
 * @param $current if use default query, you can skip it
 * @author Tuandq
 */
if (!function_exists('de_refresh')):

    function de_refresh($query, $current = '', $type = 'page', $text = '', $meta_key = '')
    {
        $query_var = array();
        /**
         * posttype args
         */
        $query_var['post_type'] = $query->query_vars['post_type'] != '' ? $query->query_vars['post_type'] : 'post';
        $query_var['post_status'] = isset($query->query_vars['post_status']) ? $query->query_vars['post_status'] : 'publish';
        $query_var['orderby'] = isset($query->query_vars['orderby']) ? $query->query_vars['orderby'] : 'date';
        // taxonomy args
        $query_var['place_category'] = isset($query->query_vars['place_category']) ? $query->query_vars['place_category'] : '';
        $query_var['location'] = isset($query->query_vars['location']) ? $query->query_vars['location'] : '';
        $query_var['showposts'] = isset($query->query_vars['showposts']) ? $query->query_vars['showposts'] : '';
        /**
         * order
         */
        $query_var['order'] = $query->query_vars['order'];
        //if(!empty($query->query_vars['meta_key']))
        $query_var['meta_key'] = $meta_key;

        $query_var = array_merge($query_var, (array)$query->query);
        $query_var['paginate'] = $type;
        echo '<script type="application/json" class="ae_query">' . json_encode($query_var) . '</script>';
        $style = '';
        echo '<div class="paginations" ' . $style . '>';
        echo '<a class="inview load-refesh-post refresh-post" data-meta-key=' . $meta_key . '>' . __("Actualizar", ET_DOMAIN) . '</a>';
        echo '</div>';
    }

endif;

if (!function_exists('de_list_carousel_comment')) {
    /**
     * Get Gallery of comment/review
     * @param Object $comment
     * @author ThanhTu
     * @return HTML
     */
    function de_list_carousel_comment($comment)
    {

        $carousel = get_comment_meta($comment->comment_ID, 'et_carousel_comment', true);
        $total_carousel = count($carousel);
        if ($carousel) {
            echo '<div class="gallery_comment">';
            ?>
            <ul class="list-images gallery_carousel">
                <?php
                $see_more = false;
                $display = '';
                $show = (et_load_mobile()) ? 3 : 6; // Mobile show 3 images, Desktop show 6 images
                foreach ($carousel as $key => $value) {
                    if ($key == $show) {
                        $see_more = true;
                        $display = "display:none";
                    }
                    $thumb_src = wp_get_attachment_image_src($value, 'thumbnail');
                    $full_src = wp_get_attachment_image_src($value, 'full');
                    ?>
                    <li class="col-md-2 col-sm-2 col-xs-4" style="<?php echo $display; ?>">
                        <a href="<?php echo $full_src[0]; ?>">
                            <img class="lazy" src="<?php echo TEMPLATEURL . '/img/lazy-loading.gif' ?>"
                                 data-original="<?php echo $thumb_src[0]; ?>">
                        </a>
                    </li>
                <?php } ?>
            </ul>
            <?php
            if ($see_more) {
                echo '<a class="see-more">' . sprintf(__('See all %s photos', ET_DOMAIN), $total_carousel) . '</a>';
            }
            echo "</div>";
        }
    }

    add_action('ae_carousel_comment', 'de_list_carousel_comment');
}

if (!function_exists('de_button_upload_image')) {
    /**
     * Button upload Gallery of comment/review
     * @author ThanhTu
     * @return HTML
     */
    function de_button_upload_image()
    {
        ?>
        <div class="form-field edit-gallery-image comment-upload-image" id="comment_gallery_container">
            <ul class="gallery-image carousel-list" id="image-comment-list">
                <li>
                    <div class="plupload_buttons" id="carousel_comment_container">
                        <span class="img-gallery" id="carousel_comment_browse_button">
                            <a href="#" class="add-img"><i class="fa fa-plus"></i></a>
                        </span>
                    </div>
                </li>
            </ul>
            <span class="et_ajaxnonce" id="<?php echo wp_create_nonce('ad_carousels_et_uploader'); ?>"></span>
        </div>
        <?php
    }

    if (ae_get_option('post_image_comment', false)) {
        add_action('ae_button_upload_image', 'de_button_upload_image');
    }
}