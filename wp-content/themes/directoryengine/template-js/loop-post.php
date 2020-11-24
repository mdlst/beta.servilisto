<script type="text/template" id="ae-post-loop">
    <div class="media">
        <# if(the_post_thumnail !== '' ) { #>
        <a class="pull-left img-blog featured-img" href="{{= permalink }}">
            <img src="{{= the_post_thumnail }}" alt="{{= post_title }}" />
        </a>
        <# } #>
        <div class="media-body">
            <a href="{{= permalink }}" title="{{= post_title }}" >
                <h4 class="media-heading title-blog">{{= post_title }}</h4>
            </a>
            <span class="time-calendar"><i class="fa fa-calendar"></i>{{= post_date }}</span>

            <div class="clearfix"></div>
            <div class="content-event">{{= post_excerpt }}</div>
            <a title="" href="{{= permalink }}" class="see-more"><?php _e("See more", ET_DOMAIN); ?></a>
        </div>
    </div>
</script>