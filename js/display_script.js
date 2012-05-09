function show_detail(postID){
	$j = jQuery.noConflict();
    $j.post(
        ajax_object.ajaxurl,
        {
            action : 'ajax_display',
            postID : postID,

            // send the nonce along with the request
            displayNonce : ajax_object.displayNonce
        },
        function( response ) {
            var divID = $j(".detail_display_area_" + postID);
			$j(divID)
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
							$j(this).dialog('close');
							$j(this).empty();
						}
					}
				});
			$j('.detail_controls').remove();
        }
    );
}
