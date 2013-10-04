<script src="<?php echo Asset::get_file('ckeditor.js', 'ckeditor')?>"></script>
<script src="<?php echo Asset::get_file('adapters/jquery.js', 'ckeditor')?>"></script>

<script>
		$( document ).ready( function() {
			
			CKEDITOR.disableAutoInline = true;
			$('#editor1').ckeditor();
			$('#loading').hide();
			
			$('#reply').click(function(){
				var content = CKEDITOR.instances.editor1.document.getBody().getChild(0).getText();
				if(validate(content)){
					$( '#loading' ).show();
					var url= '<?php echo Uri::create('ajax/user/add_reply'); ?>';
					var content = $('#editor1').val();
					var qid = $("#qid").val();
					$.post(url,{question_id: qid, content: content}, function(data) {                 					
						var response = JSON.parse(data);
						if(response.hasOwnProperty('err_msg')){
							if(0 == response.err_msg.length){
								$('#list-answer').append(response.new_reply);
								$("#r-item").fadeIn("slow");
								$('#editor1').val('');
							}else{
								showMessage(response.err_msg);
							}
							$( '#loading' ).hide();
						}
					});	
				}
				return false;
			});
			
			validate = function(content){
				$("#error").html('');
				if($.trim(content) == ""){
					showMessage("<?php echo Config::get('msg_err_not_input_reply') ?>");
					return false;
				}
				return true;
			}
			
			showMessage = function(msg){
				$("#error").html(msg);
				$("#error").fadeIn("slow");
			}
			
		});

</script>