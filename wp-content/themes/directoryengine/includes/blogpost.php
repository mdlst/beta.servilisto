<?php
// class AE_BlogAction extends AE_Base{
// 	function __construct(){
// 		$this->add_ajax('ae-fetch-blogs', 'fetch_blog');
// 	}
// 	function fetch_blog(){
// 		global $ae_post_factory;
//         $post = $ae_post_factory->get('post');

//         $page = $_REQUEST['page'];
//         extract($_REQUEST);
        
//         $thumb = isset($_REQUEST['thumbnail']) ? $_REQUEST['thumbnail'] : 'thumbnail';
        
//         $query_args = array(
//             'paged' => $page,
//             'thumbnail' => $thumb,
//             'post_status' => 'publish',
//             'showposts' => $query['showposts']
//         );
        
//         if (isset($query['category_name']) && $query['category_name']) $query_args['category_name'] = $query['category_name'];

// 		/**
//          * fetch data
//          */
//         $data = $post->fetch($query_args);
        
//         ob_start();
//         ae_pagination($data['query'], $page, $_REQUEST['paginate']);
//         $paginate = ob_get_clean();
        
//         /**
//          * send data to client
//          */
//         if (!empty($data)) {
//             wp_send_json(array(
//                 'data' => $data['posts'],
//                 'paginate' => $paginate,
//                 'msg' => __("Successs", ET_DOMAIN) ,
//                 'success' => true,
//                 'max_num_pages' => $data['max_num_pages']
//             ));
//         } else {
//             wp_send_json(array(
//                 'success' => false
//             ));
//         }
// 	}
// }

// new AE_BlogAction();