<?php
$modalId= "modeFormModal";
$modeFormJsInstance = "modeForm";
$modeFormId = "modeForm";
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
				<h4 class="modal-title" id="<?=$modalId?>Label">Créer un nouveau mode</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" role="form" id="<?=$modeFormId?>">
					<input type="hidden" name="action" id="<?=$modeFormId?>_action"
						value="none">
					<input type="hidden" name="modeId" id="<?=$modeFormId?>_modeId"
						value="-1">
					<div class="form-group">
						<label for="<?=$modeFormId?>_day" class="col-sm-2 control-label">Libellé</label>
						<div class="col-sm-10">
							<input type="test" class="form-control" name="label"
										id="<?=$modeFormId?>_label" placeholder="Ex. Confort">
						</div>
					</div>
					<div class="form-group">
						<label for="<?=$modeFormId?>_cons" class="col-sm-2 control-label">T° consigne</label>
						<div class="col-sm-10">
							<input type="test" class="form-control" name="cons"
										id="<?=$modeFormId?>_cons" placeholder="Ex. 19.5">
						</div>
					</div>
					<div class="form-group">
						<label for="<?=$modeFormId?>_max" class="col-sm-2 control-label">T° maxi</label>
						<div class="col-sm-10">
							<input type="test" class="form-control" name="max"
										id="<?=$modeFormId?>_max" placeholder="Ex. 23">
						</div>
					</div>
				</form>
				<div id="<?=$modeFormId?>_errorMessages" class="alert alert-danger"
					style="display: none;">
					<strong> Merci de corriger les erreurs ci-dessous :</strong>
					<ul>

					</ul>
				</div>
				<div id="<?=$modeFormId?>_successMessages"
					class="alert alert-success" style="display: none;"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
				<button type="button" class="btn btn-primary"
					onclick='<?=$modeFormJsInstance?>.checkFormValues();'>Enregistrer</button>
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
							<?=$modeFormJsInstance?>.treatServerResult(data);							
						});
		},						
				
		checkFormValues: function (){
			//TODO for server side check debug only
			this.submitForm();
			return;
			//END

		
			//Clear error mesages
			this.clearMessages();	
		
			
// 			if (!g_modeOk){
// 				this.setFieldOnError(this.formId+"_mode");
// 				this.addErrorMessage("La saisie du mode est obligatoire");
// 			}
		
			//Submit form if all it's ok.
			if (true) {
				this.submitForm();
			}else {
				$("#"+this.formId+"_errorMessages").show();
			}				
		},
	
		validateTemperature: function(tempValue) {
			return true;
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
			.not("#"+this.formId+"_modeId")		
			.val('')
			.removeAttr('checked')
			.removeAttr('selected');						
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


	showCreateModeForm = function(){
		$('#<?=$modalId?>Label').text('Créer un nouveau mode');		
		$("#<?=$modeFormId?>_action").val('createMode');
		$("#<?=$modeFormId?>_modeId").val('-1');
		$("#<?=$modeFormId?>_successMessages").text('Le mode a été crée');
		<?=$modeFormJsInstance?>.clearFormAfterSuccess = true;

		//Show
		$('#<?=$modalId?>').modal('show');
	};

	showUpdateModeForm = function(modeId, label, cons, max){
		$('#<?=$modalId?>Label').text('Mettre à jour le mode');
		$("#<?=$modeFormId?>_action").val('updateMode');
		$("#<?=$modeFormId?>_modeId").val(modeId);
		$("#<?=$modeFormId?>_label").val(label);
		$("#<?=$modeFormId?>_cons").val(cons);
		$("#<?=$modeFormId?>_max").val(max);
		$("#<?=$modeFormId?>_successMessages").text('Le mode a été mis à jour');
		<?=$modeFormJsInstance?>.clearFormAfterSuccess = false;

		//Show		
		$('#<?=$modalId?>').modal('show');		
	};

		
</script>
