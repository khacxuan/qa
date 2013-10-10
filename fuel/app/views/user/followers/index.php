<h2>Followers</h2>
<?php 
	$entries = $followers['retval'];
	if(count($entries)){
?>
	<table>
			<tr>
				<?php foreach ($entries as $item) { ?>
					<td style="width: 150px; text-align: center;">
						<img src="<?php echo Uri::create("assets/img/person.jpeg") ?>" alt="" />
						<br />
						<strong><?php echo $item["username"] ?></strong><br />
						<div id="follow<?php echo (string)$item['_id']; ?>">
						<?php if ($item['count_follow'] <=0 ) :?>
							<button type="button" onClick="follow('<?php echo (string)$item['_id'];?>'); return false;">Follow</button></div>
						<?php else : ?>
							<button type="button" onClick="unfollow('<?php echo (string)$item['_id'];?>'); return false;">UnFollow</button></div>
						<?php endif;?>
					</td>
				<?php } ?>
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
					$('#follow' + id).html('<button type="button" onClick="unfollow(\''+ id + '\'); return false;">UnFollow</button></div>');
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
					$('#follow' + id).html('<button type="button" onClick="follow(\''+ id + '\'); return false;">Follow</button></div>');
				}
			}
		});
	}
</script>
<?php }else{ ?>
	<span><?php echo Config::get('qa_no_data'); ?></span>
<?php } ?>
