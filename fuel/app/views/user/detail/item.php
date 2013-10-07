<?php if(isset($reply)){ ?>
<div id="r-item">
	<div class="left">
		<img src="<?php echo Uri::create("assets/img/s_1314188084.jpg") ?>" alt=""/><br />
		<span><a href="javascript:void(0)"><?php echo $reply['username'] ?></a></span>
	</div>
	<div class="right">
		<div><?php echo $reply['content'] ?></div>
		<div class="q-date">Date reply: <?php echo date('Y-m-d',$reply['date'])  ?></div>
	</div>
</div>
<div class="clear"></div>
<div class="line"></div>
<?php } ?>
