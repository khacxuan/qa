<div class="search-filter">
	<?php if ($num > 0) : ?>
	<ul class="news-horizontal">
		<?php foreach ($list as $v) : ?>
			<li><a href="<?php echo Uri::create('user/userdetail/index/'.(string)$v['_id']); ?>"><?php echo (empty($v['name']))?'Nodata':$v['name'];?></a></li>
		<?php endforeach; ?>
	</ul>
	<div class="pagination-holder">
		<div class="pagination"></div>
	</div>
	<script>
		var total_record = <?php echo isset($total_record) ? $total_record : 0; ?>;
		var itemonpage = <?php echo isset($total_record_page) ? $total_record_page : 0; ?>;
		if (total_record > 0 && total_record > itemonpage){
			$('.pagination').pagination({
				items: <?php echo isset($total_record) ? $total_record : 0; ?>,
				itemsOnPage: itemonpage,
				displayedPages : <?php echo isset($display_page) ? $display_page : 0; ?>,
				currentPage:<?php echo isset($page_index) ? $page_index : 0; ?>,
				prevText: '<<',
				nextText: '>>',
				hrefText:'<?php echo Uri::create('user/listuser/?'); ?>page=',
				onPageClick: function(pageNumber) {
				}
			});
		}
	</script>
	<?php else :?>
		<div><?php echo Config::get('qa_no_data');?></div>
	<?php endif; ?>
</div>