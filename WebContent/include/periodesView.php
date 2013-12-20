<?php

include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/DataService.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/utils/CalendarUtils.php';

$dataService = new DataService($databaseConnexion);
$periodes = $dataService->getPeriodesList();

?>




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
				<th><span class="glyphicon glyphicon-plus" style="cursor: pointer;" onclick="showCreatePeriodForm();"></span></th>
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
					<span class="glyphicon glyphicon-trash" style="cursor: pointer;"
						onclick="deletePeriod(<?= $periode->id?>)" ></span>
					<span class="glyphicon glyphicon-pencil" style="cursor: pointer;" onclick="showUpdatePeriodForm(<?=$periode->id?>,<?=$periode->jour?>,'<?=CalendarUtils::transformDate2($periode->dateDebut)?>','<?=CalendarUtils::transformDate2($periode->dateFin)?>','<?=$periode->heureDebut?>','<?=$periode->heureFin?>',<?=$periode->modeId?>);"></span>
				</td>
			</tr>
			<?php
		}
		?>


		</tbody>
	</table>


	<?php include  $_SERVER ['DOCUMENT_ROOT'] . '/include/periodeFormPopIn.php';?>

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
					}													
				}
				);
			}	
	</script>

	

