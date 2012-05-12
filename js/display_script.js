function show_detail(evt, postID){
    evt.preventDefault();
	$jq = jQuery.noConflict();
    $jq.post(
        ajax_object.ajaxurl,
        {
            action : 'ajax_display',
            postID : postID,

            // send the nonce along with the request
            displayNonce : ajax_object.displayNonce
        },
        function( response ) {
            var divID = $jq(".detail_display_area_" + postID);
            var title = $jq(divID).parents(".storeItem").siblings(".categoryDetail").text();
			$jq(divID)
				.html(response)
				.dialog({
					modal       : true,
                    title       : title,
					width       : 750,
					position    : "center",
					hide        : 'fade',
					show        : 'fade',
					dialogClass : 'wp-dialog',
                    open        : function(){
                                      $jq('.ui-widget-overlay')
                                          .on('click',function(){
                                              $jq(divID).dialog('close');
                                              $jq(divID).empty();
                                          });
                                  },
					buttons     : { "Close" : function() {
                                          $jq(this).dialog('close');
                                          $jq(this).empty();
                                  } }
				});
			$jq('.detail_controls').remove();
        }
    );
}
