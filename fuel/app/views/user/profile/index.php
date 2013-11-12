<?php
use Fuel\Core\Form;
?>
<h2>Menu</h2>
<a href="<?php echo Uri::create("user/followers") ?>">Followers</a><br /><br />
<a href="<?php echo Uri::create("user/following") ?>">Following</a>
<br />
<?php echo (isset($msg))?$msg:'';?>
<?php echo Form::open(array('action' => 'user/profile', 'method' => 'post', 'id' => 'profile'));?>
<table style="text-align: left;" border="1">
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
			<td>Registered Socials</td><td>
				<?php if (isset($social) && isset($social['registered']) && count($social['registered']) > 0) : ?>
					<?php foreach ($social['registered'] as $kr=>$vr) : ?>
						<div>
							<img alt="<?php echo $kr;?>" src="<?php echo Uri::base()?>assets/img/icon_<?php echo $kr;?>.png" />
							<a href="javascript:void(0);" onclick="deletesocial('<?php echo $kr;?>'); return false;">Delete</a>
						</div>
					<?php endforeach;?>
				<?php else:?>
					<div><?php echo Config::get('qa_no_data');?></div>
				<?php endif;?>
			</td>
		</tr>
		<tr>
			<td>Unregistered Socials</td><td>
				<?php if (isset($social)) : ?>
					<?php foreach ($social['unregistered'] as $ku=>$vu) : ?>
						<div>
							<a href="<?php echo Uri::create('user/profile/social/'.$ku);?>"><img alt="<?php echo $ku;?>" src="<?php echo Uri::base()?>assets/img/icon_<?php echo $ku;?>.png" /></a>
						</div>
					<?php endforeach;?>
				<?php endif;?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<?php echo Form::input('confirm','confirm', array('type' => 'hidden'));?>
				<?php echo Form::input('updateuser','confirm', array('type' => 'submit'));?>
			</td>
		</tr>
	</tbody>
</table>
<?php echo Form::close();?>
<!-- Stat modal -->
<div id="deleteConfirmModal" class="modal modal-small form hide fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4>削除の確認</h4>
	</div>
	<div class="modal-body">
		<div class="form-horizontal">
			<div class="control-group">
				<?php echo Config::get('msg_confirm_delete'); ?>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-main" data-dismiss="modal" aria-hidden="true">キャンセル</button>
		<button id="btnOK" class="btn btn-sub">削除する</button>
	</div>
</div>
<!-- End Stat modal -->
<script>

function deletesocial(type){
	$('#deleteConfirmModal').modal('show');
	$('#btnOK').unbind('click');
	$('#btnOK').click(function(){
		$('#deleteConfirmModal').modal('hide');
		window.location = '<?php echo Uri::create('user/profile/deletesocial/');?>'+type;
	});
	return false;
};

</script>