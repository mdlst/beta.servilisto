var $jDOPBSP=jQuery.noConflict();(function($,Views,Models,Collections){$(document).ready(function(){$('.not_is_tablet #main-single .pinned-custom').pin({containerSelector:"#main-single",padding:{top:100,bottom:120}});Collections.Events=Backbone.Collection.extend({model:AE.Models.Event,action:'ae-fetch-events',initialize:function(){this.paged=1;}});EventItem=Views.PostItem.extend({template:_.template($('#de-event-item').html()),className:'event-wrapper event-item',onItemRendered:function(){}});ListEvent=Views.ListPost.extend({tagName:'div',itemView:EventItem,itemClass:'event-item'});if($('#block-events .postdata').length>0){var postdata=JSON.parse($('#block-events').find('.postdata').html()),collection=new Collections.Events(postdata);new ListEvent({el:$('#list-events'),collection:collection});}});Views.SinglePost=Backbone.Marionette.View.extend({el:'body.single',events:{'click a.place-action':'acting','click a.write-review':'slideToReview','click a.sroll-review':'jumbToReview','click a.favorite':'favorite','click a.loved':'removeFavorite','click a.report':'openReportModal','click a.claim-place':'openClaimModal','click a.load-more-post':'loadNextPost','mouseover  .not_is_tablet .list-option-left .share-social':'showShare','mouseleave  .not_is_tablet .list-option-left .share-social':'hideShare','click  .is_tablet .list-option-left .share-social':'toggleShare'},openReportModal:function(event){event.preventDefault();var $target=$(event.currentTarget),view=this;if(typeof this.Reportmodal==='undefined'){this.Reportmodal=new Views.ReportModal({el:$("#report"),place_id:$target.attr('data-id'),user_id:$target.attr('data-user'),model:view.model});}
this.Reportmodal.place_id=$target.attr('data-id');this.Reportmodal.user_id=$target.attr('data-user');this.Reportmodal.openModal();},openClaimModal:function(event){event.preventDefault();var $target=$(event.currentTarget),view=this;if(typeof this.claimModal==='undefined'){this.claimModal=new Views.ClaimModal({el:$("#claim_modal"),place_id:$target.attr('data-id'),user_id:$target.attr('data-user'),model:view.model});}
this.claimModal.place_id=$target.attr('data-id');this.claimModal.user_id=$target.attr('data-user');this.claimModal.openModal();},showShare:function(event){event.preventDefault();var $target=$(event.currentTarget);$target.find('.list-share-social').addClass('active');},hideShare:function(event){event.preventDefault();var $target=$(event.currentTarget);$target.find('.list-share-social').removeClass('active');},toggleShare:function(event){event.preventDefault();var $target=$(event.currentTarget);$target.find('.list-share-social').toggleClass('active').toggle();},initialize:function(options){var view=this;_.bindAll(this,'updateUrl');this.options=_.extend(options,this.options);$('.tooltip-style').tooltip();if($('#next_id').length>0){var next=JSON.parse($('#next_id').html());this.next_post=next.id;this.load='next';}
if($('#prev_id').length>0){var prev=JSON.parse($('#prev_id').html());this.prev_post=prev.id;}
view.blockUi=new Views.BlockUi();view.collection=new Collections.Posts();view.collection.add(view.model);$('.popup-video').magnificPopup({type:'iframe'});},acting:function(e){var target=$(e.currentTarget),action=target.attr('data-action'),id=target.parents('.single-place-wrapper').attr('data-id'),model=this.collection.get(id),view=this;switch(action){case'edit_calender':AE.pubsub.trigger('ae:model:onCreateCalender',model);var id=$jDOPBSP('#DOPBSP-calendar-ID').val();DOPBSPBackEnd.toggleMessages('active',DOPBSPBackEnd.text('MESSAGES_LOADING'));DOPBSPBackEndSettings.toggle(id,'calendar');$.post(ajaxurl,{action:'dopbsp_calendar_get_options',id:id},function(data){$('#DOPBSP-column2 .dopbsp-column-content').html('<div id="DOPBSP-calendar"></div>');$('#DOPBSP-calendar').DOPBSPCalendarBackEnd($.parseJSON(data));$.post(ajaxurl,{action:'dopbsp_get_new_reservations',id:id},function(data){if(parseInt(data)!==0){$('#DOPBSP-new-reservations').addClass('dopbsp-new');$('#DOPBSP-new-reservations span').html(data);}});}).fail(function(data){DOPBSPBackEnd.toggleMessages('error',data.status+': '+data.statusText);});break;case'create_event':$.ajax({type:'get',url:ae_globals.ajaxURL,data:{action:'ae-check-event',post_parent:model.get('ID')},beforeSend:function(){view.blockUi.block(target.parents('.dropdown'));},success:function(res){view.blockUi.unblock();if(res.success){AE.pubsub.trigger('ae:model:onCreateEvent',model);}else{AE.pubsub.trigger('ae:notification',{msg:res.msg,notice_type:'error',});}}});break;case'edit':AE.pubsub.trigger('ae:model:onEdit',model);$('#place_category').trigger('render.drop');break;case'reject':AE.pubsub.trigger('ae:model:onReject',model);break;case'archive':if(confirm(ae_globals.confirm_message)){model.save('post_status','archive',{beforeSend:function(){},success:function(result,status){if(status.success){AE.pubsub.trigger('ae:notification',{msg:status.msg,notice_type:'success',});window.location.reload();}else{AE.pubsub.trigger('ae:notification',{msg:status.msg,notice_type:'error',});}}});}
break;case'toggleFeature':if(parseInt(model.get('et_featured'))===1){model.set('et_featured',0);}else{model.set('et_featured',1);}
model.save('','',{beforeSend:function(){},success:function(result,status){if(status.success){AE.pubsub.trigger('ae:notification',{msg:status.msg,notice_type:'success',});window.location.reload();}else{AE.pubsub.trigger('ae:notification',{msg:status.msg,notice_type:'error',});}}});break;case'approve':model.save('publish','1',{beforeSend:function(){},success:function(result,status){if(status.success){window.location.href=model.get('permalink');}}});break;default:break;}},slideToReview:function(event){event.preventDefault();if(ae_globals.user_ID==='0'){var target_offset=$(".ae-comment-reply-title").offset();}
else{var target_offset=$("#review").offset();}
var target_top=target_offset.top;$('html, body').animate({scrollTop:target_top-200},500,'easeOutExpo');},jumbToReview:function(event){event.preventDefault();var target_offset=$("#review-list").offset();var target_top=target_offset.top;$('html, body').animate({scrollTop:target_top-200},500,'easeOutExpo');},favorite:function(event){event.preventDefault();var $target=$(event.currentTarget),view=this,favorite=new Models.Favorite({comment_post_ID:$target.attr('data-id'),sync:'add'});favorite.save('sync','add',{beforeSend:function(){view.blockUi.block($target);},success:function(result,res,xhr){view.blockUi.unblock();if(res.success===true){$target.closest('li').attr('title',res.text);$target.addClass('loved').removeClass('favorite');$target.attr('data-favorite-id',res.data);AE.pubsub.trigger('ae:notification',{msg:res.msg,notice_type:'success'});}
else{AE.pubsub.trigger('ae:notification',{msg:res.msg,notice_type:'error'});}}});},removeFavorite:function(event){event.preventDefault();var $target=$(event.currentTarget),view=this,favorite=new Models.Favorite({id:$target.attr('data-favorite-id'),ID:$target.attr('data-favorite-id'),sync:'remove'});favorite.save('sync','remove',{beforeSend:function(){view.blockUi.block($target);},success:function(result,res,xhr){view.blockUi.unblock();if(res.success===true){$target.closest('li').attr('title',res.text);$target.addClass('favorite').removeClass('loved');AE.pubsub.trigger('ae:notification',{msg:res.msg,notice_type:'success'});}
else{AE.pubsub.trigger('ae:notification',{msg:res.msg,notice_type:'error'});}}});},editPostSucess:function(model){if(res.success){window.location.reload();}},loadNextPost:function(event){event.preventDefault();var model=this.next_post,$target=$(event.currentTarget),view=this;if(!model){model=this.prev_post;this.load='prev';}
$.ajax({url:ae_globals.ajaxURL,type:'get',data:{action:'de-next-place',id:model,current:view.model.get('id'),load:view.load},beforeSend:function(){view.blockUi.block($target);},success:function(res){view.blockUi.unblock();if(res.success){$('#single-more-place').append(res.content);$('#single-place-'+res.post_id).find('.fancybox').magnificPopup({type:'image',gallery:{enabled:true},});$('.not_is_tablet #single-place-'+res.post_id).find(".list-option-left").pin({containerSelector:'#single-place-'+res.post_id,padding:{top:100,bottom:120}});if(view.load==='next'){view.next_post=res.next;}
if(view.load==='prev'){view.prev_post=res.prev;}
var model=new Models.Post({id:res.post_id,ID:res.post_id,pageTitle:res.pageTitle,link:res.link});document.title=res.pageTitle;window.history.pushState({"html":res.content,"pageTitle":res.pageTitle},"",res.link);view.collection.add(model);view.bindInview();model.fetch();$('.rate-it').raty({readOnly:true,half:true,score:function(){return $(this).attr('data-score');},hints:raty.hint});var location_lat,location_lng;if(frontGetCookie('current_user_lat')&&frontGetCookie('current_user_lng')){location_lat=frontGetCookie('current_user_lat');location_lng=frontGetCookie('current_user_lng');var $itemSinglePlace=$('#single-place-'+res.post_id),lat_item=$itemSinglePlace.find('#latitude').attr('content'),lng_item=$itemSinglePlace.find('#longitude').attr('content');var dist=distance(lat_item,lng_item,location_lat,location_lng);$itemSinglePlace.find('.distance').text("a "+dist+' de tí - ');}}else{$target.remove();}}});},bindInview:function(){var view=this;this.$('.single-place-wrapper').bind('inview',view.updateUrl);},updateUrl:function(event,isVisible){var view=this;if(!isVisible){this.inViewVisible=false;return;}
var $target=$(event.currentTarget),id=$target.attr('data-id'),model=this.collection.get(parseInt(id));if(model.get('post_title')){document.title=model.get('post_title');}else{document.title=model.get('pageTitle');}
window.history.pushState({"html":$target.html(),"pageTitle":model.get('pageTitle')},"",model.get('link'));}});Views.ClaimModal=Views.Modal_Box.extend({events:{'submit form#submit_claim':'submitClaim','click a.deny-claim':'denyClaim'},initialize:function(){AE.Views.Modal_Box.prototype.initialize.call();this.blockUi=new Views.BlockUi();this.initValidator();},initValidator:function(){$("form#submit_claim").validate({ignore:"",rules:{display_name:"required",location:"required",phone:{required:true,number:true},message:"required",},errorPlacement:function(label,element){if(element.is("textarea")){label.insertAfter(element.next());}else{$(element).closest('div').append(label);}}});},denyClaim:function(event){event.preventDefault();var btn=$(event.currentTarget);this.blockUi.block(btn);$("input#claim_action").val('deny');$('.trigger-claim').trigger('click');},submitClaim:function(event){event.preventDefault();event.stopPropagation();var $form=$(event.currentTarget),button=$form.find('.btn-submit'),textarea=$form.find('textarea'),content=$form.serializeObject(),view=this;content.place_id=this.place_id;$.ajax({url:ae_globals.ajaxURL,type:'POST',data:{action:'ae_claim_place',content:content},beforeSend:function(){view.blockUi.block(button);},success:function(res){AE.pubsub.trigger('ae:notification',{msg:res.msg,notice_type:'success'});window.location.reload();$form[0].reset();view.blockUi.unblock();view.closeModal();}});},});Views.ReportModal=Views.Modal_Box.extend({events:{'submit form#submit_report':'submitReport',},initialize:function(){AE.Views.Modal_Box.prototype.initialize.call();this.blockUi=new Views.BlockUi();this.initValidator();},initValidator:function(){$("form#submit_report").validate({ignore:"",rules:{message:"required",},errorPlacement:function(label,element){if(element.is("textarea")){label.insertAfter(element.next());}else{$(element).closest('div').append(label);}}});},submitReport:function(event){event.preventDefault();event.stopPropagation();var $form=$(event.currentTarget),button=$form.find('.btn-submit'),textarea=$form.find('textarea'),view=this,report=new Models.Report({comment_post_ID:view.place_id,comment_content:textarea.val(),user_report:view.user_id,sync:'add'});report.save('sync','add',{beforeSend:function(){view.blockUi.block(button);},success:function(result,res,xhr){AE.pubsub.trigger('ae:notification',{msg:res.msg,notice_type:'success'});$form[0].reset();$('a#report_'+view.place_id).remove();view.blockUi.unblock();view.closeModal();}});},});Models.Favorite=Backbone.Model.extend({action:'ae-sync-favorite',initialize:function(){}});Models.Report=Backbone.Model.extend({action:'ae-sync-report',initialize:function(){}});function distance(lat1,lon1,lat2,lon2){var radlat1=Math.PI*lat1/180;var radlat2=Math.PI*lat2/180;var theta=lon1-lon2;var radtheta=Math.PI*theta/180;var dist=Math.sin(radlat1)*Math.sin(radlat2)+Math.cos(radlat1)*Math.cos(radlat2)*Math.cos(radtheta);dist=Math.acos(dist);dist=dist*180/Math.PI;dist=dist*60*1.1515;var unit=ae_globals.units_of_measurement;if(unit==='km'){if(dist<1){dist=Math.ceil(dist*1000)+' m';}else{dist=Math.ceil(dist*1.609)+' Km';}}else{dist=Math.ceil(dist)+' Mi';}
return dist;}
if($('body.single').length>0){var location_lat,location_lng;if(parseInt(ae_globals.geolocation)){if(frontGetCookie('current_user_lat')&&frontGetCookie('current_user_lng')){location_lat=frontGetCookie('current_user_lat');location_lng=frontGetCookie('current_user_lng');$('body.single').each(function(){var lat_item=$('#latitude',this).attr('content'),lng_item=$('#longitude',this).attr('content');var dist=distance(lat_item,lng_item,location_lat,location_lng);$('.poner-aqui-distance-calculada',this).append("(A "+dist+" de tí)");});}}}})(jQuery,AE.Views,AE.Models,AE.Collections);