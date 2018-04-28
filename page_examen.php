<!-- On lance la session php -->
<?php session_start();?>
<!-- On vérifie que l'utilisateur est bien connecté et présent dans la base de données -->
<?php include('verification.php');?>

<?php if ($resultat) { ?>
	<?php 
	$id_exam = intval(htmlspecialchars($_GET['id']));
	//Requête pour récupérer les informations sur l'examen, sur le prescripteur qui a réalisé l'examen et sur le patient
	$req_one = $bdd->prepare("SELECT `patient`.`patient_num_secu` ,`patient`.`patient_nom`,`patient`.`patient_prenom`,`patient`.`patient_sexe`,`patient`.`patient_date_naissance`, `patient`.`patient_mail`, `patient`.`patient_num_tel`,`examen`.`examen_nom`,`examen`.`examen_date`,`examen`.`examen_pathologie`,`examen`.`examen_commentaires`, `examen`.`examen_panel_gene_id`, `personnel`.`personnel_nom`,`personnel`.`personnel_prenom`,`personnel`.`personnel_mail`,pere_patient.`patient_nom` AS pere_nom,pere_patient.`patient_prenom` AS pere_prenom, mere_patient.`patient_nom` AS mere_nom, mere_patient.`patient_prenom` mere_prenom FROM `gestion_prescription`.`patient` INNER JOIN `gestion_prescription`.`examen` ON `examen_patient_id`=`patient_id` INNER JOIN `gestion_prescription`.`personnel` ON `examen_personnel_id`=`personnel_id` LEFT JOIN `patient` as pere_patient ON pere_patient.`patient_id`=`patient`.patient_pere_id LEFT JOIN `patient` as mere_patient ON mere_patient.`patient_id`=`patient`.patient_mere_id WHERE `examen_id`=:id_exam AND `examen_personnel_id`=:id_prescripteur");
	$req_one->execute(array(
			'id_exam' => $id_exam,
			'id_prescripteur' => $_SESSION['id']
		));
	$row_informations = $req_one->fetch();


	$id_panel = $row_informations['examen_panel_gene_id'];
	$req_one->closeCursor();
	//Requête pour récuperer les gènes du panel liés à l'examen (sans le nom du panel)
	$req_two = $bdd->prepare("SELECT `gene`.`gene_nom` FROM `gestion_prescription`.`assoc_panel_gene` INNER JOIN `gestion_prescription`.`gene` ON `assoc_panel_gene`.`assoc_gene_id`=`gene`.`gene_id` WHERE `assoc_panel_id`=:id_panel;");
	$req_two->execute(array(
			'id_panel' => $id_panel
		));
	$rows_gene = $req_two->fetchAll();
	$req_two->closeCursor();
	?>


	<?php include('header.php'); ?>
			<div class="row" id="first_line"> <!-- DEBUT PREMIERE LIGNE cont.row1-->

				<div class="col-lg-7 col-lg-offset-1 jumbotron"> <!-- cont.row1.col1-->
					<div class="row">
						<legend><h2>Fiche de l'examen </h2></legend>
					</div>
					<div class="row">
						<div clas="col-lg-12">
							<h4><strong>Informations principales :</strong></h4>
							<table class="table table-striped">
								<tbody>
									<tr>
										<td><strong>Nom :</strong></td>
										<td><?php echo $row_informations['examen_nom']; ?></td>
									</tr>
									<tr>
										<td><strong>Pathologie :</strong></td>
										<td><?php echo $row_informations['examen_pathologie']; ?></td>
									</tr>
									<tr>
										<td><strong>Date :</strong></td>
										<td><?php echo $row_informations['examen_date']; ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="row">
						<div clas="col-lg-12">
							<div >
								<h4><strong>Commentaires :</strong></h4>
								<?php echo $row_informations['examen_commentaires']; ?>
								<legend></legend>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-lg-offset-3">
							<button class="btn btn-primary btn-lg btn-block" type="submit" ><span class="glyphicon glyphicon-save-file" aria-hidden="true"></span> Générer le rapport</button>
						</div>
					</div>
				</div> <!-- cont.row1.col1-->
				<div class="col-lg-4">
					<div class="row">
						<div class="col-lg-12">
							<div class="jumbotron">
								<div class="row">
									<legend><h4>Information patient </h4></legend>
								</div>
								<div class="row">
									<div clas="col-lg-12">
										<table class="table table-striped">
											<tbody>
												<tr>
													<td><strong>Nom :</strong></td>
													<td><?php echo $row_informations['patient_nom']." ".$row_informations['patient_prenom']; ?></td>
												</tr>
												<tr>
													<td><strong>Numéro de sécu :</strong></td>
													<td><?php echo $row_informations['patient_num_secu']; ?></td>
												</tr>
												<tr>
													<td><strong>Sexe :</strong></td>
													<td><?php echo $row_informations['patient_sexe']; ?></td>
												</tr>
												<tr>
													<td><strong>Date de naissance :</strong></td>
													<td><?php echo $row_informations['patient_date_naissance']; ?></td>
												</tr>
												<tr>
													<td><strong>Email :</strong></td>
													<td><a href="mailto:<?php echo $row_informations['patient_mail']; ?>"><?php echo $row_informations['patient_mail']; ?></a></td>
												</tr>
												<?php if($row_informations['patient_num_tel']) {?>
												<tr>
													<td><strong>Numéro tel :</strong></td>
													<td><?php echo $row_informations['patient_num_tel']; ?></td>
												</tr>
												<?php
													}
												if ($row_informations['pere_nom']) {?>
												<tr>
													<td><strong>Père :</strong></td>
													<td><?php echo $row_informations['pere_prenom']." ".$row_informations['pere_nom']; ?></td>
												</tr>
												<?php } ?>
												<?php if ($row_informations['mere_nom']) {?>
												<tr>
													<td><strong>Mère :</strong></td>
													<td><?php echo $row_informations['mere_prenom']." ".$row_informations['mere_nom']; ?></td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<div class="jumbotron">
								<div class="row">
									<legend><h4>Information prescripteur </h4></legend>
								</div>
								<div class="row">
									<div clas="col-lg-12">
										<table class="table table-striped">
											<tbody>
												<tr>
													<td><strong>Nom :</strong></td>
													<td><?php echo $row_informations['personnel_nom']; ?></td>
												</tr>
												<tr>
													<td><strong>Prenom :</strong></td>
													<td><?php echo $row_informations['personnel_prenom']; ?></td>
												</tr>
												<tr>
													<td><strong>Mail :</strong></td>
													<td><a href="mailto:<?php echo $row_informations['personnel_mail']; ?>"><?php echo $row_informations['personnel_mail']; ?></a></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- FIN PREMIERE LIGNE -->
			<div class="row"><!--cont.row2-->
				<div class="col-lg-4 col-lg-offset-8">
					<div class="row">
						<div class="col-lg-12">
							<div class="jumbotron">
								<div class="row">
									<legend><h4>Gènes utilisés </h4></legend>
								</div>
								<div class="row">
									<div clas="col-lg-12">
										<?php foreach ($rows_gene as $row) {
											echo "<span class=\"label label-primary\">".$row['gene_nom']."</span> ";
										} ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> <!--cont.row2-->
	<?php include('footer.php'); ?>
<?php } //La fin du if ?>