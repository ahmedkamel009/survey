<?php include 'db.php'; ?>
<?php 
	if(isset($_GET["ids"]) && !empty(trim($_GET["ids"]))){
		$questions_ids = explode(",", $_GET["ids"]);
		$reversed_ids = array_reverse($questions_ids);
		
		$survey_1 = $conn->query('select survey_id,field_id from fields where id='.$questions_ids[0]);
		$survey_2 = $conn->query('select survey_id,field_id from fields where id='.$questions_ids[1]);
		
		if($survey_1->num_rows > 0 && $survey_2->num_rows > 0 ){
			$survey1_d = $survey_1->fetch_assoc();
			$survey2_d = $survey_2->fetch_assoc();
			
			$survey1_answers = $conn->query('select answer from answers where survey_id='.$survey1_d['survey_id'].' and question_id='.$survey1_d['field_id']);
			$survey2_answers = $conn->query('select answer from answers where survey_id='.$survey2_d['survey_id'].' and question_id='.$survey2_d['field_id']);
			
			if($survey1_answers->num_rows > $survey2_answers->num_rows ){
				$xArr = $survey2_answers;
				$yArr = $survey1_answers;
			}else{
				$xArr = $survey1_answers;
				$yArr = $survey2_answers;
			}
						
			$survey_data = [];
			$survey1_data = [];
			$survey2_data = [];
			$index = 0;

			while($row = $xArr->fetch_assoc()){
				$survey_data[][] = $row['answer'];
				$survey1_data[] = $row['answer'];
			}
			
			while($row = $yArr->fetch_assoc()){
				$survey_data[$index][] = $row['answer'];
				$survey2_data[] = $row['answer'];
				$index++;
			}
			
			/*foreach(array_unique($survey2_data) as $y=>$s2){
				echo $s2."<br>";
				foreach(array_unique($survey1_data) as $x=>$s1){
					$count = 0;
					for ($i = 0; $i < count($survey_data); $i++) {
						if($survey_data[$i][0] == $s1 && $survey_data[$i][1] == $s2 ) {
							 $count++;
						}
					}					
					echo $count.$s1."<br>";
				}
			}*/

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
		<div class="text-right" style="margin:10px 0;">
			<a href="?ids=<?php echo implode(',', $reversed_ids); ?>" class="btn btn-primary">Reverse</a>
		</div>
		<script type="text/javascript">
		window.onload = function () {
			var chart = new CanvasJS.Chart("chartContainer",
			{
			  legend:{
				fontSize: 20,
				fontFamily: "tamoha",
				fontColor: "Sienna"      
			  },
			  data: [
				<?php 
				foreach(array_unique($survey2_data) as $y=>$s2){ ?>
				{
					type: "spline",
					indexLabel: "{y}",
					showInLegend: true, 
				    toolTipContent: "<?php echo $s2; ?> : {label}",                
					legendText: "<?php echo $s2; ?>",
					dataPoints:  [
					<?php foreach(array_unique($survey1_data) as $x=>$s1){ 
						$count = 0;	
						for ($i = 0; $i < count($survey_data); $i++) {
							if($survey_data[$i][0] == $s1 && $survey_data[$i][1] == $s2 ) {$count++;}
						}					
					?>
						{"y": <?php echo $count; ?>, "label": "<?php echo $s1;  ?>" },
					<?php } ?>
					]
				},
				<?php } ?>
			  ]
			});

			chart.render();
		}
		</script>
		<div id="chartContainer" style="height: 300px"></div> 
	 </div>
</body>	
</html>

<?php $conn->close(); ?>