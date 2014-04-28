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


<table class="table"  style="margin: auto; max-width: 290px;">
<tbody>	
	<tr>
		<td>
			<div class="panel panel-primary">
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

</script>


</body>
</html>
