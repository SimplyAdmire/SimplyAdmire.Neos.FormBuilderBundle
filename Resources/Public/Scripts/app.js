$(document).ready(function() {
	$('form').on('submit', function(event) {
		$(this).parents('.neos-modal').modal('hide');
		window.location.reload();
	});

	$('#create-new-form').click(function(event) {
		event.preventDefault();
		$($(this).attr('href')).modal();
	});
});