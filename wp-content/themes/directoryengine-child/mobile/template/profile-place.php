<div class="tab-pane fade active body-tabs in" id="user_place">
    <div class="container-fluid choose-place-action">
        <div class="row">
            <div class="col-xs-12">
                <?php
                global $user;
                $post_total_publish = count(get_posts(array('author' => $user->ID, 'post_status' => 'publish', 'post_type' => 'place', 'posts_per_page' => -1)));
                $post_total_pending = count(get_posts(array('author' => $user->ID, 'post_status' => 'pending', 'post_type' => 'place', 'posts_per_page' => -1)));
                $post_total_reject = count(get_posts(array('author' => $user->ID, 'post_status' => 'reject', 'post_type' => 'place', 'posts_per_page' => -1)));
                $post_total_archive = count(get_posts(array('author' => $user->ID, 'post_status' => 'archive', 'post_type' => 'place', 'posts_per_page' => -1)));
                $post_total_draft = count(get_posts(array('author' => $user->ID, 'post_status' => 'draft', 'post_type' => 'place', 'posts_per_page' => -1)));
                ?>
                <select name="post_status" id="post_status">
                    <option value="publish"
                            data-type="Publicados">
                        <?php printf(__('Publicados (%s)', ET_DOMAIN), $post_total_publish); ?>
                    </option>
                    <option value="pending"
                            data-type="Pendientes">
                        <?php printf(__('Pendientes (%s)', ET_DOMAIN), $post_total_pending); ?>
                    </option>
                    <option value="archive"
                            data-type="Atrasados">
                        <?php printf(__('Atrasados (%s)', ET_DOMAIN), $post_total_archive); ?>
                    </option>
                    <option value="reject"
                            data-type="Rechazados">
                        <?php printf(__('Rechazados (%s)', ET_DOMAIN), $post_total_reject); ?>
                    </option>
                    <option value="draft"
                            data-type="Papelera">
                        <?php printf(__('Papelera (%s)', ET_DOMAIN), $post_total_draft); ?>
                    </option>
                </select>
            </div>
            <div class="col-xs-12">
                <div class="box-search">
                    <input type="text"
                           value=""
                           class="search"
                           id="place_search"
                           name="place_search"
                           placeholder="Busca por palabra">
                </div>
            </div>
        </div>
    </div>
    <div class="container" id="place-list-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="place-action active" id="publishing">
                    <ul class="list-places fullwidth" id="place-list">
                        <?php
                        global $wp_query, $post, $ae_post_factory, $user_ID, $user;
                        /**
                         * generate nearby center
                         */
                        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
                        $args = array(
                            'post_type' => 'place',
                            'orderby' => 'date',
                            'order' => 'DESC',
                            'post_status' => 'publish',
                            'posts_per_page' => $paged,
                            'showposts' => get_option('posts_per_page'),
                            'author' => $user->ID
                        );

                        $place_obj = $ae_post_factory->get('place');
                        // $search_query    =   $place_obj->nearbyPost($args);
                        $search_query = new WP_Query($args);
                        $data_arr = array();
                        if ($search_query->have_posts()) {
                            while ($search_query->have_posts()) {
                                $search_query->the_post();

                                $place_obj = $ae_post_factory->get('place');
                                // covert post
                                $convert = $place_obj->convert($post, 'thumbnail');
                                $data_arr[] = $convert;
                                get_template_part('mobile/template/loop', 'place');

                            }
                        } else {
                            ?>
                            <div class="event-active-wrapper">
                                <div class="col-md-12">
                                    <div class="event-wrapper tab-style-event">
                                        <h2 class="title-envent">No has publicado aún anuncios</h2>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </ul>
                    <?php
                    echo '<script type="json/data" class="postdata" > ' . json_encode($data_arr) . '</script>';
                    echo '<div class="paginations-wrapper">';
                    ae_pagination($search_query, 1, 'load_more');
                    echo '</div>';
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>