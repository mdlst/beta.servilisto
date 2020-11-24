<?php
require_once("../../../wp-load.php");
/**
 *  PHP-PayPal-IPN Example
 *
 *  This shows a basic example of how to use the IpnListener() PHP class to 
 *  implement a PayPal Instant Payment Notification (IPN) listener script.
 *
 *  For a more in depth tutorial, see my blog post:
 *  http://www.micahcarrick.com/paypal-ipn-with-php.html
 *
 *  This code is available at github:
 *  https://github.com/Quixotix/PHP-PayPal-IPN
 *
 *  @package    PHP-PayPal-IPN
 *  @author     Micah Carrick
 *  @copyright  (c) 2011 - Micah Carrick
 *  @license    http://opensource.org/licenses/gpl-3.0.html
 */
 
 
/*
Since this script is executed on the back end between the PayPal server and this
script, you will want to log errors to a file or email. Do not try to use echo
or print--it will not work! 

Here I am turning on PHP error logging to a file called "ipn_errors.log". Make
sure your web server has permissions to write to that file. In a production 
environment it is better to have that log file outside of the web root.
*/
ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');


// instantiate the IpnListener class
include('ipn/ipnlistener.php');
$listener = new IpnListener();


/*
When you are testing your IPN script you should be using a PayPal "Sandbox"
account: https://developer.paypal.com
When you are ready to go live change use_sandbox to false.
*/
$listener->use_sandbox = true;

/*
By default the IpnListener object is going  going to post the data back to PayPal
using cURL over a secure SSL connection. This is the recommended way to post
the data back, however, some people may have connections problems using this
method. 

To post over standard HTTP connection, use:*/
$listener->use_ssl = false;

//To post using the fsockopen() function rather than cURL, use:
//$listener->use_curl = false;


/*
The processIpn() method will encode the POST variables sent by PayPal and then
POST them back to the PayPal server. An exception will be thrown if there is 
a fatal error (cannot connect, your server is not configured properly, etc.).
Use a try/catch block to catch these fatal errors and log to the ipn_errors.log
file we setup at the top of this file.

The processIpn() method will send the raw data on 'php://input' to PayPal. You
can optionally pass the data to processIpn() yourself:
$verified = $listener->processIpn($my_post_data);
*/
try {
    $listener->requirePostMethod();
    $verified = $listener->processIpn();
} catch (Exception $e) {
    error_log($e->getMessage());
    exit(0);
}


/*
The processIpn() method returned true if the IPN was "VERIFIED" and false if it
was "INVALID".
*/
if ($verified) {
    /*
    Once you have a verified IPN you need to do a few more checks on the POST
    fields--typically against data you stored in your database during when the
    end user made a purchase (such as in the "success" page on a web payments
    standard button). The fields PayPal recommends checking are:
    
        1. Check the $_POST['payment_status'] is "Completed"
	    2. Check that $_POST['txn_id'] has not been previously processed 
	    3. Check that $_POST['receiver_email'] is your Primary PayPal email 
	    4. Check that $_POST['payment_amount'] and $_POST['payment_currency'] 
	       are correct
    
    Since implementations on this varies, I will leave these checks out of this
    example and just send an email using the getTextReport() method to get all
    of the details about the IPN.  
    */
	$paymentId = $_REQUEST['custom'];
	
	$q = "UPDATE wp_autopayment SET 
			payment_status = '".$_REQUEST['payment_status']."',
			txnid = '".$_REQUEST['txn_id']."',
			payer_id = '".$_REQUEST['payer_id']."',
			payment_gross='".$_REQUEST['payment_gross']."',
			payer_status='".$_REQUEST['payer_status']."',
			
			
			payment_fee = '".$_REQUEST['payment_fee']."',
			mc_currency = '".$_REQUEST['mc_currency']."',
			payment_date = '".$_REQUEST['payment_date']."'
	 WHERE id = '".$paymentId."'";
	$wpdb->query($q);
	
	
	$qry = "SELECT * FROM wp_autopayment WHERE id = '".$paymentId."'";
	$results = $wpdb->get_results($qry,ARRAY_A);
	
	$daysWeek = implode(',',unserialize($results[0]['daysofweek']));
	
	$itemid = $results[0]['itemid'];
	$post_info = get_post( $itemid );
	
	
	$user_info = get_user_by('id', $results[0]['user_id']);
	$subject = "You payment successful - Servilisto!";
	$message = "Your payment for booking services are made successfull. Please found the detail below: ";
	
	$msg = '<br /><br />
				<h3>Detalles</h3>
			<table>
				<tbody>
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Service Name:</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">'.$post_info->post_title.'</em></td>
					</tr>
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Dias a la semana</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">'.$daysWeek.'</em></td>
					</tr>
					
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Desde las</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">'.$results[0]['f_time'].'</em></td>
					</tr>
					
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Hasta las</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">'.$results[0]['to_time'].'</em></td>
					</tr>
					
					
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Payment Gross</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">'.$_REQUEST['mc_currency'].' '.$_REQUEST['payment_gross'].'</em></td>
					</tr>
					
					
					<tr>
						<td style="vertical-align: top; width: 150px;"><strong style="color: #898989;">Payment Status</strong></td>
						<td style="vertical-align: top;"><span style="color: #666666;">'.$_REQUEST['payer_status'].'</em></td>
					</tr>
				</tbody>
			</table>';
	
	
	
	wp_mail($user_info->user_email, $subject, $message.$msg);
	
	
	//Place Owner email
	$owner_info = get_user_by('id', $post_info->ID);
	$subject = "Congratulation! You Recieved a monthly contract - Servilisto";
	$message2 = "We Recived the payment of your contract and fallowing is given the detail of contract. ".$product_price;
	wp_mail($owner_info->user_email, $subject, $message2.$msg);
	
    //mail('zahidmalik82@glowsol.com', 'Verified IPN', $listener->getTextReport());

} else {
    /*
    An Invalid IPN *may* be caused by a fraudulent transaction attempt. It's
    a good idea to have a developer or sys admin manually investigate any 
    invalid IPN.
    */
    mail('zahidmalik82@glowsol.com', 'Invalid IPN', $listener->getTextReport());
}

?>
