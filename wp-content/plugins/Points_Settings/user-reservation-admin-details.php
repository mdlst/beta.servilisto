<h3 class="titleh3"> Listado de Reservas </h3>

<?php
global $wpdb;

if (!empty($_GET['year'])) {
    $selected_year = $_GET['year'];
    $year_qry = " and YEAR(dr.date_created)=" . $selected_year . " ";
} else {
    $selected_year = "";
    $year_qry = "";
}

if (!empty($_GET['month_id'])) {
    $selected_month = $_GET['month_id'];
    $month_qry = " and MONTH(dr.date_created)=" . $selected_month . " ";
} else {
    $selected_month = '';
    $month_qry = "";
}

if (!empty($_GET['pack_id'])) {
    $selected_pack = $_GET['pack_id'];
    $pack_qry = " INNER JOIN " . $wpdb->base_prefix . "postmeta AS pm ON dc.post_id = pm.post_id AND pm.meta_key = 'et_payment_package' and pm.meta_value = '" . $selected_pack . "' ";
} else {
    $selected_pack = '';
    $pack_qry = '';
}

if (!empty($_GET['user_id'])) {
    $user_qry = " and dc.user_id=" . $_GET['user_id'] . "";
} else {
    $user_qry = "";
}

if (!empty($_GET['status_id'])) {
    $selected_status = $_GET['status_id'];
    $status_qry = " and dr.status='" . $_GET['status_id'] . "'";
} else {
    $selected_status = "";
    $status_qry = "";
}


$query_base = "SELECT * FROM " . $wpdb->base_prefix . "dopbsp_reservations AS dr
INNER JOIN " . $wpdb->base_prefix . "dopbsp_calendars AS dc ON dr.calendar_id=dc.id ";

$reservations_qry = $query_base . $user_qry . $year_qry . $month_qry . $pack_qry . $status_qry;

$count_result = $wpdb->get_results($reservations_qry);
$total_rec = count($count_result);

if ($total_rec > 10) {
    $items_per_page = 10;
} else {
    $items_per_page = '';
}


$mpage = isset($_GET['cpage']) ? abs((int)$_GET['cpage']) : 1;
$offset = ($mpage * $items_per_page) - $items_per_page;
if ($items_per_page == "") {
    $reservations_data = $wpdb->get_results($reservations_qry);
} else {
    $reservations_data = $wpdb->get_results($reservations_qry . " LIMIT ${offset}, ${items_per_page}");
}

?>
<div id="main-content" class="main-content sleeve_main">

    <div class="controles">
        <div class="select_option">

            <label class="control-label">Año</label>

            <div class="controls">
                <select class="span3 chosen" name="year_id" id="year_id">
                    <option value="" <?php if ($selected_year == '') {
                        echo "selected='selected'";
                    } ?>> Todos los años
                    </option>
                    <?php
                    $current_year_option = date("Y");
                    for ($yy = $current_year_option; $yy >= 2014; $yy--) {
                        ?>
                        <option value="<?= $yy; ?>" <?php if ($selected_year == $yy) {
                            echo "selected='selected'";
                        } ?>><?= $yy; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="select_option">
            <label class="control-label">Mes</label>

            <div class="controls">
                <select class="span3 chosen" name="month_id" id="month_id">
                    <option value="" <?php if ($selected_month == '') {
                        echo "selected='selected'";
                    } ?>> Todos los meses
                    </option>
                    <option value="01" <?php if ($selected_month == '01') {
                        echo "selected='selected'";
                    } ?> >Enero
                    </option>
                    <option value="02" <?php if ($selected_month == '02') {
                        echo "selected='selected'";
                    } ?>>Febrero
                    </option>
                    <option value="03" <?php if ($selected_month == '03') {
                        echo "selected='selected'";
                    } ?>>Marzo
                    </option>
                    <option value="04" <?php if ($selected_month == '04') {
                        echo "selected='selected'";
                    } ?>>Abril
                    </option>
                    <option value="05" <?php if ($selected_month == '05') {
                        echo "selected='selected'";
                    } ?>>Mayo
                    </option>
                    <option value="06" <?php if ($selected_month == '06') {
                        echo "selected='selected'";
                    } ?>>Junio
                    </option>
                    <option value="07" <?php if ($selected_month == '07') {
                        echo "selected='selected'";
                    } ?>>Julio
                    </option>
                    <option value="08" <?php if ($selected_month == '08') {
                        echo "selected='selected'";
                    } ?>>Agosto
                    </option>
                    <option value="09" <?php if ($selected_month == '09') {
                        echo "selected='selected'";
                    } ?>>Septiembre
                    </option>
                    <option value="10" <?php if ($selected_month == '10') {
                        echo "selected='selected'";
                    } ?>>Octubre
                    </option>
                    <option value="11" <?php if ($selected_month == '11') {
                        echo "selected='selected'";
                    } ?>>Noviembre
                    </option>
                    <option value="12" <?php if ($selected_month == '12') {
                        echo "selected='selected'";
                    } ?>>Diciembre
                    </option>

                </select>
            </div>
        </div>

        <div class="select_option">
            <label class="control-label">Usuario</label>

            <div class="controls">
                <?php
                $users_record = get_users_from_calander_reservations($selected_year, $selected_month);


                ?>
                <select class="span3 chosen" name="user_id" id="user_id">
                    <option value=''> Todos los usuarios</option>
                    <?php
                    foreach ($users_record as $user_key => $user_val) {
                        $user_data = get_userdata($user_val);
                        $user_name = '';
                        if ($user_data->user_nicename != '') {
                            $user_name = $user_data->user_nicename;
                        } else {
                            $user_name = $user_data->display_name;
                        }
                        if ($user_name == '') {
                            $user_name = "Desconocido: ID (" . $user_val . ") ";
                        }
                        ?>
                        <option value='<?= $user_val; ?>' <?php if ($_GET['user_id'] == $user_val) {
                            echo "selected='selected'";
                        } ?> > <?= $user_name; ?> </option>
                    <?php } ?>
                </select>
            </div>
        </div>


        <?php
        global $ae_post_factory;
        $ae_pack = $ae_post_factory->get('pack');
        $packs = $ae_pack->fetch();
        $price = array();
        foreach ($packs as $k => $d) {
            $price[$k] = $d->et_price;
        }
        array_multisort($price, SORT_ASC, $packs);
        $packs_arr = array();
        ?>
        <div class="select_option">
            <label class="control-label">Pack</label>

            <div class="controls">
                <select class="span3 chosen" name="pack_id" id="pack_id">
                    <option value="" <?php if ($selected_pack == '') {
                        echo "selected='selected'";
                    } ?>> Todos los packs
                    </option>
                    <?php foreach ($packs as $key => $val) {
                        $packs_arr[$val->sku] = $val->post_title;
                        ?>
                        <option value="<?= $val->sku; ?>" <?php if ($val->sku == $selected_pack) {
                            echo "selected='selected'";
                        } ?>>
                            <?= $val->post_title; ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="select_option">
            <label class="control-label">Estado</label>

            <div class="controls">
                <select class="span3 chosen" name="status_id" id="status_id">
                    <option value="" <?php if ($selected_status == '') {
                        echo "selected='selected'";
                    } ?>> Todos los estados
                    </option>
                    <?php

                    $status_posibles = get_status_posibles_reservation_bsp();

                    foreach ($status_posibles as $key => $val) {
                        $status_eng = $val->status;

                        if ($status_eng == "approved") $status = "Aprobado";
                        if ($status_eng == "pending") $status = "Pendiente";
                        if ($status_eng == "rejected") $status = "Rechazado";
                        if ($status_eng == "canceled") $status = "Cancelado";

                        ?>
                        <option value="<?= $status_eng; ?>" <?php if ($status_eng == $selected_status) {
                            echo "selected='selected'";
                        } ?>>
                            <?= $status; ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>


    </div>
    <?php
    if ($total_rec > 0) {
        $percentage_value = get_commision_percentage_value();
        ?>
        <div class="view-subscription main">
            <table id="myTable" class="tablesorter">
                <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Anuncio</th>
                    <th>Precio total</th>
                    <th>% comisión</th>
                    <th>Comisión Servilisto</th>
                    <th>Fecha</th>
                    <th>Pack elegido</th>
                    <th>Estado reserva</th>
                </tr>
                </thead>
                <tbody>

                <?php

                foreach ($reservations_data as $reservations_row) {

                    $user_data = get_userdata($reservations_row->reserver_user_id);
                    if ($user_data->user_nicename != '') {
                        $user_name = $user_data->user_nicename;
                    } else {
                        $user_name = $user_data->display_name;
                    }
                    if ($user_name == '') {
                        $user_name = "Desconocido: ID (" . $user_data->ID . ") ";
                    }

                    $user_url = $post_url = "<a target='new' href='" . site_url("wp-admin/user-edit.php?user_id=$user_data->ID") . "'>" . $user_name . "</a>";

                    $et_payment_package = '';
                    $post_url = "Desconocido";
                    if ($reservations_row->post_id > 0) {
                        $post_url = "<a target='new' href='" . site_url("wp-admin/post.php?post=$reservations_row->post_id&action=edit") . "'>" . get_the_title($reservations_row->post_id) . "</a>";

                        // obtenemos los datos del autor
                        $post = get_post($reservations_row->post_id);
                        $user_post = get_userdata($post->post_author);

                        if ($user_post->user_nicename != '') {
                            $user_post_name = $user_post->user_nicename;
                        } else {
                            $user_post_name = $user_post->display_name;
                        }

                        $post_url .= "<a target='new' href='" . site_url("wp-admin/user-edit.php?user_id=$post->post_author") . "'> (" . $user_post_name . ")</a>";
                        $et_payment_package = get_post_meta($reservations_row->post_id, 'et_payment_package', true);
                    }

                    $date_formateado = new DateTime($reservations_row->date_created);
                    $date_formateado = $date_formateado->format('d-M-Y  (H:i:s)');

                    $status_eng = $reservations_row->status;

                    if ($status_eng == "approved") $status_esp = "Aprobado";
                    else if ($status_eng == "pending") $status_esp = "Pendiente";
                    else if ($status_eng == "rejected") $status_esp = "Rechazado";
                    else if ($status_eng == "canceled") $status_esp = "Cancelado";
                    else $status_esp = "Desconocido";

                    ?>
                    <tr>
                        <td><?= $user_url; ?> </td>
                        <td><?= $post_url ?></td>
                        <td><?= $reservations_row->price . " €"; ?></td>
                        <td><?= $percentage_value . "%"; ?></td>
                        <td><?= ($reservations_row->price * $percentage_value) / 100 . " €"; ?></td>
                        <td><?= $date_formateado ?></td>
                        <td><?= $packs_arr[$et_payment_package]; ?></td>
                        <td><?= $status_esp; ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>

        <div class="pagging">
            <?php
            if ($items_per_page != "") {
                echo paginate_links(array(
                    'base' => add_query_arg('cpage', '%#%'),
                    'format' => '',
                    'prev_text' => __('&laquo;'),
                    'next_text' => __('&raquo;'),
                    'total' => ceil($total_rec / $items_per_page),
                    'current' => $mpage
                ));
            }
            ?>


            <div> <?php

                if ((($offset + $items_per_page) > $total_rec) or $items_per_page == '') {
                    $point2 = $total_rec;
                } else {
                    $point2 = $offset + $items_per_page;

                }
                echo "<b>Mostrando página " . ($offset + 1) . " (de " . $point2 . " a " . $total_rec . " entradas)</b>";

                ?>
            </div>
        </div>


    <?php } else {
        echo '<h3 align="center"> No hay reservas! </h3>';
    }
    ?>
</div>

<script>

    (function ($) {
        jQuery(document).ready(function ($) {

            $("#year_id").change(function () {
                var id = $(this).val();
                <?php

                $month_id = (isset($_GET['month_id']) and !empty($_GET['month_id']))?'&month_id='.$_GET['month_id']:'';
                $user_id = (isset($_GET['user_id']) and !empty($_GET['user_id']))?'&user_id='.$_GET['user_id']:'';
                $pack_id = (isset($_GET['pack_id']) and !empty($_GET['pack_id']))?'&pack_id='.$_GET['pack_id']:'';
                $status_id = (isset($_GET['status_id']) and !empty($_GET['status_id']))?'&status_id='.$_GET['status_id']:'';
                ?>
                var url = 'admin.php?page=user-reservation-admin-details&year=' + id + '<?= $month_id.$user_id.$pack_id.$status_id;?>';
                window.location = url;
            });

            $("#user_id").change(function () {
                var id = $(this).val();
                <?php
                $year = (isset($_GET['year']) and !empty($_GET['year']))?'&year='.$_GET['year']:'';
                $month_id = (isset($_GET['month_id']) and !empty($_GET['month_id']))?'&month_id='.$_GET['month_id']:'';
                $pack_id = (isset($_GET['pack_id']) and !empty($_GET['pack_id']))?'&pack_id='.$_GET['pack_id']:'';
                $status_id = (isset($_GET['status_id']) and !empty($_GET['status_id']))?'&status_id='.$_GET['status_id']:'';
                ?>
                var url = 'admin.php?page=user-reservation-admin-details&user_id=' + id + '<?= $month_id.$year.$pack_id.$status_id;?>';
                window.location = url;
            });


            $("#month_id").change(function () {
                var id = $(this).val();
                <?php
                $year = (isset($_GET['year']) and !empty($_GET['year']))?'&year='.$_GET['year']:'';
                $user_id = (isset($_GET['user_id']) and !empty($_GET['user_id']))?'&user_id='.$_GET['user_id']:'';
                $pack_id = (isset($_GET['pack_id']) and !empty($_GET['pack_id']))?'&pack_id='.$_GET['pack_id']:'';
                $status_id = (isset($_GET['status_id']) and !empty($_GET['status_id']))?'&status_id='.$_GET['status_id']:'';
                ?>
                var url = 'admin.php?page=user-reservation-admin-details&month_id=' + id + '<?= $year.$user_id.$pack_id.$status_id;?>';
                window.location = url;
            });

            $("#pack_id").change(function () {
                var id = $(this).val();
                <?php
                $year = (isset($_GET['year']) and !empty($_GET['year']))?'&year='.$_GET['year']:'';
                $month_id = (isset($_GET['month_id']) and !empty($_GET['month_id']))?'&month_id='.$_GET['month_id']:'';
                $user_id = (isset($_GET['user_id']) and !empty($_GET['user_id']))?'&user_id='.$_GET['user_id']:'';
                $status_id = (isset($_GET['status_id']) and !empty($_GET['status_id']))?'&status_id='.$_GET['status_id']:'';
                ?>
                var url = 'admin.php?page=user-reservation-admin-details&pack_id=' + id + '<?= $year.$user_id.$month_id.$status_id;?>';
                window.location = url;
            });

            $("#status_id").change(function () {
                var id = $(this).val();
                <?php

                $year = (isset($_GET['year']) and !empty($_GET['year']))?'&year='.$_GET['year']:'';
                $month_id = (isset($_GET['month_id']) and !empty($_GET['month_id']))?'&month_id='.$_GET['month_id']:'';
                $user_id = (isset($_GET['user_id']) and !empty($_GET['user_id']))?'&user_id='.$_GET['user_id']:'';
                $pack_id = (isset($_GET['pack_id']) and !empty($_GET['pack_id']))?'&pack_id='.$_GET['pack_id']:'';
                ?>
                var url = 'admin.php?page=user-reservation-admin-details&status_id=' + id + '<?= $year.$user_id.$month_id.$pack_id;?>';
                window.location = url;
            });

        });
    })(jQuery);

</script>