<script type="text/template" id="de-review-item">
    <?php if(is_page_template('page-profile.php')){ ?>

        <div class="place-wrapper">
            <a href="{{= comment_link }}" title="{{= post_data.post_title }}" class="img-place">
                <img src="{{= post_data.the_post_thumnail }}" alt="{{= post_data.post_title }}"/>
                <?php if(isset($post_data->ribbon) && $post_data->ribbon){ ?>
                <# if(post_data.ribbon) { #>
                <div class="cat-{{= post_data.place_category}}">
                    <div class="ribbon">
                        <span class="ribbon-content">{{= post_data.ribbon}}</span>
                    </div>
                </div>
                <# } #>
                <?php } ?>
            </a>
            <div class="place-detail-wrapper">
                <h2 class="title-place"><a href="{{= comment_link }}" title="{{= post_data.post_title }}" >{{= post_data.post_title }}</a></h2>
                <span class="address-place"><i class="fa fa-map-marker"></i><span class="distance"></span> {{= post_data.et_full_location }}</span>
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
        <div class="content-review">
            <h5>{{= comment_author }}</h5>
            <p><img src="<?php echo get_template_directory_uri() ?>/img/quote.png" alt="quote"> {{= comment_content }}</p>
            <div class="row">
                <div class="col-sm-7 col-xs-7 no-padding-right"><i class="fa fa-clock-o"></i>{{= date_ago }}</div>
                <div class="col-sm-1 col-xs-1 no-padding"><i class="fa fa-comment"></i>{{= post_data.reviews_count}}</div>
                <div class="col-sm-4 col-xs-4 no-padding-left"><div class="rate-it" data-score="{{= et_rate }}"></div></div>
            </div>
        </div>

    <?php }else{ ?>

        <div class="place-review">
            <div class="place-review-top-wrapper">
                <div class="place-review-top">
                    <h2>
                        <a href="{{= comment_link }} ">{{= post_data.post_title }}</a>
                    </h2>
                    <span class="address-place">
                        <i class="fa fa-map-marker"></i><span class="distance"></span> {{= post_data.et_full_location }}
                    </span>
                    <span class="number-comment"><i class="fa fa-comment"></i>{{= post_data.reviews_count }}</span>
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
    <?php } ?>
</script>
