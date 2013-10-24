<?php
use Fuel\Core\Form;
?>
<?php echo (isset($err))?$err:'';?>
<?php echo Form::open(array('id' => 'login'));?>
<?php echo Form::hidden('url', Input::param('url','')); ?>
<table style="text-align: left;">
	<tbody>
		<tr>
			<td>Username : </td>
			<td>
				<?php echo Form::input('username', $username, array('id' => 'username'));?><br />
				<font color="red"><?php echo (isset($error) && isset($error['username']))?$error['username']:'';?></font>
			</td>
		</tr>
		<tr>
			<td>Password : </td>
			<td>
				<?php echo Form::input('password','',array('type' => 'password', 'id' => 'password'));?><br />
				<font color="red"><?php echo (isset($error) && isset($error['password']))?$error['password']:'';?></font>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<?php echo Form::input('confirm','confirm', array('type' => 'submit'));?><br />				
			</td>
		</tr>
	</tbody>
</table>
<?php echo Form::close();?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#login").validate({
			errorPlacement: function(error, element) {
				error.appendTo( element.siblings("font") );
			},
			rules: {
				username: {
					required: true
				},
				password: {
					required: true
				},
			},
		});
	});
</script>