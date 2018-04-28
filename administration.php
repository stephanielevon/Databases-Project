<?php
//Gère toutes les requêtes d'administration, présente sur la page d'index uniquement lorsque l'utilisateur est considéré comme administrateur

//Sélection des utilisateurs et des gènes pour l'affichage
$sql = "SELECT `personnel_id`,`personnel_nom`,`personnel_prenom` FROM `gestion_prescription`.`personnel` WHERE `personnel_actif`= 0;";
$reponse = $bdd->query($sql);
//résultats stocké dans la variable $rows_personnel
$rows_personnel = $reponse->fetchAll();
$reponse->closeCursor(); //On close la requête

//Sélection du type d'utilisateur disponible pour la création d'un nouvel utilisateur
$sql = "SELECT `type_personnel_id`,`type_personnel_nom` FROM `gestion_prescription`.`type_personnel`;";
$reponse = $bdd->query($sql);
//résultats stocké dans la variable $rows_type_user
$rows_type_user = $reponse->fetchAll();
$reponse->closeCursor();

//Sélection des gènes, va être utilisé lors de la création ou la supression d'un gène
//Le gène doit être considéré comme actif(non suprimmé)
$sql = "SELECT `gene_id`,`gene_nom` FROM `gestion_prescription`.`gene` WHERE `gene_actif`= 0;";
$reponse = $bdd->query($sql);
//résultats stockés dans $rows_gene
$rows_gene = $reponse->fetchAll();
$reponse->closeCursor();//On close la requête

//Suprimmer un gène, dans le cas ou on envoi le formulaire "del_gene"
if (isset($_POST['del_gene'])) {
	$id_gene = htmlspecialchars($_POST['list_gene']);
	//Au lieu de suprimmer le gène, on met à jour son statut en tant qu'"inactif"
	$req_gene = $bdd->prepare("UPDATE `gestion_prescription`.`gene` SET `gene_actif`= 1 WHERE `gene`.`gene_id` = :id_gene;");
	//On execute
	$req_gene->execute(array(
		'id_gene' => $id_gene
	));
	$req_gene->closeCursor(); //On close la requête


}
//Suprimmer un utilisateur, dans le cas ou on envoi le formulaire del_user
if (isset($_POST['del_user'])) {
	$id_personnel = htmlspecialchars($_POST['list_user']);
	//On ne suprimme pas la ligne, mais on update le statut en tant qu'inactif
	$req_del_user = $bdd->prepare("UPDATE `gestion_prescription`.`personnel` SET `personnel_actif`= 1 WHERE `personnel`.`personnel_id` = :id_personnel;");
	//On execute
	$req_del_user->execute(array(
		'id_personnel' => $id_personnel
	));
	$req_del_user->closeCursor();//On close la requête


}
//Suprimmer un patient, dans le cas ou on envoi le formulaire del_patient
if (isset($_POST['del_patient'])) {
	$id_patient = htmlspecialchars($_POST['list_patient']);
	//L'utilisateur est updaté en tant qu'inactif, mais pas suprimmé de la base de données
	$req_del_user = $bdd->prepare("UPDATE `gestion_prescription`.`patient` SET `patient_actif`= 1 WHERE `patient`.`patient_id` = :id_patient;");
	//On execute
	$req_del_user->execute(array(
		'id_patient' => $id_patient
	));
	$req_del_user->closeCursor();//On close la requête


}
//Ajouter un utilisateur
if (isset($_POST['add_user'])) {
	//Récupération des informations envoyés par le formulaire, on échappe les caractère et on encrypte le mot de passe den SHA1
	$user_prenom = htmlspecialchars($_POST['nom_user']);
	$user_nom = htmlspecialchars($_POST['prenom_user']);
	$user_mail = htmlspecialchars($_POST['mail_user']);
	$user_password = htmlspecialchars(sha1($_POST['pass_user']));
	$user_type = htmlspecialchars($_POST['list_type_user']);
	//On prépare la requête d'insertion, en ajoutant les variables du formulaire
	$req_add_user = $bdd->prepare("INSERT INTO `gestion_prescription`.`personnel` (`personnel_id`, `personnel_prenom`, `personnel_nom`, `personnel_mail`, `personnel_password`, `personnel_type_personnel_id`, `personnel_actif`) VALUES (NULL, :user_prenom, :user_nom, :user_mail, :user_password, :user_type, 0);");
	//On execute
	$req_add_user->execute(array(
		'user_prenom' => $user_prenom,
		'user_nom' => $user_nom,
		'user_mail' => $user_mail,
		'user_password' => $user_password,
		'user_type' => $user_type
	));
	$req_add_user->closeCursor(); //On close la requête


}


?>
<!-- Toute l'initerface qui va être capable de gérer les requêtes d'administration -->

	<!-- Fenetre qui s'ouvre lorsque l'on clique sur "suprimmer un utilisateur" -->
	<div class="modal fade" id="del_user" tabindex="-1" role="dialog" aria-labelledby="modal_del_user" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modal_del_user">Suprimmer un utilisateur</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" action="index.php" method="post">
						<div class="form-group">
							<label class="col-md-3 control-label" for="selection_user">Selection :</label>
							<div class="col-md-9">
								<select class="form-control" id="selection_user" name="list_user">
								<?php foreach ($rows_personnel as $row) { ?>
									<option name="<?php echo $row['personnel_nom']."_".$row['personnel_prenom']; ?>" value="<?php echo $row['personnel_id']; ?>"><?php echo $row['personnel_nom']." ".$row['personnel_prenom']; ?></option>
								<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
						<button type="submit" name="del_user" value="del_user" class="btn btn-danger">Suprimmer l'utilisateur</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Fin Fenetre qui s'ouvre lorsque l'on clique sur "suprimmer un utilisateur" -->

	<!-- Fenetre qui s'ouvre lorsque l'on clique sur "suprimmer un patient" -->
	<div class="modal fade" id="del_patient" tabindex="-1" role="dialog" aria-labelledby="modal_del_patient" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modal_del_patient">Suprimmer un patient</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" action="index.php" method="post">
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
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
						<button type="submit" name="del_patient" value="del_patient" class="btn btn-danger">Suprimmer le patient</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Fin Fenetre qui s'ouvre lorsque l'on clique sur "suprimmer un patient" -->


	<!-- Fenetre qui s'ouvre lorsque l'on clique sur "ajouter un utilisateur" -->
	<div class="modal fade" id="add_user" tabindex="-1" role="dialog" aria-labelledby="modal_add_user" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modal_add_user">Ajouter un utilisateur</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" action="index.php" method="post">
						<div class="form-group">
								<label for="nom_user" class="col-sm-3 control-label">Nom :</label>
								<div class="col-sm-8 col-sm-offset-1">
									<input type="text" class="form-control" id="nom_user" name="nom_user">
								</div>
						</div>
						<div class="form-group">
								<label for="prenom_user" class="col-sm-3 control-label">Prénom :</label>
								<div class="col-sm-8 col-sm-offset-1">
									<input type="text" class="form-control" id="prenom_user" name="prenom_user">
								</div>
						</div>
						<div class="form-group">
								<label for="mail_user" class="col-sm-3 control-label">Email :</label>
								<div class="col-sm-8 col-sm-offset-1">
									<input type="email" class="form-control" id="mail_user" name="mail_user">
								</div>
						</div>
						<div class="form-group">
								<label for="pass_user" class="col-sm-3 control-label">Mot de passe :</label>
								<div class="col-sm-8 col-sm-offset-1">
									<input type="password" class="form-control" id="pass_user" name="pass_user">
								</div>
						</div>
						<div class="form-group">
							<label class="col-md-3 control-label" for="selection_type_user">Statut :</label>
							<div class="col-sm-8 col-sm-offset-1">
								<select class="form-control" id="selection_user" name="list_type_user">
								<?php foreach ($rows_type_user as $row) { ?>
									<option name="<?php echo $row['type_personnel_nom']; ?>" value="<?php echo $row['type_personnel_id']; ?>"><?php echo $row['type_personnel_nom']; ?></option>
								<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
						<button type="submit" name="add_user" value="add_user" class="btn btn-primary">Ajouter un utilisateur</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Fin Fenetre qui s'ouvre lorsque l'on clique sur "ajouter un utilisateur" -->

	<!-- Fenetre qui s'ouvre lorsque l'on clique sur "suprimmer un gène" -->
	<div class="modal fade" id="del_gene" tabindex="-1" role="dialog" aria-labelledby="modal_del_gene" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="modal_del_gene">Suprimmer un gène</h4>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" action="index.php" method="post">
						<div class="form-group">
							<label class="col-md-3 control-label" for="selection_patient">Selection :</label>
							<div class="col-md-9">
								<select class="form-control" id="selection_patient" name="list_gene">
								<?php foreach ($rows_gene as $row) { ?>
									<option name="<?php echo $row['gene_nom']; ?>" value="<?php echo $row['gene_id']; ?>"><?php echo $row['gene_nom']; ?></option>
								<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
						<button type="submit" name="del_gene" value="del_gene" class="btn btn-danger">Suprimmer le gène</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<!-- Fin Fenetre qui s'ouvre lorsque l'on clique sur "suprimmer un patient" -->

