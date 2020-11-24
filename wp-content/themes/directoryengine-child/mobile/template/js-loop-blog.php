<script type="text/template" id="ae-post-loop">
    <# if(the_post_thumnail) { #>
    	<span class="img-news">
            <img class="attachment-thumbnail wp-post-image" src="{{= the_post_thumnail }}" />
        </span>
    <# } #>

    <h2 class="title-news">
    	<a href="{{= permalink }}">{{= post_title }}</a>
        <span class="time"><i class="fa fa-calendar"></i>{{= post_date }}</span>
    </h2>
    <div class="clearfix"></div>
    <div class="content-news">
    {{= post_excerpt }}
    </div>
</script>