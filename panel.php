<!-- On lance la session php -->
<?php session_start();?>
<!-- On vérifie que l'utilisateur est bien connecté et présent dans la base de données -->
<?php include('verification.php');?>

<?php if ($resultat) { 
if (!empty($_POST['panel_name']) && count($_POST)>2) { //Si le nom panel_name existe et que POST contient au moins un gene plus le nom du panel, on peut executer la requête
	//On met affichage à 1 pour afficher le résultat de l'insertion sur la page
	$affichage = 1;
	//On récupère le nom du panel, et on échappe les caractères
	$nom_panel = htmlspecialchars($_POST['panel_name']);
	unset($_POST['panel_name']);
	//On détermine le nombre de gènes à ajouter dans le panel
	$nombre_genes = count($_POST);
	//On prépare la requête d'insertion qui va créer le nouveau panel
	$req_one = $bdd->prepare("INSERT INTO `gestion_prescription`.`panel_gene` (`panel_gene_id`, `panel_gene_nom`) VALUES (NULL, :panel_name);");
	//On execute
	$req_one->execute(array(
		'panel_name' => $nom_panel
	));
	$req_one->closeCursor();
	//On récupère l'id du panel nouvellement créer
	$panel_id = $bdd->lastInsertId();
	$req_two = $bdd->prepare("INSERT INTO `gestion_prescription`.`assoc_panel_gene` (`assoc_gene_id`, `assoc_panel_id`) VALUES (:gene_id, :panel_id);");
	foreach($_POST as $key => $val) {
		$gene_id = intval(htmlspecialchars($val));
		$req_two->execute(array(
			'gene_id' => $gene_id,
			'panel_id' => $panel_id				
		));
	} 
	$req_two->closeCursor();
}
//On récupère tous les gènes de la base
$sql = "SELECT gene_id,gene_nom,gene_chromosome FROM `gestion_prescription`.`gene` WHERE `gene_actif`= 0;";
$reponse = $bdd->query($sql);
$rows = $reponse->fetchAll();
//On parcours les éléments de la requête, et on récupère les chromosomes pour le tri
foreach ($rows as $row){
	$chromosome_list[$row['gene_chromosome']] = '';
}
//On tri les chromosomes dans le bon ordre pour l'affichage
ksort($chromosome_list);
$reponse->closeCursor();
?>


	<?php include('header.php'); ?>
			<div class="row" id="first_line"> <!-- DEBUT PREMIERE LIGNE cont.row1-->
			<div class="col-lg-8 col-lg-offset-2"> <!-- cont.row1.col1-->
			<?php if ( $affichage == 1 ) {  ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<strong><span class='glyphicon glyphicon-ok-circle'></span> Création du panel réussi !</strong> Le panel <strong><?php echo $nom_panel; ?></strong> a bien été créer et contient <strong><?php echo $nombre_genes; ?> gènes</strong>
			</div>
			<?php } ?>
				<div class="row"> <!-- cont.row1.col1.row1-->
				  <div class="col-lg-12">
					<form class="form-inline" method="post" action="panel.php">
						<div class="row">
							<h1>Créer un nouveau panel de gènes</h1>
						</div>
						<div class="row">
							<div class="jumbotron">
								<div class="form-group">
									<label for="panelname">Nom du Panel :</label>
									<input type="text" class="form-control" id="panel_name" placeholder="Entrez un nom de panel" name="panel_name" required>
									<button class="btn btn-primary" type="submit" ><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span> Créer le panel</button>
									<button class="btn btn-danger" type="reset"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Recommencer le panel</button>
								</div>
							</div>
						</div>
						<?php foreach (array_keys($chromosome_list) as $chromosome) {?>
						<div class="row">
							<div class="jumbotron">
							<h4>Chromosome <?php echo $chromosome; ?></h4>
							<?php foreach ($rows as $row) {
									if ($row['gene_chromosome']== $chromosome ) {?>
										<label class="checkbox-inline"><input type="checkbox" name="<?php echo $row['gene_nom']; ?>" value="<?php echo $row['gene_id']; ?>"> <span class="label label-primary"><?php echo $row['gene_nom']; ?></span></label>
									<?php } 
								} ?>
							</div>
						</div>
						<?php } ?>
					</form>
				  </div>
				</div> <!-- cont.row1.col1.row1-->
			</div> <!-- cont.row1.col1-->
			</div> <!-- FIN PREMIERE LIGNE -->
	<?php include('footer.php'); ?>
<?php } //La fin du if ?>