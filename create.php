<?php include 'db.php'; ?>
<?php 
	if($_POST){
		
		$allFields = $_POST['field-type'];
		$allTitles = $_POST['title'];
		
		$q_field_names = $_POST['field-name'];
		$q_summarize = $_POST['summarize'];
		$q_type = $_POST['q_type'];
		$q_type_fields = $_POST['q_type_fields'];
		$q_chart = $_POST['q_chart'];
		$questionIndex = 0;
		
		$last_survey_id = $conn->query("SELECT survey_id FROM `fields` ORDER BY id DESC LIMIT 0 , 1" );
		$survey_id = $last_survey_id->fetch_assoc();
		if($survey_id['survey_id'] == ''){
			$survey_id = 1;
		}else{
			$survey_id = $survey_id['survey_id']+1;
		}
		
		
		foreach ($allFields as $index => $fieldType) {
			$id = $index+1;
			$sql = "INSERT INTO fields VALUES (
				'',
				'$survey_id',
				'$id',
				'$fieldType',
				'$allTitles[$index]'
			)";
			$conn->query($sql);

			if($fieldType == 'question'){
				$question_meta = "INSERT INTO questions_meta VALUES (
					'',
					'$survey_id',
					'$id',
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
?>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<link href="assets/styles.css" rel="stylesheet">
	
</head>
<body>
<div class="container">
	<h3 class="page-title">Survey Builder</h3>
	<button type="button" class="btn btn-primary pull-right" onclick="addField()">
		Add Question Field
	</button>
	<form action="" method="post">
		<ul class="survey-builder">
			<li class="row">
				<div class="col-md-12">
					<select class="form-control field-type" name="field-type[]" onchange="fieldTypeChange(this)">
						<option value="question">Question</option>
						<option value="instruction" selected>Instruction</option>
						<option value="explanation">Explanation</option>
						<option value="loaded">Loaded</option>
					</select>
				</div>
				<div class="field-type-tab">
					<div class="col-md-11">
						<textarea required name="title[]" placeholder="Enter Your Instruction Title .." class="inline-input instruction-field"></textarea>
					</div>
				</div>
				<div class="col-md-1">
					<div class="options-buttons">
						<button type="button" class="btn btn-danger btn-sm" type="button" onclick="deleteField(this)"><i class="glyphicon glyphicon-trash"></i></button>
						<button type="button" class="btn btn-success btn-sm" onclick="addField(this)"><i class="glyphicon glyphicon-plus"></i></button>
						<span class="handle ui-sortable-handle"><i class="glyphicon glyphicon-move"></i></span>
					</div>
				</div>
			</li>
			<li class="row">
				<div class="col-md-12">
					<select class="form-control field-type" name="field-type[]" onchange="fieldTypeChange(this)">
						<option value="question">Question</option>
						<option value="instruction">Instruction</option>
						<option value="explanation">Explanation</option>
						<option value="loaded">Loaded</option>
					</select>
				</div>
				<div class="field-type-tab">
					<div class="col-md-5">
						<input required placeholder="Enter Your Question?" class="inline-input" name="title[]">
						<input name="field-name[]" placeholder="Field Name" class="form-control inline-block field-name">
						<label class="inline-block label-gray">
							<input onchange="summarizeCheckbox(this)" type="checkbox"> Summarize
							<input type="hidden" name="summarize[]" value="0">
						</label>
					</div>
					<div class="col-md-3">
						<select class="form-control" required name="q_type[]" onchange="questionTypeChange(this)">
							<option disabled selected value="">Select question type</option>
							<option value="open-text">Open Text</option>
							<option value="radio">Radio Buttons</option>
							<option value="select">Select</option>
							<option value="checkbox">Checkboxes</option>
							<option value="short-text">Short Text</option>
						</select>
						<div class="q_type_fields">
							<select class="select2-tags form-control" multiple></select>
							<input name="q_type_fields[]" type="hidden">
						</div>
					</div>
					<div class="col-md-3 chart-options">
						<select class="form-control" name="q_chart[]">
							<option value="" selected>Select Option</option>
							<option value="pie-chart">Pie Chart</option>
							<option value="bar-chart">Bar/Column Chart</option>
							<option value="list-analysis">List Analysis</option>
							<option value="table-analysis">Table Analysis</option>
						</select>
					</div>
				</div>
				<div class="col-md-1">
					<div class="options-buttons">
						<button type="button" class="btn btn-danger btn-sm" type="button" onclick="deleteField(this)"><i class="glyphicon glyphicon-trash"></i></button>
						<button type="button" class="btn btn-success btn-sm" onclick="addField(this)"><i class="glyphicon glyphicon-plus"></i></button>
						<span class="handle ui-sortable-handle"><i class="glyphicon glyphicon-move"></i></span>
					</div>
				</div>
			</li>
		</ul>
		<button class="btn btn-success">Save and Build</button>
	</form>
</div></body>
	
<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="assets/scripts.js"></script>
	
</html>

<?php $conn->close(); ?>