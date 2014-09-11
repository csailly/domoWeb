<?php include $_SERVER ['DOCUMENT_ROOT'] . '/include/loginCheck.php'; ?>
<!DOCTYPE html>
<html>

<head>
		<?php include $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>
</head>

<body>

<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/navbar.php'; ?>

<div id="main">

<table class="table"  style="margin: auto; max-width: 305px;">
<tbody>	
	<tr>
		<td>
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title">Salon</h3>
				</div>
					<table class="table table-hover">
					<tbody>
						<tr>
							<td colspan="2" style="text-align: center;">
								<div class="btn-group" data-toggle="buttons">
								    <label id="poeleConfigAuto" class="btn btn-primary">
								        <input type="radio" name="poeleConfig" value="AUTO">Auto
								    </label>
								    <label id="poeleConfigManu" class="btn btn-primary">
								        <input type="radio" name="poeleConfig" value="MANU">Manu
								    </label>
								    <label id="poeleConfigStop" class="btn btn-primary">
								        <input type="radio" name="poeleConfig" value="STOP">Stop
								    </label>
								</div>													
							</td>
						</tr>				
						<tr>
							<td colspan="2" style="text-align: center">							
								<img id="poelePowerButton" src="/img/power-blue1.png" width="45px" style="cursor: pointer">							
							</td>
						</tr>
						<tr>
							<td colspan="2">								
								<div id="allTempView" style="display : none;">
									<div style="position: relative; left : 50px;">
										<div style="background: -webkit-linear-gradient( top, red, orangered ); width: 20px; height: 50px; border-top-left-radius: 5px 5px; border-top-right-radius: 5px 5px;"></div>
										<div style="background: -webkit-linear-gradient( top,  orangered, orange ); width: 20px; height: 50px;">
											<div style="position: relative; bottom: -40px; right: 45px; white-space: nowrap; font-family: fantasy;"><span id="maxTemp"></span> °C</div>
										</div>
										<div style="background: -webkit-linear-gradient( top, orange, orange ); width: 20px; height: 100px; border-top : 1px dashed black; border-bottom : 1px dashed black;">
											<div id="currentTempDiv" style=" display: inline-flex; position: relative; bottom: -74px; height: 0px;">
												<div style="width: 20px; border-bottom : 1px solid black;"></div>
												<div style="position: relative; top: -16px; left: 5px; white-space: nowrap; font-family: fantasy; "><span id="currentTemp1" style="font-size: 20pt;"></span> °C</div>
											</div>		
										</div>
										<div style="background: -webkit-linear-gradient( top, orange, skyblue ); width: 20px; height: 50px;">
											<div style="position: relative; top: -10px; right: 45px; white-space: nowrap; font-family: fantasy;"><span id="consTemp"></span> °C</div>
										</div>
										<div style="background: -webkit-linear-gradient( top, skyblue, royalblue ); width: 20px; height: 50px;"></div>
									</div>
								</div>								
								<div id="oneTempView" style="white-space: nowrap; font-family: fantasy; display : none; text-align: center;"><span id="currentTemp2" style="font-size: 20pt;"></span> °C</div>								
							</td>
						</tr>						
						<tr id="currentPeriodeDatetime" style="display: none;">
							<td>Période :</td>							
							<td><span id="currentPeriodeDatetimeDebut"></span> - <span id="currentPeriodeDatetimeFin"></span></td>																				
						</tr>						
					</tbody>
					</table>
			</div>
		</td>	
	</tr>
</tbody>
</table>


</div>


<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/footer.php'; ?>	


<!-- Bootstrap-switch -->
<script type="text/javascript">
	//Current Temp
	function readCurrentTemp(currentMode){
		$.post( "/service/DataWService.php", {action : "readCurrentTemp"})
		.done(	function( data ) {
				var decode = $.parseJSON(data);
				var result = decode.result;
				if (result === "success"){
					$('#currentTemp1').text(decode.currentTemp);
					$('#currentTemp2').text(decode.currentTemp);
					
					if(currentMode != null){						
						var bottomValue = -83 + (decode.currentTemp - currentMode.cons) * 99 / (currentMode.max - currentMode.cons);					
						bottomValue = Math.min(bottomValue, 110);
						bottomValue = Math.min(bottomValue, 170);					
						$('#currentTempDiv').css('bottom', bottomValue);
					}
				}else{
					$('#currentTemp1').text('--');
					$('#currentTemp2').text('--');
				}
			});
	}
	
	//Poele status
	function readPoeleStatus(){
		$.post( "/service/DataWService.php", {action : "readPoeleStatus"})
		.done(	function( data ) {
				var decode = $.parseJSON(data);
				var result = decode.result;
				if (result === "success"){
					if ( decode.poeleStatus == "ON"){
						$('#poelePowerButton').attr('src', "/img/power-green1.png" );
					}else{
						$('#poelePowerButton').attr('src', "/img/power-red1.png" );
					}									
				}
			});		
	}
	
	//Current Periode and Mode
	function getCurrentPeriodeAndMode(){
		$.post( "/service/DataWService.php", {action : "getCurrentPeriodeAndMode"})
		.done(	function( data ) {
				var decode = $.parseJSON(data);
				var result = decode.result;
				if (result === "success"){
					

					if(decode.currentMode != null){
						$('#allTempView').show();
						$('#oneTempView').hide();
						$('#maxTemp').text(decode.currentMode.max);
						$('#consTemp').text(decode.currentMode.cons);
					}else{
						$('#allTempView').hide();
						$('#oneTempView').show();
					}
					if(decode.currentPeriode){
						$('#currentPeriodeDatetime').show();
						if(decode.currentPeriode.heureDebut != null){
							$('#currentPeriodeDatetimeDebut').text(decode.currentPeriode.heureDebut);
							$('#currentPeriodeDatetimeFin').text(decode.currentPeriode.heureFin);
						}else if(decode.currentPeriode.dateDebut != null){
							$('#currentPeriodeDatetimeDebut').text(decode.currentPeriode.dateDebut);
							$('#currentPeriodeDatetimeFin').text(decode.currentPeriode.dateFin);
						}
					}else{
						$('#currentPeriodeDatetime').hide();
					}					
					readCurrentTemp(decode.currentMode);								
				}
			});		
	}

	//Poele configuration
	function readPoeleConfig(){	
		$.post( "/service/DataWService.php", {action : "readPoeleConfiguration"})
		.done(	function( data ) {
				var decode = $.parseJSON(data);
				var result = decode.result;
				if (result === "success"){
					$('input:radio[name=poeleConfig]').filter('[value=MANU]').parent().removeClass('active');
					$('input:radio[name=poeleConfig]').filter('[value=AUTO]').parent().removeClass('active');
					$('input:radio[name=poeleConfig]').filter('[value=STOP]').parent().removeClass('active');
					
					if (decode.poeleConfig === "MANU"){
						$('input:radio[name=poeleConfig]').filter('[value=MANU]').parent().addClass('active');
					}else if (decode.poeleConfig === "AUTO"){
						$('input:radio[name=poeleConfig]').filter('[value=AUTO]').parent().addClass('active');
					}else if (decode.poeleConfig === "STOP"){
						$('input:radio[name=poeleConfig]').filter('[value=STOP]').parent().addClass('active');
					}									
				}
			});
	}

	$("#poelePowerButton").click(
			function(){
				if($('#poelePowerButton').attr('src')  === "/img/power-green1.png"){
					$('#poelePowerButton').attr('src', "/img/power-blue1.png" );
					$.post( "/service/DataWService.php", {action : "offOrder", value : true})
					.done(	function( data ) {
								readPoeleStatus();
							}
						);				
				}else if($('#poelePowerButton').attr('src')  === "/img/power-red1.png"){
					$('#poelePowerButton').attr('src', "/img/power-blue1.png" );
					$.post( "/service/DataWService.php", {action : "onOrder", value : true})
					.done(	function( data ) {
								readPoeleStatus();
							}
						);
				} 
			}
		);

	$('#poeleConfigAuto, #poeleConfigManu, #poeleConfigStop').click(
		function(e){
			$.post( "/service/DataWService.php", {action : "savePoeleConfiguration", value : $(this).children().attr('value')})
			.done(	function( data ) {
						init();
					}
				);
		}
	);

	function init(){
		readPoeleConfig();
		readPoeleStatus();
		getCurrentPeriodeAndMode();		
	}
			
	$( document ).ready(function() {
		init();
		setInterval(init, 30000);
	});

</script>
</body>
</html>