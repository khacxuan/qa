<table border="1">
<tr>
	<td>My Questions</td>
	<td>Answers</td>	
</tr>
<?php foreach($iret as $iq){
	$na = isset($iq['answers'])?count($iq['answers']):0;	
?>
<tr>
	<td <?php if($na>0) echo "rowspan='$na'"?>><h1><?php echo $iq['question_title']?></h1><br/><?php echo $iq['question_content']?></td>
	<td><?php echo $na>0?$iq['answers'][0]['content']:''?></td>		
</tr>

<?php for($i=1;$i<$na;$i++){?>
<tr>	
	<td><?php echo $iq['answers'][$i]['content']?></td>	
</tr>
<?php }}?>
</table>

<br/>

<table border="1">
<tr>
	<td>Following</td><td>Questions</td>	
</tr>
<?php foreach($fret as $fq){
	$na = count($fq['qa']);
?>
<tr>
	<td <?php if($na>0) echo "rowspan='$na'"?>><?php echo $fq['fid']['username']?></td>
	<td><h1><?php echo $na>0?@$fq['qa'][0]['question_title']:''?></h1><br/><?php echo @$fq['qa'][0]['question_content']?></td>			
</tr>
<?php for($i=1;$i<$na;$i++){?>
<tr>	
	<td><h1><?php echo @$fq['qa'][$i]['question_title']?></h1><br/><?php echo @$fq['qa'][$i]['question_content']?></td>	
</tr>
<?php }}?>
</table>