<?php
use Fuel\Core\Config;
use Fuel\Core\Uri;
?>
<div id="follow">
	<?php if ($followed == FALSE) :?>
		<button type="button" onClick="follow('<?php echo (string)$userdetail['_id'];?>'); return false;">Follow</button></div>
	<?php else : ?>
		<button type="button" onClick="unfollow('<?php echo (string)$userdetail['_id'];?>'); return false;">UnFollow</button></div>
	<?php endif;?>
<br />
<h3>Total :</h3>
<table>
	<tr>
		<td>Email:</td>
		<td><?php echo $userdetail['email'];?></td>
	</tr>
	<tr>
		<td>Question Numbers:</td>
		<td><?php echo (isset($ques))?count($ques):0;?></td>
	</tr>
	<tr>
		<td>Answer Numbers:</td>
		<td><?php echo (isset($ans))?count($ans):0;?></td>
	</tr>
	<tr>
		<td>Tag Numbers:</td>
		<td><?php echo (isset($tags))?$tags:0;?></td>
	</tr>
</table>
<br />
<h3>List questions :</h3>
<?php if (isset($ques) && count($ques) > 0) : ?>
	<ul>
		<?php foreach ($ques as $k_q => $v_q) : ?>
				<li><a href="<?php echo Uri::create('user/detail').'/'.(string)$v_q['_id'];?>"><?php echo $v_q['question_content'];?></a></li>
		<?php endforeach;?>
	</ul>
<?php endif;?>
<br />
<h3>List answers :</h3>
<?php if (isset($ans) && count($ans) > 0) : ?>
	<ul>
		<?php foreach ($ans as $k_a => $v_a) : ?>
				<li><a href="<?php echo Uri::create('user/detail').'/'.$v_a['id'];?>"><?php echo $v_a['content'];?></a></li>
		<?php endforeach;?>
	</ul>
<?php endif;?>
<script type="text/javascript">
	function follow(id) {
		$.ajax({
			url:'<?php echo Uri::create('ajax/user/followUser'); ?>',
			type: 'POST',
			data: {'id': id},
			dataType: 'json',
			success: function(res) {
				if (res == false) {
					alert('<?php echo Config::get('msg_err')?>');
				}
				else {
					$('#follow').html('<button type="button" onClick="unfollow(\'<?php echo (string)$userdetail['_id'];?>\'); return false;">UnFollow</button></div>');
				}
			}
		});
	}
	function unfollow(id) {
		$.ajax({
			url:'<?php echo Uri::create('ajax/user/unFollowUser'); ?>',
			type: 'POST',
			data: {'id': id},
			dataType: 'json',
			success: function(res) {
				if (res == false) {
					alert('<?php echo Config::get('msg_err')?>');
				}
				else {
					$('#follow').html('<button type="button" onClick="follow(\'<?php echo (string)$userdetail['_id'];?>\'); return false;">Follow</button></div>');
				}
			}
		});
	}
</script>