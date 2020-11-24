<?php 
 /*
Plugin Name: automatic payment with paypal
Description: This is automatic payment with paypal
Author: Glow Solutions
*/
// automatic payment with paypal // APWP  
define( 'APWP_Path', plugins_url( '', __FILE__ ) );
define( 'APWP_CSS', APWP_Path . '/assets/css/' );
define( 'APWP_IMAGES', APWP_Path . '/assets/images/' );
define( 'APWP_JS', APWP_Path . '/assets/js/' ); 
require_once('functions.php');
register_activation_hook( __FILE__, 'Bookiing_System_Activate' );
function Bookiing_System_Activate() {
   
   
    set_time_limit(0);
	ini_set('max_execution_time', 0);
	global $wpdb;
	
	$wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix."autopayment");
	
	$tbl2 = 'CREATE TABLE IF NOT EXISTS '.$wpdb->prefix.'autopayment (
	 `id` int(11) NOT NULL AUTO_INCREMENT,
	  `user_id` int(11) NOT NULL,
	  `txnid` varchar(20) NOT NULL,
	  `payment_amount` decimal(7,2) NOT NULL,
	  `payment_status` varchar(25) NOT NULL,
	  `itemid` varchar(25) NOT NULL,
	  `createdtime` datetime NOT NULL,
	   PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1';
	$wpdb->query($tbl2);
	
}