(function($){
	jQuery(document).ready(function($){
		
		
		$('#delete_all_subscription').click(function() {
    
		if($(this).prop('checked')==true){ 
			$("[type=checkbox]").each(function() {
						$('.subscription_id').prop('checked', true);
													
				});
		}
		else
		{
			$('.subscription_id').prop('checked', false);
			
		}
     });		
			
	$('.delete_subscription').click(function (){
		 //alert('dsafda');
				 var subscription_ids = [];
				$("input:checkbox[name='subscription_id[]']").each(function() {
					if($(this).is(':checked')){
					//	alert('inner');
						subscription_ids.push($(this).closest("tr").find("input[type=checkbox]").val());
					}
				});

			if(subscription_ids != ''){
				
				$.post(
					ajaxurl,
					{subscription_ids:subscription_ids, action : 'delete_subscription_data'}
					
					).done(function (data){
						
						if(data=='done'){
						 alert("Your Record has been deleted Successfully");
						}
						location.reload(true);
						});	
	
			}
				else
				{
				 alert("Plz Select Atleast One Record");	
				}
					
			});
		
		
		
		
		 $('.change_subscriber_status').click(function (e){
				var status_change = $(this).closest('tr').find(".change_subscriber_status").html(); 
				var subscription_id = $(this).closest('tr').find(".subscription_id").val();
				
				$.post(
					ajaxurl,
					{status_change:status_change, subscription_id:subscription_id, action : 'change_subscriber_status'}
					
					).done(function (data){
					
						if(data=='done'){
						 msg='Your Subscriber Status Changed Successfully';
						 location.reload(true);	
						}
						
						});
							
					
			});
			
			
			
			
			/* $('#submit_features_price').click(function (e){
				 
				 var email_subscription_price = $('#email_subscription_feature').val();
                 var ads_remove_price = $('#ads_remove_feature').val();
				 var privacy_config_price = $('#privacy_config_feature').val();
				
				$.post(
					ajaxurl,
					{email_subscription_price:email_subscription_price, ads_remove_price:ads_remove_price, privacy_config_price:privacy_config_price, action : 'set_custom_spider_features_price'}
					
					).done(function (data){
					
						if(data=='done'){
						 msg='Your Feature Price Setting Updated Successfully';
						 location.reload(true);	
						}
						
						});
							
					
			});*/
		
		
		
		}); //ready end


			

})(jQuery); //function end	