<script type="text/template" id="ae-togo-loop">

    <div class="wrap-place-publishing">
        <a href="{{= comment_link}}" title="{{= post_title}}" class="place-publishing-img">
            <img src="{{= post_data.the_post_thumbnail}}" alt="{{= post_title}}"/>
        </a>
        <h2 class="place-publishing-title"><a href="{{= comment_link}}" title="{{= post_title}}">{{= post_title}}</a></h2>
        <span class="place-publishing-map"><i class="fa fa-map-marker"></i><span class="distance"></span> {{= post_data.et_full_location}}</span>
        <div class="rate-it" data-score="{{= post_data.rating_score}}" data-id="{{= ID}}"></div>
    </div>

</script>