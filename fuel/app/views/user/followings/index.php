<h2>Followings</h2>
<?php 
	$entries = $followings['retval'];
	if(count($entries)){
?>
	<table id="table">
			<tr>
				<?php foreach ($entries as $item) { ?>
					<td style="width: 150px; text-align: center;" id="td<?php echo (string)$item['_id']; ?>">
						<img src="<?php echo Uri::create("assets/img/person.jpeg") ?>" alt="" />
						<br />
						<strong><?php echo $item["username"] ?></strong><br />
						<div id="follow<?php echo (string)$item['_id']; ?>">
							<button type="button" onClick="unfollow('<?php echo (string)$item['_id'];?>'); return false;">UnFollow</button>
						</div>
					</td>
				<?php } ?>
			</tr>
	</table>
<script type="text/javascript">
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
					$('#table #td' + id).fadeOut();
				}
			}
		});
	}
</script>
<?php }else{ ?>
	<span><?php echo Config::get('qa_no_data'); ?></span>
<?php } ?>
