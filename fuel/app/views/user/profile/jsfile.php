<?php
use Fuel\Core\Config;
?>
<script type="text/javascript">
	function messageRemote(value) {
		return jQuery.format('<?php echo Config::get('jsmyvalidation_unique');?>', 'Username', value);
	}
	$(document).ready(function(){
		$('#tags').tagsInput({
			autocomplete_url:'<?php echo Uri::create('ajax/user/get_tags')?>',
			autocomplete:{
						source: function( request, response ) {
							var filter = request.term.toLowerCase();
							$.ajax({
								type: 'GET',
								url: '<?php echo Uri::create('ajax/user/get_tags')?>',
								dataType: 'json',
								success: function(data){
									response( $.grep(data, function(element) {
										return (element.toLowerCase().indexOf(filter) === 0);
									}));
								}
							});
						},
						messages: {
							noResults: '',
							results: function() {}
						}
					},
		});
		$("#profile").validate({
			errorPlacement: function(error, element) {
				error.appendTo( element.siblings("font") );
			},
			rules: {
				name: {
					required: true
				},
				email: {
					required: true,
					email: true
				},
				password: {
					minlength: 6
				},
				confirmpassword: {
					equalTo: '#password',
				},
			},
			messages: {
				confirmpassword: {
					equalTo: jQuery.format('<?php echo Config::get('jsmyvalidation_match');?>', 'Password', 'Confirm Password'),
				},
			}
		});
	});
</script>