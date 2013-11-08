<?php
use Fuel\Core\Form;
?>
<?php echo (isset($err))?$err:'';?>
<?php echo Form::open(array('id' => 'login'));?>
<?php echo Form::hidden('url', Input::param('url','')); ?>
<table style="text-align: left;">
	<tbody>
		<tr>
			<td>Email : </td>
			<td>
				<?php echo Form::input('email', $email, array('id' => 'email'));?><br />
				<font color="red"><?php echo (isset($error) && isset($error['email']))?$error['email']:'';?></font>
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
				<a href="<?php echo Uri::create('user/register')?>">会員登録</a><br />
				<br />
				<br />
				<a href="<?php echo Uri::create('user/login/social/facebook');?>"><img alt="facebook" src="<?php echo Uri::base()?>assets/img/icon_facebook.png" /></a>
				<br />
				<a href="<?php echo Uri::create('user/login/social/twitter');?>"><img alt="twitter" src="<?php echo Uri::base()?>assets/img/icon_twitter.png" /></a>
				<br />
				<a href="<?php echo Uri::create('user/login/social/github');?>"><img alt="twitter" src="<?php echo Uri::base()?>assets/img/icon_github.png" /></a>
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
				email: {
					required: true
				},
				password: {
					required: true
				},
			},
		});
	});
</script>