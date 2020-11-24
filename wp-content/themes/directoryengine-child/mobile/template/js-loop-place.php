<script type="text/template" id="ae-place-loop">

    <div class="place-wrapper">
        <a href="{{= permalink }}" class="img-place" title="{{= post_title }}">
            <img src="{{= the_post_thumnail }}" alt="{{= post_title }}"/>
            <# if(ribbon != '' ) {  #>
            <div class="cat-{{= place_category[0] }}" >
                <div class="ribbon">
                    <span class="ribbon-content" title="{{= ribbon }}" >{{= ribbon }}</span>
                </div>
            </div>
            <# } #>
            <# if(et_featured == 1) { #>
                <span class="tag-featured"><i class="fa fa-flag"></i></span>
            <# } #>  
        </a>
        <div class="place-detail-wrapper">
            <h2 class="title-place"><a href="{{= permalink }}" title="{{= post_title }}" >{{= post_title }}</a></h2>
            <span class="address-place"><i class="fa fa-map-marker"></i><span class="distance"></span> {{= et_full_location }}</span>
            <div class="rate-it" data-score="{{= rating_score }}"></div>
            <?php if( ae_get_option("enable_view_counter",false) ){ ?>
                    <div class="view-count"><i class="fa fa-eye"></i> {{= view_count}}</div>
            <?php } ?>
            <?php  if(ae_user_can('edit_others_posts') && false ) { ?> 
                <div class="triagle-setting mobile-setting"><i class="fa fa-cog"></i></div>
            <?php } ?>
            <# if(post_status == 'archive'){ #>
                <span class="warning-overdue"><i class="fa fa-warning"></i>0 day</span>
            <# } #>
        </div>
    </div>
    <# if(post_status == 'reject'){ #>
    <div class="content-rejected">
        {{= reject_message}}
    </div>
    <# } #>
    <?php if(ae_user_can('edit_others_posts') && false) { ?> 
        <ul class="list-option-place">
            <li><a href="#"><i class="fa fa-check"></i></a></li>
            <li><a href="#"><i class="fa fa-times"></i></a></li>
            <li><a href="#"><i class="fa fa-trash-o"></i></a></li>
        </ul>
    <?php } ?>
</script>