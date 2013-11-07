<h2>Tag List</h2>
<!-- search form -->
<form action="<?php echo uri::base().'user/taglist'?>" method="get">
	<div class="search form">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th>Tag name</th>
					<td>
						<label><input class="w100p" id="key" name="key" value="<?php echo trim(Input::param('key','')," 　\t\n\r\0\x0B") ?>"></label>
					</td>
					
				</tr>
				<tr>					
					<td colspan="2">
						<button class="btn medium primary"><i class="icon-search"></i> 検索する</button>
					</td>

				</tr>
			</tbody></table>
	</div>
</form>
<!-- END search form -->
<?php if (count($tags)>0) {  ?>
	<table class="table table-bordered table-data admin-com-index" border="1">
		<tbody><tr>				
				<th>Tag Name</th>				
			</tr>
			<?php			
			foreach ($tags as $tag) { ?>
				<tr>					
					<td><a href='<?php echo uri::base().'user/tag/'.$tag['tag']['_id']; ?>'><?php  echo $tag['tag']['name'].'('.$tag['count_qa'].')' ?></a></td>					
				</tr>
			<?php } ?>
		</tbody></table>
<?php } else { ?>
	<div class="message">該当データがありません。</div>
<?php } ?>

<div class="page-nav row">
	<div class="three columns">
		<?php if (isset($total_record) and $total_record > 0): ?>
			<?php echo isset($page_from) ? $page_from : '0' ?> ∼ <?php echo isset($page_to) ? $page_to : '0' ?>/<?php echo isset($students_count) ? $students_count : '0' ?> 件
		<?php endif; ?>
	</div>
	<?php if ($total_record > $total_record_page) { ?>
		<div class="pagination-holder nine columns" id="pagination_code">
		</div>
	<?php } ?>
</div>
<script type="text/javascript">
	$(document).ready(function() {

		var url_new='<?php echo isset($url_param) ? $url_param : "?page=" ?>';
		var total_record =<?php echo isset($total_record) ? $total_record : 0 ?>;
		if (total_record>0){
			$('#pagination_code').pagination({
				items: <?php echo isset($total_record) ? $total_record : 0 ?>,
				itemsOnPage: <?php echo isset($total_record_page) ? $total_record_page : 0 ?>,
				displayedPages : <?php echo isset($display_page) ? $display_page : 0 ?>,
				currentPage:<?php echo isset($page_index) ? $page_index : 0 ?>,
				prevText: '前のページ',
				nextText: '次のページ',
				hrefText:url_new,
				//cssStyle: 'light-theme',
				onPageClick: function(pageNumber) {
					//window.location.reload(true);
					//window.location='<?php echo uri::create("com/studentviewall/index") ?>';
				}
			});
		}
	});
</script>

