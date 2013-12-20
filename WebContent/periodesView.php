<!DOCTYPE html>
<html>
<head>
<title>Périodes</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Bootstrap -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet"
	href="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
<!-- Optional theme -->
<link rel="stylesheet"
	href="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css">
<!-- Date picker -->
<link rel="stylesheet" href="css/datepicker.css" />
<!-- Time picker -->
<link rel="stylesheet" href="css/bootstrap-timepicker.css" />

</head>

<?php
include_once '/config/config.ini';
include_once '/service/DataService.php';
include_once '/utils/CalendarUtils.php';

$dataService = new DataService($databaseConnexion);
$periodes = $dataService->getPeriodesList();

?>



<body>

	<table class="table table-hover">
		<thead>
			<tr>
				<th>#</th>
				<th>Jour</th>
				<th>Date de début</th>
				<th>Date de fin</th>
				<th>Heure de début</th>
				<th>Heure de fin</th>
				<th>Mode</th>
				<th>Supprimer</th>
				<th>
					<span class="glyphicon glyphicon-plus" onclick="showCreatePeriodForm();"></span>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($periodes as $periode) {
			$mode = $dataService->getModeById($periode->modeId);
			?>
			<tr id="periodesRow_<?= $periode->id?>">
				<td>
					<?=$periode->id?>
				</td>
				<td>
					<?=CalendarUtils::getDayLabel($periode->jour)?>
				</td>
				<td>
					<?=CalendarUtils::transformDate2($periode->dateDebut)?>
				</td>
				<td>
					<?=CalendarUtils::transformDate2($periode->dateFin)?>
				</td>
				<td>
					<?=$periode->heureDebut?>
				</td>
				<td>
					<?=$periode->heureFin?>
				</td>
				<td>
					<?=$mode->libelle?>
				</td>
				<td>
					<span class="glyphicon glyphicon-trash"
						onclick="deletePeriod(<?= $periode->id?>)"></span>
					<span class="glyphicon glyphicon-pencil" onclick="showUpdatePeriodForm(<?=$periode->id?>,<?=$periode->jour?>,'<?=CalendarUtils::transformDate2($periode->dateDebut)?>','<?=CalendarUtils::transformDate2($periode->dateFin)?>','<?=$periode->heureDebut?>','<?=$periode->heureFin?>',<?=$periode->modeId?>);"></span>
				</td>
			</tr>
			<?php
		}
		?>


		</tbody>
	</table>


	<?php include './periodeFormPopIn.php';?>

	<script>
			function deletePeriod(periodId)
			{
				$.ajax({
					type: "POST",
					url: "service/DataWService.php",
					data: { periodId: periodId, action: "deletePeriod" }
				})
				.done(function(data){
					//TODO #1 un caractère 3F est mis en tête de data ????? voir pourquoi
					var decode = $.parseJSON(data.substr(1));
					var result = decode.result;
					if (result === "error"){
					
					}else{
						$("tr[id=periodesRow_"+periodId+"]").remove();
					};													
				}
				);
			};	
		</script>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://code.jquery.com/jquery.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<!-- Latest compiled and minified JavaScript -->
	<script
		src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
	<!-- Date picker -->
	<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
	<script type="text/javascript"
		src="js/locales/bootstrap-datepicker.fr.js" charset="UTF-8"></script>
	<!-- Time picker -->
	<script type="text/javascript" src="js/bootstrap-timepicker.js"></script>

	<!-- Modal -->
	<script>
			var <?=$periodFormJsInstance?> = new GenericForm("<?=$periodFormId?>");
			$('#<?=$modalId?>').on('hidden.bs.modal', function (e) {
				<?=$periodFormJsInstance?>.clearForm();
			});				
	</script>

	<!-- Date picker -->
	<script>
			    $('#'+<?=$periodFormJsInstance?>.formId+'_datepicker').datepicker({
					language: "fr",
					autoclose: true,
					todayHighlight: true,
					clearBtn: true,
					beforeShowDay: function (date){
						var nowTemp = new Date();
						var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
						if (date.valueOf() < now.valueOf()){
							return false;
						}
					}
				});		
		</script>

	<!-- Time picker -->
	<script type="text/javascript">
			$('#'+<?=$periodFormJsInstance?>.formId+'_startHour').timepicker({
				minuteStep: 5,
				showMeridian: false,
				showInputs: false,
				defaultTime: false
			});
		 
			$('#'+<?=$periodFormJsInstance?>.formId+'_endHour').timepicker({
				minuteStep: 5,
				showMeridian: false,
				showInputs: false,
				defaultTime: false
			});		
		</script>
</body>
</html>
