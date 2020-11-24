<?php
add_action( 'init', 'dne_init_picture' );
function dne_init_picture(){
	global $ae_post_factory;
    $ae_post_factory->set('attachment', new AE_Posts('attachment'));
}
/**
 * class DE_PictureAction control all picture action
 * @author ThanhTu
 * @package DirectoryEngine
*/
class DE_PicturesAction extends AE_Base{
    /**
     * construct DE_PictureAction
    */
	function __construct(){

		$this->post_type = 'attachment';

        $this->add_filter( 'ae_convert_attachment', 'convert_attachment' );

        /**
         * ajax fetch posts
         */
        $this->add_ajax('ae-fetch-pictures', 'fetch_posts');
	}


    /**
     * catch filter ae_convert_attachment to convert attachment data
     * @param array $result
     * @return mixed
     */
    function convert_attachment($result) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id( $result->ID ), 'medium_post_thumbnail' );
		$result->src = $src[0];
        $full = wp_get_attachment_image_src( get_post_thumbnail_id( $result->ID ), 'full' );
        $result->full = $full[0];
		return $result;
	}

	/**
     * ajax callback fetch post
     * @author Dakachi
     * @version 1.1
     */
    function fetch_posts() {
        
        global $ae_post_factory;
        $attachment = $ae_post_factory->get($this->post_type);
        $page = 1;
        if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] != '' ){
            $page = $_REQUEST['page'];
        }
        extract($_REQUEST);        
        
        /** @var Array $query */
        $query_args = array(
            'paged' => $page,
            'showposts' => $query['showposts']
        );

        $query_args = wp_parse_args($query_args, $query);
        /**
         * fetch data
         */
        $data = $attachment->fetch($query_args);

        // get the pagination html string
        ob_start();
        ae_pagination($data['query'], $page, $_REQUEST['paginate']);
        $paginate = ob_get_clean();
        
        /**
         * send data to client
         */
        if (!empty($data)) {
            wp_send_json(array(
                'data' => $data['posts'],
                'paginate' => $paginate,
                'msg' => __("Successs", ET_DOMAIN) ,
                'success' => true,
                'max_num_pages' => $data['max_num_pages'],
                // 'status' => $status,
                'total' => $data['query']->found_posts
            ));
        } else {
            wp_send_json(array(
                'success' => false
            ));
        }
    }

    
}