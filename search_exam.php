<!-- On lance la session php -->
<?php session_start();?>
<!-- On vérifie que l'utilisateur est bien connecté et présent dans la base de données -->
<?php include('verification.php');?>
<?php if ($resultat) { ?>
	<?php 
	$req_exam = $bdd->prepare("SELECT `examen_id`,`examen_nom`,`examen_date`,`examen_pathologie`,`patient_nom`,`patient_prenom` FROM `gestion_prescription`.`examen` INNER JOIN `gestion_prescription`.`patient` ON `examen`.`examen_patient_id`=`patient`.`patient_id` WHERE `examen_personnel_id`=:utilisateur_id;");
	//On execute
	$req_exam->execute(array(
	  'utilisateur_id' => $_SESSION['id']
	));

	?>
	<?php include('header.php'); ?>
			<div class="row" id="first_line"> <!-- DEBUT PREMIERE LIGNE cont.row1-->
				<div class="col-lg-8 col-lg-offset-2">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Examens de <?php echo $_SESSION['prenom']." ".$_SESSION['nom']; ?></h3>
						</div>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<td><strong>Nom</strong></td>
									<td><strong>Nom du patient</strong></td>
									<td><strong>Pathologie</strong></td>
									<td><strong>Date</strong></td>
								</tr>
							</thead>
							<tbody>
								<?php while ($row = $req_exam->fetch()) { ?>
									<tr>
										<td><a href="page_examen.php?id=<?php echo $row['examen_id']; ?>"><?php echo $row['examen_nom']; ?></a></td>
										<td><a href="page_examen.php?id=<?php echo $row['examen_id']; ?>"><?php echo $row['patient_prenom']." ".$row['patient_nom']; ?></a></td>
										<td><a href="page_examen.php?id=<?php echo $row['examen_id']; ?>"><?php echo $row['examen_pathologie']; ?></a></td>
										<td><a href="page_examen.php?id=<?php echo $row['examen_id']; ?>"><?php echo $row['examen_date']; ?></a></td>
									</tr>
								<?php } 
								$req_exam->closeCursor(); ?>
							</tbody>
						</table>
					</div>
				</div>
			</div> <!-- FIN PREMIERE LIGNE -->
	<?php include('footer.php'); ?>
<?php } //La fin du if ?>