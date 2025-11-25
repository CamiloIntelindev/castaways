jQuery(document).ready(function($) { // Usar $ en lugar de jQuery por conveniencia
	$(document).on('click', '.get-posts', function (e) {
        e.preventDefault();
		jQuery('#loading').show();
        const termId = $(this).data('term-id'); // Usar .data() es más limpio para obtener el valor del atributo data-term-id

        $.ajax({
            url: ajaxCall.ajax_url,
            method: 'POST',
            data: {
                action: 'get_posts_by_taxonomy',
                term_id: termId, // Pasar el term_id al servidor
                nonce: ajaxCall.nonce
            },
			
            success: function(response) {
                //console.log(response); // Maneja la respuesta del servidor
				jQuery('.group_trips-container').empty();
				jQuery('.group_trips-container').html(response);
				jQuery('#loading').hide();
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error); // Maneja los errores de la solicitud AJAX
				jQuery('#loading').hide();
            }
        });
    });
	
	$(document).on('click', '#all', function (e) {
        e.preventDefault();
		jQuery('#loading').show();
        //const termId = $(this).data('term-id'); // Usar .data() es más limpio para obtener el valor del atributo data-term-id

        $.ajax({
            url: ajaxCall.ajax_url,
            method: 'POST',
            data: {
                action: 'get_all_posts',
                nonce: ajaxCall.nonce
                //term_id: termId, // Pasar el term_id al servidor
            },
			
            success: function(response) {
                //console.log(response); // Maneja la respuesta del servidor
				jQuery('.group_trips-container').empty();
				jQuery('.group_trips-container').html(response);
				jQuery('#loading').hide();
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error); // Maneja los errores de la solicitud AJAX
				jQuery('#loading').hide();
            }
        });
    });
	
	// Ajax for deals
	 $(document).on('click', '.get-deal', function (e) {
        e.preventDefault();

        const termId = $(this).data('term-id'); // Usar .data() es más limpio para obtener el valor del atributo data-term-id

        $.ajax({
            url: ajaxCall.ajax_url,
            method: 'POST',
            data: {
                action: 'get_deals_by_taxonomy',
                term_id: termId, // Pasar el term_id al servidor
                nonce: ajaxCall.nonce
            },
            success: function(response) {
                //console.log(response); // Maneja la respuesta del servidor
				jQuery('.group_trips-container').empty();
				jQuery('.group_trips-container').html(response);
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error); // Maneja los errores de la solicitud AJAX
            }
        });
    });
	
	//Ajax for destinations
	 $(document).on('click', '.get-posts-destination', function (e) {
        e.preventDefault();
		jQuery('#loading').show();
        const termId = $(this).data('term-id'); // Usar .data() es más limpio para obtener el valor del atributo data-term-id

        $.ajax({
            url: ajaxCall.ajax_url,
            method: 'POST',
            data: {
                action: 'get_posts_by_destination',
                term_id: termId, // Pasar el term_id al servidor
                nonce: ajaxCall.nonce
            },
			
            success: function(response) {
                //console.log(response); // Maneja la respuesta del servidor
				jQuery('.group_trips-container').empty();
				jQuery('.group_trips-container').html(response);
				jQuery('#loading').hide();
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error); // Maneja los errores de la solicitud AJAX
				jQuery('#loading').hide();
            }
        });
    });

    //Ajax for host couples
    $(document).on('click', '.get-posts-host_couple', function (e) {
        e.preventDefault();
        jQuery('#loading').show();
        const termId = $(this).data('term-id'); // Usar .data() es más limpio para obtener el valor del atributo data-term-id

        $.ajax({
            url: ajaxCall.ajax_url,
            method: 'POST',
            data: {
                action: 'get_posts_by_host_couple',
                term_id: termId, // Pasar el term_id al servidor
                nonce: ajaxCall.nonce
            },
			
            success: function(response) {
                //console.log(response); // Maneja la respuesta del servidor
                jQuery('.group_trips-container').empty();
                jQuery('.group_trips-container').html(response);
                jQuery('#loading').hide();
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error); // Maneja los errores de la solicitud AJAX
                jQuery('#loading').hide();
            }
        });
    });
	
	 $(document).on('click', '.calendar', function (e) {
		  window.location.replace("https://castawaystravel.com/group-trips-calendar/");
	 });
	
	//reorder group-trips
	
    // Selecciona el elemento con el valor "River Cruise Takeover"
    var riverCruise = $('.categories_link_item input[value="River Cruise Takeover"]').parent();
    // Muévelo después del segundo elemento
    riverCruise.insertAfter($('.categories_link_item:nth-child(2)'));

    // Empty booking button text if link is missing
    var $bookingBtn = $('.booking-button');
    if ($bookingBtn.length) {
        var href = $bookingBtn.find('a').attr('href');
        if (!href) {
            $bookingBtn.find('span').text('Online booking available soon');
        }
    }

});
