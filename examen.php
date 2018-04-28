<!-- On lance la session php -->
<?php session_start();?>
<!-- On vérifie que l'utilisateur est bien connecté et présent dans la base de données -->
<?php include('verification.php');?>
<!-- Si la requête de vérification contient un résultat, on peut afficher l'interface et executer les requêtes -->
<?php if ($resultat) { 
	if (!empty( $_POST['examen_nom']) && !empty( $_POST['examen_pathologie'])) { //Si le nom panel_name existe et que POST contient au moins un gene plus le nom du panel, on peut executer la requête
		//On récupère les données envoyées en formulaire tout en échappant les caractères
		$examen_nom = htmlspecialchars($_POST['examen_nom']);
		$examen_pathologie = htmlspecialchars($_POST['examen_pathologie']);
		$examen_commentaires = htmlspecialchars($_POST['examen_commentaires']);
		$examen_patient_id = intval(htmlspecialchars($_POST['list_patient']));
		$examen_panel_id = intval(htmlspecialchars($_POST['list_panel']));
		$examen_date = date("Y-m-d");
		//On prépare la requête d'insertion qui va créer le nouveau panel
		$req = $bdd->prepare("INSERT INTO `gestion_prescription`.`examen` (`examen_id`, `examen_nom`, `examen_date`, `examen_pathologie`, `examen_commentaires`, `examen_patient_id`, `examen_panel_gene_id`, `examen_personnel_id`) VALUES (NULL, :exam_nom, :exam_date, :exam_pathologie, :exam_commentaires, :patient_id, :panel_id, :personnel_id);");
		//On execute
		$req->execute(array(
			'exam_nom' => $examen_nom,
			'exam_date' => $examen_date, 
			'exam_pathologie' => $examen_pathologie,
			'exam_commentaires' => $examen_commentaires,
			'patient_id' => $examen_patient_id,
			'panel_id' => $examen_panel_id, 
			'personnel_id' => $_SESSION['id']
		));
		$req->closeCursor(); // on indique que la requête est terminée
	}
	?>

	<?php 
	//Requête pour récuperer les noms et prénoms des patients
	$sql = "SELECT `patient_id`,`patient_nom`,`patient_prenom` FROM `gestion_prescription`.`patient`;";
	$reponse = $bdd->query($sql);
	$rows_patient = $reponse->fetchAll(); //On stock les données dans la variable $rows_patient
	$reponse->closeCursor(); // on indique que la requête est terminée
	//Requête pour récuperer les différents panels
	$sql = "SELECT `panel_gene_nom`,`panel_gene_id` FROM `gestion_prescription`.`panel_gene`;";
	$reponse = $bdd->query($sql);
	$rows_panel = $reponse->fetchAll(); //On stock les données dans la variable $rows_panel
	$reponse->closeCursor(); // on indique que la requête est terminée
	?>

	<!-- On inclut le header -->
	<?php include('header.php'); ?>
			<div class="row" id="first_line"> <!-- DEBUT PREMIERE LIGNE cont.row1-->
				<div class="col-lg-10 col-lg-offset-1"> <!-- cont.row1.col1-->
					<?php if (!empty( $_POST['examen_nom']) && !empty( $_POST['examen_pathologie'])) {//Si on envoit le formulaire sur la page, on indique le succes de l'opération sur l'interface sous la forme d'une petite fenêtre qu'on peut fermer ?>
					<div class="row">
						<div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<strong><span class='glyphicon glyphicon-ok-circle'></span> Création de l'examen réussi !</strong> 
						</div>
					</div>
					<?php } ?>
					<!-- Première ligne pour le titre -->
					<div class="row">
						<legend><h1>Créer un nouvel examen</h1></legend>
					</div>
					<!-- Début du formulaire -->
					<form class="form-horizontal" action="examen.php" method="post">
						<!-- Deuxième ligne, avec les input pour choisir le nom de l'examen, et sa pathologie -->
						<!-- Ainsi que la sélection du patient et du panel -->
						<div class="row">
							<div class="col-lg-6">
								<div class="well well-sm">
									<legend class="text-center">Informations principales</legend>
									<div class="form-group">
										<label class="col-md-3 control-label" for="nom">Nom :</label>
										<div class="col-md-9">
											<input id="nom" name="examen_nom" type="text" placeholder="Nom de l'examen" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" for="patho">Pathologie :</label>
										<div class="col-md-9">
											<input id="patho" name="examen_pathologie" type="text" placeholder="Nom de la pathologie" class="form-control">
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" for="date">Date :</label>
										<div class="col-md-9">
											<p class="form-control-static" id="date"><strong><?php echo date("d-m-Y"); ?></strong></p>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-8 col-md-offset-2">
											<button class="btn btn-primary btn-lg btn-block" type="submit" ><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> Créer l'examen</button>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="row">
									<div class="well well-sm">
										<legend class="text-center">Informations Patient</legend>
										<div class="form-group">
											<label class="col-md-3 control-label" for="selection_patient">Selection :</label>
											<div class="col-md-9">
												<select class="form-control" id="selection_patient" name="list_patient">
												<?php foreach ($rows_patient as $row) { ?>
													<option name="<?php echo $row['patient_nom']."_".$row['patient_prenom']; ?>" value="<?php echo $row['patient_id']; ?>"><?php echo $row['patient_nom']." ".$row['patient_prenom']; ?></option>
												<?php } ?>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="well well-sm">
										<legend class="text-center">Sélection du panel</legend>
										<div class="form-group">
											<label class="col-md-3 control-label" for="selection_panel">Selection :</label>
											<div class="col-md-9">
												<select class="form-control" id="selection_panel" name="list_panel">
												<?php foreach ($rows_panel as $row) { ?>
													<option name="<?php echo $row['panel_gene_nom']; ?>" value="<?php echo $row['panel_gene_id']; ?>"><?php echo $row['panel_gene_nom']; ?></option>
												<?php } ?>
												</select>
											</div>
										</div>				
									</div>
								</div>
							</div>
						</div>
						<!-- Troisième ligne, contient le formulaire pour inclure les commentaires sur l'examen -->
						<div class="row">
							<div class="col-lg-12">
								<div class="well well-sm">
									<legend class="text-center">Commentaires</legend>
											<textarea name="examen_commentaires" class="form-control" rows="10" style="resize:vertical;"></textarea>
								</div>
							</div>
						</div>
					</form> <!-- Fin du formulaire -->
				</div>
			</div> <!-- FIN PREMIERE LIGNE -->
	<?php include('footer.php'); ?>
<?php } //La fin du if ?>

