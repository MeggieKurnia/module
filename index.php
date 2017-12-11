<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="vendor/components/bootstrap/css/bootstrap.min.css">
	<script src="vendor/components/jquery/jquery.min.js"></script>
   </head>
	<body>
		<div class="container" id="content">
			<form action="server.php" class="inline" method="POST">
				<div class="form-group">
				  <label for="usr">Project:</label>
				  <input type="text" name="project" value="<?php echo isset($_SESSION['project']) ? $_SESSION['project'] : '';?>" class="form-control">
				</div>
				<div class="form-group">
				  <label for="pwd">Dokument Root:</label>
				  <input type="text" name="module" value="<?php echo isset($_SESSION['module']) ? $_SESSION['module'] : $_SERVER['DOCUMENT_ROOT'];?>" class="form-control">
				</div>
				<div class="form-group">
				  <label for="pwd">Section:</label>
				  <input type="text" name="section" class="form-control">
				</div>
				<div class="form-group">
				  <label for="pwd">Table</label>
				  <input type="text" name="table" class="form-control">
				</div>
				<div class="form-inline" id="x">
					<div class="form-group">
					  <label for="pwd">Filed</label>
					  <input type="text" name="field[]" class="form-control">
					</div>
					<div class="form-group">
					  <label for="pwd">Type</label>
					  <select name="type[]" class="form-control" onchange="ch(this,'0')">
					  	<option value="text">text</option>
					  	<option value="textarea">textarea</option>
					  	<option value="ckeditor">ckeditor</option>
					  	<option value="file">file</option>
					  	<option value="template">Template</option>
					  </select>
					</div>
					<div class="form-group">
					  <label for="pwd">Multi Language</label>
					  <select name="multilang[]" class="form-control lang">
					  	<option value="true">true</option>
					  	<option value="false">false</option>
					  </select>
					</div>
				</div>
				<div id="apn">

				</div>
				<div class="form-group" style="margin-top:5px;">
				 	<button class="btn btn-success" type="button" id="add">Add</button>
				 	<button class="btn btn-danger" type="button" style="display:none;" id="rem">Remove</button>
				</div>

				<div class="form-group" style="margin-top:15px;">
				 	<button class="btn btn-primary" type="submit">Generate</button>
				</div>

			</form>
		</div>
		<script>
			$(document).ready(function(){
				var x = 1;
				$("#add").click(function(){
					var frm_inline = $(".form-inline").eq(0).clone();
					$(frm_inline).attr("id",x);
					$("#apn").append(frm_inline);
					var clone = $("#apn .form-inline").last();
					$(clone).find('[name="field[]"]').val('');
					$(clone).find('[name="field[]"]').focus();
					$("#rem").show();
					var l = $("#apn .form-inline .form-group").last();
					if(typeof $(l).attr('id') !== 'undefined'){
						$(l).remove();
					}
					$(frm_inline).find('.form-group').eq(1).find('select').attr("onchange","ch(this,'"+x+"')");
					x++;
				});

				$("#rem").click(function(){
					if($("#apn .form-inline").length){
						$("#apn .form-inline").last().remove();
					}
					var g = 0;
					$("#apn .form-inline").each(function(k,v){
						g++;
					});
					if(g == 0){
						$("#rem").hide();
					}
				});
			});

			function ch(t,i){
				var id = "";
				if(i == "0"){
					id = "x";
				}else{
					id = i;
				}
				if($(t).val() == "template"){
					var htm = "<div class='form-group' id='s_"+i+"'>";
					htm+="<label>Section</label>";
					htm+="<input type='text' name='tem_name[]' class='form-control'>";
					htm+="</div>";
					$("#"+id).append(htm);
				}else{
					$("#s_"+i).remove();
				}
			}
		</script>
	</body>
</html>