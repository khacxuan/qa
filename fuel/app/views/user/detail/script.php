<script src="<?php echo Asset::get_file('ckeditor.js', 'ckeditor')?>"></script>
<script src="<?php echo Asset::get_file('adapters/jquery.js', 'ckeditor')?>"></script>

<script>
		$( document ).ready( function() {
			
			CKEDITOR.disableAutoInline = true;
			$('#editor1').ckeditor();
			$('#loading').hide();
			
	        jQuery(window).bind(
			    "beforeunload", 
			    function() { 
			    	var content = CKEDITOR.instances.editor1.document.getBody().getChild(0).getText();
			    	if($.trim(content) != ""){
			    		return true;
			    	}
			    }
			)
			
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
								$('#editor1').val('');
								$('body, html').animate({ scrollTop: $("#" + response.date).offset().top }, 800);
							}else{
								showMessage(response.err_msg);
							}
							$( '#loading' ).hide();
						}
					});	
				}
				return false;
			});
			
			$('#bookmark').click(function(){
					var url= '<?php echo Uri::create('ajax/user/bookmark'); ?>';
					var qid = $("#qid").val();
					$.post(url,{question_id: qid}, function(data) {                 					
						var response = JSON.parse(data);
						if(response.hasOwnProperty('err_msg')){
							if(0 == response.err_msg.length){
								if(response.flag == 0){
									$('#bookmark').html('Bookmark');
								}else{
									$('#bookmark').html('Remove bookmark');
								}
							}else{
								alert(response.err_msg);
							}
						}
					});	
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