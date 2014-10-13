<!-- Static navbar -->
<div class="navbar navbar-default navbar-static-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse"
				data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span> <span
					class="icon-bar"></span> <span class="icon-bar"></span> <span
					class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/index.php">DomoWeb</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
<!-- 				<li class="active"><a href="/index.php">Home</a></li> -->
<!-- 				<li><a href="#about">About</a></li> -->
<!-- 				<li><a href="#contact">Contact</a></li> -->
				<li class="dropdown"><a href="#" class="dropdown-toggle"
					data-toggle="dropdown">Configurer <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="/view/tempChartsView.php">Historique des températures</a></li>
						<li class="divider"></li>
						<li><a href="/view/periodesView.php">Périodes</a></li>
						<li><a href="/view/modesView.php">Modes</a></li>
					</ul>
				</li>
				<li class="dropdown"><a href="#" class="dropdown-toggle"
					data-toggle="dropdown">Administrer <b class="caret"></b></a>
					<ul class="dropdown-menu">						
						<li><a href="/view/recapPoeleView.php">Paramètres</a></li>
						<li><a id="update" href="#">Mettre à jour</a></li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="/logout.php">Déconnexion</a></li>
			</ul>
		</div>
		<!--/.nav-collapse -->
	</div>
</div>

