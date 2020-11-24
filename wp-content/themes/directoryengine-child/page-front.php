<?php
/**
 * Template Name: Front Page Template
 */
get_header();

global $de_place_query, $post;

$args = array(
    'post_type' => 'place',
    'paged' => get_query_var('paged'),
    'post_status' => array('publish')
);

$de_place_query = new WP_Query($args);
echo '<script type="data/json"  id="total_place">' . json_encode(array('number' => $de_place_query->found_posts)) . '</script>';

$ruta_tema = get_stylesheet_directory_uri();


?>

    <div id="home">
        <div id="buscador_home">
            <div class="contenedor_h1">
                <h1>Busca y ofrece servicios cerca de ti.</h1>

                <div class="box_home">
                    <form action="<?php echo home_url(); ?>" method="get">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="de-scontrol">
                                    <div class="select-category-with-clear-button">
                                        <i style="top: 4px!important" title="Limpiar resultados"
                                           class="fa fa-times hover" aria-hidden="true"></i>
                                        <?php
                                        ae_tax_dropdown('place_category',
                                            array('attr' => 'multiple data-placeholder="' . __("Choose categories", ET_DOMAIN) . '"',
                                                'class' => 'chosen-single-category tax-item',
                                                'orderby' => "id",
                                                'show_option_all' => "Selecciona tu categoría",
                                                'hide_empty' => true,
                                                'hierarchical' => true,
                                                'id' => 'place_category-single',
                                                'value' => 'slug',
                                                'name' => 'c',
                                                'name_option' => 'c',
                                                'selected' => (isset($_REQUEST['c']) && $_REQUEST['c']) ? $_REQUEST['c'] : ''
                                            )
                                        )
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="de-scontrol">
                                    <i class="fa fa-map-marker address-search-icon search-location-marker"
                                       title="Utiliza mi geolocalización"
                                       aria-hidden="true"></i>
                                    <?php ae_tax_dropdown('location',
                                        array('class' => 'chosen-single tax-item',
                                            'hide_empty' => true,
                                            'hierarchical' => true,
                                            // 'id' => 'location' ,
                                            'show_option_all' => "Todas las ciudades",
                                            'value' => 'slug',
                                            'name' => 'l',
                                            'id' => 'location-advanced-search-home',
                                            'name_option' => 'l',
                                            'selected' => (isset($_REQUEST['l']) && $_REQUEST['l']) ? $_REQUEST['l'] : ''
                                        )
                                    ); ?>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="s"/>
                        <input class="btn-search hover" type="submit" value="<?php _e("Search", ET_DOMAIN); ?>"/>
                        <div class="clear"></div>
                    </form>
                </div>

                <div class="linksapps" style="display: none;">
                    <a target="new" href="https://itunes.apple.com/us/app/servilisto-servicios-cerca/id1106090063?mt=8">
                        <img class="apple_link" alt="App Servilisto Appstore"
                             src="<?= $ruta_tema . "/images/app-store-badge_es.png" ?>"/>
                    </a>
                    <a target="new"
                       href="https://play.google.com/store/apps/details?id=com.servilisto.android.play&hl=es">
                        <img class="android_link" alt="App Servilisto Google Play"
                             src="<?= $ruta_tema . "/images/play-store-badge_es.png" ?>"/>
                    </a>
                </div>

            </div>

            <div id="carousel_banner">

                <div id="video_home">
                    <video autoplay="autoplay" loop="loop" id="video_background" preload="none" muted>
                        <source src="<?= $ruta_tema . "/videos/background.mp4" ?>" type="video/mp4"/>
                        <img alt="Fondo Principal" src="<?= $ruta_tema . "/images/fondo_principal.png" ?>"/>
                    </video>
                    <div class="tp-dottedoverlay threexthree"></div>
                </div>
            </div>
        </div>

        <div id="caracteristicas_home">

            <div class="contenedor row">

                <h3>Todos los servicios en una sola web</h3>

                <div class="caracteristica col-md-4 col-sm-6">
                    <i class="fa fa-search"></i>

                    <h4>Búsqueda fácil en tan sólo 2 pasos</h4>
                </div>

                <div class="caracteristica col-md-4 col-sm-6">
                    <i class="fa fa-desktop"></i>

                    <h4>Búsqueda totalmente gratis</h4>
                </div>

                <div class="caracteristica col-md-4 col-sm-6">
                    <i class="fa fa-book"></i>

                    <h4>Reservas online de servicios</h4>
                </div>

                <div class="caracteristica col-md-4 col-sm-6">
                    <i class="fa fa-area-chart"></i>

                    <h4>Descuentos y servicios gratuitos</h4>
                </div>

                <div class="caracteristica col-md-4 col-sm-6">
                    <i class="fa fa-heart"></i>

                    <h4>Guarda tus favoritos</h4>
                </div>

                <div class="caracteristica col-md-4 col-sm-6">
                    <i class="fa fa-star"></i>

                    <h4>Opiniones reales de otros usuarios</h4>
                </div>
                <div class="clear"></div>
            </div>
        </div>

        <div id="categorias_home">
            <div class="contenedor">
                <div class="cabecera">
                    <p class="title">Todos los servicios que te puedas imaginar</p>
                    <p class="subtitle">Categorías</p>
                    <span class="line_small_blue"></span>
                </div>

                <div class="categorias">
                    <div class="fila row">
                        <div class="categoria col-md-4 col-sm-6">
                            <a target='new' href="<?= site_url("clases-particulares/") ?>">
                                <img class="hover" alt="Categoría Profesores"
                                     src="<?= $ruta_tema . "/images/categories_home/profesores.jpg" ?>"/>
                                <h2>Profesores</h2>
                            </a>
                        </div>
                        <span class="separation-sm visible-xs">&nbsp;</span>

                        <div class="categoria col-md-4 col-sm-6">
                            <a target='new' href="<?= site_url("canguro/") ?>">
                                <img class="hover" alt="Categoría Canguro"
                                     src="<?= $ruta_tema . "/images/categories_home/canguro.jpg" ?>"/>
                                <h2>Canguro</h2>
                            </a>
                        </div>

                        <span class="separation-sm visible-xs">&nbsp;</span>
                        <span class="separation-sm visible-sm">&nbsp;</span>

                        <div class="categoria col-md-4 col-sm-12 col-sm-mimargin-top">
                            <a target='new' href="<?= site_url("bienestar-vida-sana/") ?>">
                                <img class="hover" alt="Categoría Bienestar y Salud"
                                     src="<?= $ruta_tema . "/images/categories_home/bienestarysalud.jpg" ?>"/>
                                <h2>Bienestar y Salud</h2>
                            </a>
                        </div>
                    </div>
                    <div class="fila row">
                        <div class="categoria col-md-8 col-sm-6 hidden-sm hidden-xs">
                            <a target='new' href="<?= site_url("reformas/") ?>">
                                <img class="hover" alt="Categoría Reparaciones"
                                     src="<?= $ruta_tema . "/images/categories_home/reparaciones.jpg" ?>"/>
                                <h2>Reparaciones</h2>
                            </a>
                        </div>
                        <div class="categoria col-md-8 col-sm-6 visible-sm visible-xs">
                            <a target='new' href="<?= site_url("reformas/") ?>">
                                <img class="hover" alt="Categoría Reparaciones"
                                     src="<?= $ruta_tema . "/images/categories_home/reparaciones_cuadrado.jpg" ?>"/>
                                <h2>Reparaciones</h2>
                            </a>
                        </div>
                        <span class="separation-sm visible-xs">&nbsp;</span>

                        <div class="categoria col-md-4 col-sm-6">
                            <a target='new' href="<?= site_url("servicio-domestico/") ?>">
                                <img class="hover" alt="Categoría Servicio doméstico"
                                     src="<?= $ruta_tema . "/images/categories_home/servicio_domestico.jpg" ?>"/>
                                <h2>Servicio doméstico</h2>
                            </a>
                        </div>
                    </div>

                    <div class="fila row">
                        <div class="categoria col-md-4 col-sm-6">
                            <a target='new' href="<?= site_url("cuidado-mascotas/") ?>">
                                <img class="hover" alt="Categoría Mascotas"
                                     src="<?= $ruta_tema . "/images/categories_home/mascotas.jpg" ?>"/>
                                <h2>Mascotas</h2>
                            </a>
                        </div>
                        <span class="separation-sm visible-xs">&nbsp;</span>

                        <div class="categoria col-md-8 col-sm-6 hidden-sm hidden-xs">
                            <a target='new' href="<?= site_url("servicios-mudanzas/") ?>">
                                <img class="hover" alt="Categoría Mudanzas"
                                     src="<?= $ruta_tema . "/images/categories_home/mudanzas.jpg" ?>"/>
                                <h2>Mudanzas</h2>
                            </a>
                        </div>
                        <div class="categoria col-md-8 col-sm-6 visible-sm visible-xs">
                            <a target='new' href="<?= site_url("servicios-mudanzas/") ?>">
                                <img class="hover" alt="Categoría Mudanzas"
                                     src="<?= $ruta_tema . "/images/categories_home/mudanzas_cuadrado.jpg" ?>"/>
                                <h2>Mudanzas</h2>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div id="opiniones_home">

            <div class="contenedor">
                <div class="cabecera">
                    <p class="title">Usuarios felices</p>
                    <p class="subtitle">Opinan sobre Servilisto</p>
                    <span class="line_small_blue"></span>
                </div>

                <div class="opiniones">
                    <div class="fila row">
                        <div class="opinion col-sm-6 row">
                            <div class="imagen_opinion col-md-3 col-sm-12">
                                <img alt="Opinion Profesor"
                                     src="<?= $ruta_tema . "/images/opiniones_home/opinion-1.png" ?>"/>
                            </div>
                            <div class="texto_opinion col-md-9 col-sm-12">
                                <p class="profesion">Usuario</p>

                                <p class="nombre">Luis Báez Sotomayor</p>
                                <span class="line_small_blue"></span>

                                <p class="descripcion">
                                    Estoy encantado con esta web. Puedo buscar todo lo que necesito en un solo sitio.
                                    Encuentro cerca de mi casa, es fácil de usar y lo mejor es que es totalmente gratis.
                                </p>
                            </div>
                        </div>
                        <div class="opinion col-sm-6 row">
                            <div class="imagen_opinion col-md-3 col-sm-12">
                                <img alt="Opinion Interiorista"
                                     src="<?= $ruta_tema . "/images/opiniones_home/opinion-2.png" ?>"/>
                            </div>
                            <div class="texto_opinion col-md-9 col-sm-12">
                                <p class="profesion">Familia</p>

                                <p class="nombre">María Fernandez Garcia</p>
                                <span class="line_small_blue"></span>

                                <p class="descripcion">
                                    Nos ha sido muy sencillo encontrar una chica para cuidar a los niños, lo mejor es
                                    que es vecina nuestra y gracias a la web contactamos con ella.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="fila row">
                        <div class="opinion col-sm-6 row">
                            <div class="imagen_opinion col-md-3 col-sm-12">
                                <img alt="Opinion Fotógrafo"
                                     src="<?= $ruta_tema . "/images/opiniones_home/opinion-3.png" ?>"/>
                            </div>
                            <div class="texto_opinion col-md-9 col-sm-12">
                                <p class="profesion">Profesor</p>

                                <p class="nombre">Jose Luis Paredes Mínguez</p>
                                <span class="line_small_blue"></span>

                                <p class="descripcion">
                                    Soy licenciado en magisterio, esta web me ha ayudado mucho a encontrar mis primeros
                                    alumnos.
                                </p>
                            </div>
                        </div>
                        <div class="opinion col-sm-6 row">
                            <div class="imagen_opinion col-md-3 col-sm-12">
                                <img alt="Opinion Estilista"
                                     src="<?= $ruta_tema . "/images/opiniones_home/opinion-4.png" ?>"/>
                            </div>
                            <div class="texto_opinion col-md-9 col-sm-12">
                                <p class="profesion">Servicio del hogar</p>

                                <p class="nombre">Rosana González Fernández</p>
                                <span class="line_small_blue"></span>

                                <p class="descripcion">
                                    Trabajo en el servicio del hogar 30 años y siempre me quedaban mañanas
                                    y horas muertas entre una casa y otra y gracias a este sitio indico las horas que me
                                    quedan libres y gano más.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="cerdito_home">
            <h3>Consigue descuentos y servicios gratis</h3>
            <img alt="Descuentos" src="<?= $ruta_tema . "/images/cerdito.jpg" ?>"/>
        </div>

        <div id="app_home">
            <div class="contenedor row">
                <div class="imagen col-md-5 col-sm-12">
                    <img alt="Descarga la App de Servilisto" src="<?= $ruta_tema . "/images/app.png" ?>"/>
                </div>
                <div class="col-md-7 col-sm-12">
                    <h3>Descárgate la App Gratis</h3>

                    <p class="title">Busca y ofrece servicios cómodamente desde tu móvil o tablet</p>
                    <span class="line_small_blue"></span>

                    <div class="linksapps">
                        <a target="new"
                           href="https://itunes.apple.com/es/app/id1235106074?mt=8">
                            <img class="apple_link" alt="App Servilisto AppStore"
                                 src="<?= $ruta_tema . "/images/app-store-badge_es.png" ?>"/>
                        </a>
                        <a target="new"
                           href="https://play.google.com/store/apps/details?id=com.servilisto.android.play&hl=es">
                            <img class="android_link" alt="App Servilisto Google Play"
                                 src="<?= $ruta_tema . "/images/play-store-badge_es.png" ?>"/>
                        </a>
                    </div>

                </div>


            </div>
        </div>
    </div>

<?php

get_footer();

