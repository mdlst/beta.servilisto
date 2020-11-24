<?php

/**
 * Render select review criteria field
 * @return mixed
 */
function select_review_criteria()
{
    global $ae_post_factory, $current_user, $wp_query;
    $place_obj = $ae_post_factory->get('place');
    $place = $place_obj->get_current_post();
    if (function_exists('ae_tax_dropdown')) {
        if (is_page_template('page-post-place.php')) {
            ?>
            <li class="form-field icon-input">
                <div class="row multirating_use">
                    <div class="col-md-4">
                    <span class="title-plan">
                        <?php _e("REVIEW CRITERIAS", ET_DOMAIN); ?>
                        <span><?php _e("Select the most suitable one for your business", ET_DOMAIN); ?></span>
                    </span>
                    </div>
                    <div class="col-md-8 search-category">
                        <?php
                        ae_tax_dropdown('review_criteria',
                            array('attr' => 'multiple data-placeholder="' . __("Choose review criterias", ET_DOMAIN) . '"',
                                'class' => 'chosen  tax-item required review_criteria_use',
                                'hide_empty' => false,
                                'hierarchical' => false,
                                'id' => 'review_criteria',
                                'show_option_all' => false
                            )
                        );
                        ?>
                    </div>
                </div>
            </li>
            <?php
        } else {
            // if (current_user_can('manage_options') || $current_user->ID == $place->post_author) {
            ?>
            <div class="form-field">
                <label><?php _e("REVIEW CRITERIAS", ET_DOMAIN) ?><span class="alert-icon">*</span></label>
                <?php
                ae_tax_dropdown('review_criteria',
                    array('attr' => 'multiple data-placeholder="' . __("Choose review criterias", ET_DOMAIN) . '"',
                        'class' => 'chosen  tax-item review_criteria_use',
                        'hide_empty' => false,
                        'hierarchical' => false,
                        'id' => 'review_criteria',
                        'show_option_all' => false
                    )
                );
                ?>
            </div>
            <?php
            //}
        }
    }
}

add_action('select_review_criteria', 'select_review_criteria');

add_action('de_multirating_render_review', 'de_multirating_render_review');
/**
 * Render  multi_rating
 * @return mixed
 */
function de_multirating_render_review()
{
    $enable_critical = ae_get_option('enable_critical');
    $fc = new Critical_category();
    global $post, $ae_post_factory;
    $place_obj = $ae_post_factory->get('place');
    $place = $place_obj->current_post;

    /* 10_11_2016 Id (M): 1 S */       // el anuncio aún no tiene puntuación pero queremos mostrar el bloque con valor 0
    if (!($place->multi_rating_score)) {
        $place->multi_rating_score = true;
        $place->multi_overview_score = 0;
    }
    /* 10_11_2016 Id (M): 1 E */

    //new rating
    if (isset($place->multi_rating_score)
        && $place->multi_rating_score != ''
        && !empty($place->multi_rating_score)
    ) {
        ?>
        <div class="multi-rating-place-wrapper" itemscope itemtype="http://data-vocabulary.org/Recipe">
            <meta itemprop="name" content='<?php echo $place->post_title; ?>'>
            <div class="multi-rating-place-overview" itemprop="" itemscope
                 itemtype="http://data-vocabulary.org/Review-aggregate">
                <meta itemprop="rating" content="<?php echo $place->rating_score; ?>">
                <meta itemprop="votes" content="<?php echo $place->reviews_count; ?>">
                <p class="multi-rating-overall-title"><?php _e('Overall Rating', ET_DOMAIN); ?> </p>

                <p class="multi-rating-overall-score"><?php echo $place->multi_overview_score ?> </p>

                <p class="multi-rating-reviews">
                    <?php
                    if (((int)$place->multi_reviews_count) > 1) {
                        echo '<a href="#review-list-ancla">';
                        printf(__('(%s reviews)', ET_DOMAIN), de_nide_number((int)$place->multi_reviews_count));
                        echo '</a>';
                    } else if (((int)$place->multi_reviews_count) == 1) {
                        echo '<a href="#review-list-ancla">';
                        printf(__('(%s review)', ET_DOMAIN), de_nide_number((int)$place->multi_reviews_count));
                        echo '</a>';
                    } else {
                        printf(__('No reviews', ET_DOMAIN));
                    }
                    ?>
                </p>
                <?php
                if (ae_get_option("enable_view_counter", false)) {
                    if (isset($place->view_count) && $place->view_count != '') {
                        ?>
                        <p class="multi-rating-views"><?php printf(__('(%s views)', ET_DOMAIN), $place->view_count) ?> </p>
                        <?php
                    }
                }
                ?>
            </div>
            <?php de_multirating_render_review_criterias($place, 'multi-rating-place-criteria', null, null, null, true); ?>
            <div class="criteria-line"></div>
        </div>
        <?php
    }
}

/**
 * Render de multirating criteria
 * @param  Object $place
 * @param  string $class the css class of tag ul
 * @param  string $rate_action
 * @param  array $args argument to get object term are used in wp_get_object_terms
 * @param  array $rates
 * @param  bool $render_view
 * @return mixed
 */
function de_multirating_render_review_criterias($place, $class = '', $rate_action = '', $args = array(), $rates = array(), $render_view = false)
{
    /*echo "<pre>";
    print_r($place);
    echo $class;exit;*/
    $enable_critical = ae_get_option('enable_critical');
    $default = array(
        'orderby' => ae_get_option('de_multirating_orderby', 'name'),
        'order' => ae_get_option('de_multirating_order', 'DESC')
    );
    $args = wp_parse_args($args, $default);
    $term_list = wp_get_object_terms($place->ID, 'review_criteria', $args);


    $mask = array();
    //critical by category
   // echo $enable_critical;exit;
    if ($enable_critical) {

        //get list critical by category of place
        if ($place->de_critical_cate) {
            $critical_category = Critical_category::get_critical_options($place->de_critical_cate);
        } else {
            $critical_category = Critical_category::get_critical_options($place->place_category[0]);
        }


       // print_r ($critical_category);
        //get name of critical
        if ($critical_category != 1 && $critical_category != null) {

            $term_name = Critical_category::critical_name($critical_category);
            $mask = $term_name;
        }
    } else {
        foreach ($term_list as $key => $value) {
            array_push($mask, $value->name);
        }
    }

    $mask = apply_filters('de_multirating_render_review_mask', $mask);
    /*echo "<pre>";
    print_r($mask);
    echo "</pre>";*/
    if ($rate_action && $rate_action != '') {

        $rate_class = 'multi-rating-it'; ?>
        <ul class="<?php echo $class; ?>">
            <?php
            if (isset($mask) && !empty($mask)) {
                foreach ($mask as $key => $value) {
                    ?>
                    <li>
                        <div class="rate rate-criteria"
                             title="<?php echo de_multirating_filter_review_criteria($value); ?>"><?php echo de_multirating_filter_review_criteria($value); ?> </div>
                        <div class="<?php echo $rate_class; ?> rating-it-style"
                             data-score-name="score['<?php echo $value ?>']"
                             title="<?php echo de_multirating_filter_review_criteria($value); ?>"></div>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
        <?php
    } else {
        $rate_class = 'multi-rate-it';
        ?>
        <ul class="<?php echo $class; ?>">
            <?php
            if (isset($place->multi_rating_score) && $place->multi_rating_score) {
                if ($render_view == true) {
                    foreach ($mask as $key => $value) { ?>
                        <li>
                            <div class="rate rate-criteria"
                                 title="<?php echo de_multirating_filter_review_criteria($value); ?>"><?php echo de_multirating_filter_review_criteria($value); ?> </div>
                            <div class="<?php echo $rate_class; ?> rating-it-style"
                                 data-score='<?php echo isset($place->multi_rating_score[$value]) ? $place->multi_rating_score[$value] : 0; ?>'
                                 title="<?php echo de_multirating_filter_review_criteria($value); ?>"></div>
                        </li>
                        <?php
                    }
                } else {
                    foreach ($mask as $key => $value) { ?>
                        <li>
                            <div
                                class="rate rate-criteria"><?php echo de_multirating_filter_review_criteria($value); ?> </div>
                            <div class="<?php echo $rate_class; ?> rating-it-style"
                                 data-score='<?php echo isset($rates[de_multirating_filter_review_criteria($value)]) ? $rates[de_multirating_filter_review_criteria($value)] : 0; ?>'></div>
                        </li>
                        <?php
                    }
                }
            }
            ?>
        </ul>
        <?php
    }
}

/**
 *
 */
function select_cate_critical()
{
    if (is_page_template('page-post-place.php')) {
        ?>
        <li class="form-field icon-input">
            <div class="row multirating_use">
                <div class="col-md-4">
            <span class="title-plan">
                <?php _e("CRITERIA CATEGORY", ET_DOMAIN); ?>
                <span><?php _e("Select the criteria category you want for this place", ET_DOMAIN); ?></span>
            </span>
                </div>
                <div class="col-md-8 search-category">
                    <select class="form-control tax-item" name="de_critical_cate" id="critical_cate">
                        <option><?php _e("Please select", ET_DOMAIN) ?></option>
                    </select>
                </div>
            </div>
        </li>
    <?php } else { ?>
        <div class="form-field icon-input">
            <label><?php _e("CRITERIA CATEGORY", ET_DOMAIN) ?></label>
            <select class="form-control tax-item" name="de_critical_cate" id="critical_cate">
                <option><?php _e("Please select", ET_DOMAIN) ?></option>
            </select>
        </div>
        <?php
    }
}

add_action('select_cate_critical', 'select_cate_critical');