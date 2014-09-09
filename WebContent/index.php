<?php include $_SERVER ['DOCUMENT_ROOT'] . '/include/loginCheck.php'; ?>
<!DOCTYPE html>
<html>

<head>
		<?php include $_SERVER ['DOCUMENT_ROOT'] . '/include/header.php'; ?>
</head>

<body>

<?php include_once $_SERVER ['DOCUMENT_ROOT'] . '/include/navbar.php'; ?>

<div id="main">

<?php
$currentPeriode = $dataService->getCurrentPeriode ();
$currentMode = null;
if ($currentPeriode != null){
	$currentMode = $dataService->getModeById ( $currentPeriode->modeId );
}
?>


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
								<?php if ($currentPeriode != null){?>
								<div>
									<div style="position: relative; left : 50px;">
										<div style="background: -webkit-linear-gradient( top, red, orangered ); width: 20px; height: 50px; border-top-left-radius: 5px 5px; border-top-right-radius: 5px 5px;"></div>
										<div style="background: -webkit-linear-gradient( top,  orangered, orange ); width: 20px; height: 50px;">
											<div style="position: relative; bottom: -40px; right: 45px; white-space: nowrap; font-family: fantasy;"><span id="maxTemp"><?=$currentMode->max ?></span> °C</div>
										</div>
										<div style="background: -webkit-linear-gradient( top, orange, orange ); width: 20px; height: 100px; border-top : 1px dashed black; border-bottom : 1px dashed black;">
											<div id="currentTempDiv" style=" display: inline-flex; position: relative; bottom: -74px; height: 0px;">
												<div style="width: 20px; border-bottom : 1px solid black;"></div>
												<div style="position: relative; top: -16px; left: 5px; white-space: nowrap; font-family: fantasy; "><span id="currentTemp" style="font-size: 20pt;"></span> °C</div>
											</div>		
										</div>
										<div style="background: -webkit-linear-gradient( top, orange, skyblue ); width: 20px; height: 50px;">
											<div style="position: relative; top: -10px; right: 45px; white-space: nowrap; font-family: fantasy;"><span id="consTemp"><?=$currentMode->cons ?></span> °C</div>
										</div>
										<div style="background: -webkit-linear-gradient( top, skyblue, royalblue ); width: 20px; height: 50px;"></div>
									</div>
								</div>
								<?php }else{?>
								<div style="white-space: nowrap; font-family: fantasy;"><span id="currentTemp" style="font-size: 20pt;"><?=$externalService->getCurrentTemp()?></span> °C</div>
								<?php }?>
							</td>
						</tr>
						<?php if ($currentPeriode != null){?>
						<tr>
							<td>Période :</td>
							<?php if ($currentPeriode->heureDebut != null){?>
							<td><?=$currentPeriode->heureDebut?> - <?=$currentPeriode->heureFin?></td>
							<?php }else if ($currentPeriode->dateDebut != null) {?>
							<td><?=$currentPeriode->dateDebut?> - <?=$currentPeriode->dateFin?></td>
							<?php }?>
						</tr>
						<?php }?>							
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
	function readCurrentTemp(){
		$.post( "/service/DataWService.php", {action : "readCurrentTemp"})
		.done(	function( data ) {
				var decode = $.parseJSON(data);
				var result = decode.result;
				if (result === "success"){
					$('#currentTemp').text(decode.currentTemp);

					var bottomValue = -83 + (decode.currentTemp - <?=$currentMode->cons ?>) * 99 / (<?=$currentMode->max ?> - <?=$currentMode->cons ?>);					
					bottomValue = Math.min(bottomValue, 110);
					bottomValue = Math.min(bottomValue, 170);

					$('#currentTempDiv').css('bottom', bottomValue);
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
					if ( decode.poeleStatus == "ON"){
						$('#poelePowerButton').attr('src', "/img/power-green1.png" );
					}else{
						$('#poelePowerButton').attr('src', "/img/power-red1.png" );
					}									
				}
			});		
	}
	setInterval(readPoeleStatus, 30000);



	$("#poelePowerButton").click(
		function(){
			if($('#poelePowerButton').attr('src')  === "/img/power-green1.png"){
				$('#poelePowerButton').attr('src', "/img/power-blue1.png" );
				$.post( "/service/DataWService.php", {action : "offForced", value : true})
				.done(	function( data ) {
							readPoeleStatus();
						}
					);
				
			}else if($('#poelePowerButton').attr('src')  === "/img/power-red1.png"){
				$('#poelePowerButton').attr('src', "/img/power-blue1.png" );
				$.post( "/service/DataWService.php", {action : "onForced", value : true})
				.done(	function( data ) {
							readPoeleStatus();
						}
					);
			} 
		}
	);

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
	setInterval(readPoeleConfig, 30000);

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
		readCurrentTemp();
		readPoeleStatus();
		readPoeleConfig();
	}

	
	
	$( document ).ready(function() {
		init();
	});
	
</script>


</body>
</html>
