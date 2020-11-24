<?php  
		require_once("../../../wp-load.php");
		get_header();
		global $wpdb;
        if (!empty($_REQUEST)) {
            $product_no = $_REQUEST['item_number']; // Product ID
            $product_transaction = $_REQUEST['tx']; // Paypal transaction ID
            $product_price = $_REQUEST['amt']; // Paypal received amount value
            $product_currency = $_REQUEST['cc']; // Paypal received currency type
            $product_status = $_REQUEST['st']; // Paypal product status  
			$product_cm = $_REQUEST['cm'];
        }
        ?>
				<!-- Page Blog -->
				<section id="blog-page">
					<div class="container">
						<div class="row">
							<!-- Column left -->
							<div class="col-md-9 col-xs-12">
								<div class="blog-wrapper">
									<!-- post title -->
									<div class="section-detail-wrapper padding-top-bottom-20">
										<h1 class="media-heading title-blog">Recuuring payment information</h1>
										   <?php
											if ($_REQUEST['st'] == 'Completed') {
												$user_info = get_userdata(get_current_user_id());
												//$wpdb->query("insert into ".$wpdb->prefix."autopayment (txnid, user_id, payment_amount, payment_status,createdtime) values ('".$product_transaction."', '".$product_cm."','".$product_price."','".$product_status."','".date("Y-m-d H:i:s")."')");
												echo "<h3>Your subscrabtion successfully has been added</h3>";
												$subject = "The service was contrated";
												$message = "The service was contrated in ".$product_price;
												wp_mail($user_info->user_email, $subject, $message);
												
											} else {
													echo "<h3 id='fail'>Payment Failed</h3>";
													echo "<P>Transaction Status - Unompleted</P>";
													echo "<div class='back_btn'><a  href='index.php' id= 'btn'><< Back</a></div>";
											}
											?>
										<div class="clearfix"></div>
									</div>
				 
								</div>
							</div>

						</div>
					</div>
				</section>
    </div>
<?php get_footer(); ?>
