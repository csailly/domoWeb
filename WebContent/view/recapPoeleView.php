<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/loginCheck.php'; ?>
<!DOCTYPE html>
<html>
	
<head>
		<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>
</head>

<body>

<?php

include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/navbar.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/utils/ParameterUtils.php';




$currentPeriode = $dataService->getCurrentPeriode ();

$currentMode = null;
if ($currentPeriode != null){
	$currentMode = $dataService->getModeById ( $currentPeriode->modeId );
}

$poeleStatus = $dataService->getParameter(Constants::POELE_ETAT)->value;
$onForced = $dataService->getParameter(Constants::POELE_MARCHE_FORCEE)->value;
$offForced = $dataService->getParameter(Constants::POELE_ARRET_FORCE)->value;

$consForced = $dataService->getParameter(Constants::TEMP_CONSIGNE_MARCHE_FORCEE)->value;
$maxiForced = $dataService->getParameter(Constants::TEMP_MAXI_MARCHE_FORCEE)->value;

$parameters = $dataService->getAllParameters();

?>


<table class="table"  style="margin: auto; max-width: 290px;">
<tbody>	
	<tr>
		<td colspan="2">		
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Infos</h3>
				</div>
					<table class="table table-hover">
					<tbody>
						<tr>
							<td>Température :</td>
							<td><span id="currentTemp"><?=$externalService->getCurrentTemp()?></span>°C</td>
						</tr>
						
						<tr>
							<td>Etat poêle :</td>
							<td><input type="checkbox" name="poeleStatusCheckBox"></td>
						</tr>								
					</tbody>
					</table>
			</div>		
		</td>		
	</tr>
	
	<?php  if ($currentMode != null){ ?>
	<tr>
		<td colspan="2">		
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Mode en cours - <?=$currentMode->libelle?></h3>
				</div>
					<table class="table table-hover">
					<tbody>
						<tr>
							<td>Consigne :</td>
							<td><?=$currentMode->cons ?>°C</td>
						</tr>
						<tr>
							<td>Maxi :</td>
							<td><?=$currentMode->max ?>°C</td>
						</tr>								
					</tbody>
					</table>				
			</div>		
		</td>		
	</tr>
	<?php }?>
	<tr>
		<td colspan="2">		
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Mode forcé</h3>
				</div>
					<table class="table table-hover">
					<tbody>
						<tr id="onForcedLine">
							<td>Marche forcée :</td>
							<td><input type="checkbox" name="onForcedCheckBox"></td>
						</tr>
						<tr id="offForcedLine">
							<td>Arrêt forcé :</td>
							<td><input type="checkbox" name="offForcedCheckBox"></td>
						</tr>
						<?php  if ($currentMode == null){ ?>
						<tr>
							<td>Consigne :</td>
							<td><span class="glyphicon glyphicon-minus" style="cursor: pointer;" onclick="downConsForced();"></span>
								<span id="consForced"></span>°C
								<span class="glyphicon glyphicon-plus" style="cursor: pointer;"	onclick="upConsForced();"></span></td>
						</tr>
						<tr>
							<td>Maxi :</td>
							<td><span class="glyphicon glyphicon-minus" style="cursor: pointer;" onclick="downMaxiForced();"></span>
								<span id="maxiForced"></span>°C
								<span class="glyphicon glyphicon-plus" style="cursor: pointer;"	onclick="upMaxiForced();"></span></td>
						</tr>
						<?php }?>	
					</tbody>
					</table>				
			</div>		
		</td>		
	</tr>
	<?php if ($parameters != null){ ?>
	<tr>
		<td colspan="2">
		<table class="table">
		<?php 
		foreach ($parameters as $parameter) {?>
		<tr>
			<td><?=$parameter->code ?></td>
			<td>
			<?php if (ParameterUtils::isBooleanParameter($parameter)){?>
				<input type="checkbox" name="param_<?=$parameter->code ?>">
			<?php }else if (ParameterUtils::isSelectParameter($parameter)){?>
				<select name="param_<?=$parameter->code ?>" class="selectpicker">
			<?php foreach (ParameterUtils::getSelectValues($parameter) as $value){?>
					<option value="<?=$value ?>"  <?php echo $parameter->value === $value?'selected':''?>><?=$value ?></option>			
			<?php }?>
				</select>
			<?php }else{?>
				<input type="text" name="param_<?=$parameter->code ?>" value="<?=$parameter->value ?>">			
			<?php }?>
			</td>
		<tr>	
		<?php } ?>
		</table>
		</td>
	</tr>
	<?php } ?>
	
</tbody>
</table>







<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/footer.php'; ?>

<!-- Bootstrap-switch -->
<script type="text/javascript">
	$("[name='poeleStatusCheckBox']").bootstrapSwitch();	
	$("[name='onForcedCheckBox']").bootstrapSwitch();
	$("[name='offForcedCheckBox']").bootstrapSwitch();

	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('state', <?php echo  ($poeleStatus === Constants::POELE_ETAT_ON) ? 'true': 'false';?>);	
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('readonly', true);
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('size', 'mini');
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('onColor', 'success');
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('offColor', 'danger');
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('onText', 'Allumé');
	$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('offText', 'Eteint');

	


		$('input[name="onForcedCheckBox"]').bootstrapSwitch('size', 'mini');
		$('input[name="onForcedCheckBox"]').bootstrapSwitch('onColor', 'success');
		$('input[name="onForcedCheckBox"]').bootstrapSwitch('offColor', 'danger');
		$('input[name="onForcedCheckBox"]').bootstrapSwitch('state', <?php echo ($onForced === Constants::POELE_MARCHE_FORCEE_ON)?  'true':   'false';?>);

		$('input[name="onForcedCheckBox"]').on('switchChange.bootstrapSwitch', function(event, state) {
			$.post( "/service/DataWService.php", {action : "onOrder", value : state})
			.done(	function( data ) {
						readPoeleStatus();
					}
				);
			});

		$('input[name="offForcedCheckBox"]').bootstrapSwitch('size', 'mini');
		$('input[name="offForcedCheckBox"]').bootstrapSwitch('onColor', 'success');
		$('input[name="offForcedCheckBox"]').bootstrapSwitch('offColor', 'danger');
		$('input[name="offForcedCheckBox"]').bootstrapSwitch('state', <?php echo ($offForced === Constants::POELE_ARRET_FORCE_ON)? 'true' : 'false';?>);
	
	
		
	
		$('input[name="offForcedCheckBox"]').on('switchChange.bootstrapSwitch', function(event, state) {
			$.post( "/service/DataWService.php", {action : "offOrder", value : state})
			.done(	function( data ) {
						readPoeleStatus();
					}
				);
			});


		<?php 
		if ($parameters != null){ 
			foreach ($parameters as $parameter) {
				if (ParameterUtils::isBooleanParameter($parameter)){?>
					$('input[name="param_<?=$parameter->code ?>"]').bootstrapSwitch();
					$('input[name="param_<?=$parameter->code ?>"]').bootstrapSwitch('size', 'mini');
					$('input[name="param_<?=$parameter->code ?>"]').bootstrapSwitch('onColor', 'success');
					$('input[name="param_<?=$parameter->code ?>"]').bootstrapSwitch('offColor', 'danger');
					$('input[name="param_<?=$parameter->code ?>"]').bootstrapSwitch('state', <?php echo ParameterUtils::isTrueValue($parameter)? 'true' : 'false';?>);
					$('input[name="param_<?=$parameter->code ?>"]').bootstrapSwitch('onText', '1');
					$('input[name="param_<?=$parameter->code ?>"]').bootstrapSwitch('offText', '0');
					$('input[name="param_<?=$parameter->code ?>"]').on('switchChange.bootstrapSwitch', function(event, state) {
						var booleanValue = null;
						if (state){
							booleanValue = '<?=ParameterUtils::getBooleanTrueValue($parameter)?>';
						}else{
							booleanValue = '<?=ParameterUtils::getBooleanFalseValue($parameter)?>';
						}
						
						$.post( "/service/DataWService.php", {action : "saveParameter", code : "<?=$parameter->code ?>" , value : booleanValue});
						});	
		<?php
 				}
			}
		}?>
		
</script>

	
<script>

	//Cons Forced
	function upConsForced(){
		currentCons = parseFloat($('#consForced').text());
		currentMaxi = parseFloat($('#maxiForced').text());

		if (currentCons +1 >= currentMaxi){
			return;
		}

		
		$.post( "/service/DataWService.php", {action : "upConsForced", value : (parseFloat($('#consForced').text())+0.5) })
		.done(	function( data ) {
			$('#consForced').text((parseFloat($('#consForced').text())+0.5).toFixed(1));
			});	
	}

	function downConsForced(){
		$.post( "/service/DataWService.php", {action : "downConsForced", value : (parseFloat($('#consForced').text())-0.5) })
		.done(	function( data ) {
			$('#consForced').text((parseFloat($('#consForced').text())-0.5).toFixed(1));
			});
	}
	$('#consForced').text(parseFloat(<?=$consForced ?>).toFixed(1));

	//Maxi Forced
	function upMaxiForced(){
		$.post( "/service/DataWService.php", {action : "upMaxiForced", value : (parseFloat($('#maxiForced').text())+0.5) })
		.done(	function( data ) {
			$('#maxiForced').text((parseFloat($('#maxiForced').text())+0.5).toFixed(1));
			});
	}

	function downMaxiForced(){
		currentCons = parseFloat($('#consForced').text());
		currentMaxi = parseFloat($('#maxiForced').text());

		if (currentCons +1 >= currentMaxi){
			return;
		}
		
		$.post( "/service/DataWService.php", {action : "downMaxiForced", value : (parseFloat($('#maxiForced').text())-0.5) })
		.done(	function( data ) {
			$('#maxiForced').text((parseFloat($('#maxiForced').text())-0.5).toFixed(1));
			});
	}
	$('#maxiForced').text(parseFloat(<?=$maxiForced ?>).toFixed(1));

	//Current Temp
	function readCurrentTemp(){
		$.post( "/service/DataWService.php", {action : "readCurrentTemp"})
		.done(	function( data ) {
				var decode = $.parseJSON(data);
				var result = decode.result;
				if (result === "success"){
					$('#currentTemp').text(decode.currentTemp);
				}else{
					$('#currentTemp').text('--');
				}
			});
	}
	setInterval(readCurrentTemp, 30000);

	//Poele status
	function readPoeleStatus(){
		$.post( "/service/DataWService.php", {action : "readPoeleStatus"})
		.done(	function( data ) {
				var decode = $.parseJSON(data);
				var result = decode.result;
				if (result === "success"){
					var value = decode.poeleStatus == "<?=Constants::POELE_ETAT_ON?>" ? true : false;

					$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('readonly', false);
					$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('state', value, true);
					$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('readonly', true);

					//TODO appeler un ws de récupératio des états forcés
					//showHideForcedLines(decode.poeleStatus, $('input[name="onForcedCheckBox"]').bootstrapSwitch('state'), $('input[name="offForcedCheckBox"]').bootstrapSwitch('state'));
					
				}else{
					$('#currentTemp').text('--');
				}
			});		
	}
	setInterval(readPoeleStatus, 30000);

	$( document ).ready(function() {
		myLoading.hidePleaseWait();
	});

</script>
	
</body>
</html>
	

