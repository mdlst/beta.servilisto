<?php
global $wpdb;
    
	$table = $wpdb->prefix . 'tbl_points_log';
	
	
  
	
	if(isset($_POST['action']) &&  $_POST["action"] ="add_cancel_form")
	{
		
	  if($_POST['user_id']!="")	{
		$no_of_points = $_POST['no_of_points'];
		$add_cancel_points = $_POST['add_cancel_points'];
		$post_ID = 0;
		$current_date = date("Y-m-d H:i:s");
  	  if( filter_var($_POST['no_of_points'], FILTER_VALIDATE_FLOAT)!== false){
		
		if($add_cancel_points=='add')
		{
		
		$data  = array(user_id=>$_POST['user_id'],post_id=>$post_ID,points_added=>$no_of_points,type=>'by_admin_points',curr_date=>$current_date);
	$wpdb->insert( $table, $data);	
		
		if($data){
		echo "<br><h4 class='success_msg'>"."Points Successfully Added</h4>";	
		}
			
		}
		else
		{
		$data2  = array(user_id=>$_POST['user_id'],post_id=>$post_ID,points_redeem=>$no_of_points,type=>'by_admin_points',curr_date=>$current_date);
	$wpdb->insert( $table, $data2);	
		
		if($data2){
		echo "<br><h4 class='success_msg'>"."Points Successfully Canceled/Minus</h4>";	
		}
			
		}
		
	  }
	  else
	  {
		echo "<br><h4 class='error_msg'>"."Plz enter valid value for No. of Points</h4>";  
		  
	  }
	}
	
	  else
	  {
		echo "<br><h4 class='error_msg'>"."Plz select User</h4>";   
	  }
	
  }
 
	
	
?>
<form method="post" name="point_set"  id="point_set">
	<table>
    	<tr>
        <td align="center" colspan="2"><h1>Add/Cancel User Points</h1></td>
        </tr>
        
        <tr>
        <td align="center">
        <div class="select_option">
      <label class="control-label">Select User</label>
            
            
			<div class="controls">	
            <?php 
			global $wpdb;
	    $all_user_records = @$wpdb->get_results("SELECT * FROM ".$wpdb->base_prefix."users" );		
	          	?>							
			 <select class="span3 chosen" name="user_id" id="user_id">
             <option value=''> All Users </option>
          <?php   
           foreach($all_user_records as $user_val){
		      $user_data =  get_userdata( $user_val->ID );
			  $user_name= '';
			   if($user_data->user_nicename!=''){
				 $user_name= $user_data->user_nicename;
			   }
			   else{
				$user_name = $user_data->display_name;   
			   }
			   if($user_name==''){
				$user_name = "Unknown: ID (".$user_val->ID.") ";  
			   }
            ?>
				<option value='<?php echo $user_val->ID; ?>' <?php if($_GET['user_id']==$user_val->ID) {echo "selected='selected'" ;}?>  > <?php echo $user_name; ?> </option>
                <?php } ?>
            </select>															
	  </div>
      </div>
      <br>
      <br>
      </td>
      </tr>  
      
     <?php  if($_GET['user_id']!=''){ ?> 
      
        <tr>
        <td align="center">
      <label class="control-label">Total Points of Current User:  </label>
            
            <?php 
			
	    $user_total_points = $wpdb->get_row("SELECT (COALESCE(sum(points_added),0) - COALESCE(sum(points_redeem),0)) as total_points FROM ".$table." where user_id = '".$_GET['user_id']."' group by user_id  " );
		
		//echo $user_total_points->total_points=='' ? 0 : $user_total_points->total_points;
		echo $user_total_points->total_points=='' ? 0 : $user_total_points->total_points;
         	?>							
																
	 
     
      <br>
      <br>
      </td>
      </tr> 
      
       <?php  } ?> 
      
      
    	<tr>
        <td>Number of Points:</td>
        <td><input  type="text" name="no_of_points" id="no_of_points" /></td>
        </tr>
                
        <tr>
        <td>Add or Cancel</td> 
        <td><input type="radio" name="add_cancel_points" value="add" checked>Add
<br>
<input type="radio" name="add_cancel_points" value="cancel">Cancel/Minus</td>
        </tr>       
        <tr>
        <td colspan="2" align="center">
       		 <input type="hidden" name="action" value="add_cancel_form" />
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
		

<script>
 
 (function($){
	jQuery(document).ready(function($){
			  
 $("#user_id").change(function(){
	
		var id = $(this).val();
		
		var url ='admin.php?page=add-cancel-user-points&user_id='+id+'';
		window.location =url;
	});
	
	
	
 
 }); //ready end
})(jQuery); //function end	

 </script>