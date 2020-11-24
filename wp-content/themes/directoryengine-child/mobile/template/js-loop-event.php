<script type="text/template" id="ae-event-loop">
	
	<div class="place-wrapper">
		<a href="{{= post_data.permalink}}" titla="{{= post_data.post_title}}" class="img-place">
			<img src="{{= the_post_thumbnail}}" alt="{{= post_data.post_title}}"/>
			<# if(post_data.ribbon){ #>
			<div class="cat-{{= post_data.place_category[0]}}">
			    <div class="ribbon">
			        <span class="ribbon-content">{{= post_data.ribbon}}</span>
			    </div>
			</div>
			<# } #>
		</a>
		<div class="place-detail-wrapper">
			<h2 class="title-place"><a href="{{= post_data.permalink}}" title="{{= post_data.post_title}}" >{{= post_data.post_title}}</a></h2>
			<span class="address-place"><i class="fa fa-map-marker"></i><span class="distance"></span> {{= post_data.et_full_location}}</span>
			<div class="rate-it" data-score="{{= post_data.rating_score}}"></div>
			<?php  if(ae_user_can('edit_others_posts') && false ) { ?> 
			    <div class="triagle-setting mobile-setting"><i class="fa fa-cog"></i></div>
			<?php } ?>
		</div>
		<div class="place-config">
			<i class="fa fa-cog edit-config"></i>
		</div>
		<div class="edit-place-post">
			<i class="fa fa-history place-extend"></i>
			<i class="fa fa-pencil place-edit"></i>
			<i class="fa fa-trash-o place-remove"></i>
		</div>
	</div>
	<div class="content-event">
		<h5>{{= post_title}}</h5>
		<p>{{= post_content_trim}}</p>
		<span class="note-event">{{= event_time}}</span>
	</div>
	<?php if(ae_user_can('edit_others_posts') && false ) { ?> 
		<ul class="list-option-place">
			<li><a href="#"><i class="fa fa-check"></i></a></li>
			<li><a href="#"><i class="fa fa-times"></i></a></li>
			<li><a href="#"><i class="fa fa-trash-o"></i></a></li>
		</ul>
	<?php } ?>

</script>