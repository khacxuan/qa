<div id="follow">
	<?php if ($followed == FALSE) :?>
		<button type="button" onClick="follow('<?php echo (string)$userdetail['_id'];?>'); return false;">Follow</button></div>
	<?php else : ?>
		Followed
	<?php endif;?>
<table>
	<tr>
		<td>Username:</td>
		<td><?php echo $userdetail['username'];?></td>
	</t>
	<tr>
		<td>Question Numbers:</td>
		<td><?php echo (!empty($countQA) && !empty($countQA['ques']))?$countQA['ques']:'0';?></td>
	</tr>
	<tr>
		<td>Answer Numbers:</td>
		<td><?php echo (!empty($countQA) && !empty($countQA['ans']))?$countQA['ans']:'0';?></td>
	</tr>
</table>
<script type="text/javascript">
	function follow(id) {
		$.ajax({
			url:'<?php echo Uri::create('ajax/user/followUser'); ?>',
			type: 'POST',
			data: {'id': id},
			dataType: 'json',
			success: function(res) {console.log(res);
				if (res == false) {
					alert('h');
				}
				else {
					$('#follow').text('Followed');
				}
			}
		});
	}
</script>