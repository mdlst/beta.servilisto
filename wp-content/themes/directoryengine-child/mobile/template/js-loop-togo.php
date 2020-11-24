<script type="text/template" id="ae-togo-loop">

    <div class="place-wrapper">
        <a href="" class="img-place" title="{{= post_title }}">
            <img src="{{=  post_data.the_post_thumnail }}" />
            <# if( post_data.ribbon != '' ) {  #>
            <div class="cat-{{=  post_data.place_category[0] }}" >
                <div class="ribbon">
                    <span class="ribbon-content" title="{{=  post_data.ribbon }}" >{{=  post_data.ribbon }}</span>
                </div>
            </div>
            <# } #>
            
        </a>
        <div class="place-detail-wrapper">
            <h2 class="title-place"><a href="" title="{{= post_title }}" >{{= post_title }}</a></h2>
            <span class="address-place"><i class="fa fa-map-marker"></i><span class="distance"></span> {{= post_data.et_full_location }}</span>
            <div class="rate-it" data-score="{{=  post_data.rating_score }}"></div>
            <?php  if(ae_user_can('edit_others_posts') && false ) { ?> 
                <div class="triagle-setting mobile-setting"><i class="fa fa-cog"></i></div>
            <?php } ?>
            <# if(post_data.post_status == 'archive'){ #>
                <span class="warning-overdue"><i class="fa fa-warning"></i>0 day</span>
            <# } #>
        </div>
        <div class="place-config">
            <i class="fa fa-cog edit-config"></i>
        </div>
        <div class="edit-place-post">
            <div class="place-config-post">
                <i class="fa fa-cog edit-config-post"></i>
            </div>
            <i class="fa fa-history place-extend"></i>
            <i class="fa fa-pencil place-edit"></i>
            <i class="fa fa-trash-o place-remove"></i>
        </div>
    </div>
    
</script>