<?php include 'db.php'; ?>
<?php 
	if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
		$survey_id = $_GET["id"];
		$survey = $conn->query("SELECT * FROM `fields` where survey_id=".$_GET["id"] );
		if($survey->num_rows == 0){
			echo "Survey Not found, Please make sure the survey ID is correct!";
			exit;
		}
		if($_POST){
			$conn->query("delete FROM fields WHERE survey_id=".$survey_id." limit ".$survey->num_rows);
			$conn->query("delete FROM questions_meta WHERE survey_id=".$survey_id." limit ".$survey->num_rows);

			$fields_ids = $_POST['field-id'];
			$fields_types = $_POST['field-type'];
			$all_titles = $_POST['title'];
			$q_field_names = $_POST['field-name'];
			$q_summarize = $_POST['summarize'];
			$q_type = $_POST['q_type'];
			$q_type_fields = $_POST['q_type_fields'];
			$q_chart = $_POST['q_chart'];
			$questionIndex = 0;
			foreach ($fields_types as $index => $fieldType) {
				if($fields_ids[$index] == ''){
					$field_id = mt_rand(1000, 9999);
				}else{
					$field_id = $fields_ids[$index];
				}
				$sql = "INSERT INTO fields VALUES (
					'',
					'$survey_id',
					'$field_id',
					'$fieldType',
					'$all_titles[$index]'
				)";
				$conn->query($sql);

				if($fieldType == 'question'){
					$question_meta = "INSERT INTO questions_meta VALUES (
						'',
						'$survey_id',
						'$field_id',
						'$q_field_names[$questionIndex]',
						'$q_summarize[$questionIndex]',
						'$q_type[$questionIndex]',
						'$q_type_fields[$questionIndex]',
						'$q_chart[$questionIndex]'
					)";
					$conn->query($question_meta);

					$questionIndex++;
				}
			}
			header('Location: /survey.php?id='.$survey_id);
			exit;
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
	<div class="container">
		<div style="margin-bottom:20px">
			<h3 class="" style="font-size:28px">Edit Survey <?php echo $_GET["id"]; ?></h3>
		</div>

<form action="" method="post">
<ul class="survey-builder">
	<?php
	while($row = $survey->fetch_assoc()) { ?>
		<li class="row">
			<input type="hidden" name="field-id[]" value="<?php echo $row['field_id']; ?>">
			<div class="col-md-12">
				<select class="form-control field-type" name="field-type[]" onchange="fieldTypeChange(this)">
					<option value="question" <?php if($row['field_type'] == 'question'){echo 'selected';} ?> >Question</option>
					<option value="instruction" <?php if($row['field_type'] == 'instruction'){echo 'selected';} ?> >Instruction</option>
					<option value="explanation" <?php if($row['field_type'] == 'explanation'){echo 'selected';} ?> >Explanation</option>
					<option value="loaded" <?php if($row['field_type'] == 'loaded'){echo 'selected';} ?> >Loaded</option>
				</select>
			</div>
			<div class="field-type-tab">
			<?php if($row['field_type'] == 'instruction'){ ?>
				<div class="col-md-11">
					<textarea required name="title[]" placeholder="Enter Your Instruction Title .." class="inline-input instruction-field"><?php echo $row['title']; ?></textarea>
				</div>
			<?php }else if($row['field_type'] == 'explanation'){ ?>
				<div class="col-md-11">
					<textarea required name="title[]" placeholder="Enter Your Explanation Title .." class="inline-input expanation-field"><?php echo $row['title']; ?></textarea>
				</div>
			<?php }else if($row['field_type'] == 'question'){ ?>
				<?php 
				 	$question = "select * from questions_meta where survey_id=".$survey_id." and question_id=".$row['field_id'];
					$question = $conn->query($question);
					$question = $question->fetch_assoc();
					$questionFields = explode(',', $question['type_fields']);
				?>
				<div class="col-md-5">
					<input required placeholder="Enter Your Question?" class="inline-input" name="title[]" value="<?php echo $row['title']; ?>">
					<input name="field-name[]" placeholder="Field Name" class="form-control inline-block field-name" value="<?php echo $question['field_name']; ?>">
					<label class="inline-block label-gray">
						<input onchange="summarizeCheckbox(this)" type="checkbox" <?php if($question['summarize'] == 1){echo 'checked';} ?> > Summarize
						<input type="hidden" name="summarize[]" value="<?php echo $question['summarize']; ?>">
					</label>
				</div>
				<div class="col-md-3">
					<select class="form-control" required name="q_type[]" onchange="questionTypeChange(this)">
						<option value="" disabled>Select question type</option>
						<option value="open-text" <?php if($question['type'] == 'open-text'){echo 'selected';} ?>>Open Text</option>
						<option value="radio" <?php if($question['type'] == 'radio'){echo 'selected';} ?> >Radio Buttons</option>
						<option value="select" <?php if($question['type'] == 'select'){echo 'selected';} ?> >Select</option>
						<option value="checkbox" <?php if($question['type'] == 'checkbox'){echo 'selected';} ?> >Checkboxes</option>
						<option value="short-text" <?php if($question['type'] == 'short-text'){echo 'selected';} ?> >Short Text</option>
					</select>
					<div class="q_type_fields" style="<?php if(in_array($question['type'], ['radio', 'select', 'checkbox'])){echo 'display:block';} ?>">
						<select class="select2-tags form-control" multiple>
							<?php 
								foreach($questionFields as $questionField){
									echo '<option value="'.$questionField.'" selected>'.$questionField.'</option>';
								}
							?>
						</select>
						<input name="q_type_fields[]" type="hidden" value="<?php echo $question['type_fields']; ?>">
					</div>
				</div>
				<div class="col-md-3 chart-options">
					<select class="form-control" name="q_chart[]" style="<?php if($question['summarize'] == 1){echo 'display:block';} ?>">
						<option value="">Select Option</option>
						<option value="pie-chart" <?php if($question['chart'] == 'pie-chart'){echo 'selected';} ?> >Pie Chart</option>
						<option value="bar-chart" <?php if($question['chart'] == 'bar-chart'){echo 'selected';} ?> >Bar/Column Chart</option>
						<option value="list-analysis" <?php if($question['chart'] == 'list-analysis'){echo 'selected';} ?> >List Analysis</option>
						<option value="table-analysis" <?php if($question['chart'] == 'table-analysis'){echo 'selected';} ?> >Table Analysis</option>
					</select>
				</div>
				<?php }else if($row['field_type'] == 'loaded'){ ?>
				<div class="col-md-11">
					<textarea required name="title[]" placeholder="Enter Your Loaded Title .." class="inline-input"><?php echo $row['title']; ?></textarea>
				</div>
			<?php } ?>
			</div>
			<div class="col-md-1">
				<div class="options-buttons">
					<button type="button" class="btn btn-danger btn-sm" type="button" onclick="deleteField(this)"><i class="glyphicon glyphicon-trash"></i></button>
					<button type="button" class="btn btn-success btn-sm" onclick="addField(this)"><i class="glyphicon glyphicon-plus"></i></button>
					<span class="handle ui-sortable-handle"><i class="glyphicon glyphicon-move"></i></span>
				</div>
			</div>
		</li>
	<?php }
	?>
</ul>
	<button type="submit" class="btn btn-success">Update Survey</button>
</form>
	</div>
</body>
	
<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="assets/scripts.js"></script>
	
</html>

<?php $conn->close(); ?>