<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<a class="navbar-brand" href="index.php"><span id="nav_maintitle">Hopital Core Database</span></a>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav navbar-left">
				<li><a href="examen.php"><strong><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> examen</strong></a></li>
				<li><a href="panel.php"><strong><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> panel</strong></a></li>
				<li><a href="search_exam.php"><strong><span class="glyphicon glyphicon-search" aria-hidden="true"></span> examens</strong></a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#"><strong><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?php echo $_SESSION['type']; ?> <?php echo $_SESSION['prenom']." ".$_SESSION['nom']; ?></strong></li></a>
				<li><a href="index.php?action=deconnexion"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Deconnexion</li></a>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>