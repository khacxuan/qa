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
				<button  name="<?php echo 'btn_better_'.$reply['by'] ?>">Very good</button>
			<?php } ?>
			Date reply: <?php echo date('Y-m-d',$reply['date'])  ?>
		</div>
	</div>
</div>
<div class="clear"></div>
<div class="line"></div>
<?php } ?>
