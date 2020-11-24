<?php
global $wp_query;
$curpage = isset($wp_query->query_vars['taxonomy']) ? $wp_query->query_vars['taxonomy'] : '';

if ($curpage=="location"){  // es pagina location. /provincia/granada.
    $_REQUEST['l']=$term;  //Para cargar en el filtro la provincia
}
?>

<ol class="list-option-filter <?= $curpage; ?> col-md-12">

    <div class="col-md-8 col-sm-12 col-xs-12">

        <li class="col-md-5 col-sm-6 col-xs-12">
            <div class="select-category-with-clear-button">
                <i title="Limpiar resultados" class="fa fa-times hover" aria-hidden="true"></i>
                <?php

                ae_tax_dropdown('place_category',
                    array(
                        'hide_empty' => true,
                        'hierarchical' => true,
                        'show_option_all' => 'Todas las categorías',
                        'taxonomy' => 'place_category',
                        'id' => 'place-filter-select-categorias',
                        'class' => 'chosen-single_category2 tax-item con-click-event',
                        'value' => 'slug',
                        'selected' => (isset($_REQUEST['c']) && $_REQUEST['c']) ? $_REQUEST['c'] : ''
                    )
                );

                ?>
            </div>
        </li>

        <li class="col-md-5 col-sm-6 col-xs-12">


            <?php ae_tax_dropdown('location',
                array(
                    'hide_empty' => true,
                    'id' => 'place-filter-select-provincias',
                    'hierarchical' => true,
                    'show_option_all' => 'Todas las ciudades',
                    'taxonomy' => 'location',
                    'class' => 'chosen-single tax-item',
                    'value' => 'slug',
                    'selected' => (isset($_REQUEST['l']) && $_REQUEST['l']) ? $_REQUEST['l'] : ''
                )
            ); ?>

        </li>

    </div>
    <?php /*
        <li>
            <select class="showposts" name="showposts">
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
        </li>
		*/ ?>
    <!--// select how many post you want to see perpage -->

    <div class="col-md-4 col-sm-12 col-xs-12 oculto">
        <li class="sort-rates-lastest">

            <div class="col-md-12">
                <span class="ordenar_por">Ordenar por:</span>
            </div>

            <div class="col-md-12">
                <a title="Ordenar por puntuación" href="" class="sort-icon orderby hover" data-order=""
                   data-sort="rating_score"/>
                Puntuación
                </a> /

                <a title="Ver primero los últimos" href="" class="sort-icon orderby active hover" data-order=""
                   data-sort="date">
                    Últimos
                </a> /

                <a title="Ordenar por precio" href="" class="sort-icon orderby hover" data-order=""
                   data-sort="hourly_rate1">
                    Precio
                </a>

                <i title="Cambiar el orden" class="fa fa-sort-numeric-desc hover" aria-hidden="true"></i>

                <span title="Vista de grilla" class="icon-view grid-style active"><i class="fa fa-th"></i></span>

                <span title="Vista de lista" class="icon-view fullwidth-style"><i class="fa fa-th-list"></i></span>

            </div>
        </li>


    </div>

</ol>

<div class="clearfix"></div>