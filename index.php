<?php include 'db.php'; ?>
<?php 
$surveys = $conn->query('select DISTINCT survey_id FROM fields')
?>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<link href="assets/styles.css" rel="stylesheet">
	
</head>
<body>
	<div class="container">
		<h3 class="page-title pull-left">All Surveys</h3>
		<a href="create.php" class="btn btn-success pull-right" style="margin-top: 22px;">Create new Survey</a>
		<table class="table ">
			<thead>
				<th>Name</th>
				<th>Actions</th>
			</thead>
			<tbody>
			<?php 
				while($row = $surveys->fetch_assoc()) { ?>
					<tr>
						<td>Survey <?php echo $row['survey_id']; ?></td>
						<td>
							<a class="btn btn-primary btn-sm" href="survey.php?id=<?php echo $row['survey_id']; ?>">View</a>
							<a class="btn btn-warning btn-sm" href="edit.php?id=<?php echo $row['survey_id']; ?>">Edit</a>
							<a class="btn btn-success btn-sm" href="summerize.php?id=<?php echo $row['survey_id']; ?>">Summerize</a>
						</td>
					</tr>
			<?php }?>
			</tbody>
		</table>
	</div>
</body>
	
<script src="https://code.jquery.com/jquery-3.1.1.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
</html>

<?php $conn->close(); ?>