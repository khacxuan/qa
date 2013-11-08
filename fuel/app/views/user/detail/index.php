<div class="question">
	<?php 
		$question = $replies['retval'][0]['question'];
		$bookmark = $replies['retval'][0]['bookmark'];
		if(count($question) > 0){ 
	?>
	<div class="left">
		<img src="<?php echo Uri::create("assets/img/s_1314188084.jpg") ?>" alt=""/><br />
		<span><a href="javascript:void(0)"><?php echo $question['username'] ?></a></span>
	</div>
	<div class="right">
		<h3><?php echo $question['question_title'] ?></h3>
		<div><?php echo $question['question_content'] ?></div>
		<div class="tag">
			<?php 
				$tags = $replies['retval'][0]['tag'];
				foreach ($tags as $item) {
			?>
			<a href="javascript:void(0)"><?php echo $item['name'] ?></a>
			<?php } ?>
		</div>
		<div class="q-date">
			<button id="bookmark"><?php echo $bookmark == '1' ? "Remove bookmark" : "Bookmark" ?> </button>
			Date post: <?php echo date('Y-m-d',$question['created_at'])  ?>
		</div>
	</div>
	<div class="clear"></div>
	<input type="hidden" id="qid" value="<?php echo $question['_id'] ?>" />
	<input type="hidden" id="email" value="<?php echo (isset($question['send_email']) && $question['send_email'] ==1 ? $question['email'] : '') ?>" />
	<?php } ?>
</div>
<div class="line"></div>
<div class="answer" id="list-answer">
	<?php 
		$reply = $replies['retval'][0]['replies'];
		$better_flag = $replies['retval'][0]['better_flag'];
		$i = 0;
		$questioner_reply = "";
		foreach ($reply as $item) {
	?>
	<div>
		<div class="left">
			<img src="<?php echo Uri::create("assets/img/s_1314188084.jpg") ?>" alt=""/><br />
			<span><a href="javascript:void(0)"><?php echo $item['username'] ?></a></span><br />
			good: <span name="<?php echo 'counter_'.$item['by'] ?>"><?php echo $item['count_better'] ?></span>
		</div>
		<div class="right">
			<div><?php echo $item['content'] ?></div>
			<div class="q-date">
				<?php if($login_id == $question['questioner'] && $item['better_flag'] != 1 && $better_flag != 1 ) { ?>
					<button id="<?php echo 'better_'.$i ?>" name="<?php echo 'btn_better_'.$item['by'] ?>">Very good</button>
				<?php } ?>
				Date reply: <?php echo date('Y-m-d',$item['date']); ?>
				<span id="<?php echo 'better_img_'.$i ?>">
					<?php if($item['better_flag'] == 1) { $questioner_reply = $item['questioner_reply']; ?>
						<img src="<?php echo Uri::create('assets/img/check.png') ?>" alt="" />
						<?php if($item['questioner_reply'] ==""){  ?>
							<a href="javascript:void(0)" id="<?php echo 'ureply_'.$i ?>" name="<?php echo 'btn_ureply_'.$item['by'] ?>">Reply</a>
						<?php } ?>
					<?php } ?>
				</span>
			</div>
			<br /><br />
			<div name="div_reply" id="<?php echo 'div_reply_'.$i ?>" class="q-reply" <?php echo $item['questioner_reply'] !="" ? "" : "style='display: none;'" ?> >
				<?php if($item['questioner_reply'] != ""){ echo $item['questioner_reply']; }?>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<div class="line"></div>
	<?php $i++; } ?>
	<input type="hidden" id="better_flag" value="<?php echo $better_flag ?>" />
</div>
<div id="loading" class="loading"><img src="<?php echo Uri::create('assets/img/loading.gif') ?>" alt="" /></div>
<div style="width: 645px;" id="i-content">
	<div>
		<textarea cols="80" id="editor1" name="content" rows="10"></textarea>
		<div id="error" style="display: none;"></div>
	</div><br />
	<div><button id="reply">Your answer</button></div>
	<br /><br /><br />
</div>
<div>The same question</div>
<?php 
	foreach ($same_qa['retval'] as $item) {
?>
<div><a href="<?php echo Uri::create('user/detail/'.$item['_id']) ?>"><?php echo $item['question_title'] ?></a></div>
<?php } ?>
