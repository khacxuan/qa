<?php
use Fuel\Core\Config;
?>
<script type="text/javascript">
	function messageRemote(value) {
		return jQuery.format('<?php echo Config::get('jsmyvalidation_unique');?>', 'Username', value);
	}
	$(document).ready(function(){
		$("#register").validate({
			errorPlacement: function(error, element) {
				error.appendTo( element.siblings("font") );
			},
			rules: {
				username: {
					required: true,
					remote: {
						url: '<?php echo Uri::create('ajax/user/checkExist'); ?>',
						type: 'post',
						data: {
							username: function() {
								username = $('#username').val();
								return $( '#username' ).val();
							}
						}
					},
				},
				password: {
					required: true,
					minlength: 6
				},
				confirmpassword: {
					equalTo: '#password',
				},
			},
			messages: {
				username: {
					remote: messageRemote
				},
				confirmpassword: {
					equalTo: jQuery.format('<?php echo Config::get('jsmyvalidation_match');?>', 'Password', 'Confirm Password'),
				},
			}
		});
	});
</script>