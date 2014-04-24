<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/loginCheck.php'; ?>
<!DOCTYPE html>
<html>
	
<head>
		<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>
</head>

<body>

<?php

include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/navbar.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/DataService.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/PoeleService.php';

$dataService = new DataService($databaseConnexion);
$poeleService = new PoeleService($databaseConnexion);

$currentPeriode = $dataService->getCurrentPeriode ();

$currentMode = null;
if ($currentPeriode != null){
	$currentMode = $dataService->getModeById ( $currentPeriode->modeId );
}

$poeleStatus = $dataService->getParameter('POELE_ETAT')->value;
$onForced = $dataService->getParameter('POELE_MARCHE_FORCEE')->value;
$offForced = $dataService->getParameter('POELE_ARRET_FORCE')->value;

?>


<table class="table table-hover modesTable">
<tbody>
	<tr>
		<td>Température :</td>
		<td>5°C</td>
	</tr>
	<?php 
		if ($currentMode != null){
	?>
	
	
	<tr>
		<td>Consigne :</td>
		<td><?=$currentMode->cons ?>°C</td>
	</tr>
	<tr>
		<td>Maxi :</td>
		<td><?=$currentMode->max ?>°C</td>
	</tr>
	<?php }?>
	<tr>
		<td>Etat poêle :</td>
		<td><input type="checkbox" name="poeleStatusCheckBox"></td>
	</tr>
	
	
	<?php if ($poeleStatus == 'OFF' || $onForced == 'TRUE') {?>
	
	<tr>
		<td>Marche forcée :</td>
		<td><input type="checkbox" name="onForcedCheckBox"></td>
	</tr>
	<?php }else if ($poeleStatus == 'ON' || $offForced == 'TRUE') {?>
	<tr>
		<td>Arrêt forcé :</td>
		<td><input type="checkbox" name="offForcedCheckBox"></td>
	</tr>
	<?php }?>
</tbody>
</table>

<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/footer.php'; ?>

<!-- Bootstrap-switch -->
<script type="text/javascript">
	$("[name='poeleStatusCheckBox']").bootstrapSwitch();	
	$("[name='onForcedCheckBox']").bootstrapSwitch();
	$("[name='offForcedCheckBox']").bootstrapSwitch();

	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('state', <?php echo  ($poeleStatus == 'ON') ? 'true': 'false';?>);	
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('disabled', true);
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('size', 'mini');
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('onColor', 'success');
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('offColor', 'danger');
	

	

	<?php if ($poeleStatus == 'OFF' || $onForced == 'TRUE') {?>
		$('input[name="onForcedCheckBox"]').bootstrapSwitch('size', 'mini');
		$('input[name="onForcedCheckBox"]').bootstrapSwitch('onColor', 'success');
		$('input[name="onForcedCheckBox"]').bootstrapSwitch('offColor', 'danger');
		$('input[name="onForcedCheckBox"]').bootstrapSwitch('state', <?php echo ($onForced == 'TRUE')?  'true':   'false';?>);

		$('input[name="onForcedCheckBox"]').on('switchChange.bootstrapSwitch', function(event, state) {
			$.post( "/service/DataWService.php", {action : "onForced", value : state})
			.done(	function( data ) {});
			  console.log(this); // DOM element
			  console.log(event); // jQuery event
			  console.log(state); // true | false
			});
	<?php }?>

	<?php if ($poeleStatus == 'ON' || $offForced == 'TRUE') {?>
		$('input[name="offForcedCheckBox"]').bootstrapSwitch('size', 'mini');
		$('input[name="offForcedCheckBox"]').bootstrapSwitch('onColor', 'success');
		$('input[name="offForcedCheckBox"]').bootstrapSwitch('offColor', 'danger');
		$('input[name="offForcedCheckBox"]').bootstrapSwitch('state', <?php echo ($offForced == 'TRUE')? 'true' : 'false';?>);
	
	
		
	
		$('input[name="offForcedCheckBox"]').on('switchChange.bootstrapSwitch', function(event, state) {
			$.post( "/service/DataWService.php", {action : "offForced", value : state});								
			  console.log(this); // DOM element
			  console.log(event); // jQuery event
			  console.log(state); // true | false
			});
	<?php }?>
	
</script>

</body>
</html>
	

