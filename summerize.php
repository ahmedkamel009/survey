<?php include 'db.php'; ?>
<?php 
	if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
		$survey_id = $_GET["id"];
		$survey = $conn->query("SELECT * FROM `fields` where field_type='question' and survey_id=".$_GET["id"] );
		if($survey->num_rows == 0){
			echo "Survey Not found, Please make sure the survey ID is correct!";
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
	<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script> 
</head>
<body>
	<div class="container">
		<div style="margin-bottom:35px; text-align:center">
			<h3 class="" style="font-size:28px">Summerize for survey <?php echo $_GET["id"]; ?></h3>
		</div>
<table class="table results-table">
<?php $q_count=1;
	while($row = $survey->fetch_assoc()) {?>
		<tr class="<?php echo $row['field_type']; ?>">
			<td style="">
				<h4><?php echo $q_count."- ".$row['title']; ?></h4>
			</td>
			<td>
			<?php 
				$question = "select summarize,chart,type_fields from questions_meta where survey_id=".$survey_id." and question_id=".$row['field_id'];
				$question = $conn->query($question);
				$question = $question->fetch_assoc();
			  	if($question['summarize'] == 1 and $question['chart'] != ""){
					$answers = "select answer,note from answers where survey_id=".$survey_id." and question_id=".$row['field_id'];
					$answers = $conn->query($answers);
					$answerIndex = 1;
					if($answers->num_rows > 0){
						if($question['chart'] == 'list-analysis'){ ?>
							<div>
								<?php while($answer = $answers->fetch_assoc()) { ?>
									<a href="#" class="list-group-item">
									  <h4 class="list-group-item-heading"> <?php echo $answer['answer']; ?></h4>
									  <p class="list-group-item-text"> <?php echo $answer['note']; ?></p>
									</a>
								<?php $answerIndex++; } ?>
							</div>
						<?php }else if($question['chart'] == 'table-analysis'){ ?>
							<table class="table table-bordered table-hover">
								<thead>
									<th>#</th>
									<th>Answer</th>
									<th>Note</th>
								</thead>
								<tbody>
									<?php while($answer = $answers->fetch_assoc()) { ?>
									<tr>
										<td><?php echo $answerIndex; ?></td>
										<td><?php echo $answer['answer']; ?></td>
										<td><?php echo $answer['note']; ?></td>
									</tr>
									<?php $answerIndex++; } ?>
								</tbody>
							</table>
						<!-- PIE AND BAR CHART START -->
						<?php }else if($question['chart'] == 'pie-chart' || $question['chart'] == 'bar-chart' ){ 
							$questionFields = explode(',', $question['type_fields']);
						?>
						  <div class="text-right">
							  <button class="btn btn-sm btn-primary add-compare-btn" onclick="addToCompare(this, <?php echo $row['id']; ?>)">Add to Compare</button>
						  </div>
				
						<?php 
							$ansStats = [];
							$x = 0;
							foreach($questionFields as $questionField){
								$count = $conn->query('select id from answers where survey_id='.$survey_id.' and question_id='.$row['field_id'].' and answer="'.$questionField.'"');
								$ansStats[$x]['label'] = $questionField;
								$ansStats[$x]['count'] = $count->num_rows;
								$x++;
						 	} 
							if($question['chart'] == 'pie-chart'){ ?>
								<script type="text/javascript"> 
									$(document).ready(function(){
										$("#chart<?php echo $row['field_id']; ?>").CanvasJSChart({ 
											legend :{ 
												verticalAlign: "center", 
												horizontalAlign: "right" 
											}, 
											data: [ 
											{ 
												type: "pie", 
												showInLegend: true, 
												toolTipContent: "{label} #{y} (#percent%)", 
												indexLabel: "{label} (#percent%)", 
												dataPoints: [ 
													<?php foreach($ansStats as $ansStat){
													?>
														{ label: "<?php echo $ansStat['label']; ?>",  y: <?php echo $ansStat['count']; ?>, legendText: "<?php echo $ansStat['label']; ?>"}, 
													<?php } ?>
												] 
											} 
											] 
										}); 
									})
								</script> 
							<?php } else{ ?>
								<script type="text/javascript"> 
									$(document).ready(function(){
										$("#chart<?php echo $row['field_id']; ?>").CanvasJSChart({ 
											data: [ 
											{ 
												type: "column", 
												showInLegend: false, 
												toolTipContent: "{label} #{y}", 
												indexLabel: "{label}", 
												dataPoints: [ 
													<?php foreach($ansStats as $ansStat){
													?>
														{ label: "<?php echo $ansStat['label']; ?>",  y: <?php echo $ansStat['count']; ?>}, 
													<?php } ?>
												] 
											} 
											] 
										}); 
									})
								</script> 								
							<?php }?>
							<div id="chart<?php echo $row['field_id']; ?>" style="height: 300px"></div> 
				
							<div class="panel-group" role="tablist" aria-multiselectable="true" style="margin-top:15px;">
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="headingOne">
										<h4 class="panel-title">
											<a role="button" data-toggle="collapse" href="#collapse<?php echo $row['field_id']; ?>" aria-expanded="true" aria-controls="collapseOne">Summery Table</a>
										</h4>
									</div>
									<div id="collapse<?php echo $row['field_id']; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
										<table class="table table-small table-bordered">
											<thead>
												<tr>
													<th>Answer</th>
													<th>Count</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($ansStats as $ansStat){ ?>
													<tr>
														<td>
															<?php echo $ansStat['label']; ?>
														</td>
														<td>
															<?php echo $ansStat['count']; ?>
														</td>
													</tr>
													<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>						
						<?php }
					}
				}
				?>
			</td>
		</tr>
	<?php $q_count++;}
?>
</table>
		  <div class="" style="position: fixed;bottom: 15px;right: 15px;">
				<a class="btn-success open-compare-btn disabled" onclick="openComparison(this)">
					 <span class="text1">Click here</span>
					 <span class="text2">Add 1 more chart</span>
				</a>		  
		 </div>
	 </div>
</body>	
	 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	 <script type="text/javascript">
		  var ids = [];

		  function addToCompare(el, id) {
				if ($(el).hasClass('disabled')) {
					 $(el).removeClass('disabled');
					 ids.splice(ids.indexOf(id), 1);
				} else {
					if(ids.length == 2){
						  alert('Only 2 charts a time can be merged!')
						  return false;
					 }

					 $(el).addClass('disabled')
					 $(".open-compare-btn").css('display', 'table-cell');
					 ids.push(id)
				}
				
				if (ids.length == 2) {
					 $(".open-compare-btn").removeClass('disabled');
				}else if(ids.length == 0){
					 $(".open-compare-btn").addClass('disabled');
					 $(".open-compare-btn").hide()
				}else if(ids.length == 1){
					 $(".open-compare-btn").addClass('disabled');
				}
				console.log(ids)
		  }
		  function openComparison(el){
				if(!$(el).hasClass('disabled')){
					 var url = "compare.php?ids="+ids.toString();
					 window.open(url, 'newwindow', 'width=700,height=500'); 
				}
		  }
	 </script>
</html>

<?php $conn->close(); ?>