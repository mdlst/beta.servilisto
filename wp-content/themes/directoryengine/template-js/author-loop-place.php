<script type="text/template" id="ae-place-loop">
    <# if(post_status == 'reject'){ #>
        <div class="col-md-12">
        <div class="wrap-place-publishing reject col-md-3 col-sm-3 col-xs-3">
            <?php if(ae_user_can( 'edit_posts' )) { ?>
            <ol class="box-edit-place">
                <# if( post_status === 'publish' || post_status === 'pending' || post_status === 'reject' ) { #>
                <li><a href="#edit_place" class="action edit" data-target="#" data-action="edit"><i class="fa fa-pencil"></i></a></li>
                <li><a href="#" class="action archive 5" data-action="archive"><i class="fa fa-trash-o"></i></a></li>
                <# } #>
            </ol>
            <?php } ?>
            <a href="{{= permalink }}" title="{{= post_title }}" class="place-publishing-img">
                <img src="{{= the_post_thumnail }}" alt="{{= post_title }}" />
            </a>
        </div>
        <div class="wrap-place-reason col-md-9 col-sm-9 col-xs-9">
            <h2 class="place-publishing-title"><a href="{{= permalink }}" title="{{= post_title }}">{{= post_title }}</a></h2>
            <span class="place-publishing-map"><i class="fa fa-map-marker"></i>
                <span class="distance"></span>
                <span class="location">{{= et_full_location }}</span>
            </span>
            <div class="rate-it" data-score="{{= rating_score }}" data-id="{{= ID }}"></div>
            <h4><?php _e('Note:',ET_DOMAIN);?></h4>
            {{= reject_message}}
        </div>
    <# }else{ #>
        <div class="col-lg-3 col-md-4 col-sm-4">
            <div class="wrap-place-publishing">
                <?php if(ae_user_can( 'edit_posts' )) { ?>
                <ol class="box-edit-place <# if( post_status === 'archive' ) { #> overdue <# }#>">
                    <# if( post_status === 'archive' ) { #>
                        <li><a href="{{= renew_place}}" class="edit" data-action="edit"><i class="fa fa-history"></i></a></li>
                        <li><a href="#edit_place" class="action edit" data-target="#" data-action="edit"><i class="fa fa-pencil"></i></a></li>
                        <li><a href="#" class="action delete" data-action="delete"><i class="fa fa-times" style="color:red"></i></a></li>
                    <# } else if( post_status === 'publish' || post_status === 'pending' || post_status === 'reject' || post_status === 'draft' ) { #>
                        <li><a href="#edit_place" class="action edit" data-target="#" data-action="edit"><i class="fa fa-pencil"></i></a></li>

                            <li><a href="#" class="action delete" data-action="delete"><i class="fa fa-times" style="color:red"></i></a></li>

                    <# } #>
                </ol>
                <?php } ?>
                <a href="{{= permalink }}" title="{{= post_title }}" class="place-publishing-img">
                    <img src="{{= the_post_thumnail}}" alt="{{= post_title }}"/>
                </a>
                <# if(et_featured == 1){ #>
                    <span class="tag-featured"><i class="fa fa-flag"></i><?php _e('Featured',ET_DOMAIN)?></span>
                <# } #>

                <# if(post_status == 'publish'){ #>
                    <# if(time_to_expired != ''){ #>
                        <span class="tag-remaining"><i class="fa fa-clock-o"></i> {{= time_to_expired}}</span>
                    <# } #>
                <# }else if(post_status == 'archive'){ #>
                    <span class="tag-remaining" style="color:#fb5643;"><i class="fa fa-exclamation-triangle"></i> <?php _e('0 day',ET_DOMAIN);?></span>
                <# } #>

                <h2 class="place-publishing-title">
                    <a href="{{= permalink }}" title="{{= post_title }}">{{= post_title }}</a>
                </h2>
                <span class="place-publishing-map"><i class="fa fa-map-marker"></i>
                    <span class="distance"></span>
                    <span class="location">{{= et_full_location }}</span>
                </span>
                <div class="rate-it" data-score="{{= rating_score }}" data-id="{{= ID }}"></div>
            </div>
        </div>
    <# } #>

</script>