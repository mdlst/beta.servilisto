<?php
global $wp_query, $current_user;
$user_total_points = $wpdb->get_row("SELECT (COALESCE(sum(points_added),0) - COALESCE(sum(points_redeem),0)) as total_points FROM " . $wpdb->prefix . "tbl_points_log where user_id = '" . $current_user->ID . "' group by user_id  ");

if ($user_total_points) {
    $table_pt = $wpdb->prefix . 'tbl_point_settings';
    $results = $wpdb->get_results('SELECT * FROM ' . $table_pt . '');

    $points_worth = $results[0]->points_worth;

    $total_points = $user_total_points->total_points == '' ? 0 : $user_total_points->total_points;
    $total_price = $total_points * $points_worth;
}
else{
    $total_points=0;
    $total_price=0;
}
?>

<?php if ($post->ID != ""){ ?>
<div class="content-reviews tab-pane fade" id="tab-puntos">
    <?php } ?>
    <ul class="list-place-review list-posts list-places" id="publish-places" data-list="publish"
        data-thumb="big_post_thumbnail">

        <li class="points">
            <p class="pts">Puntos actuales: <?php echo $total_points; ?></p>

            <p class="val"><?php echo $total_points ?> Puntos = <?php echo $total_price; ?> &euro;</p>
        </li>

    </ul>
    <?php if ($post->ID != ""){ ?>
</div>
<?php } ?>
