function show_detail(postID){
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
			$jq(divID)
				.html(response)
				.dialog({
					'modal': true,
					'width' : 750,
					'position' : "center",
					hide: 'fade',
					show: 'fade',
					'dialogClass': 'wp-dialog',
					'buttons' : {
						"Close" : function() {
							$jq(this).dialog('close');
							$jq(this).empty();
						}
					}
				});
			$jq('.detail_controls').remove();
        }
    );
}
