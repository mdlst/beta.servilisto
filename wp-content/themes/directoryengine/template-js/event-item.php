<?php global $post; ?>
<script type="text/template" id="de-event-item">
	<span class="img-event"><img src="{{= large_thumbnail }}" ></span>
    <h3 class="title-envent">
        {{= post_title }}
        <span class="ribbon-event"><span class="ribbon-event-content">{{= ribbon }}</span></span>
        <ol class="edit-event-option">
            <li style="display:inline-block" class="status">
                <a href="#" class="{{= post_status }}" >
                    {{= status_text }}
                </a>
            </li>
            <?php if(isset($post->post_status) && $post->post_status === 'pending' && ae_user_can( 'edit_others_posts' ) ) { ?>
            <li style="display:inline-block"><a href="#" class="action approve" data-action="approve"><i class="fa fa-check"></i></a></li>                 
            <?php }
            ?>
            <li style="display:inline-block"><a href="#" class="action edit" data-action="edit"><i class="fa fa-pencil"></i></a></li>
           
            <li style="display:inline-block"><a href="#" class="action archive" data-action="archive"><i class="fa fa-trash-o"></i></a></li>                
        </ol>
    </h3>
    <div class="content-event">{{= post_content }}</div>
	<time>
    <?php 
        _e("Time remains:", ET_DOMAIN); echo '&nbsp;&nbsp;';         
    ?>
    {{= event_time }}
    </time>
    <div class="line-event"></div>
</script>