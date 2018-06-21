jQuery( document ).on( 'click', '.house_and_land_link', function() {
	var lot_width = jQuery(this).val();
	var ab=jQuery('.house_and_land_link').html();
	jQuery.ajax({
		url : handl.ajax_url,
		type : 'get',
		data : {
			action : 'house_and_land_post',
			lotwidth :'0 , '+lot_width
		},
		beforeSend: function( xhr ) {
			jQuery('.house_and_land_link').addClass('buttonload');
			jQuery('.house_and_land_link').html("<i class='fa fa-spinner fa-spin'></i>Loading");
		},
		success : function( response ) {
			jQuery('.house_and_land_link').removeClass('buttonload');
			jQuery('.result_house_and_land').html( response );
			jQuery('.house_and_land_link').html(ab);
			jQuery('html, body').animate({
				scrollTop: jQuery('.result_house_and_land').offset().top - 50
			}, 800, function () {
			});
		}
	});

	return false;
})