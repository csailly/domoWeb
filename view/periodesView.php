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

$periodes = $dataService->getAllPeriodes ();
$currentPeriode = $dataService->getCurrentPeriode ();


?>


<table class="table"  style="margin: auto; max-width: 674px;">
<tbody>	
	<tr>
		<td>
			<div class="panel panel-info">
			  <!-- Default panel contents -->
			  <div class="panel-heading"><h3 class="panel-title">Périodes</h3></div>
			
			  <!-- Table -->
				<table class="table table-hover periodesTable">
					<thead>
						<tr>
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
							<?php 
							} ?>				
									<td><?=CalendarUtils::getDayLabel($periode->jour)?></td>
									<td><?=CalendarUtils::transformDate2($periode->dateDebut)?></td>
									<td><?=CalendarUtils::transformDate2($periode->dateFin)?></td>
									<td><?=$periode->heureDebut?></td>
									<td><?=$periode->heureFin?></td>
									<td>
										<span title="<?=$mode->cons?> - <?=$mode->max?>"><?=$mode->libelle?></span>
									</td>
									<td><span class="glyphicon glyphicon-trash" style="cursor: pointer;" onclick="deletePeriod(<?= $periode->id?>)"></span> 
										<span class="glyphicon glyphicon-pencil" style="cursor: pointer;" onclick="showUpdatePeriodForm(<?=$periode->id?>,<?php echo isset($periode->jour)? $periode->jour : -1 ?>,'<?=CalendarUtils::transformDate2($periode->dateDebut)?>','<?=CalendarUtils::transformDate2($periode->dateFin)?>','<?=$periode->heureDebut?>','<?=$periode->heureFin?>',<?=$periode->modeId?>);"></span>
									</td>
								</tr>
						<?php
						} ?>
			
			
					</tbody>
				</table>
			</div>
		</td>
	</tr>
</tbody>
</table>


<?php include  $_SERVER ['DOCUMENT_ROOT'] . '/view/periodeFormPopIn.php';?>

	<script>
			function deletePeriod(periodId)
			{
				bootbox.confirm("T'es sûr de ton coup ?", function(choice) {
					if (choice){
						$.post( "/service/DataWService.php", {action : "deletePeriod", periodId: periodId})
						.done(function(data){
							var decode = $.parseJSON(data);
							var result = decode.result;
							if (result === "error"){
								null;
							}else{
								$("tr[id=periodesRow_"+periodId+"]").remove();
							};
						});
					};
				});
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
							};
						}
		});		

	<!-- Clock picker -->
	$('.clockpicker').clockpicker({
		align : 'left',
		placement : 'bottom'
	});

	$( document ).ready(function() {
		myLoading.hidePleaseWait();
	});
	</script>

</body>
</html>


