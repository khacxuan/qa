<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<?php echo Asset::css('style.css'); ?>
		
		<?php echo Asset::js('jquery-1.9.1.min.js'); ?>
		<?php echo Asset::js('bootstrap.min.js'); ?>
		<?php echo Asset::js('jquery.simplePagination.js'); ?>
		<?php echo Asset::js('jquery-ui-1.10.0.custom.min.js'); ?>
		
		<?php
		//Add css local for evey page
		if (isset($js_file) and is_array($js_file)) {
			foreach ($js_file as $file) {
				if (\Fuel\Core\Asset::get_file($file, 'js')) {
					echo Asset::js($file);
				}
			}
		}
		?>
		<?php echo isset($script_file) ? render($script_file) : "" ?>
	</head>
	<body>
		<div id="wrapper">
			<div id="content">
				<?php echo $content; ?>
			</div>
		</div>
	</body>
</html>