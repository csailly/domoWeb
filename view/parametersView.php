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


$parameters = $dataService->getAllParameters();

?>


<table class="table"  style="margin: auto; max-width: 290px;">
<tbody>	
	<?php if ($parameters != null){ ?>
	<tr>
		<td colspan="2">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Paramètres</h3>
				</div>
				<table class="table  table-hover">
				<tbody>
				<?php 
				foreach ($parameters as $parameter) {?>
				<tr>
					<td title="<?=$parameter->comment ?>"><?=$parameter->code ?></td>
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
				</tbody>
				</table>
			</div>
		</td>
	</tr>
	<?php } ?>
	
</tbody>
</table>







<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/footer.php'; ?>

<!-- Bootstrap-switch -->
<script type="text/javascript">

		<?php 
		if ($parameters != null){ 
			foreach ($parameters as $parameter) {
				if (ParameterUtils::isBooleanParameter($parameter)){
		?>
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
 				}elseif (ParameterUtils::isSelectParameter($parameter)) {
		?>
					$('select[name="param_<?=$parameter->code ?>"]').on('change', function() {
						$.post( "/service/DataWService.php", {action : "saveParameter", code : "<?=$parameter->code ?>" , value : this.value});						
					});
		<?php
				}else{
		?>
					$('input[name="param_<?=$parameter->code ?>"]').on('change', function() {
						$.post( "/service/DataWService.php", {action : "saveParameter", code : "<?=$parameter->code ?>" , value : this.value});						
					});
		<?php
				}
			}
		}?>
		
	

	$( document ).ready(function() {
		myLoading.hidePleaseWait();
	});

</script>
	
</body>
</html>
	

