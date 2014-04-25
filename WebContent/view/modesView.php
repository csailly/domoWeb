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


$dataService = new DataService($databaseConnexion);
$modes = $dataService->getAllModes();


$consForced = $dataService->getParameter('TEMP_CONSIGNE_MARCHE_FORCEE')->value;
$maxiForced = $dataService->getParameter('TEMP_MAXI_MARCHE_FORCEE')->value;

?>

<div class="panel panel-primary">
  <!-- Default panel contents -->
  <div class="panel-heading"><h3 class="panel-title">Modes utilisateur</h3></div>

  <!-- Table -->
  <table class="table table-hover modesTable">
		<thead>
			<tr>
				<th>#</th>
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
					<?=$mode->id?>
				</td>
				<td>
					<?=$mode->libelle?>
				</td>
				<td>
					<?=$mode->cons?>
				</td>
				<td>
					<?=$mode->max?>
				</td>
				<td>
					<span class="glyphicon glyphicon-trash" style="cursor: pointer;"
						onclick="deletemode(<?= $mode->id?>)" ></span>
					<span class="glyphicon glyphicon-pencil" style="cursor: pointer;" onclick="showUpdateModeForm(<?=$mode->id?>,'<?=$mode->libelle?>','<?=$mode->cons?>','<?=$mode->max?>');"></span>
				</td>
			</tr>
			<?php
		}
		?>


		</tbody>
	</table>
</div>



			<div class="panel panel-primary">
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


	


	<?php include  $_SERVER ['DOCUMENT_ROOT'] . '/view/modeFormPopIn.php';?>

	<script>
			function deleteMode(modeId)
			{
				$.ajax({
					type: "POST",
					url: "/service/DataWService.php",
					data: { modeId: modeId, action: "deleteMode" }
				})
				.done(function(data){
					
					var decode = $.parseJSON(data);
					var result = decode.result;
					if (result === "error"){
					
					}else{
						$("tr[id=modesRow_"+modeId+"]").remove();
					}													
				}
				);
			}	
	</script>
	
	

	

	<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/footer.php'; ?>
	


	<!-- Modal -->
	<script>
			var <?=$modeFormJsInstance?> = new GenericForm("<?=$modeFormId?>");
			$('#<?=$modalId?>').on('hidden.bs.modal', function (e) {
				<?=$modeFormJsInstance?>.clearForm();
			});				
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


</script>
	

</body>
</html>
	

