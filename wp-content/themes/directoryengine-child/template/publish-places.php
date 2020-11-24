<?php
global $wp_query;
?>

<div class="col-md-9 publish_place_wrapper" id="publish_place_wrapper">


    <div class="clearfix"></div>

    <div class="row">

        <?php if (have_posts()) { ?>

            <ul class="list-places list-posts" id="publish-places" data-list="publish"
                data-thumb="medium_post_thumbnail">

                <?php

                $post_arr = array();


                while (have_posts()) {


                    the_post();

                    global $post, $ae_post_factory;

                    $ae_post = $ae_post_factory->get('place');

                    $convert = $ae_post->convert($post, 'medium_post_thumbnail');

                    $post_arr[] = $convert;

                    get_template_part('template/loop', 'place');

                }


                echo '<script type="json/data" class="postdata" id="ae-publish-posts"> ' . json_encode($post_arr) . '</script>';


                ?>


            </ul>

            <div class="paginations-wrapper main-pagination">

                <?php

                ae_pagination($wp_query);

                wp_reset_postdata();

                ?>

            </div>

            <?php

        } else {

            get_template_part('template/place', 'notfound');

        }

        ?>

    </div>

</div>