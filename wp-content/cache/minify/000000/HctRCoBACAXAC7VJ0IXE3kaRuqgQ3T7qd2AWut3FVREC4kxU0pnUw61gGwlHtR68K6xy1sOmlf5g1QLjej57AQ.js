jQuery(function(a){if("undefined"==typeof wc_cart_fragments_params)return!1;try{$supports_html5_storage="sessionStorage"in window&&null!==window.sessionStorage,window.sessionStorage.setItem("wc","test"),window.sessionStorage.removeItem("wc")}catch(b){$supports_html5_storage=!1}if($fragment_refresh={url:wc_cart_fragments_params.ajax_url,type:"POST",data:{action:"woocommerce_get_refreshed_fragments"},success:function(b){b&&b.fragments&&(a.each(b.fragments,function(b,c){a(b).replaceWith(c)}),$supports_html5_storage&&(sessionStorage.setItem(wc_cart_fragments_params.fragment_name,JSON.stringify(b.fragments)),sessionStorage.setItem("wc_cart_hash",b.cart_hash)),a("body").trigger("wc_fragments_refreshed"))}},$supports_html5_storage){a("body").bind("added_to_cart",function(a,b,c){sessionStorage.setItem(wc_cart_fragments_params.fragment_name,JSON.stringify(b)),sessionStorage.setItem("wc_cart_hash",c)});try{var c=a.parseJSON(sessionStorage.getItem(wc_cart_fragments_params.fragment_name)),d=sessionStorage.getItem("wc_cart_hash"),e=a.cookie("woocommerce_cart_hash");if((null===d||void 0===d||""===d)&&(d=""),(null===e||void 0===e||""===e)&&(e=""),!c||!c["div.widget_shopping_cart_content"]||d!=e)throw"No fragment";a.each(c,function(b,c){a(b).replaceWith(c)}),a("body").trigger("wc_fragments_loaded")}catch(b){a.ajax($fragment_refresh)}}else a.ajax($fragment_refresh);a.cookie("woocommerce_items_in_cart")>0?a(".hide_cart_widget_if_empty").closest(".widget_shopping_cart").show():a(".hide_cart_widget_if_empty").closest(".widget_shopping_cart").hide(),a("body").bind("adding_to_cart",function(){a(".hide_cart_widget_if_empty").closest(".widget_shopping_cart").show()})});;var addComment={moveForm:function(a,b,c,d){var e,f=this,g=f.I(a),h=f.I(c),i=f.I("cancel-comment-reply-link"),j=f.I("comment_parent"),k=f.I("comment_post_ID");if(g&&h&&i&&j){f.respondId=c,d=d||!1,f.I("wp-temp-form-div")||(e=document.createElement("div"),e.id="wp-temp-form-div",e.style.display="none",h.parentNode.insertBefore(e,h)),g.parentNode.insertBefore(h,g.nextSibling),k&&d&&(k.value=d),j.value=b,i.style.display="",i.onclick=function(){var a=addComment,b=a.I("wp-temp-form-div"),c=a.I(a.respondId);if(b&&c)return a.I("comment_parent").value="0",b.parentNode.insertBefore(c,b),b.parentNode.removeChild(b),this.style.display="none",this.onclick=null,!1};try{f.I("comment").focus()}catch(l){}return!1}},I:function(a){return document.getElementById(a)}};