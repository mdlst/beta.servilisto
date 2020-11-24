<?php
/*
Plugin Name: Points Setting
Plugin URI: http://glowsol.com/
Description:  For manage the user's points  
Version: 1.0
Author: Glowsol 
Author URI: http://glowsol.com
License: GPL
*/
if ( is_admin())
{
	
	/*creating tables in data base */
	
	register_activation_hook( __FILE__, 'create_points_tables' );

	function create_points_tables()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . 'tbl_point_settings';
		$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL ,
		review_points varchar(255) DEFAULT NULL,
		news_ltr_points varchar(255) DEFAULT NULL,
		recommendation_points varchar(255) DEFAULT NULL,
		points_worth varchar(255) DEFAULT NULL,
		percentage_earning varchar(255) DEFAULT NULL,
		UNIQUE KEY id (id)
		);";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		
		$table_name2 = $wpdb->prefix . 'tbl_points_log';
		$sql2 = "CREATE TABLE $table_name2 (
		id int(11) NOT NULL AUTO_INCREMENT,
		user_id int(255) DEFAULT NULL,
		post_id int(255) DEFAULT NULL,
		points_added varchar(255) DEFAULT NULL,
		points_redeem varchar(255) DEFAULT NULL,
		type varchar(255) DEFAULT NULL,
		curr_date varchar(255) DEFAULT NULL,
		UNIQUE KEY id (id)
		);";
		dbDelta( $sql2 );
	}
	
	
	/* Call the html code */
	add_action('admin_menu', 'points_settings');
	function points_settings()
		{
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
			add_menu_page( 'Points Setting ', 'Points Setting', 'administrator', 'points_setting', 'setting_function','', 79 );
			
			add_submenu_page( 'points_setting', 'Add/Cancel Points', 'Add/Cancel Points', 'administrator', 'add-cancel-user-points', 'custom_add_cancel_user_points', '', 79 );
			
			add_submenu_page( 'points_setting', 'Reservas de usuarios', 'Reservas de usuarios', 'administrator', 'user-reservation-admin-details', 'user_calander_reservations_admin_details', '', 79 );
			
		}	


function custom_add_cancel_user_points(){
	
	include("add-cancel-user-points.php");
	
}
	
function user_calander_reservations_admin_details(){
	
	include("user-reservation-admin-details.php");
}	
		
		
		
/*	add_action ( 'admin_enqueue_scripts', 'enqueue_admin_settings' );
	function enqueue_admin_settings() {
	//wp_enqueue_script('jquery');	
	wp_enqueue_script('jquery', plugins_url('jquery.min.js', __FILE__), array());
	wp_enqueue_script('scripts2', plugins_url('admin.js', __FILE__), array());
	wp_enqueue_style('style', plugins_url('style.css', __FILE__), array(),'','all');
	}*/
			
}


	
function setting_function()
{
	if(isset($_POST['action']) &&  $_POST["action"] ="settings_form")
	{
		$review_points = $_POST['review_points'];
		$news_ltr_points = $_POST['news_ltr_points'];
		$recommendation_points = $_POST['recommendation_points'];
		$points_worth = $_POST['points_worth'];
		$percentage_earning = $_POST['percentage_earning'];
		$id = '1';
		
		
		
		global $wpdb;
		$table = $wpdb->prefix . 'tbl_point_settings';
		
		$results = $wpdb->get_results( 'SELECT * FROM '.$table.'');
	
  	  if( filter_var($_POST['percentage_earning'], FILTER_VALIDATE_FLOAT)!== false){
		
		if(!empty ($results))
		{
			$data  = array(review_points=>$review_points,news_ltr_points=>$news_ltr_points, recommendation_points=>$recommendation_points, points_worth=>$points_worth, percentage_earning=>$percentage_earning);
		$res =$wpdb->update($table,$data, array('id'=>$id));
			 if($res)
			 { echo "<br><h4 class='success_msg'>"."Successfully Updated</h4>";}
			 else
			 {echo "<br><h4 class='error_msg'>"."Sorry ! Plz make a change before updating</h4>";}
			
		}
		else
		{
			$data  = array(id=>$id,review_points=>$review_points,news_ltr_points=>$news_ltr_points, recommendation_points=>$recommendation_points, points_worth=>$points_worth, percentage_earning=>$percentage_earning);
		
		$res = $wpdb->insert( $table, $data);
			 if($res)
			 { echo "<br><h4 class='success_msg'>"."Successfully Saved !</h4>";}else
			 {echo "<br><h4 class='error_msg'>"."Sorry ! Try again</h4>";}
			
		}
		
	  }
	  else
	  {
		echo "<br><h4 class='error_msg'>"."Plz enter valid value for Percentage Earning</h4>";  
		  
	  }
	}
	global $wpdb;
	$table_pt = $wpdb->prefix . 'tbl_point_settings';
	$results = $wpdb->get_results( 'SELECT * FROM '.$table_pt.'');
	
	$review_points = $results[0]->review_points;
	$news_ltr_points = $results[0]->news_ltr_points;
	$recommendation_points = $results[0]->recommendation_points;
	$points_worth = $results[0]->points_worth;
	$percentage_earning = $results[0]->percentage_earning;
	
	
?>
<form method="post" name="point_set"  id="point_set">
	<table>
    	<tr>
        <td align="center" colspan="2"><h1>Points Settings </h1></td>
        </tr>
    	<tr>
        <td>Review Points:</td>
        <td><input  type="text" name="review_points" id="review_points" value="<?php echo $review_points;?>" /></td>
        </tr>
                
        <tr>
        <td>News Letter Subscription Points:</td>
        <td><input  type="text" name="news_ltr_points" id="news_ltr_points" value="<?php echo $news_ltr_points ;?>"/></td>
        </tr>
        
        <tr>
        <td>Recommendation Points:</td>
        <td><input  type="text" name="recommendation_points" id="recommendation_points" value="<?php echo $recommendation_points ;?>"/></td>
        </tr>
        
        <tr>
        <td>Points Worth:</td>
        <td><input  type="text" name="points_worth" id="points_worth" value="<?php echo $points_worth ;?>"/></td>
        </tr>
        
         <tr>
        <td>Percentage Value for Earning:</td>
        <td><input  type="text" name="percentage_earning" id="percentage_earning" value="<?php echo $percentage_earning ;?>"/></td>
        </tr>
        
        <tr>
        <td colspan="2" align="center">
       		 <input type="hidden" name="action" value="settings_form" />
        	<input type="submit" id="submit" name="submit"  value="Submit">
        </td>
        </tr>
    </table>
    
 
</form>

<style>
h4.error_msg {
    color: red;
    font-size: 20px;
}
h4.success_msg {
    color: green;
    font-size: 20px;
}
</style>	
		
<?php } ?>