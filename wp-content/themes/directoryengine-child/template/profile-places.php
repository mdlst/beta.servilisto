<?php

global $user;

$post_total_publish = count(get_posts(array('author' => $user->ID, 'post_status' => 'publish', 'post_type' => 'place', 'posts_per_page' => -1)));
$post_total_pending = count(get_posts(array('author' => $user->ID, 'post_status' => 'pending', 'post_type' => 'place', 'posts_per_page' => -1)));
$post_total_reject = count(get_posts(array('author' => $user->ID, 'post_status' => 'reject', 'post_type' => 'place', 'posts_per_page' => -1)));
$post_total_archive = count(get_posts(array('author' => $user->ID, 'post_status' => 'archive', 'post_type' => 'place', 'posts_per_page' => -1)));
$post_total_draft = count(get_posts(array('author' => $user->ID, 'post_status' => 'draft', 'post_type' => 'place', 'posts_per_page' => -1)));

$total = $post_total_publish + $post_total_pending + $post_total_reject + $post_total_archive + $post_total_draft;

$inact = "in active";

?>
<div class="content-place tab-pane fade<?php echo $inact; ?>" id="tab-place">

    <?php

    if ($total == 0) {

        ?>
        <ul class="list-place-publishing">
            <li class="col-md-12">
                <div class="event-active-wrapper">
                    <div class="col-md-12">
                        <div class="event-wrapper tab-style-event">
                            <h2 class="title-envent no-title-envent ">Actualmente, no tiene ningún anuncio aún.</h2>
                            <div class="publica-tu-primer-anuncio-profile">
                                <p>¿Porqué no publicas tu <a href="<?= site_url("/publica-tu-anuncio/")  ?>">primer
                                        anuncio</a> GRATIS?</p>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <?php
    } else {  // tiene algun anuncio en algun estado

        if ($post_total_pending > 0 || $post_total_archive > 0 || $post_total_reject > 0 || $post_total_draft > 0) {
            ?>
            <div class="con_anuncios_no_publicados">
                <p>* Tienes anuncios que aún no están publicados</p>
            </div>
            <?php
        }
        ?>
        <!--content place-->
        <ul class="nav nav-tabs list-place-tabs">
            <li class="select-place">
                <select class="chosen-single" name="post_status" id="post_status">
                    <option value="any" data-type="Todos">Todos (<?= $total ?>)</option>
                    <option value="publish" data-type="Publicados">Publicados (<?= $post_total_publish ?>)</option>
                    <option value="pending" data-type="Pendientes">Pendientes (<?= $post_total_pending ?>)</option>
                    <option value="archive" data-type="Caducados">Archivados (<?= $post_total_archive ?>)</option>
                    <option value="reject" data-type="Rechazados">Rechazados (<?= $post_total_reject ?>)</option>
                    <option value="draft" data-type="Borradores">Borradores (<?= $post_total_draft ?>)</option>
                </select>
            </li>
            <li class="place-search">
                <div class="box-search">
                    <input type="text" class="search" value="" id="place_search" name="place_search"
                           placeholder="Buscar entre tus anuncios">
                    <span class="btn-search-place"><i class="fa fa-search"></i></span>
                </div>
            </li>
        </ul>

        <div class="content-place-tabs tab-content">
            <div id="place-publishing" class="author-place-block tab-pane fade in active">
                <ul class="list-place-publishing" data-id="publishing" id="tab-place-publishing">
                    <?php
                    /**
                     * Loop Status Publish
                     */
                    global $post, $ae_post_factory;
                    $place_obj = $ae_post_factory->get('place');
                    $paged = (get_query_var('page')) ? get_query_var('page') : 1;

                    $query_args = array(
                        'orderby' => 'meta_value',
                        'order' => 'DESC',
                        'post_status' => array('publish', 'pending', 'reject', 'archive', 'draft'),   // mostramos todos
                        'posts_per_page' => $paged,
                        'showposts' => get_option('posts_per_page'),
                        'author' => $user->ID,
                        'meta_key' => 'et_featured'
                    );

                    $query = $place_obj->query($query_args);

                    if ($query->have_posts()) {
                        global $post, $ae_post_factory;
                        $post_arr = array();
                        while ($query->have_posts()) {
                            $query->the_post();
                            $ae_post = $ae_post_factory->get('place');
                            $convert = $ae_post->convert($post);
                            $post_arr[] = $convert;
                            get_template_part('template/profile', 'loop-place');
                        }
                        echo '<script type="json/data" class="postdata" > ' . json_encode($post_arr) . '</script>';
                    }

                    ?>
                </ul>
                <?php
                echo "<div class='paginations-wrapper'>";
                ae_pagination($query, 1);
                echo "</div>";
                wp_reset_query();
                ?>
            </div>
        </div>
    <?php } ?>
    <!--/content place-->
</div>