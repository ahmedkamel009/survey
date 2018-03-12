<?php include 'db.php'; ?>
<?php 
	if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
		$survey_id = $_GET["id"];
		$survey = $conn->query("SELECT * FROM `fields` where survey_id=".$survey_id );
		if($survey->num_rows == 0){
			echo "Survey Not found, Please make sure the survey ID is correct!";
			exit;
		}
		if($_POST) {
			$notes = $_POST["note"];
			$index = 0;
			
			foreach ($_POST as $key => $value){
				if($key != 'note'){
					if(is_array($value)){
						$value = implode (",", $value);
					}
					
					if($value != '' or $notes[$index] != ''){
						$sql = "INSERT INTO answers VALUES (
							'',
							'$survey_id',
							'$key',
							'$value',
							'$notes[$index]'
						)";
						$conn->query($sql);
					}
					$index++;
				}
			} 
		}
	}else{
		echo "Error!";
		exit;
	}
?>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<link href="assets/styles.css" rel="stylesheet">
	
</head>
<body>
	<?php if($_POST) { ?>
		<div class="container mt-20"><div class="alert alert-success">Thank you for your submission.</div></div>
	<?php 
		  exit; 
	 } ?>
	<div class="container">
		<div style="margin-bottom:10px">
			<h3 class="" style="font-size:28px">Survey #<?php echo $_GET["id"]; ?></h3>
			<div class="text-right">
				<a href="/survey/summerize.php?id=<?php echo $_GET['id']; ?>" class="btn btn-primary ">
					Summerize page
				</a>
			</div>
		</div>

		<form action="" method="post">
<?php
	while($row = $survey->fetch_assoc()) { ?>
		<div class="row <?php echo $row['field_type']; ?>">
			<div class="col-md-<?php if($row['field_type'] == 'question'){echo '4';}else{echo '12';}; ?>">
				<label class="<?php echo $row['field_type']; ?>-title"><?php echo $row['title']; ?></label>
			</div>
			<?php if($row['field_type'] == 'question'){ ?>
				<div class="col-md-4">
					<?php $question = "select * from questions_meta where survey_id=".$survey_id." and question_id=".$row['field_id'];
					$question = $conn->query($question);
					$question = $question->fetch_assoc();
					$questionFields = explode(',', $question['type_fields']);
					if($question['type'] == "radio"){
						foreach($questionFields as $questionField){
							echo '<div class="radio"><label><input required value="'.$questionField.'" type="radio" name="'.$row['field_id'].'">'.$questionField.'</label></div>';
						}
					}else if($question['type'] == "open-text"){
						echo '<textarea required class="form-control" placeholder="Enter your answer" name="'.$row['field_id'].'"></textarea>';
					}else if($question['type'] == "short-text"){
						echo '<label><input required class="form-control" placeholder="Enter your answer" type="text" name="'.$row['field_id'].'"></label>';
					}else if($question['type'] == "checkbox"){
						foreach($questionFields as $questionField){
							echo '<div class="checkbox"><label><input required value="'.$questionField.'" type="checkbox" name="'.$row['field_id'].'[]">'.$questionField.'</label></div>';
						}
					}else if($question['type'] == "select"){
						echo '<select required class="form-control" name="'.$row['field_id'].'">';
						echo '<option value="" disabled selected>Select an Answer</option>';
						foreach($questionFields as $questionField){
							echo '<option>'.$questionField.'</option>';
						}
						echo "</select>";
					} ?>
				</div>
				<div class="col-md-4">
					<textarea class="form-control" placeholder="Note" name="note[]"></textarea>
				</div>
			<?php } ?>
		</div>
	<?php }
?>
			<button type="submit" class="btn btn-success">Submit Survey</button>
		</form>
	</div>
</body>
	
<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="assets/scripts.js"></script>
	
</html>

<?php $conn->close(); ?>