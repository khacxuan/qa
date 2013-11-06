<?php
use Fuel\Core\Config;
?>
<div id="follow">
	<?php if ($followed == FALSE) :?>
		<button type="button" onClick="follow('<?php echo (string)$userdetail['_id'];?>'); return false;">Follow</button></div>
	<?php else : ?>
		<button type="button" onClick="unfollow('<?php echo (string)$userdetail['_id'];?>'); return false;">UnFollow</button></div>
	<?php endif;?>
<table>
	<tr>
		<td>Username:</td>
		<td><?php echo $userdetail['username'];?></td>
	</tr>
	<tr>
		<td>Question Numbers:</td>
		<td><?php echo (!empty($countQA) && !empty($countQA['ques']))?$countQA['ques']:'0';?></td>
	</tr>
	<tr>
		<td>Answer Numbers:</td>
		<td><?php echo (!empty($countQA) && !empty($countQA['ans']))?$countQA['ans']:'0';?></td>
	</tr>
	<tr>
		<td>Tag Numbers:</td>
		<td><?php echo (!empty($countQA) && !empty($countQA['tags']))?$countQA['tags']:'0';?></td>
	</tr>
</table>
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