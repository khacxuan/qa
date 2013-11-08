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
					var email = $("#email").val();
					var better_flag = $("#better_flag").val();
					$.post(url,{question_id: qid, content: content, better_flag: better_flag}, function(data) {                 					
						var response = JSON.parse(data);
						if(response.hasOwnProperty('err_msg')){
							if(0 == response.err_msg.length){ 
								$('#list-answer').append(response.new_reply);
								$('#editor1').val('');
								$('body, html').animate({ scrollTop: $("#" + response.date).offset().top }, 800);
								//send mail
								if(email != ""){
									url= '<?php echo Uri::create('ajax/user/sendmail'); ?>'; 
									$.post(url,{email: email, reply: content}, function() {});	
								}
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
			
			$("button[id^=better]").click(function(event){
				set_better(this);
				return false;
			});
			
			$("a[id^=ureply]").click(function(){
				set_better(this);
				return false;
			});
						
			set_better = function(obj){
				
				var url= '<?php echo Uri::create('ajax/user/set_better'); ?>';
				var qid = $("#qid").val(); 
				var id = obj.id.split('_');
				var by = obj.name.split('_'); 
				
				var index = -1;
				if(id[1] != null){
					index = id[1]; 
				}
				var html = '<br><br><textarea cols="80" id="questioner_reply" name="questioner_reply" rows="10"></textarea>';
				    html += '<button id="btn_reply_' + index + '" name="btn_reply_' + by[2] +'">save</button>';
				$('[name=div_reply]').html('');
				$('#div_reply_' + index).html(html);
				$('#questioner_reply').ckeditor();	
				$( "#btn_reply_" + index).bind( "click", send_questioner_reply);
				$('#div_reply_' + index).fadeIn("slow");
				
			}
			
			send_questioner_reply =  function(){
				var url= '<?php echo Uri::create('ajax/user/set_better'); ?>';
				var qid = $("#qid").val();
				var id = this.id.split('_'); 
				var by = this.name.split('_'); 
				var index = -1;
				var comment =CKEDITOR.instances.questioner_reply.document.getBody().getChild(0).getText();
				var content = $("#questioner_reply").val();
				
				if(id[2] != null){
					index = id[2]; 
				}
				$.post(url,{question_id: qid, index: index, comment: content}, function(data) {                 					
					var response = JSON.parse(data);
					if(response.hasOwnProperty('err_msg')){
						if(0 == response.err_msg.length){
							if(response.success == true){
								var v = $("[name=counter_" + by[2] + "]").first().text(); 
								if(v == "" || v == null){
									v = 0;
								}else{
									v = parseInt(v);
								}
								if($("[id^=ureply_]").length <= 0){
									v = v + 1;
								}
								$("[name=counter_" + by[2] + "]").text(v);
								$("button[name^=btn_better_]").remove();
								var c = '<img src="<?php echo Uri::create('assets/img/check.png') ?>" alt="" />';
								if($.trim(comment) == ""){
									c += '<a href="javascript:void(0)" id="ureply_' + index + '" name="btn_ureply_' + by[2]+ '">Reply</a>';
								}
								$("#better_img_" + index).html(c);
								$('#div_reply_' + index).hide();
								$('#div_reply_' + index).html(content);
								$('#div_reply_' + index).fadeIn("slow");
								if($.trim(comment) == ""){
									$( "#ureply_" + index).bind( "click", function(){ set_better(this) });
								}
							}
						}else{
							alert(response.err_msg);
						}
					}
				});	
				return false;
			}
			
		});

</script>