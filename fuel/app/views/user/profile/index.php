<?php
use Fuel\Core\Form;
?>
<h2>Menu</h2>
<a href="<?php echo Uri::create("user/followers") ?>">Followers</a><br /><br />
<a href="<?php echo Uri::create("user/following") ?>">Following</a>
<br />
<?php echo (isset($msg))?$msg:'';?>
<?php echo Form::open(array('action' => 'user/profile/index', 'method' => 'post', 'id' => 'profile'));?>
<table style="text-align: left;">
	<tbody>
		<tr>
			<td>Name : </td>
			<td>
				<?php echo Form::input('name', $name, array('id' => 'name'));?><br />
				<font color="red"><?php echo (isset($error) && isset($error['name']))?$error['name']:'';?></font>
			</td>
		</tr>
		<tr>
			<td>Email : </td>
			<td>
				<?php echo Form::input('email', $email,array('id' => 'email'));?><br />
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
			<td>Confirm Password : </td>
			<td>
				<?php echo Form::input('confirmpassword','',array('type' => 'password', 'id' => 'confirmpassword'));?><br />
				<font color="red"><?php echo (isset($error) && isset($error['confirmpassword']))?$error['confirmpassword']:'';?></font>
			</td>
		</tr>
		<tr>
			<td>Tags</td><td>
				<?php echo Form::input('tags', ((isset($tags))?$tags:''),array('type' => 'text', 'id' => 'tags'));?><br />
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<?php echo Form::input('confirm','confirm', array('type' => 'submit'));?>
			</td>
		</tr>
	</tbody>
</table>
<?php echo Form::close();?>