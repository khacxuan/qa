<h2>User List</h2>
<!-- search form -->
<form action="<?php echo uri::base() . 'admin/listuser' ?>" method="get">
	<div class="search form">
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th>開始日</th>
					<td>
						<label><input class="w100p" id="key" name="key" value="<?php echo trim(Input::param('key', ''), " 　\t\n\r\0\x0B") ?>"></label>
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
<?php if ($data['ok'] == 1 && !empty($data['retval'])) { ?>
	<table class="table table-bordered table-data admin-com-index" border="1">
		<tbody>
			<tr>
				<th>User Name</th>
				<th>Name</th>
				<th>Date</th>
				<th>Email</th>
				<th>Banned</th>
			</tr>
			<?php
			$data = $data['retval'];
			foreach ($data as $dt) {
				?>
				<tr>
					<td><?php echo $dt['username']?></td>
					<td><?php echo @$dt['name']; ?></td>					
					<td><?php echo (@$dt['created_at'] != "") ? date('Y-m-d', @$dt['created_at']) : ""; ?></td>
					<td><?php echo @$dt['email']?></td>
					<td><?php echo Form::checkbox('banned[]', $dt['_id'], (isset($dt['banned']) && $dt['banned']==1)?true:false)?></td>
				</tr>
			<?php } ?>
		</tbody></table>
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
<?php } else { ?>
	<div class="message">該当データがありません。</div>
<?php } ?>


<script type="text/javascript">
	$(document).ready(function() {
		$('input[name="banned[]"]').change(function() {		   
		    var flag = 0;
		    if ($(this).is(':checked')) {
				flag = 1;
		    }
	
		    $.ajax({
			type: 'POST',
			datatype: 'json',
			data: {'id': this.value, 'flag':flag},
			url: "<?php echo Config::get('base_url') . "ajax/admin/update_banned" ?>",
			success: function(data) {
			    if (data.err_msg) {
					alert(data.err_msg);
			    }
			}						
		    });	
		});

		
		var url_param = '<?php echo isset($url_param) ? $url_param : "?page=" ?>';
		var page_index = <?php echo isset($page_index) ? $page_index : 0 ?>;
		
		$('a[id^="qadelete_"]').click(function() {
		    qa_id = this.id.split('_')[1];
		    if(confirm('<?php echo Config::get('qa_confirm_delete') ?>')){
		    	window.location = "<?php echo uri::create('admin/list/delete/') ?>" + qa_id + url_param + page_index;
			    return false;
		    }
		});
		
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

