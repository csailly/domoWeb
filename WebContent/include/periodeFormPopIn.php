<?php
//@TODO Récupérer la liste des modes
//@See http://www.eyecon.ro/bootstrap-datepicker/
$modalId= "periodFormModal";
$periodFormJsInstance = "periodForm";
$periodFormId = "periodForm";
?>



<!-- Modal -->
<div class="modal fade" id="<?=$modalId?>"
	tabindex="-1" role="dialog" aria-labelledby="<?=$modalId?>Label"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
					aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="<?=$modalId?>Label">Créer une nouvelle période</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" role="form" id="<?=$periodFormId?>">
					<input type="hidden" name="action" id="<?=$periodFormId?>_action"
						value="none">
					<input type="hidden" name="periodId" id="<?=$periodFormId?>_periodId"
						value="-1">
					<div class="form-group">
						<label for="<?=$periodFormId?>_day" class="col-sm-2 control-label">Jour</label>
						<div class="col-sm-10">
							<select class="form-control" name="day" id="<?=$periodFormId?>_day">
								<option value="-1">-</option>
								<option value="1">Lundi</option>
								<option value="2">Mardi</option>
								<option value="3">Mercredi</option>
								<option value="4">Jeudi</option>
								<option value="5">Vendredi</option>
								<option value="6">Samedi</option>
								<option value="7">Dimanche</option>
							</select>
						</div>
					</div>
					<div class="input-daterange" id="<?=$periodFormId?>_datepicker">
						<div class="form-group">
							<label for="<?=$periodFormId?>_startDate"
								class="col-sm-2 control-label">Date de début</label>
							<div class="col-sm-10">
								<div class="input-group date">
									<input type="date" class="form-control" name="startDate"
										id="<?=$periodFormId?>_startDate" readonly=""
										placeholder="Ex. 11/12/2013">
									<span class="input-group-addon">
										<i class="glyphicon glyphicon-calendar"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="<?=$periodFormId?>_endDate"
								class="col-sm-2 control-label">Date de fin</label>
							<div class="col-sm-10">
								<div class="input-group date">
									<input type="date" class="form-control" name="endDate"
										id="<?=$periodFormId?>_endDate" readonly=""
										placeholder="Ex. 11/12/2013">
									<span class="input-group-addon">
										<i class="glyphicon glyphicon-calendar"></i>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="bootstrap-timepicker">
						<div class="form-group">
							<label for="<?=$periodFormId?>_startHour"
								class="col-sm-2 control-label">Heure de début</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="time" class="form-control" name="startHour"
										id="<?=$periodFormId?>_startHour" readonly=""
										placeholder="Ex. 10:15" value="0:00">
									<span class="input-group-addon">
										<i class="glyphicon glyphicon-time"></i>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="bootstrap-timepicker">
						<div class="form-group">
							<label for="<?=$periodFormId?>_endHour"
								class="col-sm-2 control-label">Heure de fin</label>
							<div class="col-sm-10">
								<div class="input-group">
									<input type="time" class="form-control" name="endHour"
										id="<?=$periodFormId?>_endHour" readonly=""
										placeholder="Ex. 11:15" value="1:00">
									<span class="input-group-addon">
										<i class="glyphicon glyphicon-time"></i>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for="<?=$periodFormId?>_mode" class="col-sm-2 control-label">Mode</label>
						<div class="col-sm-10">
							<select class="form-control" name="mode"
								id="<?=$periodFormId?>_mode">
								<option value="-1">-</option>
								<option value="1">Confort</option>
								<option value="2">Eco</option>
							</select>
						</div>
					</div>
				</form>
				<div id="<?=$periodFormId?>_errorMessages" class="alert alert-danger"
					style="display: none;">
					<strong> Merci de corriger les erreurs ci-dessous :</strong>
					<ul>

					</ul>
				</div>
				<div id="<?=$periodFormId?>_successMessages"
					class="alert alert-success" style="display: none;"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				<button type="button" class="btn btn-primary"
					onclick='<?=$periodFormJsInstance?>.checkFormValues();'>Enregistrer</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>


	var GenericForm = function(formId) {
		this.formId = formId;
		this.clearFormAfterSuccess = true;
	};
	
	GenericForm.prototype = {
		submitForm: function (){
			$.post( "/service/DataWService.php", $( '#'+this.formId ).serialize())
				.done(	function( data ) {							
							//TODO #1 un caractère 3F est mis en tête de data ????? voir pourquoi
							//TODO #2 voir pour ne pas mettre en dur newPeriodeForm mais utiliser l'attribut formId de la classe
							<?=$periodFormJsInstance?>.treatServerResult(data.substr(1));							
						});
		},						
				
		checkFormValues: function (){
			//TODO for server side check debug only
			this.submitForm();
			return;
			//END

		
			//Clear error mesages
			this.clearMessages();	
		
			//Validate start date and end date format
			var f_startDateOk = true;
			var startDateValue = $("#"+this.formId+"_startDate").val();
			if (startDateValue && !this.validateDate(startDateValue)){
				f_startDateOk = false;
			}
			var f_endDateOk = true;
			var endDateValue = $("#"+this.formId+"_endDate").val();				
			if (endDateValue && !this.validateDate(endDateValue)){
				f_endDateOk = false;
			}
			
			
			//Validate start date and end date gestion rules
			if (startDateValue && !endDateValue){
				endDateValue = startDateValue; 
			}
			
			var g_datesOk = true;
			if (f_startDateOk && f_endDateOk && startDateValue && endDateValue) {
				var startDate = new Date(startDateValue.split('/')[2],startDateValue.split('/')[1],startDateValue.split('/')[0]);
				var endDate = new Date(endDateValue.split('/')[2],endDateValue.split('/')[1],endDateValue.split('/')[0]);
				
				g_datesOk = startDate.valueOf() <= endDate.valueOf();
			}
			
			//Validate day or dates gestion rules
			var g_dayOrDateOk = true;
			
			if ($("#"+this.formId+"_day").val() > 0) {				
				g_dayOrDateOk = (startDateValue.length === 0) && (endDateValue.length === 0);
			}else{
				g_dayOrDateOk = (startDateValue.length > 0) && (endDateValue.length > 0) && g_datesOk;
			}
			
		
		
			//Validate start hour and end hour format
			var f_startHourOk = true;
			var startHourValue = $("#"+this.formId+"_startHour").val();
			if (startHourValue && !this.validateHour(startHourValue)) {
				f_startHourOk = false;
			}
			
			var f_endHourOk = true;
			var endHourValue = $("#"+this.formId+"_endHour").val();
			if (endHourValue && !this.validateHour(endHourValue)) {
				f_endHourOk = false;
			}
			
			//Validate start hour and end hour gestion rules
			var g_hoursOk = true;
			if (f_startHourOk && f_endHourOk){
				var startHour = new Date(1970, 1, 1, startHourValue.split(':')[0], startHourValue.split(':')[1], 0, 0);
				var endHour = new Date(1970, 1, 1, endHourValue.split(':')[0], endHourValue.split(':')[1], 0, 0);		
				g_hoursOk = startHour.valueOf() < endHour.valueOf();
			}
		
			//Validate mode gestion rule
			var g_modeOk = $("#"+this.formId+"_mode").val() > 0;
		
			//Show errors			
			if (!f_startDateOk){
				this.setFieldOnError(this.formId+"_startDate");
				this.addErrorMessage("Date de début : format invalide");
			}
			if (!f_endDateOk){
				this.setFieldOnError(this.formId+"_endDate");
				this.addErrorMessage("Date de fin : format invalide");
			}
			if (!f_startHourOk){
				this.setFieldOnError(this.formId+"_startHour");
				this.addErrorMessage("Heure de début : format invalide");
			}
			if (!f_endHourOk){
				setFieldOnError(this.formId+"_endHour");
				addErrorMessage("Heure de fin : format invalide");
			}
			if (!g_dayOrDateOk){
				this.setFieldOnError(this.formId+"_day");
				this.setFieldOnError(this.formId+"_startDate");
				this.setFieldOnError(this.formId+"_endDate");
				this.addErrorMessage("Vous devez saisir le jour OU les dates de début et de fin");
			}
			if (!g_datesOk){
				this.setFieldOnError(this.formId+"_startDate");
				this.setFieldOnError(this.formId+"_endDate");
				this.addErrorMessage("La date de début doit être antérieure à la date de fin");
			}
			if (!g_hoursOk){
				this.setFieldOnError(this.formId+"_startHour");
				this.setFieldOnError(this.formId+"_endHour");
				this.addErrorMessage("L'heure de début doit être antérieure à l'heure de fin");
			}
			if (!g_modeOk){
				this.setFieldOnError(this.formId+"_mode");
				this.addErrorMessage("La saisie du mode est obligatoire");
			}
		
			//Submit form if all it's ok.
			if (g_dayOrDateOk && f_startDateOk && f_endDateOk  && g_datesOk && f_startHourOk && f_endHourOk && g_hoursOk && g_modeOk) {
				this.submitForm();
			}else {
				$("#"+this.formId+"_errorMessages").show();
			}				
		},
	
		validateHour: function(hrValue) {
			//Valid formats are H:MM or HH:MM
			var hrRegex = new RegExp(/\b([0-9]|0[0-9]|1[0-9]|2[0-3]):([0-5]\d)\b/);				
			return hrRegex.test(hrValue);
		},
		
		validateDate: function(dtValue) {
			//Valid formats are DD/MM/YYYY
			var dtRegex = new RegExp(/\b([0-2][0-9]|3[0-1])\/([0]\d|[1][0-2])\/\d{4}\b/);				
			return dtRegex.test(dtValue);
		},
		
		setFieldOnError: function(fieldId){
			$('#'+this.formId).find("#"+fieldId ).parents( ".form-group" ).addClass( "has-error" );
		},
		
		clearForm: function(){
			this.resetForm();	
			this.clearMessages();						
		},
		
		clearMessages: function(){
			$("#"+this.formId+"_errorMessages").hide();
			$("#"+this.formId+"_errorMessages").find("ul").empty();
			$('#'+this.formId).find(".form-group").removeClass("has-error");
			
			$("#"+this.formId+"_successMessages").hide();
		},
		
		resetForm: function(){
			$('#'+this.formId+' :input')
			.not(':button, :submit, :reset')
			.not("#"+this.formId+"_action")
			.not("#"+this.formId+"_periodId")		
			.val('')
			.removeAttr('checked')
			.removeAttr('selected');
			
			$("#"+this.formId+"_startHour").val('0:00');
			$("#"+this.formId+"_endHour").val('1:00');
			$("#"+this.formId+"_day").val('-1');
			$("#"+this.formId+"_mode").val('-1');
			
		},
		
		addErrorMessage: function(errorMessage){
			$("#"+this.formId+"_errorMessages").find("ul").append("<li>"+errorMessage+"</li>");
		},

		treatServerResult: function(datas){
			this.clearMessages();	
			var decode = $.parseJSON(datas);
			var result = decode.result;
			
			
			if (result === "error"){
				var errorsMsgs = decode.errors.errorsMsgs;
				for(var fieldName in errorsMsgs){			
					var msg = errorsMsgs[fieldName];
					if (msg.length > 0){
						this.addErrorMessage(msg);
					}
					this.setFieldOnError(this.formId+"_"+fieldName);
				}
				$("#"+this.formId+"_errorMessages").show();				
			}else{
				if (this.clearFormAfterSuccess){
					this.clearForm();
				}
				$("#"+this.formId+"_successMessages").show();
			}
		}		
	};


	showCreatePeriodForm = function(){
		$('#<?=$modalId?>Label').text('Créer une nouvelle période');		
		$("#<?=$periodFormId?>_action").val('createPeriod');
		$("#<?=$periodFormId?>_periodId").val('-1');
		$("#<?=$periodFormId?>_successMessages").text('La période a été créée');
		<?=$periodFormJsInstance?>.clearFormAfterSuccess = true;

		//Show
		$('#<?=$modalId?>').modal('show');
	};

	showUpdatePeriodForm = function(periodId, day, startDate, endDate, startHour, endHour, mode){
		$('#<?=$modalId?>Label').text('Mettre à jour la période');
		$("#<?=$periodFormId?>_action").val('updatePeriod');
		$("#<?=$periodFormId?>_periodId").val(periodId);
		$("#<?=$periodFormId?>_day").val(day);
		$("#<?=$periodFormId?>_startDate").val(startDate);
		$("#<?=$periodFormId?>_endDate").val(endDate);
		$("#<?=$periodFormId?>_startHour").val(startHour);
		$("#<?=$periodFormId?>_endHour").val(endHour);		
		$("#<?=$periodFormId?>_mode").val(mode);
		$("#<?=$periodFormId?>_successMessages").text('La période a été mise à jour');
		<?=$periodFormJsInstance?>.clearFormAfterSuccess = false;

		//Show		
		$('#<?=$modalId?>').modal('show');		
	};

		
</script>
