<script type="text/template" id="ae-place-nearby-loop">

    <div class="place-wrapper nearby">
        <div class="hidden-img">
		<!-- button event for admin control  -->
		<?php if(ae_user_can( 'edit_others_posts' )) { ?>
		<ol class="edit-place-option">            
			<li style="display:inline-block"><a href="#" class="action" data-action="edit"><i class="fa fa-pencil"></i></a></li>
			<# if(post_status === 'pending'){ #>
			<li style="display:inline-block"><a href="#" class="action" data-action="reject"><i class="fa fa-times"></i></a></li>
			<li style="display:inline-block"><a href="#" class="action" data-action="approve"><i class="fa fa-check"></i></a></li>
			<# } #>
			<# if( post_status === 'publish') { #>
			<li style="display:inline-block"><a href="#" class="action" data-action="archive"><i class="fa fa-trash-o"></i></a></li>                
			<# } #>
		</ol>
		<?php } ?>
		
        <# if(the_post_thumnail) { #>
        <a href="{{= permalink }}" class="img-place" title="{{= post_title }}">
            <img src="{{= the_post_thumnail }}">
            <# if(ribbon != '' ) {  #>
            <div class="cat-{{= place_category[0] }}" >
                <div class="ribbon">
                    <span class="ribbon-content" title="{{= ribbon }}" >{{= ribbon }}</span>
                </div>
            </div>
            <# } #>
        </a>
        <# } #>
        </div>
        <div class="place-detail-wrapper">
            <h2 class="title-place aa2"><a href="{{= permalink }}">{{= post_title }}</a></h2>
            <span class="address-place"><i class="fa fa-map-marker"></i>
                <span itemprop="latitude" id="latitude" content="{{= et_location_lat}}"></span> 
                <span itemprop="longitude" id="longitude" content="{{= et_location_lng }}"></span> 
                <span class="distance"></span> 
                {{= et_full_location }}
            </span>
            <div class="rate-it" data-score="{{= rating_score }}"></div>
        </div>
    </div>

</script>