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
	<?php } ?>
</div>
<div class="line"></div>
<div class="answer" id="list-answer">
	<?php 
		$replies = $replies['retval'][0]['replies'];
		foreach ($replies as $item) {
	?>
	<div>
	<div class="left">
		<img src="<?php echo Uri::create("assets/img/s_1314188084.jpg") ?>" alt=""/><br />
		<span><a href="javascript:void(0)"><?php echo $item['username'] ?></a></span>
	</div>
	<div class="right">
		<div><?php echo $item['content'] ?></div>
		<div class="q-date">Date reply: <?php echo date('Y-m-d',$item['date'])  ?></div>
	</div>
	</div>
	<div class="clear"></div>
	<div class="line"></div>
	<?php } ?>
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
