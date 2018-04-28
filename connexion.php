<?php
//Connexion à la base de données gestion_prescription, présente sur toutes les pages
try
{
	$bdd = new PDO('mysql:host=localhost;dbname=gestion_prescription;charset=utf8', 'root', 'root');
}
//On gère l'erreur pour ne pas afficher les logs de la base de données
catch (Exception $e)
{
		die('Erreur : ' . $e->getMessage());
}
?>