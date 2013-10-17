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
		$replies = $replies['retval'][0]['replies'];
		$i = 0;
		foreach ($replies as $item) {
	?>
	<div>
	<div class="left">
		<img src="<?php echo Uri::create("assets/img/s_1314188084.jpg") ?>" alt=""/><br />
		<span><a href="javascript:void(0)"><?php echo $item['username'] ?></a></span><br />
		better answers: <span id="<?php echo 'counter'.$i ?>"><?php echo $item['count_better'] ?></span>
	</div>
	<div class="right">
		<div><?php echo $item['content'] ?></div>
		<div class="q-date">
			<?php if($login_id == $question['questioner'] && $item['better_flag'] != 1) { ?>
				<button id="<?php echo 'better_'.$i ?>">Very good</button>
			<?php } ?>
			Date reply: <?php echo date('Y-m-d',$item['date']); ?>
		</div>
	</div>
	</div>
	<div class="clear"></div>
	<div class="line"></div>
	<?php $i++; } ?>
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
