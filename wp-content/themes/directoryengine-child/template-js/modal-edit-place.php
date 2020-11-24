<?php
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

?>

<div class="modal fade modal-submit-questions" id="edit_place" role="dialog" aria-labelledby="myModalLabel_editplace"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-times"></i>
                </button>

                <h4 class="modal-title modal-title-sign-in"
                    id="myModalLabel_editplace"><?php _e("Place Info", ET_DOMAIN) ?></h4>

            </div>

            <div class="modal-body">

                <form id="submit_form" class="form_modal_style edit-anuncio-popup">

                    <ul class="nav nav-tabs list-edit-place" role="tablist" id="myTab">

                        <li class="active">
                            <a href="#information_places" role="tab"
                               data-toggle="tab"><?php _e("Information", ET_DOMAIN); ?></a></li>

                        <li><a href="#cover_container" role="tab"
                               data-toggle="tab">Cabecera</a></li>

                        <li >
                            <?php if (!getIfApp()) :?>
                            <a href="#gallery_place1" role="tab"
                               data-toggle="tab"><?php _e("Gallery", ET_DOMAIN); ?>
                            </a>
                            <?php else: ?>
                                <a class="aviso-movil-upload modal-edit-place-cabecera-fotos"><?php _e("Gallery", ET_DOMAIN); ?></a>
                            <?php endif; ?>
                        </li>

                    </ul>

                    <div class="tab-content">

                        <!-- Tabs 1 / Start -->

                        <div class="tab-pane fade active body-tabs in" id="information_places">

                            <!-- categoria-->
                            <div class="form-field">

                                <label><?php _e("CATEGORY", ET_DOMAIN) ?><span class="alert-icon">*</span></label>

                                <?php ae_tax_dropdown('place_category',
                                    array('attr' => 'multiple data-placeholder="' . 'Ej. Fontanería, Informática, etc.' . '"',
                                        'class' => 'chosen multi-tax-item required ',
                                        'hide_empty' => false,
                                        'hierarchical' => true,
                                        'id' => 'place_category',
                                        'show_option_all' => false
                                    )
                                );
                                ?>

                            </div>

                            <!-- Titulo -->
                            <div class="form-field">

                                <label><?php _e("PLACE NAME", ET_DOMAIN) ?><span class="alert-icon">*</span></label>
                                <input placeholder="Ej. Electricista a domicilio" type="text"
                                       class="text-field required" name="post_title" id="post_title"/>

                            </div>

                            <!-- Descripción -->
                            <div class="form-field">

                                <label><?php _e("DESCRIPTION", ET_DOMAIN) ?></label>
                                <?php wp_editor('', 'post_content', ae_editor_settings()); ?>
                                <div class="message_error_textarea error"></div>
                            </div>


                            <!-- Dirección -->
                            <div class="form-field">

                                <label><?php _e("Código Postal", ET_DOMAIN) ?><span class="alert-icon">*</span></label>

                                <input type="text"
                                       size="5" maxlength="5"
                                       onkeyup="this.value=this.value.replace(/[^\d]/,'')"
                                       placeholder="Ej. 18110 (Obligatorio)"
                                       class="text-field required" name="et_full_location" id="et_full_location"/>

                                <input type="text" placeholder="Ej. Calle Cádiz nº1 (Opcional)"
                                       class="text-field may_error" name="et_full_location2"
                                       id="et_full_location2"/>

                                <input type="hidden" class="" name="et_location_lat" id="et_location_lat"/>

                                <input type="hidden" class="" name="et_location_lng" id="et_location_lng"/>

                                <div id="map" class="map" style="display: none;"></div>
                            </div>


                            <!-- Localización -->
                            <div class="form-field">
                                <label><?php _e("Provincia", ET_DOMAIN) ?><span class="alert-icon">*</span></label>
                                <?php ae_tax_dropdown('location',
                                    array('class' => 'chosen-single tax-item required',
                                        'hide_empty' => false,
                                        'hierarchical' => true,
                                        'id' => 'location',
                                        'class' => 'location_popup text-field',
                                        'show_option_all' => __("Select your location", ET_DOMAIN)
                                    )
                                );

                                ?>

                            </div>

                            <!-- Distancia -->
                            <div class="form-field">

                                <label><?php _e("Distancia", ET_DOMAIN) ?></label>

                                <input placeholder="Ej. Toda la ciudad, 5km, etc." type="text" class="text-field"
                                       name="et_distance" id="et_distance"/>

                            </div>


                            <!--Horas y dias-->
                            <div class="form-field" style="height: 110px;">
                                <label><?php _e('OPENING TIME', ET_DOMAIN); ?></label>

                                <div class="open-block">
                                    <div class="open-date">
                                        <span class="select-date-all dselect-date-all"><?php _e("Select All", ET_DOMAIN); ?></span>

                                        <ul class="date-list select_time_to_payment clearfix">
                                            <li id="Mon" class="bdate" data-name="Mon" open-time-2="" close-time-2=""
                                                data-toggle="tooltip"
                                                data-placement="bottom"><?php _e("Mon", ET_DOMAIN); ?></li>
                                            <li id="Tue" class="bdate" data-name="Tue" open-time-2="" close-time-2=""
                                                data-toggle="tooltip"
                                                data-placement="bottom"><?php _e("Tue", ET_DOMAIN); ?></li>
                                            <li id="Wed" class="bdate" data-name="Wed" open-time-2="" close-time-2=""
                                                data-toggle="tooltip"
                                                data-placement="bottom"><?php _e("Wed", ET_DOMAIN); ?></li>
                                            <li id="Thu" class="bdate" data-name="Thu" open-time-2="" close-time-2=""
                                                data-toggle="tooltip"
                                                data-placement="bottom"><?php _e("Thu", ET_DOMAIN); ?></li>
                                            <li id="Fri" class="bdate" data-name="Fri" open-time-2="" close-time-2=""
                                                data-toggle="tooltip"
                                                data-placement="bottom"><?php _e("Fri", ET_DOMAIN); ?></li>
                                            <li id="Sat" class="bdate" data-name="Sat" open-time-2="" close-time-2=""
                                                data-toggle="tooltip"
                                                data-placement="bottom"><?php _e("Sat", ET_DOMAIN); ?></li>
                                            <li id="Sun" class="bdate lbdate" data-name="Sun" open-time-2=""
                                                close-time-2="" data-toggle="tooltip"
                                                data-placement="bottom"><?php _e("Sun", ET_DOMAIN); ?></li>
                                        </ul>
                                        <span
                                                class="select-date-all mselect-date-all"><?php _e("Select All", ET_DOMAIN); ?></span>
                                        <span class="reset-all"><?php _e("Reset All", ET_DOMAIN); ?></span>
                                        <span class="open-input">
                                            </span>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="open-times">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-3 col-xs-3">
                                                <div class="container-open-time">
                                                    <input name="open_time" class="text-field time-picker open-time"
                                                           data-template="modal" data-minute-step="1"
                                                           data-modal-backdrop="true" type="text"
                                                           placeholder="--"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-3">
                                                <div class="container-close-time">
                                                    <input name="close_time" class="text-field time-picker close-time"
                                                           data-template="modal" data-minute-step="1"
                                                           data-modal-backdrop="true" type="text"
                                                           placeholder="--"/>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-3 col-xs-3">
                                                <div class="container-open-time-2">
                                                    <input name="open_time_2" class="text-field time-picker open-time-2"
                                                           data-template="modal" data-minute-step="1"
                                                           data-modal-backdrop="true" type="text"
                                                           placeholder="--"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3 col-xs-3">
                                                <div class="container-close-time-2">
                                                    <input name="close_time_2"
                                                           class="text-field time-picker close-time-2"
                                                           data-template="modal" data-minute-step="1"
                                                           data-modal-backdrop="true" type="text"
                                                           placeholder="--"/>
                                                </div>
                                            </div>

                                        </div>
                                        <span><?php _e("to", ET_DOMAIN); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!--Precio por horas-->
                            <div class="form-field">

                                <label>TIPO DE RESERVA<span class="alert-icon">*</span></label>

                                <div class="row">

                                    <div class="tipo_servicio col-md-12 col-sm-12">


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

                                    </div>
                                </div>

                                <div class="row tipo_servicio_hora">
                                    <div class="col-md-12 title-plan-modal">
                                        <?php
                                        global $ae_post_factory;
                                        $place_obj = $ae_post_factory->get('place');
                                        $place = $place_obj->current_post;
                                        $tipo_reserva = get_tipo_reserva($place->ID);

                                        if ($tipo_reserva == "horas") {
                                            echo "<label>PRECIO POR HORA<span class='alert-icon'>*</span></label>";
                                        } else if ($tipo_reserva == "servicio") {
                                            echo "<label>PRECIO POR SERVICIO<span class='alert-icon'>*</span></label>";
                                        } else { // a convenir, no mostramos nada
                                        }
                                        ?>
                                    </div>


                                    <div class="tiempo_servicio col-md-12 col-sm-12 col-xs-12 right">
                                        <select id="duracion_servicio" name="duracion_servicio" class="chosen-single"
                                                onchange="document.getElementById('duracion_servicio').value=this.value;">
                                            <option selected="selected" value="menos">Menos de 1 hora</option>
                                            <option value="1">1 hora</option>
                                            <option value="2">2 horas</option>
                                            <option value="3">3 horas</option>
                                            <option value="mas">+ de 4 horas</option>
                                        </select>

                                    </div>
                                    <div class="precio_hora col-md-12 col-sm-12 col-xs-12 input-euro right">
                                        <input type="text" step="any" class="text-field input-item hourlyrate"
                                               name="hourly_rate1"
                                               id="hourly_rate1" placeholder="Ej. 15">
                                    </div>
                                </div>
                            </div>

                            <!--Teléfono-->
                            <div class="form-field">
                                <label><?php _e("PHONE", ET_DOMAIN) ?></label>
                                <input placeholder="Ej. 666465465" type="text" class="text-field" name="et_phone"
                                       id="et_phone"/>
                            </div>

                            <!--Vehículo-->
                            <div class="form-field">

                                <label><?php _e("Do you have vehicle to move?", ET_DOMAIN) ?></label>

                                <select id="et_have_car_sel" class="chosen-single"
                                        onchange="document.getElementById('et_have_car').value=this.value;">
                                    <option value="No">No</option>
                                    <option value="Si">Si</option>
                                </select>

                                <input type="text" value="No" class="text-field input-item" name="et_have_car"
                                       id="et_have_car" style="display:none;"/>
                            </div>


                            <!--Sitio web-->
                            <div class="form-field">
                                <label><?php _e("WEBSITE", ET_DOMAIN) ?></label>
                                <input placeholder="Ej. http://www.paginaweb.com" type="text" class="text-field"
                                       name="et_url" id="et_url"/>
                            </div>

                            <!--Facebook-->
                            <div class="form-field">
                                <label><?php _e("FACEBOOK", ET_DOMAIN) ?></label>
                                <input placeholder="Ej. https://www.facebook.com/nombreusuario" type="text"
                                       class="text-field" name="et_fb_url" id="et_fb_url"/>
                            </div>

                            <!--Google+-->
                            <div class="form-field">
                                <label><?php _e("GOOGLE+", ET_DOMAIN) ?></label>
                                <input placeholder="Ej. https://plus.google.com/+nombreusuario" type="text"
                                       class="text-field" name="et_google_url" id="et_google_url"/>
                            </div>

                            <!--Twitter-->
                            <div class="form-field">
                                <label><?php _e("TWITTER", ET_DOMAIN) ?></label>
                                <input placeholder="Ej. https://twitter.com/nombreusuario" type="text"
                                       class="text-field" name="et_twitter_url" id="et_twitter_url"/>
                            </div>

                            <?php do_action('ae_edit_post_form', $post); ?>

                            <div class="clearfix"></div>

                        </div>

                        <!-- Tabs 2 / Start -->

                        <div class="tab-pane fade body-tabs" id="cover_container">


                                <span class="et_ajaxnonce"
                                      id="<?php echo wp_create_nonce('cover_et_uploader'); ?>"></span>

                            <div class="form-field edit-cover-image <?= (getIfApp() ? 'aviso-movil-upload modal-edit-place-imageanuncio' : '')?>">

                                <label><?php _e("Cover Image", ET_DOMAIN) ?></label>

                                <p><?php _e("Your cover image's minimum size must be 1440x500. ", ET_DOMAIN); ?>

                                    <br><?php _e("Tips: Remember the video space when designing your image.", ET_DOMAIN); ?>
                                </p>

                                <ul class="option-cover-image">

                                    <li><span class="image-cover" id="cover_browse_button">

                                                <span id="cover_thumbnail"></span>

                                                <i class="fa fa-cloud-upload"></i>

                                            </span>

                                    </li>

                                    <li><a id="delete-cover-image" href="#"><i
                                                    class="fa fa-trash-o"></i> <?php _e("Delete image", ET_DOMAIN); ?>
                                        </a>
                                    </li>

                                    <!-- <li><a href="#"><i class="fa fa-cloud-upload"></i> <?php _e("Upload new image", ET_DOMAIN); ?></a></li> -->

                                </ul>

                            </div>
                            <div class="form-field edit-cover-image">

                                <label><?php _e("VIDEO", ET_DOMAIN) ?></label>

                                <input type="text" class="text-field" name="et_video" id="et_video"
                                       placeholder="Ej. https://www.youtube.com/watch?v=d7MY1l3kcvo"/>

                            </div>

                            <div class="form-field row posiciones-video">

                                <label class="col-md-6 col-xs-6">

                                    <input value="left" type="radio" class="video-position text-field"
                                           name="video_position"
                                           id="video_left"> <?php _e("Left", ET_DOMAIN); ?>

                                </label>

                                <label class="col-md-6 col-xs-6">

                                    <input checked value="right" type="radio" class="video-position text-field"
                                           name="video_position" id="video_right"> <?php _e("Right", ET_DOMAIN); ?>

                                </label>

                            </div>

                            <div class="form-field edit-cover-image">

                                <label><?php _e("PREVIEW HEADER", ET_DOMAIN) ?></label>

                                <span class="img-preview image" id="cover_background">

                                        <img src="<?php echo get_template_directory_uri() ?>/img/demo-preview-video.jpg"
                                             class="left-img-preview">

                                    </span>

                            </div>

                            <div class="clearfix"></div>

                        </div>

                        <!-- Tabs 3 / Start -->

                        <div class="tab-pane fade body-tabs" id="gallery_place1">

                            <div class="form-field edit-gallery-image" id="gallery_container">

                                <label><?php _e("PHOTOS", ET_DOMAIN) ?></label>

                                <p><?php _e("Select one picture for your featured image", ET_DOMAIN); ?></p>

                                <ul class="gallery-image carousel-list" id="image-list">

                                    <li>

                                        <div class="plupload_buttons" id="carousel_container">

                                                <span class="img-gallery" id="carousel_browse_button">

                                                    <a href="#" class="add-img"><i class="fa fa-plus"></i></a>

                                                </span>

                                        </div>

                                    </li>

                                </ul>

                                <span class="et_ajaxnonce"
                                      id="<?php echo wp_create_nonce('ad_carousels_et_uploader'); ?>"></span>

                                <img class="rotat" src="<?php echo get_template_directory_uri() ?>/img/rotate.png"
                                     style="cursor:pointer"> Rotar Imagen

                            </div>

                            <div class="clearfix"></div>

                        </div>

                    </div>

                    <div class="submit-style align-centered">

                        <input type="submit" value="<?php _e("Submit", ET_DOMAIN); ?>" class="btn-submit"/>

                    </div>

                    <input type="hidden" id="alguna_opcion_date_seleccionada" name="alguna_opcion_date_seleccionada"
                           value=""/>
                    <input type="hidden" id="necesary_terms" name="necesary_terms" value="0"/>

                    <input type="hidden" name="DOPBSP-calendar-ID" id="DOPBSP-calendar-ID" value=""/>

                </form>

            </div>

        </div>

    </div>

</div>
