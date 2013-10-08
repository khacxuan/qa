<h2>List bookmark</h2>
<?php if(count($questions['retval']) > 0){  ?>
	<?php if(Session::get_flash('msg_remove_bookmark_sucess')){ ?>
		<span><?php echo Session::get_flash('msg_remove_bookmark_sucess') ?></span>
	<?php } ?>
	<table style="width: 50%">
		<tr>
			<th>Title</th>
			<th></th>
		</tr>
		<?php foreach ($questions['retval'] as $item) { ?>
			<tr>
				<td><a href="<?php echo Uri::create('user/detail/index/'.$item["_id"])  ?>"><?php echo $item["question_title"] ?></a></td>
				<td><a href="<?php echo Uri::create('user/bookmark/remove/').$item["_id"]  ?>" onclick="return confirm('<?php echo Config::get('msg_confirm_delete') ?>');">Remove bookmark</a></td>
			</tr>	
		<?php } ?>
		
	</table>
<?php }else{ ?>
	<div><?php echo Config::get('qa_no_data') ?></div>
<?php } ?>