<script type="text/template" id="de-review-item">
    
        <div class="place-review">
            <div class="place-review-top-wrapper">
                <div class="place-review-top">
                    <h2>
                        <a href="{{= comment_link }} ">{{= post_data.post_title }}</a>
                    </h2>
                    <span class="address-place">
                        <i class="fa fa-map-marker"></i><span class="distance"></span>{{= post_data.et_full_location }}
                    </span>
                    <span class="number-comment"><i class="fa fa-comment"></i>&nbsp; {{= post_data.review_count }}</span>
                </div>
            </div>
            <div class="place-image-wrapper">
                <img src="{{= post_data.the_post_thumnail }}" alt="{{= post_data.post_title }}" title="{{= post_data.post_title }}">
            </div>
            <div class="place-review-bottom-wrapper">
                <div class="place-review-bottom">
                <# if(typeof author_data !== 'undefined') { #>
                    <a href="{{= author_data.author_url }}" title="{{= author_data.display_name }}" class="name-author">{{= author_data.display_name }}</a>
                <# }else{ #> 
                    <a href="#" title="{{= comment_author }}" class="name-author">{{= comment_author }}</a>
                <#} #>
                    <span class="quote">
                        <img src="<?php echo get_template_directory_uri() ?>/img/quote.png" alt="quote">
                        {{= comment_content }}
                    </span>
                    <div class="time">
                        <span style="display:inline-block;">
                            <i class="fa fa-clock-o"></i> {{= date_ago }}
                        </span>
                        <!-- rating -->
                        <div class="rate-it" style="display: inline-block; margin-left: 5px;" data-score="{{= et_rate }}" ></div>
                    </div>
                </div>
            </div>
        </div>
</script>