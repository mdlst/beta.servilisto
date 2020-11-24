<?php
$st1 = 'active';
$st2 = '';
if(isset($defaultdisplay)){
    if($defaultdisplay === '1'){
        $st1 = '';
        $st2 = 'active';
    }
}
?>    
    <ol class="list-option-filter">
        <li>
            <div>
            <span class="title"><?php _e("FILTER BY:", ET_DOMAIN); ?></span>
            <?php 
            ae_tax_dropdown('location', array(  'hide_empty' => true,
                                                'class' => 'chosen-single tax-item',
                                                'hierarchical' => true, 
                                                'show_option_all' => __("All locations", ET_DOMAIN) , 
                                                'taxonomy' => 'location' ,
                                                'value' => 'slug', 
                                                'selected' => (isset($_REQUEST['l']) && $_REQUEST['l']) ? $_REQUEST['l'] : ''
                                            )); ?>
            </div>
        </li>
        <li>
            <div>
                <?php 
            ae_tax_dropdown('place_category', array(    'hide_empty' => true,
                                                        'class' => 'chosen-single tax-item',
                                                        'hierarchical' => true, 
                                                        'show_option_all' => __("All categories", ET_DOMAIN) , 
                                                        'taxonomy' => 'place_category' ,
                                                        'value' => 'slug',
                                                        'selected' => (isset($_REQUEST['c']) && $_REQUEST['c']) ? $_REQUEST['c'] : ''
                                                    )); ?>
            </div>
            
        </li>
        <!-- select how many post you want to see perpage -->
        <li class="filter-per-page hidden-xs hidden-sm hidden-md">            
            <!-- <span class="title"><?php _e("PLACE PER PAGE:", ET_DOMAIN); ?></span> -->
            <div>
                <select class="showposts chosen-single tax-item" name="showposts">
                    <option value="">
                        <?php _e("Places/page", ET_DOMAIN); ?> 
                    </option>
                    <option value="4">
                        <?php _e("4 Places/Page", ET_DOMAIN); ?> 
                    </option>
                    <option value="8">
                        <?php _e("8 Places/Page", ET_DOMAIN); ?>
                    </option>
                    <option  value="12">
                        <?php _e("12 Places/Page", ET_DOMAIN); ?>
                    </option>
                </select>
            </div>
        </li>
        <!--// select how many post you want to see perpage -->
        <li class="sort-rates-lastest">
            <span class="title"><?php _e("SORT BY:", ET_DOMAIN); ?></span>
            <a href="" class="sort-icon orderby" data-order="" data-theme="de" data-post-in="<?php global $post_in;echo $post_in;$post_in = ''; ?>" data-sort="rating_score">
                <?php _e("Rating", ET_DOMAIN); ?>
            </a> / 
            <a href="" class="sort-icon orderby active" data-order="" data-theme="de" data-post-in="<?php global $post_in;echo $post_in; ?>" data-sort="date" >
                <?php _e("Latest", ET_DOMAIN); ?>
            </a>
        </li>

        <li class="icon-list-view">
            <span class="icon-view grid-style <?php echo $st1; ?>"><i class="fa fa-th"></i></span>
            <span class="icon-view fullwidth-style <?php echo $st2; ?>"><i class="fa fa-th-list"></i></span>
        </li>
    </ol>
    <div class="clearfix"></div>
