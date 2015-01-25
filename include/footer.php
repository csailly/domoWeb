	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<!-- Latest compiled and minified JavaScript -->
	<script
		src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
		
	<!-- Date picker -->
	<script src="/js/bootstrap-datepicker.js"></script>
	<script src="/js/locales/bootstrap-datepicker.fr.js" charset="UTF-8"></script>
	
	<!-- Clock picker -->
	<script src="/js/bootstrap-clockpicker.js"></script>
	
	<!-- Bootstrap-switch -->
	<script src="/js/bootstrap-switch.js"></script>
	
	<!-- Bootstrap-select -->
	<script src="/js/bootstrap-select.js"></script>
	<script src="/js/i18n/bootstrap-select-fr_FR.js" charset="UTF-8"></script>
	
	<!-- Bootbox -->
	<script src="/js/bootbox.js"></script>
	
	<script>
		<!-- Fenêtre de chargement -->
		var myLoading;
		myLoading = myLoading || (function () {
		    var pleaseWaitDiv = $('<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow: hidden;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h1>Processing...</h1></div><div class="modal-body"><div class="progress progress-striped active"><div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div></div></div></div></div>');
		    return {
		        showPleaseWait: function() {
		            pleaseWaitDiv.modal();
		        },
		        hidePleaseWait: function () {
		            pleaseWaitDiv.modal('hide');
		        },
	
		    };
		})();
		
		myLoading.showPleaseWait();


		function updateWebApp(){
			myLoading.showPleaseWait();
			$.post( "/service/DomoWebWS.php", {action : "updateWebApp"})
			.done(	function( data ) {
						var decode = $.parseJSON(data);
						var result = decode.result;
						if (result === "success"){
							bootbox.alert("Mise à jour effectuée");			
						}
						myLoading.hidePleaseWait();
					});		
		};

		function updateCore(){
			myLoading.showPleaseWait();
			$.post( "/service/DomoWebWS.php", {action : "updateCore"})
			.done(	function( data ) {
						var decode = $.parseJSON(data);
						var result = decode.result;
						if (result === "success"){
							bootbox.alert("Mise à jour effectuée");			
						}
						myLoading.hidePleaseWait();
					});		
		};

		$("#updateDomoWeb").click(function() {
			bootbox.confirm("T'es sûr de ton coup ?", function(choice) {
				if (choice){
					updateWebApp();
				}
			});
		});

		$("#updateDomoCore").click(function() {
			bootbox.confirm("T'es sûr de ton coup ?", function(choice) {
				if (choice){
					updateCore();
				}
			});
		});

	</script>
	