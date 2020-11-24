<?php
global $wpdb, $wp_query, $current_user;

/******************************   VEMOS LAS RESERVAS DEL USUARIO*****************************************/
$query_base = "SELECT * FROM " . $wpdb->base_prefix . "dopbsp_reservations AS dr INNER JOIN " . $wpdb->base_prefix . "dopbsp_calendars AS dc ON dr.calendar_id=dc.id ";
$user_qry = " and dc.user_id=" . $current_user->ID . "";
$reservations_qry = $query_base . $user_qry;

$count_result = $wpdb->get_results($reservations_qry);
$total_reservas = count($count_result);

/***********************************************************************/
$reservervations = new DOPBSPViewsBackEndReservationsFront();
/***********************************************************************/

$calendars = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'dopbsp_calendars WHERE user_id="' . $current_user->ID . '" ORDER BY id');
$calendar_id = '';
if (is_array($calendars) and count($calendars) > 0) {
    foreach ($calendars as $calendar) {
        $calendar_id .= ',' . $calendar->id;
    }
    $calendar_id = trim($calendar_id, ',');
}

/***********************************************************************/
?>
<input type="hidden" name="DOPBSP-calendar-ID" id="DOPBSP-calendar-ID" value="<?= $calendar_id; ?>"/>

<div class="content-reservas tab-pane fade" id="tab-reservas">

    <?php if ($total_reservas == 0) { ?>
        <ul class="list-place-publishing">
            <li class="col-md-12">
                <div class="event-active-wrapper">
                    <div class="col-md-12">
                        <div class="event-wrapper tab-style-event">
                            <h2 class="title-envent no-title-envent ">Actualmente, no tiene ninguna reserva.</h2>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    <?php } else { ?>

        <ul class="list-posts list-places" id="publish-places" data-list="publish" data-thumb="big_post_thumbnail">
            <?php
            //$reservervations->reservationsList();
            //$reservervations->returnTranslations();
            $reservervations->template();
            add_action('wp_footer', 'carga_calendario_al_perfil', 100);
            ?>
        </ul>
    <?php } ?>

</div>
