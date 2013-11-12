<h2>Answer List</h2>
<!-- search form -->
<form action="<?php echo uri::base() . 'admin/listanswer' ?>" method="get">
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
					<th>Option</th>
					<td>						
						<input type="radio" name="answer" value="-1" <?php if(!isset($_GET['answer'])||$_GET['answer']==-1) echo "checked"; ?>>All
						<input type="radio" name="answer" value="1" <?php if(isset($_GET['answer'])&&$_GET['answer']==1) echo "checked"; ?> >Answered
						<input type="radio" name="answer" value="0" <?php if(isset($_GET['answer'])&&$_GET['answer']==0) echo "checked"; ?>>Not answer						
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
		<tbody><tr>
				<th>Title</th>
				<th>Content</th>
				<th>Answer</th>
				<th>Date</th>
				<th>Action</th>
			</tr>
			<?php
			$data1 = $data['retval'][1];
			$data = $data['retval'][0];	

			$users = array();
			foreach ($data1 as $d){
				if(!empty($d)){								
					$users[$d['_id']->{'$id'}] = $d;
				}
			}

			foreach ($data as $dt) {
				?>
				<tr>
					<td><a href="<?php echo uri::base() . 'user/detail/' . $dt['qa']['_id']; ?>"><?php echo @$dt['qa']['question_title']; ?></a></td>
					<td>
						<?php echo @$dt['qa']['question_content']; ?>											
					</td>
					<td><?php if(isset($dt['qa']['answers']) && count($dt['qa']['answers']) > 0){
							$answers = $dt['qa']['answers'];
							$akeys = array_keys($answers);
							$n = count($akeys);
						?>						
						<table border="1">
							<tr>
								<th>Answer's Name</th>
								<th>Answer Content</th>
								<th>Answer Date</th>
								<th>Action</th>
							</tr>
							<?php for($i=0;$i<$n;$i++){?>
							<tr>
								<td><?php $name = @$users[$answers[$akeys[$i]]['by']->{'$id'}]['username'];
									if(empty($name))
										$name = @$users[$answers[$akeys[$i]]['by']->{'$id'}]['name'];
									echo $name;
								?></td>
								<td><?php echo $answers[$akeys[$i]]['content'];?></td>
								<td><?php echo ($answers[$akeys[$i]]['date'] != "") ? date('Y-m-d', $answers[$akeys[$i]]['date']) : ""; ?></td>
								<td align="center"><a href="javascript:void(0)" class="icon" id="adelete_<?php echo $answers[$akeys[$i]]['by'].'_'.$dt['qa']['_id'].'_'.$akeys[$i]?>">x</a></td>
							</tr>
							<?php }?>
						</table>
						<?php }?></td>
					<td><?php echo (@$dt['qa']['created_at'] != "") ? date('Y-m-d', @$dt['qa']['created_at']) : ""; ?></td>
					<td align="center"><a href="javascript:void(0)" class="icon" id="qadelete_<?php echo $dt['qa']['questioner'].'_'.$dt['qa']['_id']?>">x</a></td>
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
		var url_param = '<?php echo isset($url_param) ? $url_param : "?page=" ?>';
		var page_index = <?php echo isset($page_index) ? $page_index : 0 ?>;

		$('a[id^="adelete_"]').click(function() {
		    if(confirm('<?php echo Config::get('qa_confirm_delete') ?>')){
		    	window.location = "<?php echo uri::create('admin/listanswer/delete_answer/') ?>" + this.id + url_param + page_index;
			    return false;
		    }
		});
		
		$('a[id^="qadelete_"]').click(function() {		    
		    if(confirm('<?php echo Config::get('qa_confirm_delete') ?>')){
		    	window.location = "<?php echo uri::create('admin/listanswer/delete/') ?>" + this.id + url_param + page_index;
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

