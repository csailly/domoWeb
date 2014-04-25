<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/loginCheck.php'; ?>
<!DOCTYPE html>
<html>

<head>
		<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>
</head>

<body>

<?php

include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/navbar.php';

include_once $_SERVER ['DOCUMENT_ROOT'] . '/utils/CalendarUtils.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/DataService.php';
$dataService = new DataService ( $databaseConnexion );
$periodes = $dataService->getAllPeriodes ();
$currentPeriode = $dataService->getCurrentPeriode ();


?>



<div class="panel panel-primary">
  <!-- Default panel contents -->
  <div class="panel-heading"><h3 class="panel-title">Périodes</h3></div>

  <!-- Table -->
	<table class="table table-hover periodesTable">
		<thead>
			<tr>
				<th>#</th>
				<th>Jour</th>
				<th>Date de début</th>
				<th>Date de fin</th>
				<th>Heure de début</th>
				<th>Heure de fin</th>
				<th>Mode</th>
				<th><span class="glyphicon glyphicon-plus" style="cursor: pointer;"
					onclick="showCreatePeriodForm();"></span></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $periodes as $periode ) {
				$mode = $dataService->getModeById ( $periode->modeId );

				if ($currentPeriode != null && $currentPeriode->id == $periode->id) {
				?>
			<tr id="periodesRow_<?= $periode->id?>" style="font-weight: bold;">
				<?php 
				}else{
				?>
			<tr id="periodesRow_<?= $periode->id?>">	
				<?php } ?>
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
				<td><span class="glyphicon glyphicon-trash" style="cursor: pointer;"
					onclick="deletePeriod(<?= $periode->id?>)"></span> <span
					class="glyphicon glyphicon-pencil" style="cursor: pointer;"
					onclick="showUpdatePeriodForm(<?=$periode->id?>,<?php echo isset($periode->jour)? $periode->jour : -1 ?>,'<?=CalendarUtils::transformDate2($periode->dateDebut)?>','<?=CalendarUtils::transformDate2($periode->dateFin)?>','<?=$periode->heureDebut?>','<?=$periode->heureFin?>',<?=$periode->modeId?>);"></span>
				</td>
			</tr>
			<?php
			}
			?>


		</tbody>
	</table>
</div>

	<?php include  $_SERVER ['DOCUMENT_ROOT'] . '/view/periodeFormPopIn.php';?>

	<script>
			function deletePeriod(periodId)
			{
				$.ajax({
					type: "POST",
					url: "/service/DataWService.php",
					data: { periodId: periodId, action: "deletePeriod" }
				})
				.done(function(data){
					var decode = $.parseJSON(data);
					var result = decode.result;
					if (result === "error"){
						null;
					}else{
						$("tr[id=periodesRow_"+periodId+"]").remove();
					}													
				}
				);
			}	
	</script>

		<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/footer.php'; ?>
	


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


