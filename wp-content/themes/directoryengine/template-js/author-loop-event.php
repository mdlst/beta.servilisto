<script type="text/template" id="ae-event-loop">

   <div class="wrap-place-publishing col-md-3">
        <div class="block-place-publishing">
            <a href="{{= permalink}}" title="{{= post_title}}" class="place-publishing-img">
                <img alt="{{= post_title}}" src="{{= the_post_thumnail}}">
            </a>
            <h2 class="place-publishing-title">
                <a href="{{= permalink}}" title="{{= post_title}}">{{= post_title}}</a>
            </h2>
            <span class="place-publishing-map"><i class="fa fa-map-marker"></i><span class="distance"></span> {{= post_data.et_full_location}}</span>
            <div class="rate-it" data-score="{{= post_data.rating_score}}" data-id="{{= post_data.ID}}"></div>
        </div>
    </div>
    <div class="wrap-content-event col-md-9">
        <?php if(ae_user_can( 'edit_others_posts' )) { ?>
            <div class="config event-config dropdown">
                <i class="fa fa-cog dropdown-toggle" data-toggle="dropdown"></i>
                <ol class="dropdown-menu menu-edit-event" role="menu" aria-labelledby="menu1">
                    <li><a href="#" class="action edit" data-action="edit"><i class="fa fa-pencil"></i><?php _e('Edit this event',ET_DOMAIN);?></a></li>
                    <li><a href="#" class="action archive 4 " data-action="archive"><i class="fa fa-trash-o"></i><?php _e('Put to archive',ET_DOMAIN);?></a></li>
                </ol>
            </div>
        <?php } ?>
        <h4><a href="{{= permalink}}" title="{{= post_title}}">{{= post_title}}</a> <span class="ribbon-event-discount">{{= ribbon}}</span></h4>
        <div class="desc">{{= post_content_trim}}</div>
        <div class="note-event">
           {{= event_time}}
        </div>
    </div>

</script>