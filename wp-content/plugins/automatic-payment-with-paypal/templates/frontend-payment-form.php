<div>
<?php 
    global $wpdb;
	$serve_day = count(get_post_meta(get_the_ID(),'serve_day',true));
	
	/*echo "<pre>";
	print_r(get_post_meta(get_the_ID(),'serve_time',true));exit;*/
	
	$hourly_rate1 = get_post_meta(get_the_ID(),'hourly_rate1',true);
	$hourly_rate6 = get_post_meta(get_the_ID(),'hourly_rate2',true);
	$hourly_rate15 = get_post_meta(get_the_ID(),'hourly_rate3',true);
	$hourly_rate30 = get_post_meta(get_the_ID(),'hourly_rate4',true);
	
	//echo $hourly_rate1."<br/>".$hourly_rate6."<br/>".$hourly_rate15."<br/>".$hourly_rate30;exit;
	
	$open_time_from1 = get_post_meta(get_the_ID(),'open_time',true);
	$open_time_to1 = get_post_meta(get_the_ID(),'close_time',true);
	$open_time_from2 = get_post_meta(get_the_ID(),'open_time_2',true);
	$open_time_to2 = get_post_meta(get_the_ID(),'close_time_2',true);
	
	$timediff1 =  (strtotime($open_time_to1)-strtotime($open_time_from1))/(60*60);
	$timediff2 =  (strtotime($open_time_to2)-strtotime($open_time_from2))/(60*60);
    
	//$per_month = ($timediff1 + $timediff2)*count(get_post_meta(get_the_ID(),'serve_day',true))*4;
	$per_hour_rate = $per_month * $hourly_rate1;
	$user_info = get_userdata(get_current_user_id());
	$serve_time = get_post_meta(get_the_ID(),'serve_time',true);
	
 ?>
        <div id="main-subscription">
            <div id="container">
                <div class="cost-sys">
                
                  <div class="contractacion-">
                  <center> <h3> Contratacion Fija </h3></center>
                   <div class="contract-form">
                    <form action="<?php echo APWP_Path.'/process.php' ?>" method="post" name="">
					<input type="hidden" name="monthly_charges" id="monthly_charges" value="<?php echo $per_hour_rate; ?>" />
                    <input type="hidden" name="pid" value="<?php echo get_the_ID(); ?>" />
					<input type="hidden" name="hourly_rate1" id="hourly_rate1" value="<?php echo $hourly_rate1?>" />
                    <input type="hidden" name="hourly_rate6" id="hourly_rate6" value="<?php echo $hourly_rate6?>" />
                    <input type="hidden" name="hourly_rate15" id="hourly_rate15" value="<?php echo $hourly_rate15?>" />
                    <input type="hidden" name="hourly_rate30" id="hourly_rate30" value="<?php echo $hourly_rate30?>" />
                        <div class="section">
                           <div class="section-item left">
                              <label for=""> Dias a la semana   </label>
							   <select name="daysofweek[]"  class="daysofweek" id="daysofweek" data-placeholder="Select days" style="width: 350px; display: none;" multiple="" tabindex="-1">
									<?php 
									$opening_time = $closing_time = "";
									foreach($serve_time as $key=>$serve_val) {
										if(!empty($serve_val['open_time']) or !empty($serve_val['open_time_2'])) {
											/*
										?>
                                        <script>
										<?php if(!empty($serve_val['open_time'])){ ?>
                                        timeOpen['<?php echo $key; ?>'] = '<?php echo $serve_val['open_time']; ?>';
										<?php 
										}
										if(!empty($serve_val['open_time_2'])){ ?>
                                        timeOpen2['<?php echo $key; ?>'] = '<?php echo $serve_val['open_time_2']; ?>';
										<?php 
										}
										if(!empty($serve_val['close_time'])){ ?>
                                        timeClose['<?php echo $key; ?>'] = '<?php echo $serve_val['close_time']; ?>';
										<?php 
										}
										if(!empty($serve_val['close_time_2'])){ ?>
                                        timeClose2['<?php echo $key; ?>'] = '<?php echo $serve_val['close_time_2']; ?>';
										<?php 
										}
										?>
										</script>
                                        <?php
											
										$opening_time = (isset($serve_val['open_time']) and !empty($serve_val['open_time'])) ? $serve_val['open_time'] : '';
										$opening_time .= (isset($serve_val['open_time_2']) and !empty($serve_val['open_time_2'])) ? '-'.$serve_val['open_time_2'] : '';
										$closing_time = (isset($serve_val['close_time']) and !empty($serve_val['close_time'])) ? $serve_val['close_time'] : '';
										$closing_time .= (isset($serve_val['close_time_2']) and !empty($serve_val['close_time_2'])) ? '-'.$serve_val['close_time_2'] : '';*/
										$opening_time = (isset($serve_val['open_time']) and !empty($serve_val['open_time'])) ? $serve_val['open_time'] : '';
										$opening_time_2 = (isset($serve_val['open_time_2']) and !empty($serve_val['open_time_2'])) ? $serve_val['open_time_2'] : '';
										$closing_time = (isset($serve_val['close_time']) and !empty($serve_val['close_time'])) ? $serve_val['close_time'] : '';
										$closing_time_2 = (isset($serve_val['close_time_2']) and !empty($serve_val['close_time_2'])) ? $serve_val['close_time_2'] : '';
										?>
															 
									<option value="<?php echo $key ?>" data-openingtime="<?php echo $opening_time; ?>" data-closingtime="<?php echo $closing_time; ?>" data-openingtime2="<?php echo $opening_time_2; ?>" data-closingtime2="<?php echo $closing_time_2; ?>"> <?php echo $key ?> </option>
									
									<?php 
										}  
									}
									?>
							  </select>
                           </div>
                           <div class="section-item middle">
                               <label for=""> Desde las   </label>
                              <select name="sinceto" id="sinceto"  class="sinceto" disabled="disabled">
                                 <option value=""> Select Sinceto</option>
                              </select>
                           </div>
                           <div class="section-item right">
                               <label for=""> Hasta las  </label>
                              <select name="untilltime" id="untilltime"  class="untilltime" disabled="disabled">
                                 <option value="">Select Untill</option>
                              </select>
                           </div>
                           <div class="point-hd"> Acmularas <span id="per_hour_rate">0</span> Points</div>
                            </div>
                           <div class="section"> 
                            <span class="cost-measure" id="tot_price">Coste <?php echo $per_hour_rate ?>$ Mensuales</span>
							<input type="submit" value="Pagar" name="submit" id="subscribe" class="btn btn-default">
                           </div>
                     </form>
                   
                   </div>
                   </div>

                </div>
            </div>
        </div>
</div>
 
 