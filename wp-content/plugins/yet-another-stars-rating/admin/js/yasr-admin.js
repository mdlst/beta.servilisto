!function(e){var t={};function r(o){if(t[o])return t[o].exports;var a=t[o]={i:o,l:!1,exports:{}};return e[o].call(a.exports,a,a.exports,r),a.l=!0,a.exports}r.m=e,r.c=t,r.d=function(e,t,o){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(r.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var a in e)r.d(o,a,function(t){return e[t]}.bind(null,a));return o},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="",r(r.s=0)}([function(e,t,r){r(1),r(2),e.exports=r(3)},function(e,t){copyToClipboard=e=>{const t=document.createElement("textarea");t.value=e,t.setAttribute("readonly",""),t.style.position="absolute",t.style.left="-9999px",document.body.appendChild(t),t.select(),document.execCommand("copy"),document.body.removeChild(t)},tippy(document.querySelectorAll(".yasr-copy-shortcode"),{content:"Copied! Insert into your post!",theme:"yasr",arrow:"true",arrowType:"round",trigger:"click"})},function(e,t){jQuery(document).ready((function(){jQuery(".yasr-log-pagenum").on("click",(function(){jQuery("#yasr-loader-log-metabox").show();var e={action:"yasr_change_log_page",pagenum:jQuery(this).val(),totalpages:jQuery("#yasr-log-total-pages").data("yasr-log-total-pages")};jQuery.post(ajaxurl,e,(function(e){jQuery("#yasr-loader-log-metabox").hide(),jQuery("#yasr-log-container").html(e)}))})),jQuery(document).ajaxComplete((function(e,t,r){var o=!0;"undefined"!==r.data&&(o=r.data.search("action=yasr_change_log_page")),-1!==o&&jQuery(".yasr-log-pagenum").on("click",(function(){jQuery("#yasr-loader-log-metabox").show();var e={action:"yasr_change_log_page",pagenum:jQuery(this).val(),totalpages:jQuery("#yasr-log-total-pages").data("yasr-log-total-pages")};jQuery.post(ajaxurl,e,(function(e){jQuery("#yasr-log-container").html(e)}))}))}))})),jQuery(document).ready((function(){jQuery(".yasr-user-log-page-num").on("click",(function(){jQuery("#yasr-loader-user-log-metabox").show();var e={action:"yasr_change_user_log_page",pagenum:jQuery(this).val(),totalpages:jQuery("#yasr-user-log-total-pages").data("yasr-log-total-pages")};jQuery.post(ajaxurl,e,(function(e){jQuery("#yasr-loader-log-metabox").hide(),jQuery("#yasr-user-log-container").html(e)}))})),jQuery(document).ajaxComplete((function(e,t,r){void 0!==r.data&&-1!==r.data.search("action=yasr_change_user_log_page")&&jQuery(".yasr-user-log-page-num").on("click",(function(){jQuery("#yasr-loader-user-log-metabox").show();var e={action:"yasr_change_user_log_page",pagenum:jQuery(this).val(),totalpages:jQuery("#yasr-user-log-total-pages").data("yasr-log-total-pages")};jQuery.post(ajaxurl,e,(function(e){jQuery("#yasr-user-log-container").html(e)}))}))}))}))},function(e,t){document.addEventListener("DOMContentLoaded",(function(e){if(void 0===document.getElementsByClassName("nav-tab-active")[0])return;let t=document.getElementsByClassName("nav-tab-active")[0].id;if("general_settings"===t){let e=document.getElementById("yasr_auto_insert_switch").checked,t=document.getElementById("yasr-general-options-stars-title-switch").checked,r=document.getElementById("yasr-general-options-text-before-stars-switch").checked;!1===e&&jQuery(".yasr-auto-insert-options-class").prop("disabled",!0),!1===t&&jQuery(".yasr-stars-title-options-class").prop("disabled",!0),jQuery("#yasr_auto_insert_switch").change((function(){jQuery(this).is(":checked")?jQuery(".yasr-auto-insert-options-class").prop("disabled",!1):jQuery(".yasr-auto-insert-options-class").prop("disabled",!0)})),jQuery("#yasr-general-options-stars-title-switch").change((function(){jQuery(this).is(":checked")?jQuery(".yasr-stars-title-options-class").prop("disabled",!1):jQuery(".yasr-stars-title-options-class").prop("disabled",!0)})),!1===r&&jQuery(".yasr-general-options-text-before").find(":input").prop("disabled",!0),jQuery("#yasr-general-options-text-before-stars-switch").change((function(){jQuery(this).is(":checked")?(jQuery(".yasr-general-options-text-before").find(":input").prop("disabled",!1),jQuery("#yasr-general-options-custom-text-before-overall").val("Our Score"),jQuery("#yasr-general-options-custom-text-before-visitor").val("Click to rate this post!"),jQuery("#yasr-general-options-custom-text-after-visitor").val("[Total: %total_count%  Average: %average%]"),jQuery("#yasr-general-options-custom-text-must-sign-in").val("You must sign in to vote"),jQuery("#yasr-general-options-custom-text-already-rated").val("You have already voted for this article")):jQuery(".yasr-general-options-text-before").find(":input").prop("disabled",!0)})),jQuery("#yasr-doc-custom-text-link").on("click",(function(){return jQuery("#yasr-doc-custom-text-div").toggle("slow"),!1})),jQuery("#yasr-stats-explained-link").on("click",(function(){return jQuery("#yasr-stats-explained").toggle("slow"),!1}))}if("manage_multi"===t){let e=document.getElementById("n-multiset").value;if(jQuery("#yasr-multi-set-doc-link").on("click",(function(){jQuery("#yasr-multi-set-doc-box").toggle("slow")})),jQuery("#yasr-multi-set-doc-link-hide").on("click",(function(){jQuery("#yasr-multi-set-doc-box").toggle("slow")})),1===e){var r=jQuery("#yasr-edit-form-number-elements").attr("value");r++,jQuery("#yasr-add-field-edit-multiset").on("click",(function(){if(r>9)return jQuery("#yasr-element-limit").show(),jQuery("#yasr-add-field-edit-multiset").hide(),!1;var e=jQuery(document.createElement("tr"));e.html('<td colspan="2">Element #'+r+' <input type="text" name="edit-multi-set-element-'+r+'" value="" ></td>'),e.appendTo("#yasr-table-form-edit-multi-set"),r++}))}e>1&&(jQuery("#yasr-button-select-set-edit-form").on("click",(function(){var e={action:"yasr_get_multi_set",set_id:jQuery("#yasr_select_edit_set").val()};return jQuery.post(ajaxurl,e,(function(e){jQuery("#yasr-multi-set-response").show(),jQuery("#yasr-multi-set-response").html(e)})),!1})),jQuery(document).ajaxComplete((function(){var e=jQuery("#yasr-edit-form-number-elements").attr("value");e++,jQuery("#yasr-add-field-edit-multiset").on("click",(function(){if(e>9)return jQuery("#yasr-element-limit").show(),jQuery("#yasr-add-field-edit-multiset").hide(),!1;var t=jQuery(document.createElement("tr"));t.html('<td colspan="2">Element #'+e+' <input type="text" name="edit-multi-set-element-'+e+'" value="" ></td>'),t.appendTo("#yasr-table-form-edit-multi-set"),e++}))})))}"style_options"===t&&(wp.codeEditor.initialize(document.getElementById("yasr_style_options_textarea"),yasr_cm_settings),jQuery("#yasr-color-scheme-preview-link").on("click",(function(){return jQuery("#yasr-color-scheme-preview").toggle("slow"),!1}))),"migration_tools"===t&&(jQuery("#yasr-import-ratemypost-submit").on("click",(function(){document.getElementById("yasr-import-ratemypost-answer").innerHTML='<img src="'+yasrCommonDataAdmin.loaderHtml+'"</img>';var e={action:"yasr_import_ratemypost",nonce:document.getElementById("yasr-import-rmp-nonce").value};jQuery.post(ajaxurl,e,(function(e){e=JSON.parse(e),document.getElementById("yasr-import-ratemypost-answer").innerHTML=e}))})),jQuery("#yasr-import-wppr-submit").on("click",(function(){document.getElementById("yasr-import-wppr-answer").innerHTML='<img src="'+yasrCommonDataAdmin.loaderHtml+'"</img>';var e={action:"yasr_import_wppr",nonce:document.getElementById("yasr-import-wppr-nonce").value};jQuery.post(ajaxurl,e,(function(e){document.getElementById("yasr-import-wppr-answer").innerHTML=e}))})),jQuery("#yasr-import-kksr-submit").on("click",(function(){document.getElementById("yasr-import-kksr-answer").innerHTML='<img src="'+yasrCommonDataAdmin.loaderHtml+'"</img>';var e={action:"yasr_import_kksr",nonce:document.getElementById("yasr-import-kksr-nonce").value};jQuery.post(ajaxurl,e,(function(e){document.getElementById("yasr-import-kksr-answer").innerHTML=e}))})),jQuery("#yasr-import-mr-submit").on("click",(function(){document.getElementById("yasr-import-mr-answer").innerHTML='<img src="'+yasrCommonDataAdmin.loaderHtml+'"</img>';var e={action:"yasr_import_mr",nonce:document.getElementById("yasr-import-mr-nonce").value};jQuery.post(ajaxurl,e,(function(e){document.getElementById("yasr-import-mr-answer").innerHTML=e}))})))}))}]);