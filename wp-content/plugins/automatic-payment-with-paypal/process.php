<?php
require_once("../../../wp-load.php");
if (isset($_POST['submit'])) {
	
	
	
	$qry = "SELECT * FROM wp_autopayment WHERE id = '8'";
	$results = $wpdb->get_results($qry,ARRAY_A);
	
	$daysWeek = implode(',',unserialize($results[0]['daysofweek']));
	
	
	$fTime = $_POST['sinceto'];
	$tTime = $_POST['untilltime'];
	$tHour = $tTime-$fTime;
	$totDays = count($_POST['daysofweek']);
	//implode(','$_POST['daysofweek']);
	$daysofweek = serialize($_POST['daysofweek']);
	$tHour = $tHour*$totDays;
	$pid = $_POST['pid'];
	
	if($tHour>=30){
		$price=get_post_meta($pid,'hourly_rate4',true);
	}elseif($tHour>=15){
		$price=get_post_meta($pid,'hourly_rate3',true);
	}else if($tHour>=6){
		$price=get_post_meta($pid,'hourly_rate2',true);
	}else{
		$price=get_post_meta($pid,'hourly_rate1',true);
	}
	$tHour = $tHour*4;
	$cycle_amount = $tHour*$price;
	
	$points = $cycle_amount*3;
	
    $total_cycle = 24;
    $product_name = 'Monthly Booking';
    $product_currency = 'EUR';
   /* if ($_POST['select_plan'] == 'Daily') {
        $cycle_amount = 5;
        $cycle = 'D';
    } else if ($_POST['select_plan'] == 'Weekly') {
        $cycle_amount = 30;
        $cycle = 'W';
    } else if ($_POST['select_plan'] == 'Monthly') {*/
    //$cycle_amount = 150;
    $cycle = 'M';
    /*} else if ($_POST['select_plan'] == 'Yearly') {
        $cycle_amount = 1400;
        $cycle = 'Y';
    }*/
    //Here we can use paypal url or sanbox url.
    $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    //Here we can used seller email id. 
    $merchant_email = 'business_dev.hashmatkhattak@gmail.com';
    //here we can put cancle url when payment is not completed.
    $cancel_return = site_url();
    //here we can put cancle url when payment is Successful.
	$current_user = wp_get_current_user();
	
	$q = sprintf(
         'INSERT INTO wp_autopayment SET 
			user_id = "%s",
			payment_status ="%s",
			itemid = "%s",
			payment_amount = "%s", 
			perHourPrice = "%s",
			points="%s",
			daysofweek="%s",
			f_time="%s",
			to_time = "%s"
		 ',
		 $current_user->ID,
		 'pending',
		 $pid,
		 $cycle_amount,
		 $price,
		 $points,
		 mysql_real_escape_string($daysofweek),
		 $fTime,
		 $tTime
         
     );
	//echo $q;exit;
	$wpdb->query($q);
	$paymentId = $wpdb->insert_id;
	//echo $paymentId;exit;
	
	
	$success_return = APWP_Path."/success.php";
	$notify_url = APWP_Path."/ipn.php";
	
    ?>
    <div style="margin-left: 38%"><img src="<?php echo APWP_Path.'/assets/images/ajax-loader.gif' ?>"/><img src="<?php echo APWP_Path.'/assets/images/ajax-loader.gif' ?>"/></div>
    <form name = "myform" action = "<?php echo $paypal_url; ?>" method = "post" target = "_top">
        <input type="hidden" name="cmd" value="_xclick-subscriptions">
        <input type = "hidden" name = "business" value = "<?php echo $merchant_email; ?>" />
        <input type="hidden" name="lc" value="IN" />
        <input type = "hidden" name = "item_name" value = "<?php echo $product_name; ?>" />
        <input type="hidden" name="no_note" value="1" />
        <input type="hidden" name="src" value="1" />
        <?php if (!empty($total_cycle)) { ?>
            <input type="hidden" name="srt" value="<?php echo $total_cycle; ?>" />
        <?php } ?>
        <input type="hidden" name="a3" value="<?php echo $cycle_amount; ?>" />
        <input type="hidden" name="p3" value="1" />
        <input type="hidden" name="custom" value="<?php echo $paymentId;?>" />
        
        <input type="hidden" name="t3" value="<?php echo $cycle; ?>" />
        <input type="hidden" name="currency_code" value="<?php echo $product_currency; ?>" />
        
        <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>">
        <input type = "hidden" name = "cancel_return" value = "<?php echo $cancel_return ?>">
        <input type = "hidden" name = "return" value = "<?php echo $success_return; ?>">
		<input type="hidden" value="<?php echo get_current_user_id(); ?>" name="custom" readonly="readonly" />
        <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
    </form>
    <script type="text/javascript">
        document.myform.submit();
    </script>
<?php }
?>