<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo Asset::get_file('js/tag-it.js', 'tag-it')?>" type="text/javascript" charset="utf-8"></script>

<script src="<?php echo Asset::get_file('ckeditor.js', 'ckeditor')?>"></script>
<script src="<?php echo Asset::get_file('adapters/jquery.js', 'ckeditor')?>"></script>

<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
<link href="<?php echo Asset::get_file('css/jquery.tagit.css', 'tag-it')?>" rel="stylesheet" type="text/css">
<script>

		CKEDITOR.disableAutoInline = true;

		$( document ).ready( function() {
			 $('#form_taglist').val('');
			$( '#editor1' ).ckeditor(); // Use CKEDITOR.replace() if element is <textarea>.
			//$( '#editable' ).ckeditor(); // Use CKEDITOR.inline().

			$("#myTags").tagit({
				autocomplete: { source: function( request, response ) {
					// var filter = request.term.toLowerCase();
					var filter = request.term.toLowerCase();
					$.ajax({
						type: "GET",
						url: "<?php echo Uri::create('ajax/user/get_tags')?>" + '/' + request.term,
						dataType: "json",
						success: function(data){
							response( $.grep(data, function(element) {
								// Only match autocomplete options that begin with the search term.
								// (Case insensitive.)
								return (element.toLowerCase().indexOf(filter) === 0);
							}));
						}
					});
				}},

				afterTagAdded: function(event, ui) {
					// do something special
				    //console.log(ui.tagLabel);
				    names = [];
				    if($('#form_taglist').val() != ''){
				    	names = $('#form_taglist').val().split(',');
				    }
				    names.push(ui.tagLabel);
				    $('#form_taglist').val(names.join(','));
				    console.log($('#form_taglist').val());			    
				},

				afterTagRemoved: function(event, ui) {
					// do something special
					names = [];
				    if($('#form_taglist').val() != ''){
				    	names = $('#form_taglist').val().split(',');
				    }
				    pos = names.indexOf(ui.tagLabel);
					if(pos > -1){				    
				    	names.splice(pos, 1);
					}
				    $('#form_taglist').val(names.join(','));
				    console.log($('#form_taglist').val());
				},
			});
		});

</script>

<?php echo Form::open(array('action' => 'user/newqa', 'method' => 'post')); ?>
	<?php echo Form::hidden('taglist', '')?>
	<table>
		<tr>
			<td>Title</td>
			<td>
				<input type='text' name='title' width="500px"></input>
				<?php if(isset($error['title'])){ ?>
					<br/>
					<span class="error"><?php echo $error['title'] ?></span>
				<?php } ?>		
			</td>
		</tr>
		<tr>
			<td>Content</td>
			<td>
				<textarea cols="80" id="editor1" name="content" rows="10">
				</textarea>
				<?php if(isset($error['content'])){ ?>
					<span class="error"><?php echo $error['content'] ?></span>
				<?php } ?>
			</td>
		</tr>		
		<tr>		
			<td>Tags</td><td>		
				<ul id="myTags">
			    
			    </ul>		
			</td>	    
		</tr>
		<tr><td colspan="2"><input type="submit" value="Post Your Question"></input></td></tr>
	</table>
<?php echo Form::close()?>