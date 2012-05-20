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
            var divID = $jq("<div class='cartalog-item-display-this'></div>")
				.appendTo("body");
			$jq(divID)
				.html(response)
				.dialog({
					modal       : true,
					width       : 750,
					position    : "center",
					hide        : 'fade',
					show        : 'fade',
					dialogClass : 'wp-dialog',
                    open        : function(){
                                      $jq('.ui-widget-overlay')
                                          .on('click',function(){
                                              $jq(divID).dialog('close');
											  setTimeout(function() { $jq(divID).remove(); }, 1000);
                                          });
                                  },
					buttons     : { "Close" : function() {
                                          $jq(this).dialog('close');
										  setTimeout(function() { $jq(divID).remove(); }, 1000);
                                  } }
				});
			$jq('.detail_controls').remove();
        }
    );
}
