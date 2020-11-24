<!-- Step 3 -->
<?php
global $user_ID;
$step = 3;


$plan_gratis = true;
if (is_user_logged_in()) {
    global $current_user;
    if ($current_user) {
        $package_data = AE_Package::get_package_data($user_ID);
        if ($package_data !== "" && !isset($package_data["001"])) {  // si tiene anuncios pero su paquete es vacio, es gratis
            $plan_gratis = false;
        }
    }
}

$disable_plan = ae_get_option('disable_plan', false);
if ($disable_plan) $step--;
if ($user_ID) $step--;
if ($plan_gratis) $step--;

$post = '';
if (isset($_REQUEST['id'])) {
    $post = get_post($_REQUEST['id']);
    if ($post) {
        global $ae_post_factory;
        $post_object = $ae_post_factory->get($post->post_type);
        echo '<script type="data/json"  id="edit_postdata">' . json_encode($post_object->convert($post)) . '</script>';
    }
}

?>


<div class="step-wrapper step-post" id="step-post">
    <a href="#" class="step-heading active">

        <span class="number-step"><?= $step + 1 ?></span>
        <h2 class="text-heading-step"><?php _e("Detalles de tu anuncio", ET_DOMAIN); ?></h2>
        <i class="fa fa-caret-right"></i>

    </a>

    <div class="step-content-wrapper content" style="<?php if ($step != 2) echo "display:none;" ?>">

        <form action="" class="post">

            <ul class="list-form-login">

                <li class="Title-bloc-anuncio">Datos del anuncio</li>
                <!-- li categoria-->
                <li>

                    <div class="row">

                        <div class="col-md-6">

                            <span class="title-plan">

                                <span class="mandatory_fields">*</span> <?php _e("CATEGORY", ET_DOMAIN); ?>

                                <span><?php _e("Selecciona los servicios que realizas", ET_DOMAIN); ?></span>

                            </span>

                        </div>

                        <div class="col-md-6 search-category onfocustop">

                            <?php

                            ae_tax_dropdown('place_category',

                                array(
                                    'attr' => 'multiple data-placeholder="Selecciona aqui las categorias donde quieres aparecer"',

                                    'class' => 'chosen multi-tax-item tax-item',

                                    'hide_empty' => false,

                                    'hierarchical' => true,

                                    'id' => 'place_category',

                                    'show_option_all' => false

                                )

                            ); ?>

                        </div>

                    </div>

                </li>

                <!--Título-->
                <li>

                    <div class="row">

                        <div class="col-md-6">

                            <span class="title-plan">

                                <span class="mandatory_fields">*</span> <?php _e("PLACE NAME", ET_DOMAIN); ?>

                                <span><?php _e("Keep it short & clear", ET_DOMAIN); ?></span>

                            </span>

                        </div>

                        <div class="col-md-6">

                            <input type="text" name="post_title" id="post_title"
                                   class="text-field input-item onfocustop"
                                   placeholder="Ej. Electricista a domicilio">

                        </div>

                    </div>

                </li>

                <!--Description-->
                <li>

                    <div class="row">

                        <div class="col-md-6">

                            <span class="title-plan">

                                <span class="mandatory_fields">*</span> <?php _e("DESCRIPTION", ET_DOMAIN); ?>

                                <span><?php _e("Ideally 3 short paragraphs", ET_DOMAIN); ?></span>

                            </span>

                        </div>

                        <div class="col-md-6 onfocustop">

                            <?php wp_editor('', 'post_content', ae_editor_settings()); ?>

                            <div class="message_error_textarea error"></div>
                        </div>

                    </div>

                </li>

                <!--Fotos-->

                <li>

                    <div class="row" id="gallery_place">

                        <div class="col-md-6">

                            <span class="title-plan">
                             <?php _e("PHOTOS", ET_DOMAIN); ?>
                                <span><?php printf(__("Up to %s pictures<br>Select one picture for your featured image", ET_DOMAIN), ae_get_option('max_carousel', 5)); ?></span>
                            </span>

                        </div>

                        <div class="form-group form-field edit-gallery-image col-md-6 onfocustop" id="gallery_container">

                            <ul class="gallery-image carousel-list" id="image-list">

                                <li>

                                    <div class="plupload_buttons <?= (getIfApp() ? 'aviso-movil-upload post-place-step3-anuncio' : '') ?>"
                                         id="carousel_container">

                                        <span class="img-gallery" id="carousel_browse_button">

                                            <a href="#" class="add-img"><i class="fa fa-plus"></i></a>

                                        </span>

                                        <input type="text" value="" class="text-field input-item"
                                               name="et_have_image"
                                               id="et_have_image" style="display:none;"/>
                                    </div>

                                </li>

                            </ul>

                            <span class="et_ajaxnonce"
                                  id="<?php echo wp_create_nonce('ad_carousels_et_uploader'); ?>"></span>


                        </div>

                    </div>

                </li>

                <li class="Title-bloc-anuncio linea3px">Localización del anuncio</li>

                <!--Dirección-->
                <li>

                    <div class="row">

                        <div class="col-md-6">

                            <span class="title-plan">

                                <span
                                        class="mandatory_fields">*</span> <?php _e("Código Postal y Dirección", ET_DOMAIN); ?>

                                <span>Introduce tu código postal en la primera fila y <br/>opcionalmente introduce tu dirección en la segunda fila</span>

                            </span>

                        </div>


                        <div class="col-md-6">

                            <input type="text" name="et_full_location" id="et_full_location"
                                   class="text-field input-item vvv onfocustop"
                                   size="5" maxlength="5"
                                   onkeyup="this.value=this.value.replace(/[^\d]/,'')"
                                   placeholder="Ej. 18110 (Obligatorio)">

                            <input type="hidden" name="et_location_lat" id="et_location_lat"
                                   class="text-field input-item ">

                            <input type="hidden" name="et_location_lng" id="et_location_lng"
                                   class="text-field input-item ">

                            <input type="text" class="text-field input-item may_error onfocustop"
                                   name="et_full_location2"
                                   id="et_full_location2"
                                   placeholder="Ej. Calle Cádiz nº1 (Opcional)">

                            <div id="map"></div>

                            <span style=" font-size: 0.8em; font-style: italic;">
                                <?php _e("Drag the marker to specify correct coords.", ET_DOMAIN); ?>
                            </span>

                        </div>

                    </div>

                </li>

                <!--Localización-->
                <li>

                    <div class="row">

                        <div class="col-md-6">

                            <span class="title-plan">

                                <span class="mandatory_fields">*</span> <?php _e("Provincia", ET_DOMAIN); ?>

                                <span><?php _e("Seleccione su provincia", ET_DOMAIN); ?></span>

                            </span>

                        </div>

                        <div class="col-md-6 onfocustop">


                            <?php ae_tax_dropdown('location',

                                array('class' => 'chosen-single tax-item',

                                    'hide_empty' => false,

                                    'hierarchical' => true,

                                    'id' => 'location',

                                    'show_option_all' => __("Selecciona tu ciudad", ET_DOMAIN)

                                )

                            ); ?>


                        </div>

                    </div>

                </li>


                <!--Distancia-->
                <li>

                    <div class="row">

                        <div class="col-md-6">

                            <span class="title-plan">

                                <?php _e("Distancia", ET_DOMAIN) ?>

                                <span><?php _e("Your place's twitter url", ET_DOMAIN); ?></span>

                            </span>

                        </div>

                        <div class="col-md-6">

                            <input type="text" class="text-field input-item onfocustop" name="et_distance"
                                   placeholder="Ej. Toda la ciudad, 5km, etc."
                                   id="et_distance"/>

                        </div>

                    </div>

                </li>

                <!--<span class="cont-title">  Contratacion Fija </span>-->
                <?php /*
                <li class="form-field icon-input cont-field">
                    <div class="row">

                        <div class="col-md-6">

                            <span class="title-plan">

                                <?php _e("OPEN DAYS", ET_DOMAIN) ?>

                                <span><?php _e("Your place's serve day", ET_DOMAIN); ?></span>

                            </span>

                        </div>
						
                        <div class="col-md-6">

                            <select name="serve_day" data-placeholder="<?php _e("Select some days", ET_DOMAIN); ?>" class="chosen-multi input-item" multiple style="width:20%;" data-disable-search="true" >

                                <option value="0"><?php _e("All day in week", ET_DOMAIN); ?></option>

                                <option value="1"><?php _e("Monday", ET_DOMAIN); ?></option>

                                <option value="2"><?php _e("Tuesday", ET_DOMAIN); ?></option>

                                <option value="3"><?php _e("Wednesday", ET_DOMAIN); ?></option>

                                <option value="4"><?php _e("Thursday", ET_DOMAIN); ?></option>

                                <option value="5"><?php _e("Friday", ET_DOMAIN); ?></option>

                                <option value="6"><?php _e("Saturday", ET_DOMAIN); ?></option>

                                <option value="7"><?php _e("Sunday", ET_DOMAIN); ?></option>

                            </select>

                        </div>

                    </div>

                </li>
				*/ ?>

                <li class="Title-bloc-anuncio linea3px">Horarios</li>
                <!--Días y horas disponibles-->
                <li id="dias_disponibles">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <span class="title-plan">
							<span class="mandatory_fields">*</span>
                                <?php _e('OPENING TIME', ET_DOMAIN); ?>
                                <span><?php _e('Indica los días y las horas disponibles para la contratación. Una vez publicado tu anuncio podras editar en cualquier momento tu calendario de disponibilidad de forma detallada desde el panel de tu anuncio.', ET_DOMAIN) ?></span>
                            </span>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="open-block onfocustop">

                                <div class="open-date">
                                        <span class="select-date-all dselect-date-all hover">
                                            <?php _e("Select All", ET_DOMAIN); ?>
                                        </span>
                                    <ul class="date-list clearfix col-md-12">
                                        <li class="bdate" data-name="Mon" open-time-2="" close-time-2=""
                                            data-toggle="tooltip"
                                            data-placement="bottom"><?php _e("Mon", ET_DOMAIN); ?>
                                        </li>
                                        <li class="bdate" data-name="Tue" open-time-2="" close-time-2=""
                                            data-toggle="tooltip"
                                            data-placement="bottom"><?php _e("Tue", ET_DOMAIN); ?>
                                        </li>
                                        <li class="bdate" data-name="Wed" open-time-2="" close-time-2=""
                                            data-toggle="tooltip"
                                            data-placement="bottom"><?php _e("Wed", ET_DOMAIN); ?>
                                        </li>
                                        <li class="bdate" data-name="Thu" open-time-2="" close-time-2=""
                                            data-toggle="tooltip"
                                            data-placement="bottom"><?php _e("Thu", ET_DOMAIN); ?>
                                        </li>
                                        <li class="bdate" data-name="Fri" open-time-2="" close-time-2=""
                                            data-toggle="tooltip"
                                            data-placement="bottom"><?php _e("Fri", ET_DOMAIN); ?>
                                        </li>
                                        <li class="bdate" data-name="Sat" open-time-2="" close-time-2=""
                                            data-toggle="tooltip"
                                            data-placement="bottom"><?php _e("Sat", ET_DOMAIN); ?>
                                        </li>
                                        <li class="bdate lbdate" data-name="Sun" open-time-2="" close-time-2=""
                                            data-toggle="tooltip"
                                            data-placement="bottom"><?php _e("Sun", ET_DOMAIN); ?>
                                        </li>
                                    </ul>
                                    <span
                                            class="select-date-all mselect-date-all"><?php _e("Select All", ET_DOMAIN); ?></span>
                                    <span class="reset-all"><?php _e("Reset All", ET_DOMAIN); ?></span>
                                    <span class="open-input"></span>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="open-block-sep clearfix">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <div class="sep-title with-border">Mañana</div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                            <div class="sep-title">Tardes</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="open-times">
                                    <div class="row">

                                        <div id="datepair-time-one">
                                            <div class="col-md-3 col-sm-3 col-xs-3 label-first">
                                                <div class="container-open-time">
                                                    <input name="open_time" onfocus="this.blur()"
                                                           class="text-field time-picker open-time time start"
                                                           data-template="modal" data-minute-step="1"
                                                           data-modal-backdrop="true" value="" type="text"
                                                           placeholder="--"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-3 label-last">
                                                <div class="container-close-time">
                                                    <input name="close_time" onfocus="this.blur()"
                                                           class="text-field time-picker close-time time end"
                                                           data-template="modal" data-minute-step="1"
                                                           data-modal-backdrop="true" value="" type="text"
                                                           placeholder="--"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="datepair-time-two">
                                            <div class="col-md-3 col-sm-3 col-xs-3 label-first">
                                                <div class="container-open-time-2">
                                                    <input name="open_time_2" onfocus="this.blur()"
                                                           class="text-field time-picker open-time-2 time start"
                                                           data-template="modal" data-minute-step="1"
                                                           data-modal-backdrop="true" value="" type="text"
                                                           placeholder="--"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-3 label-last">
                                                <div class="container-close-time-2">
                                                    <input name="close_time_2" onfocus="this.blur()"
                                                           class="text-field time-picker close-time-2 time end"
                                                           data-template="modal" data-minute-step="1"
                                                           data-modal-backdrop="true" value="" type="text"
                                                           placeholder="--"/>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <span>Y</span>
                                </div>
                                <div class="clearfix"></div>
                                <input type="hidden" id="alguna_opcion_date_seleccionada"
                                       name="alguna_opcion_date_seleccionada"
                                       value=""/>

                            </div>
                        </div>
                    </div>
                </li>

                <!--Precio por horas-->
                <li>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="title-plan">
							   <span class="mandatory_fields">*</span>
                              TIPO DE RESERVA
                                <span>Indique si el tipo de reserva es por "horas", "servicio" o "a convenir"  </span>
                            </span>
                        </div>

                        <div class="tipo_servicio col-md-6 col-sm-12 onfocustop">
                            <span class="aclaratoria_tipo_servicio"></span>

                            <?php if ($plan_gratis): // en plan gratis no hay a convenir?>

                                <label class="radio-inline uno col-md-6 col-sm-6 col-xs-6">
                                    <input type="radio" name="tipo_reserva" value="horas">Por horas
                                </label>

                                <label class="radio-inline dos col-md-6 col-sm-6 col-xs-6">
                                    <input type="radio" name="tipo_reserva" value="servicio">
                                    Por servicio
                                </label>

                            <?php else: ?>

                                <label class="radio-inline uno col-md-4 col-sm-4 col-xs-4">
                                    <input type="radio" name="tipo_reserva" value="horas">Por horas
                                </label>

                                <label class="radio-inline dos col-md-4 col-sm-4 col-xs-4">
                                    <input type="radio" name="tipo_reserva" value="servicio">
                                    Por servicio
                                </label>

                                <label class="radio-inline tres col-md-4 col-sm-4 col-xs-4">
                                    <input type="radio" name="tipo_reserva" value="convenir">
                                    A convenir
                                </label>

                            <?php endif; ?>
                            <div class="clearfix"></div>
                        </div>


                    </div>

                    <div class="row tipo_servicio_hora">
                        <div class="col-md-6">
                            <span class="title-plan">
							   <span class="mandatory_fields">*</span>
                                PRECIO POR HORAS
                                <span>Indique el precio por hora</span>
                            </span>
                        </div>


                        <div class="tiempo_servicio onfocustop col-md-2 col-sm-6 col-xs-12 right">
                            <select id="duracion_servicio" name="duracion_servicio" class="chosen-single">
                                <option value="menos">Menos de 1 hora</option>
                                <option value="1">1 hora</option>
                                <option value="2">2 horas</option>
                                <option value="3">3 horas</option>
                                <option value="mas">+ de 4 horas</option>
                            </select>
                        </div>
                        <div class="precio_hora onfocustop col-md-2 col-sm-6 col-xs-12 input-euro right">
                            <input type="text" step="any" class="text-field input-item hourlyrate" name="hourly_rate1"
                                   id="hourly_rate1" placeholder="Ej. 15">
                        </div>
                    </div>

                </li>

                <!--Ajustes del calendario-->
                <li class="form-field icon-input front-hide">
                    <div class="row">
                        <div class="col-md-6">
                            <span class="title-plan">
                                <?php _e("Ajustes del Calendario:", ET_DOMAIN) ?>
                                <span><?php _e("Actualiza tu calendario", ET_DOMAIN); ?></span>
                            </span>
                        </div>
                        <div class="col-md-6 time-picker-body">

                            <input type="hidden" name="DOPBSP-calendar-jump-to-day" id="DOPBSP-calendar-jump-to-day"
                                   value=""/>

                            <?php /*<input type="hidden" id="calendar_id" name="calendar_id" class="input-item" value="<?php echo $calid; ?>" />
                        <input type="hidden" name="calendar_jump_to_day" id="calendar_jump_to_day" value="" />
                        <input type="hidden" value="" id="calendar_refresh" name="calendar_refresh">*/ ?>
                            <ul class="dropdown-menu-right single-place-control" role="menu"
                                aria-labelledby="dropdownMenu1">
                                <li role="presentation">

                                    <a class="place-action edit_calender edit" role="menuitem"
                                       data-action="edit_calender" href="#edit_calender" data-target="#edit_calender">

                                        <i class="fa fa-pencil"></i> <?= "Configurar calendario" ?>

                                    </a>
                                    <input type="text" value="1" class="text-field input-item" name="et_have_calendar"
                                           id="et_have_calendar" style="display:none;"/>
                                </li>
                            </ul>
                        </div>
                    </div>
                </li>

                <li class="Title-bloc-anuncio linea3px">Datos del anunciante</li>
                <!--Teléfono-->
                <li>

                    <div class="row">

                        <div class="col-md-6">

                            <span class="title-plan">

                                <?php _e("PHONE", ET_DOMAIN) ?>

                                <span><?php _e("Tu teléfono de contacto", ET_DOMAIN); ?></span>

                            </span>

                        </div>

                        <div class="col-md-6">

                            <input type="text"
                                   placeholder="Ej. 666465465"
                                   class="text-field input-item onfocustop" name="et_phone" id="et_phone"/>

                        </div>

                    </div>

                </li>

                <!--Vehículo-->
                <li>

                    <div class="row">

                        <div class="col-md-6">

                            <span class="title-plan">

                                <?php _e("Do you have vehicle to move?", ET_DOMAIN) ?>

                            </span>

                        </div>

                        <div class="dispone_coche onfocustop col-md-2 col-sm-12 col-xs-12">
                            <select id="et_have_car_sel" class="chosen-single"
                                    onchange="document.getElementById('et_have_car').value=this.value;">
                                <option value="No">No</option>
                                <option value="Si">Si</option>

                            </select>

                            <input type="text" value="No" class="text-field input-item" name="et_have_car"
                                   id="et_have_car" style="display:none;"/>

                        </div>

                    </div>

                </li>

                <li class="Title-bloc-anuncio linea3px">Otros datos</li>

                <!--Sitio web-->
                <li>

                    <div class="row">

                        <div class="col-md-6">

                            <span class="title-plan">

                                <?php _e("WEBSITE", ET_DOMAIN) ?>

                                <span><?php _e("Your place's website url", ET_DOMAIN); ?></span>

                            </span>

                        </div>

                        <div class="col-md-6">

                            <input type="text" placeholder="Ej. http://www.paginaweb.com"
                                   class="text-field input-item is_url onfocustop" name="et_url" id="et_url"/>

                        </div>

                    </div>

                </li>

                <!--Facebook-->
                <li>

                    <div class="row">

                        <div class="col-md-6">

                            <span class="title-plan">

                                <?php _e("FACEBOOK", ET_DOMAIN) ?>

                                <span><?php _e("Your place's facebook url", ET_DOMAIN); ?></span>

                            </span>

                        </div>

                        <div class="col-md-6">

                            <input type="text" placeholder="Ej. https://www.facebook.com/nombreusuario"
                                   class="text-field input-item is_url onfocustop" name="et_fb_url" id="et_fb_url"/>

                        </div>

                    </div>

                </li>

                <!--Google+-->
                <li>

                    <div class="row">

                        <div class="col-md-6">

                            <span class="title-plan">

                                <?php _e("GOOGLE+", ET_DOMAIN) ?>

                                <span><?php _e("Indique la url de su perfil de Google+", ET_DOMAIN); ?></span>

                            </span>

                        </div>

                        <div class="col-md-6">

                            <input type="text" placeholder="Ej. https://plus.google.com/+nombreusuario"
                                   class="text-field input-item is_url onfocustop" name="et_google_url"
                                   id="et_google_url"/>

                        </div>

                    </div>

                </li>

                <!--Twitter-->
                <li>

                    <div class="row">

                        <div class="col-md-6">

                            <span class="title-plan">

                                <?php _e("TWITTER", ET_DOMAIN) ?>

                                <span><?php _e("Indique la url de su perfil de Twitter", ET_DOMAIN); ?></span>

                            </span>

                        </div>

                        <div class="col-md-6">

                            <input type="text" placeholder="Ej. https://twitter.com/nombreusuario"
                                   class="text-field input-item is_url onfocustop" name="et_twitter_url"
                                   id="et_twitter_url"/>

                        </div>

                    </div>

                </li>

                <li class="linea3px">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 pull-right conditions">
                            <span class="title-plan">

                                <input type="checkbox"
                                       class="checkbox"
                                       name="terms_conditions"
                                       id="terms_conditions"
                                       style="float:left"/>

                                <label class="terms_conditions_label" for="terms_conditions">
                                    <span class="mandatory_fields">*</span>
                                    Estoy de acuerdo y acepto los
                                    <a target="new" href="<?= get_bloginfo('url'); ?>/terminos-y-condiciones/">términos
                                        y condiciones</a>
                                </label>
                            </span>
                        </div>
                    </div>
                    <input type="hidden" id="necesary_terms" name="necesary_terms" value="1"/>
                </li>
                <?php do_action('ae_submit_post_form', $post); ?>

                <li class="linea3px">

                    <div class="row">

                        <div class="col-md-6"></div>

                        <div class="col-md-6">

                            <input type="submit"
                                   value="<?php echo (!$disable_plan) ? __("Continuar", ET_DOMAIN) : __("Publicar anuncio", ET_DOMAIN); ?>"
                                   class="btn btn-submit-login-form"/>

                        </div>

                    </div>

                </li>
            </ul>

        </form>

    </div>

</div>
<?php if (isset($_REQUEST['id'])) { ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            setTimeout(function () {
                //jQuery('[data-name="Mon"]').click();
                jQuery('.vbdate').click();
            }, 3000);
        });
    </script>
<?php } ?>
<!-- Step 3 / End -->