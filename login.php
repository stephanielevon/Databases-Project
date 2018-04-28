<!-- Page de login, est inclue automatiquement à la première session et si l'utilisateur n'est pas identifiable dans la base -->
<!-- A partir de cette page, on peut soit se connecter si on est un utilisateur, soit récupérer le dernier examen réalisé si on est un patient -->
		<div class="col-lg-4 col-lg-offset-4">
			<div class="row">
				<div class="well well-lg">
					<legend><h2 class="text-center">Authentification</h2></legend>
					<form method="post" action="index.php">
						<div class="form-group">
							<label for="mail">Adresse Email</label>
							<input name="email_utilisateur" type="email" class="form-control" id="mail" placeholder="Entrez votre adresse email">
						</div>
						<div class="form-group">
							<label for="mdp">Mot de passe</label>
							<input name="mdp_utilisateur" type="password" class="form-control" id="mdp" placeholder="Entrez votre mot de passe">
						</div>
						<legend></legend>
						<button type="submit" class="btn btn-primary btn-lg btn-block">Connexion</button>
					</form>
				</div>
			</div>
			<div class="row">
				<div class="well well-lg">
					<legend><h2 class="text-center">Vous êtes un patient ?</h2></legend>
					<form method="post" action="recup_rapport.php">
						<div class="form-group">
							<label for="mail">Adresse Email</label>
							<input name="email_patient" type="email" class="form-control" id="mail" placeholder="Entrez votre adresse email">
						</div>
						<div class="form-group">
							<label for="num_secu">Numéro de sécurité sociale</label>
							<input name="num_secu_patient" type="text" class="form-control" id="num_secu" placeholder="Entrez votre numéro de sécurité sociale">
						</div>
						<legend></legend>
						<button type="submit" class="btn btn-danger btn-lg btn-block">Résultats du dernier examen</button>
					</form>
				</div>
			</div>
		</div>
		</div> <!-- FIN PREMIERE LIGNE -->
		</div> <!-- FIN CONTAINER -->

		<!-- Bootstrap core JavaScript
		================================================== -->
		<script src="source/js/jquery.js"></script>
		<script src="source/js/bootstrap.min.js"></script>
	</body>
</html>