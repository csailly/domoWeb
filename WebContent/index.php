<!DOCTYPE html>
<html>
	<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>
<body>

	<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/periodesView.php';?>


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
						}
					}
				});		
	</script>

	<!-- Time picker -->
	<script type="text/javascript">
			$('#'+<?=$periodFormJsInstance?>.formId+'_startHour').timepicker({
				minuteStep: 5,
				showMeridian: false,
				showInputs: false,
				defaultTime: false
			});
		 
			$('#'+<?=$periodFormJsInstance?>.formId+'_endHour').timepicker({
				minuteStep: 5,
				showMeridian: false,
				showInputs: false,
				defaultTime: false
			});		
	</script>
</body>
</html>
