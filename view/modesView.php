<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/loginCheck.php'; ?>
<!DOCTYPE html>
<html>
	
<head>
<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>
</head>

<body>

<?php
include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/navbar.php';
$modes = $dataService->getAllModes();
$consForced = $dataService->getParameter('TEMP_CONSIGNE_MARCHE_FORCEE')->value;
$maxiForced = $dataService->getParameter('TEMP_MAXI_MARCHE_FORCEE')->value;

?>

<table class="table"  style="margin: auto; max-width: 320px;">
<tbody>	
	<tr>
		<td>

			<div class="panel panel-info">
			  <!-- Default panel contents -->
			  <div class="panel-heading"><h3 class="panel-title">Modes utilisateur</h3></div>
			
			  <!-- Table -->
			  <table class="table table-hover modesTable">
					<thead>
						<tr>
							<th>Libellé</th>
							<th>T° consigne</th>
							<th>T° max</th>
							<th><span class="glyphicon glyphicon-plus" style="cursor: pointer;" onclick="showCreateModeForm();"></span></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($modes as $mode) {			
						?>
						<tr id="modesRow_<?= $mode->id?>">
							<td>
								<?=$mode->libelle?>
							</td>
							<td>
								<?=$mode->cons?>°C
							</td>
							<td>
								<?=$mode->max?>°C
							</td>
							<td>
								<span class="glyphicon glyphicon-trash" style="cursor: pointer;"
									onclick="deleteMode(<?= $mode->id?>)" ></span>
								<span class="glyphicon glyphicon-pencil" style="cursor: pointer;" onclick="showUpdateModeForm(<?=$mode->id?>,'<?=$mode->libelle?>','<?=$mode->cons?>','<?=$mode->max?>');"></span>
							</td>
						</tr>
						<?php }?>
					</tbody>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Mode forcé</h3>
				</div>
					<table class="table table-hover">
					<tbody>
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

	
<?php include  $_SERVER ['DOCUMENT_ROOT'] . '/view/modeFormPopIn.php';?>

<script>
function deleteMode(modeId) {
	bootbox.confirm("T'es sûr de ton coup ?", function(choice) {
		if (choice){
			$.post( "/service/DomoWebWS.php", {action : "deleteMode", modeId: modeId})
			.done(function(data){
				var decode = $.parseJSON(data);
				var result = decode.result;
				if (result === "error"){
					null;
				}else{
					$("tr[id=modesRow_"+modeId+"]").remove();
				};
			});
		};
	});
}	
</script>
	
	
<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/footer.php'; ?>
	

	<script>
			var <?=$modeFormJsInstance?> = new GenericForm("<?=$modeFormId?>");
			$('#<?=$modalId?>').on('hidden.bs.modal', function (e) {
				<?=$modeFormJsInstance?>.clearForm();
			});				

	//Cons Forced
	function upConsForced(){
		currentCons = parseFloat($('#consForced').text());
		currentMaxi = parseFloat($('#maxiForced').text());

		if (currentCons +1 >= currentMaxi){
			return;
		}
		
		$.post( "/service/DomoWebWS.php", {action : "upConsForced", value : (parseFloat($('#consForced').text())+0.5) })
		.done(	function( data ) {
			$('#consForced').text((parseFloat($('#consForced').text())+0.5).toFixed(1));
			});	
	}

	function downConsForced(){
		$.post( "/service/DomoWebWS.php", {action : "downConsForced", value : (parseFloat($('#consForced').text())-0.5) })
		.done(	function( data ) {
			$('#consForced').text((parseFloat($('#consForced').text())-0.5).toFixed(1));
			});
	}

	$('#consForced').text(parseFloat(<?=$consForced ?>).toFixed(1));

	//Maxi Forced
	function upMaxiForced(){
		$.post( "/service/DomoWebWS.php", {action : "upMaxiForced", value : (parseFloat($('#maxiForced').text())+0.5) })
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
		
		$.post( "/service/DomoWebWS.php", {action : "downMaxiForced", value : (parseFloat($('#maxiForced').text())-0.5) })
		.done(	function( data ) {
			$('#maxiForced').text((parseFloat($('#maxiForced').text())-0.5).toFixed(1));
			});
	}
	$('#maxiForced').text(parseFloat(<?=$maxiForced ?>).toFixed(1));


	$( document ).ready(function() {
		myLoading.hidePleaseWait();
	});
	
</script>
	

</body>
</html>
	

