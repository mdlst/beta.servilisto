<script type="text/template" id="ae-review-loop">

    <div class="wrap-place-publishing">
        <a href="{{= comment_link }}" class="place-publishing-img">
            <img src="{{= post_data.the_post_thumnail}}" title="{{= post_data.post_title }}" />
        </a>
        <h2 class="place-publishing-title"><a href="{{= comment_link }}" title="{{= post_data.post_title }}">{{= post_data.post_title }}</a></h2>
        <span class="place-publishing-map"><i class="fa fa-map-marker"></i><span class="distance"></span> {{= post_data.et_full_location}}</span>
        <div class="reviews">
            <p class="username">
                <# if(typeof author_data !== 'undefined'){ #>
                    <a href="{{= author_data.author_url }}" title="{{= comment_author }}" class="name-author">{{= author_data.display_name }}</a>
                <# }else { #>
                    <a href="#" title="{{= comment_author }}" class="name-author">{{= comment_author }}</a>
                <# } #>
            </p>
            <p class="text"><img src="<?php echo get_template_directory_uri();?>/img/quote.png">{{= comment_content }}</p>
            <span class="time pull-left"><i class="fa fa-clock-o"></i>{{= time_ago }}</span>
            <div class="rate-it pull-right" data-score="{{= post_data.rating_score}}"></div>
        </div>
    </div>

</script>