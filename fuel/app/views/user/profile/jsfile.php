<?php
use Fuel\Core\Config;
?>
<script type="text/javascript">
	function messageRemote(value) {
		return jQuery.format('<?php echo Config::get('jsmyvalidation_unique');?>', 'Username', value);
	}
	$(document).ready(function(){
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