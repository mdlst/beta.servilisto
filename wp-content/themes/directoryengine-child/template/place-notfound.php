<?php
/**
 * template not found
*/
?>

<li class="notfound full-width">
	<!-- <div class="col-md-8"> -->
    <div class="row">
        <div class="col-md-12 col-xs-12 notfound-title">
            <div class="notfound-result">
                <?php _e("No results found for your query.", ET_DOMAIN); ?>
            </div>
        </div>
    </div>
</li>

<div class="paginations-wrapper main-pagination">
    <?php
    ae_pagination($wp_query);
    wp_reset_postdata();
    ?>
</div>