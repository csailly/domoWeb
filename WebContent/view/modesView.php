<!DOCTYPE html>
<html>
	<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>
<body>

<?php

include_once $_SERVER ['DOCUMENT_ROOT'] . '/service/DataService.php';


$dataService = new DataService($databaseConnexion);
$modes = $dataService->getAllModes();

?>




	<table class="table table-hover">
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
					//TODO #1 un caractère 3F est mis en tête de data ????? voir pourquoi
					var decode = $.parseJSON(data.substr(1));
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

</body>
</html>
	

