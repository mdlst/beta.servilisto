<?php
global $post, $ae_post_factory, $current_user, $user_ID;
$place_obj = $ae_post_factory->get('place');
$place = $place_obj->current_post;

/**
 * get favorite comment and check added or not
 */
$favorite = get_comments(array(
    'post_id' => $post->ID,
    'type' => 'favorite',
    'author_email' => $current_user->user_email,
    'number' => 1
));
$report = get_comments(array(
    'post_id' => $post->ID,
    'type' => 'report',
    'author_email' => $current_user->user_email,
    'number' => 1
));
?>
<div class="list-option-left-wrapper">
    <!-- user action -->
    <ul class="list-option-left pinned-custom">
        <!-- favorite -->
        <?php if (empty($favorite)) { ?>
            <li title="Añadir a favoritos">
                <a class="<?= ($user_ID) ? "favorite" : "authenticate" ?>"
                   href="#" class="" data-id="<?php echo $post->ID; ?>">
                    <i class="fa fa-heart"></i>
                </a>
            </li>
        <?php } else { ?>
            <li title="Eliminar de la lista de favoritos">
                <a class="loved" href="#" data-id="<?php echo $post->ID; ?>"
                   data-favorite-id="<?= $favorite[0]->comment_ID; ?>">
                    <i class="fa fa-heart"></i>
                </a>
            </li>
        <?php }

        $api = get_option('et_addthis_api', '');
        $api = 'ra-525f557a07fee94d';
        if ($api)
            $api = '#pubid=' . $api;

        ?>
        <!--// favorite -->
        <!-- social share -->
        <li class="share-social">
            <a href="#"><i class="fa fa-share-square-o"></i></a>
            <ul class="list-share-social addthis_toolbox">
                <li>
                    <a id="addthis_button_facebook sharing-btn"
                       class="addthis_button_facebook at300b sharing-btn btn-fb"
                       onclick="window.open(this.href, '', 'resizable=no,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;"
                       href="http://api.addthis.com/oexchange/0.8/forward/facebook/offer?url=<?php echo $place->permalink; ?>&title=<?php echo $place->post_title; ?>"
                       rel="nofollow"
                       title="Compartir en Facebook">
                        <i class="fa fa-facebook"></i>
                    </a>
                </li>
                <li>
                    <a id="addthis_button_twitter  sharing-btn" class="addthis_button_twitter at300b sharing-btn btn-tw"
                       onclick="window.open(this.href, '', 'resizable=no,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;"
                       href="http://api.addthis.com/oexchange/0.8/forward/twitter/offer?url=<?php echo $place->permalink; ?>&title=<?php echo $place->post_title; ?>"
                       rel="nofollow"
                       title="Compartir en Twitter">
                        <i class="fa fa-twitter"></i>
                    </a>
                </li>
                <li>
                    <a id="addthis_button_google_plusone_share  sharing-btn"
                       class="addthis_button_google_plusone_share at300b sharing-btn btn-gg"
                       onclick="window.open(this.href, '', 'resizable=no,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;"
                       href="http://api.addthis.com/oexchange/0.8/forward/googleplus/offer?url=<?php echo $place->permalink; ?>&title=<?php echo $place->post_title; ?>"
                       rel="nofollow"
                       title="Compartir en Google+">
                        <i class="fa fa-google-plus"></i>
                    </a>
                </li>
            </ul>

            <script type="text/javascript">
                var addthis_config = {
                    "data_track_addressbar": false,
                };
            </script>
            <script type="text/javascript"
                    src="//s7.addthis.com/js/300/addthis_widget.js#async=1<?php echo $api; ?>"></script>

        </li>
        <!--// social share -->

        <li title="Deja un comentario">
            <a class="write-review" href="#review" class=""><i class="fa fa-pencil"></i></a>
        </li>
        <?php if (empty($report)) { ?>
            <li title="Reportar un problema/error">
                <a href="#" class="<?= ($user_ID) ? "report" : "authenticate" ?>"
                   id="report_<?= $post->ID; ?>" data-user="<?= $current_user->ID ?>"
                   data-id="<?= $post->ID; ?>">
                    <i class="fa fa-flag"></i>
                </a>
            </li><!-- Report -->
        <?php } ?>
        <li title="Contactar con el anunciante">
            <a data-user="<?php echo $place->post_author; ?>" href="#"
               class="<?= (is_user_logged_in()) ? 'contact-owner' : 'authenticate' ?>">
                <i class="fa fa-envelope"></i></a>
        </li>
    </ul>
    <!--// user action -->
</div>
