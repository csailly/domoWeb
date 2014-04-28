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
include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/ExternalService.php';

$dataService = new DataService($databaseConnexion);
$poeleService = new PoeleService($databaseConnexion);
$externalService = new ExternalService($externalCommandTemp, $externalCommandMcz);

$currentPeriode = $dataService->getCurrentPeriode ();

$currentMode = null;
if ($currentPeriode != null){
	$currentMode = $dataService->getModeById ( $currentPeriode->modeId );
}

$poeleStatus = $dataService->getParameter('POELE_ETAT')->value;
$onForced = $dataService->getParameter('POELE_MARCHE_FORCEE')->value;
$offForced = $dataService->getParameter('POELE_ARRET_FORCE')->value;

$consForced = $dataService->getParameter('TEMP_CONSIGNE_MARCHE_FORCEE')->value;
$maxiForced = $dataService->getParameter('TEMP_MAXI_MARCHE_FORCEE')->value;

?>


<table class="table"  style="margin: auto; max-width: 290px;">
<tbody>	
	<tr>
		<td colspan="2">		
			<div class="panel panel-primary">
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
			<div class="panel panel-primary">
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
			<div class="panel panel-primary">
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
					</tbody>
					</table>				
			</div>		
		</td>		
	</tr>
	
	
</tbody>
</table>







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
	

	


		$('input[name="onForcedCheckBox"]').bootstrapSwitch('size', 'mini');
		$('input[name="onForcedCheckBox"]').bootstrapSwitch('onColor', 'success');
		$('input[name="onForcedCheckBox"]').bootstrapSwitch('offColor', 'danger');
		$('input[name="onForcedCheckBox"]').bootstrapSwitch('state', <?php echo ($onForced == 'TRUE')?  'true':   'false';?>);

		$('input[name="onForcedCheckBox"]').on('switchChange.bootstrapSwitch', function(event, state) {
			$.post( "/service/DataWService.php", {action : "onForced", value : state})
			.done(	function( data ) {
						readPoeleStatus();
					}
				);
			});

		$('input[name="offForcedCheckBox"]').bootstrapSwitch('size', 'mini');
		$('input[name="offForcedCheckBox"]').bootstrapSwitch('onColor', 'success');
		$('input[name="offForcedCheckBox"]').bootstrapSwitch('offColor', 'danger');
		$('input[name="offForcedCheckBox"]').bootstrapSwitch('state', <?php echo ($offForced == 'TRUE')? 'true' : 'false';?>);
	
	
		
	
		$('input[name="offForcedCheckBox"]').on('switchChange.bootstrapSwitch', function(event, state) {
			$.post( "/service/DataWService.php", {action : "offForced", value : state})
			.done(	function( data ) {
						readPoeleStatus();
					}
				);
			});





		function showHideForcedLines(poeleStatus, onForced, offForced ){
			$('#onForcedLine').hide();
			$('#offForcedLine').hide();

			if (onForced) {
            	$('#onForcedLine').show();
	       	}else if (offForced) {
	        	$('#offForcedLine').show();
	       	}else if (poeleStatus == 'OFF') {
	        	$('#onForcedLine').show();
	       	}else if (poeleStatus == 'ON') {
	        	$('#offForcedLine').show();
	       	}				
		}

		showHideForcedLines('<?=$poeleStatus?>', <?php echo ($onForced == 'TRUE')?  'true':   'false';?>, <?php echo ($offForced == 'TRUE')? 'true' : 'false';?>);

	
</script>

	
<script type="text/javascript">

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
					var value = decode.poeleStatus == "ON" ? true : false;

					$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('readonly', false);
					$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('state', value, true);
					$('input[name="poeleStatusCheckBox"]').bootstrapSwitch('readonly', true);

					//TODO appeler un ws de récupératio des états forcés
					showHideForcedLines(decode.poeleStatus, $('input[name="onForcedCheckBox"]').bootstrapSwitch('state'), $('input[name="offForcedCheckBox"]').bootstrapSwitch('state'));
					
				}else{
					$('#currentTemp').text('--');
				}
			});		
	}
	setInterval(readPoeleStatus, 30000);

</script>
	
</body>
</html>
	

