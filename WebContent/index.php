<?php include $_SERVER ['DOCUMENT_ROOT'] . '/include/loginCheck.php'; ?>
<!DOCTYPE html>
<html>
	
<head>
		<?php include $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>
</head>

<body>

<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/navbar.php'; ?>

<div id="main">

<?php
$currentPeriode = $dataService->getCurrentPeriode ();
$currentMode = null;
if ($currentPeriode != null){
	$currentMode = $dataService->getModeById ( $currentPeriode->modeId );
}
$poeleStatus = $dataService->getParameter('POELE_ETAT')->value;
?>


<table class="table"  style="margin: auto; max-width: 305px;">
<tbody>	
	<tr>
		<td>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Salon</h3>
				</div>
					<table class="table table-hover">
					<tbody>
						<tr>
							<td>Température :</td>
							<td><span id="currentTemp"><?=$externalService->getCurrentTemp()?></span>°C</td>
						</tr>						
						<tr>
							<td>Poêle :</td>
							<td><input type="checkbox" name="poeleStatusCheckBox"></td>
						</tr>
						<tr>
							<td>Mode :</td>
							<td><?=$currentMode->libelle?> - <?=$currentMode->cons ?>°C - <?=$currentMode->max ?>°C</td>
						</tr>								
					</tbody>
					</table>
			</div>
		</td>	
	</tr>
</tbody>
</table>


</div>


<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/footer.php'; ?>	


<!-- Bootstrap-switch -->
<script type="text/javascript">
	$("[name='poeleStatusCheckBox']").bootstrapSwitch();	
	$("[name='onForcedCheckBox']").bootstrapSwitch();
	$("[name='offForcedCheckBox']").bootstrapSwitch();

	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('state', <?php echo  ($poeleStatus == 'ON') ? 'true': 'false';?>);	
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('readonly', true);
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('size', 'mini');
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('onColor', 'success');
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('offColor', 'danger');
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('onText', 'Allumé');
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('offText', 'Eteint');


	//Poele status
	function readPoeleStatus(){
		$.post( "/service/DataWService.php", {action : "readPoeleStatus"})
		.done(	function( data ) {
				var decode = $.parseJSON(data);
				var result = decode.result;
				if (result === "success"){
					var value = decode.poeleStatus == "ON" ? true : false;

					$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('readonly', false);
					$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('state', value, true);
					$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('readonly', true);

					showHideForcedLines(decode.poeleStatus, $('input[name="onForcedCheckBox"]').bootstrapSwitch('state'), $('input[name="offForcedCheckBox"]').bootstrapSwitch('state'));				
				}
			});		
	}
	setInterval(readPoeleStatus, 30000);
	
</script>


</body>
</html>
