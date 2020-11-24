<?php
global $post;
if($post->post_type == 'place') {
    get_template_part('template/comment' , 'place');
}else {
    get_template_part('template/comment' , 'blog');
}