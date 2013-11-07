<?php if(isset($reply)){ ?>
<div id="<?php echo $reply['date'] ?>">
	<div class="left">
		<img src="<?php echo Uri::create("assets/img/s_1314188084.jpg") ?>" alt=""/><br />
		<span><a href="javascript:void(0)"><?php echo $reply['username'] ?></a></span><br />
		good: <span name="<?php echo 'counter_'.$reply['by'] ?>"><?php echo $count_better ?></span>
	</div>
	<div class="right">
		<div><?php echo $reply['content'] ?></div>
		<div class="q-date">
			<?php if($show_button == true ) { ?>
				<button id="<?php echo 'better_'.$total_answer ?>" name="<?php echo 'btn_better_'.$reply['by'] ?>">Very good</button>
			<?php } ?>
			Date reply: <?php echo date('Y-m-d',$reply['date'])  ?>
			<span id="<?php echo 'better_img_'.$total_answer ?>"></span>
		</div>
		<br /><br />
		<div name="div_reply" id="<?php echo 'div_reply_'.$total_answer ?>" class="q-reply" style='display: none;'></div>
	</div>
</div>
<div class="clear"></div>
<div class="line"></div>
<script>
	$( document ).ready( function() {
		$("button[id^=better]").click(function(){
			set_better(this);
		});
	});
</script>
<?php } ?>
