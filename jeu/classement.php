<?php
session_start();
require_once("../fonctions.php");
require_once("f_combat.php");

$mysqli = db_connexion();

include ('../nb_online.php');

date_default_timezone_set('Europe/Paris');

function get_id_type_perso($type_perso) {
	switch ($type_perso) {
	case "chef": return 1;
	case "cav_lourde": return 2;
	case "infanterie": return 3;
	case "soigneur": return 4;
	case "artillerie": return 5;
	case "chien": return 6;
	case "cav_legere": return 7;
	default :
		return 0;
	}
}

if (isset($_POST["choix_class"])){
	
	$num_class = $_POST["choix_class"];
	
	$verif = preg_match("#^[0-9]+$#i",$num_class);
		
	if($verif){
		header("Location:classement.php?top=ok&classement=$num_class");	
	}
	else {
		$erreur = 'Paramètre incorrect';
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Nord VS Sud - Classement</title>
		
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="https://drvic10k.github.io/bootstrap-sortable/Contents/bootstrap-sortable.css" />
		<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
		
	</head>

	<body background="../images/background.jpg">
	
		<div class="container">

<?php
if(isset($erreur)){
	echo "<center><font color='red'>$erreur</font></center>";
}

if(isset($_GET["top"])){
	echo "<div align=\"center\"><h2><font color=darkred>Top 50</font></h2></div>";
	
	echo "<div align=\"center\">";
	echo "	<a class='btn btn-warning' href=\"index.php\">Retour Accueil</a>";
	echo "	<a class='btn btn-warning' href=\"jouer.php\">Retour au jeu</a>";
	echo "</div>";
	echo "<br/>";
	
	echo "<div align=\"center\">";
	echo "	<a class='btn btn-primary' href=\"classement.php?grade\">Haut gradés</a>";
	echo "	<a class='btn btn-primary' href=\"classement.php\">Experience</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?dernier_tombe=ok\">Derniers tombés</a>";
	echo "</div>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=1\">Machines à tuer</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=2\">Habitués des hopitaux</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=3\">Chasseurs</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=4\">Grosses fortunes</a>";
	echo "</center>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?titre=ok\">Les Titres</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?training=ok\">Les pros de l'entrainement</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?super=ok\">Les Supermans</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?stats=ok\">Les Statistiques de chaque camps</a>";
	echo "</center>";
	echo "<br/>";

	$type_perso = isset($_GET["type_perso"]) ? $_GET["type_perso"] : 'tous';
	$id_type_perso = get_id_type_perso($type_perso);
	echo '<div align=center><form method="GET">';
	echo '<input type="hidden" name="top" value="ok"/>';
	echo '<input type="hidden" name="classement" value="'.(isset($_GET["classement"]) ? $_GET["classement"] : 1).'"/>';
	echo '<input type="radio" id="tous" name="type_perso" value="tous" onclick="this.form.submit();" '.($type_perso == 'tous' ? 'checked' : '').'> <label for="tous">Tous</label>';
	echo '<input type="radio" id="chef" name="type_perso" value="chef" onclick="this.form.submit();" '.($type_perso == 'chef' ? 'checked' : '').'> <label for="chef">Chef</label>';
	echo '<input type="radio" id="cav_lourde" name="type_perso" value="cav_lourde" onclick="this.form.submit();" '.($type_perso == 'cav_lourde' ? 'checked' : '').'> <label for="cav_lourde">Cavalerie lourde</label>';
	echo '<input type="radio" id="infanterie" name="type_perso" value="infanterie" onclick="this.form.submit();" '.($type_perso == 'infanterie' ? 'checked' : '').'> <label for="infanterie">Infanterie</label>';
	echo '<input type="radio" id="soigneur" name="type_perso" value="soigneur" onclick="this.form.submit();" '.($type_perso == 'soigneur' ? 'checked' : '').'> <label for="soigneur">Soigneur</label>';
	echo '<input type="radio" id="artillerie" name="type_perso" value="artillerie" onclick="this.form.submit();" '.($type_perso == 'artillerie' ? 'checked' : '').'> <label for="artillerie">Artillerie</label>';
	echo '<input type="radio" id="chien" name="type_perso" value="chien" onclick="this.form.submit();" '.($type_perso == 'chien' ? 'checked' : '').'> <label for="chien">Chien</label>';
	echo '<input type="radio" id="cav_legere" name="type_perso" value="cav_legere" onclick="this.form.submit();" '.($type_perso == 'cav_legere' ? 'checked' : '').'> <label for="cav_legere">Cavalerie légère</label>';
	echo '</form></div>';
	
	if(isset($_GET["classement"])) {
		$num_c = $_GET["classement"];
		$verif = preg_match("#^[0-9]+$#i",$num_c);
		
		if($verif){
			switch($num_c) {
				case 1:
					$class = "nb_kill";
					break;
				case 2:
					$class = "nb_mort";
					break;
				case 3:
					$class = "nb_pnj";
					break;
				case 4:
					$class = "or_perso";
					break;
				default:
					$class = "nb_kill";
					break;
			}
		}
	}
	else {
		$class = "nb_kill";
	}
	
	if((isset($verif) && $verif) || !isset($_GET["classement"])){
	
		$sql = "SELECT id_perso, nom_perso, clan, $class FROM perso WHERE id_perso >= '100' ".($id_type_perso ? "AND type_perso=$id_type_perso" : "")." ORDER BY $class DESC LIMIT 50";
		$res = $mysqli->query($sql);
		
		echo "<div class='table-responsive'>";
		echo "	<table class='table table-bordered table-hover sortable' style='width:100%'>";
		echo "		<thead>";
		echo "			<tr>";
		echo "				<th><font color=darkred>position</font></th><th><font color=darkred>Nom[id]</font></th><th><font color=darkred>$class</font></th>";
		echo"			</tr>";
		echo "		</thead>";
		echo "		<tbody>";
		
		$c = 0;
		
		if($class == "nb_kill") {
			echo "<center><h3><font color=darkred>Les machines à tuer</font></h3></center>";
		}
		if($class == "nb_mort") {
			echo "<center><h3><font color=darkred>Les habitués des hôpitaux</font></h3></center>";
		}
		if($class == "nb_pnj") {
			echo "<center><h3><font color=darkred>Les Chasseurs</font></h3></center>";
		}
		if($class == "or_perso") {
			echo "<center><h3><font color=darkred>Grosses fortunes</font></h3></center>";
		}
			
		while($t = $res->fetch_assoc()){
			
			$c++;
			
			$id_perso	= $t['id_perso'];
			$nom_perso	= $t['nom_perso'];
			$id_camp 	= $t["clan"];
			
			if($id_camp == "1"){
				$couleur_camp = "blue";
			}
			if($id_camp == "2"){
				$couleur_camp = "red";
			}
			
			echo "			<tr>";
			echo "				<td width=10><center>$c</center></td>";
			echo "				<td align=center><font color='".$couleur_camp."'>" .$nom_perso. "</font> [<a href=\"evenement.php?infoid=".$id_perso."\">" .$id_perso. "</a>]</td><td align=center width=150>".$t["$class"]."</td>";
			echo "			</tr>";
		}
		
		echo "		</tbody>";
		echo "	</table>";
		echo "</div>";
	}
	else {
		echo "<br /><center><b>Paramètre incorrect</b></center>";
	}
}

if(isset($_GET["titre"])){
	echo "<div align=\"center\"><h2><font color=darkred>Les Titres</font></h2></div>";

	echo "<div align=\"center\">";
	echo "	<a class='btn btn-warning' href=\"index.php\">Retour Accueil</a>";
	echo "	<a class='btn btn-warning' href=\"jouer.php\">Retour au jeu</a>";
	echo "</div>";
	echo "<br/>";
	
	echo "<div align=\"center\">";
	echo "	<a class='btn btn-primary' href=\"classement.php?grade\">Haut gradés</a>";
	echo "	<a class='btn btn-primary' href=\"classement.php\">Experience</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?dernier_tombe=ok\">Derniers tombés</a>";
	echo "</div>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=1\">Machines à tuer</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=2\">Habitués des hopitaux</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=3\">Chasseurs</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=4\">Grosses fortunes</a>";
	echo "</center>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?titre=ok\">Les Titres</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?training=ok\">Les pros de l'entrainement</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?super=ok\">Les Supermans</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?stats=ok\">Les Statistiques de chaque camps</a>";
	echo "</center>";
	echo "<br/>";
	
	$sql = "SELECT id_pnj FROM perso_as_killpnj GROUP BY id_pnj";
	$res = $mysqli->query($sql);
	
	echo "<div class='table-responsive'>";
	echo "	<table class='table table-bordered table-hover sortable' style='width:100%'>";
	echo "		<thead>";
	echo "			<tr>";
	echo "				<th><font color=darkred>Nom[id]</font></th>";
	echo "				<th><font color=darkred>Titre</font></th>";
	echo "			</tr>";
	echo "		</thead>";
	echo "		<tbody>";
	
	while ($t_pnj = $res->fetch_assoc()){
		$id_pnj = $t_pnj["id_pnj"];
		
		//echo "id_pnj : ".$id_pnj." ";
		
		$sql_p = "SELECT id_perso, id_pnj, nb_pnj FROM perso_as_killpnj WHERE nb_pnj=(SELECT MAX(nb_pnj) FROM perso_as_killpnj WHERE id_pnj=$id_pnj) AND id_pnj=$id_pnj";
		$res_p = $mysqli->query($sql_p);

		while($t = $res_p->fetch_assoc()){
			
			$id_perso_t = $t["id_perso"];
			$id_pnj_t 	= $t["id_pnj"];
			$nb_pnj_t 	= $t["nb_pnj"];
			
			// recuperation du nom du perso
			$sql_n = "SELECT nom_perso, clan FROM perso WHERE id_perso='$id_perso_t'";
			$res_n = $mysqli->query($sql_n);
			$t_n = $res_n->fetch_assoc();
			
			$nom_perso 	= $t_n["nom_perso"];
			$camp_perso	= $t_n["clan"];
			
			if($camp_perso == "1"){
				$couleur_camp = "blue";
			}
			if($camp_perso == "2"){
				$couleur_camp = "red";
			}
			
			// recuperation du nom du pnj
			$sql_n = "SELECT nom_pnj FROM pnj WHERE id_pnj='$id_pnj_t'";
			$res_n = $mysqli->query($sql_n);
			$t_n = $res_n->fetch_assoc();
			
			$nom_pnj = $t_n["nom_pnj"];
			
			$titre = "<font color='".$couleur_camp."'>".$nom_perso."</font> le Pourfendeur de ".$nom_pnj."s";
			
			echo "			<tr>";
			echo "				<td align=center><font color='".$couleur_camp."'>" .$nom_perso. "</font> [<a href=\"evenement.php?infoid=".$id_perso_t."\">" .$id_perso_t. "</a>]</td>";
			echo "				<td align=center width=75%><b>".$titre."</></td>";
			echo "			</tr>";
		}
	}
	
	echo "		</tbody>";
	echo "	</table>";
	echo "</div>";
	
}

if(isset($_GET["stats"]) && $_GET["stats"] == 'ok'){
	echo "<div align=\"center\"><h2><font color=darkred>Statistiques des camps</font></h2></div>";
	
	echo "<div align=\"center\">";
	echo "	<a class='btn btn-warning' href=\"index.php\">Retour Accueil</a>";
	echo "	<a class='btn btn-warning' href=\"jouer.php\">Retour au jeu</a>";
	echo "</div>";
	echo "<br/>";
	
	echo "<div align=\"center\">";
	echo "	<a class='btn btn-primary' href=\"classement.php?grade\">Haut gradés</a>";
	echo "	<a class='btn btn-primary' href=\"classement.php\">Experience</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?dernier_tombe=ok\">Derniers tombés</a>";
	echo "</div>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=1\">Machines à tuer</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=2\">Habitués des hopitaux</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=3\">Chasseurs</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=4\">Grosses fortunes</a>";
	echo "</center>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?titre=ok\">Les Titres</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?training=ok\">Les pros de l'entrainement</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?super=ok\">Les Supermans</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?stats=ok\">Les Statistiques de chaque camps</a>";
	echo "</center>";
	echo "<br/>";
	
	// recuperation des stats
	$sql = "SELECT id_camp, nb_kill FROM stats_camp_kill";
	$res = $mysqli->query($sql);
	
	// Nombre de joueurs actifs au Nord
	$sql_nbb = "SELECT id_perso FROM perso WHERE clan='1' AND est_gele='0' and chef=1";
	$res_nbb = $mysqli->query($sql_nbb);
	$nbb = $res_nbb->num_rows;
	
	// Nombre de joueurs actifs au Sud
	$sql_nbr = "SELECT id_perso FROM perso WHERE clan='2' AND est_gele='0' and chef=1";
	$res_nbr = $mysqli->query($sql_nbr);
	$nbr = $res_nbr->num_rows;

	// Nombre de persos au Nord actifs
	$sql_nbbact = "SELECT id_perso FROM perso WHERE clan='1' AND est_gele='0' AND est_renvoye=0";
	$res_nbbact = $mysqli->query($sql_nbbact);
	$nbbact = $res_nbbact->num_rows;

	// Nombre de persos au Sud actifs
	$sql_nbract = "SELECT id_perso FROM perso WHERE clan='2' AND est_gele='0' AND est_renvoye=0";
	$res_nbract = $mysqli->query($sql_nbract);
	$nbract = $res_nbract->num_rows;
		
	// Nombre de Point de victoire au Nord
	$sql_pvictb = "SELECT points_victoire FROM stats_camp_pv WHERE id_camp='1'";
	$res_pvictb = $mysqli->query($sql_pvictb);
	$t = $res_pvictb->fetch_assoc();
	$nbvictb = $t['points_victoire'];
	
	// Nombre de Point de victoire au Sud
	$sql_pvictr = "SELECT points_victoire FROM stats_camp_pv WHERE id_camp='2'";
	$res_pvictr = $mysqli->query($sql_pvictr);
	$t = $res_pvictr->fetch_assoc();
	$nbvictr = $t['points_victoire'];
	
	// Nombre de persos du sud capturés par le Nord
	$sql = "SELECT * FROM dernier_tombe WHERE camp_perso_capture=2 AND camp_perso_captureur=1";
	$res_ev_capt = $mysqli->query($sql);
	$nb_ennemis_capt_nord = $res_ev_capt->num_rows;
	
	// Nombre de persos du nord capturés par le Sud
	$sql = "SELECT * FROM dernier_tombe WHERE camp_perso_capture=1 AND camp_perso_captureur=2";
	$res_ev_capt =  $mysqli->query($sql);
	$nb_ennemis_capt_sud = $res_ev_capt->num_rows;
	
	// Nombre de persos du nord capturés par le Nord
	$sql = "SELECT * FROM dernier_tombe WHERE camp_perso_capture=1 AND camp_perso_captureur=1";
	$res_ev_capt =  $mysqli->query($sql);
	$nb_allies_capt_nord = $res_ev_capt->num_rows;
	
	// Nombre de persos du sud capturés par le Sud
	$sql = "SELECT * FROM dernier_tombe WHERE camp_perso_capture=2 AND camp_perso_captureur=2";
	$res_ev_capt =  $mysqli->query($sql);
	$nb_allies_capt_sud = $res_ev_capt->num_rows;
	
	// Nombre de persos du nord capturés par autre chose qu'un perso (pnj)
	$sql = "SELECT * FROM dernier_tombe WHERE camp_perso_capture=1 AND camp_perso_captureur=0";
	$res_ev_capt =  $mysqli->query($sql);
	$nb_autre_capt_nord = $res_ev_capt->num_rows;
	
	// Nombre de persos du sud capturés par autre chose qu'un perso (pnj)
	$sql = "SELECT * FROM dernier_tombe WHERE camp_perso_capture=2 AND camp_perso_captureur=0";
	$res_ev_capt =  $mysqli->query($sql);
	$nb_autre_capt_sud = $res_ev_capt->num_rows;
	
	// Nombre de capture de PNJ
	
	// Nombre de destruction
	
	// Nombre d'attaques (dont charges)
	
	// Nombre de charges
	
	// Nombre de soins
	
	// Nombre de réparations	

	echo "<div class='table-responsive'>";
	echo "	<table class='table table-bordered table-hover sortable' style='width:100%'>";
	echo "		<thead>";
	echo "			<tr>";
	echo "				<th style='text-align:center'><font color=darkred>Camp</font></th>";
	echo "				<th style='text-align:center'><font color=darkred>Nombre de joueurs actifs</font></th>";
	echo "				<th style='text-align:center'><font color=darkred>Nombre de persos actifs</font></th>";
	echo "				<th style='text-align:center'><font color=darkred>Nombre de captures ennemis</font></th>";
	echo "				<th style='text-align:center'><font color=darkred>Nombre de captures alliés</font></th>";
	echo "				<th style='text-align:center'><font color=darkred>Nombre de captures autres <br />(capturé un pnj ou perso neutre)</font></th>";
	echo "				<th style='text-align:center'><font color=darkred>Points de victoires</font></th>";
	echo "			</tr>";
	echo "		</thead>";
	echo "		<tbody>";

	while ($tc_kill = $res->fetch_assoc()){
		
		$id_camp = $tc_kill["id_camp"];
		
		if($id_camp == "1"){
			$couleur_camp 	= "blue";
			$nom_camp 		= "Nord";
			$nb 			= $nbb;
			$nbact 			= $nbbact;
			$pvict 			= $nbvictb;
			$nb_kill		= $nb_ennemis_capt_nord;
			$meutre 		= $nb_allies_capt_nord;
			$autres			= $nb_autre_capt_nord;
		}
		if($id_camp == "2"){
			$couleur_camp 	= "red";
			$nom_camp 		= "Sud";
			$nb 			= $nbr;
			$nbact 			= $nbract;
			$pvict 			= $nbvictr;
			$nb_kill		= $nb_ennemis_capt_sud;
			$meutre 		= $nb_allies_capt_sud;
			$autres			= $nb_autre_capt_sud;
		}
		if($id_camp == "3"){
			$couleur_camp = "green";
		}
		
		echo "			<tr>";
		echo "				<td align=center><font color=\"$couleur_camp\">".$nom_camp."</font></td>";
		echo "				<td align=center>$nb</td>";
		echo "				<td align=center>$nbact</td>";
		echo "				<td align=center>$nb_kill</td>";
		echo "				<td align=center>$meutre</td>";
		echo "				<td align=center>$autres</td>";
		echo "				<td align='center'>".$pvict." <a href='classement.php?stats=ok&histo=".$id_camp."' class='btn btn-primary'>Consulter l'historique</a></td>";
		echo "			</tr>";
	}
	
	echo "		</tbody>";
	echo "	</table>";
	echo "</div>";
	
	if (isset($_GET['histo']) && trim($_GET['histo']) != "") {
		
		$id_camp_histo = $_GET['histo'];
		
		echo "<div class='table-responsive'>";
		echo "	<table class='table table-bordered table-hover sortable' style='width:100%'>";
		echo "		<thead>";
		echo "			<tr>";
		echo "				<th style='text-align:center'><font color=darkred>Date</font></th>";
		echo "				<th style='text-align:center'><font color=darkred>Détail</font></th>";
		echo "				<th style='text-align:center'><font color=darkred>Gain PV</font></th>";
		echo "			</tr>";
		echo "		</thead>";
		echo "		<tbody>";
		
		$sql = "SELECT UNIX_TIMESTAMP(date_pvict) as date_pvict, gain_pvict, texte FROM histo_stats_camp_pv WHERE id_camp='$id_camp_histo' ORDER BY date_pvict ASC";
		$res =  $mysqli->query($sql);
		
		while ($t = $res->fetch_assoc()){
			
			$date_pv = $t['date_pvict'];
			$gain_pv = $t['gain_pvict'];
			$text_pv = $t['texte'];
			
			$date_pv = date('Y-m-d H:i:s', $date_pv);
			
			echo "			<tr>";
			echo "				<td align=center>".$date_pv."</td>";
			echo "				<td align=center>".$text_pv."</td>";
			echo "				<td align=center>".$gain_pv."</td>";
			echo "			</tr>";
			
		}
		
		echo "		</tbody>";
		echo "	</table>";
		echo "</div>";		
	}
}

if(isset($_GET['super']) && $_GET['super'] == 'ok'){
	echo "<div align=\"center\"><h2><font color=darkred>Les Supermans</font></h2></div>";
	
	echo "<div align=\"center\">";
	echo "	<a class='btn btn-warning' href=\"index.php\">Retour Accueil</a>";
	echo "	<a class='btn btn-warning' href=\"jouer.php\">Retour au jeu</a>";
	echo "</div>";
	echo "<br/>";
	
	echo "<div align=\"center\">";
	echo "	<a class='btn btn-primary' href=\"classement.php?grade\">Haut gradés</a>";
	echo "	<a class='btn btn-primary' href=\"classement.php\">Experience</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?dernier_tombe=ok\">Derniers tombés</a>";
	echo "</div>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=1\">Machines à tuer</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=2\">Habitués des hopitaux</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=3\">Chasseurs</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=4\">Grosses fortunes</a>";
	echo "</center>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?titre=ok\">Les Titres</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?training=ok\">Les pros de l'entrainement</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?super=ok\">Les Supermans</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?stats=ok\">Les Statistiques de chaque camps</a>";
	echo "</center>";
	echo "<br/>";
	
	// bleus
	$sql = "SELECT 	max(id_grade) as id_grade_max, 
					max(xp_perso) as xp_max, 
					max(pvMax_perso) as pv_max, 
					max(pmMax_perso) as pm_max, 
					max(perception_perso) as perception_max, 
					max(recup_perso) as recup_max, 
					max(paMax_perso) as pa_max, 
					max(nb_kill) as kill_max, 
					max(nb_pnj) as pnj_max 
			FROM perso, perso_as_grade
			WHERE perso.id_perso >= '100' 
			AND perso_as_grade.id_perso = perso.id_perso 
			AND perso_as_grade.id_grade != 1 AND perso_as_grade.id_grade != 101 AND perso_as_grade.id_grade != 102
			AND clan='1'";
	$res = $mysqli->query($sql);
	$t_b = $res->fetch_assoc();
	
	$id_grade_max_b = $t_b['id_grade_max'];
	$xp_max_b = $t_b['xp_max'];
	$pv_max_b = $t_b['pv_max'];
	$pm_max_b = $t_b['pm_max'];
	$perception_max_b = $t_b['perception_max'];
	$recup_max_b = $t_b['recup_max'];
	$pa_max_b = $t_b['pa_max'];
	$kill_max_b = $t_b['kill_max'];
	$pnj_max_b = $t_b['pnj_max'];
	
	$sql = "SELECT nom_grade FROM grades WHERE id_grade = '$id_grade_max_b'";
	$res = $mysqli->query($sql);
	$t = $res->fetch_assoc();
	
	$nom_grade_b = $t['nom_grade'];
	
	// rouges
	$sql = "SELECT 	max(id_grade) as id_grade_max, 
					max(xp_perso) as xp_max, 
					max(pvMax_perso) as pv_max, 
					max(pmMax_perso) as pm_max, 
					max(perception_perso) as perception_max, 
					max(recup_perso) as recup_max, 
					max(paMax_perso) as pa_max, 
					max(nb_kill) as kill_max, 
					max(nb_pnj) as pnj_max 
			FROM perso, perso_as_grade
			WHERE perso.id_perso >= '100' 
			AND perso_as_grade.id_perso = perso.id_perso 
			AND perso_as_grade.id_grade != 1 AND perso_as_grade.id_grade != 101 AND perso_as_grade.id_grade != 102
			AND clan='2'";
	$res = $mysqli->query($sql);
	$t_r = $res->fetch_assoc();
	
	$id_grade_max_r 	= $t_r['id_grade_max'];
	$xp_max_r 			= $t_r['xp_max'];
	$pv_max_r 			= $t_r['pv_max'];
	$pm_max_r 			= $t_r['pm_max'];
	$perception_max_r 	= $t_r['perception_max'];
	$recup_max_r 		= $t_r['recup_max'];
	$pa_max_r 			= $t_r['pa_max'];
	$kill_max_r 		= $t_r['kill_max'];
	$pnj_max_r 			= $t_r['pnj_max'];
	
	$sql = "SELECT nom_grade FROM grades WHERE id_grade = '$id_grade_max_r'";
	$res = $mysqli->query($sql);
	$t = $res->fetch_assoc();
	
	$nom_grade_r = $t['nom_grade'];
	
	echo "<div class='table-responsive'>";
	echo "	<table class='table table-bordered table-hover sortable' style='width:100%'>";
	echo "		<thead>";
	echo "			<tr>";
	echo "				<th>Nom</th>";
	echo "				<th>grade max</th>";
	echo "				<th>XP</th><th>PV</th><th>PM</th><th>Perception</th><th>Recup</th><th>Pa</th><th>Nombre de kills</th><th>Nombre de pnj tués</th>";
	echo "			</tr>";
	echo "		</thead>";
	echo "		<tbody>";
	echo "			<tr><td align='center'><font color='blue'>Super Unioniste</font></td><td align='center'>$nom_grade_b <img src=\"../images/grades/" . $id_grade_max_b . ".gif\" /></td><td align='center'>$xp_max_b</td><td align='center'>$pv_max_b</td><td align='center'>$pm_max_b</td><td align='center'>$perception_max_b</td><td align='center'>$recup_max_b</td><td align='center'>$pa_max_b</td><td align='center'>$kill_max_b</td><td align='center'>$pnj_max_b</td></tr>";
	echo "			<tr><td align='center'><font color='red'>Super Confédéré</font></td><td align='center'>$nom_grade_r <img src=\"../images/grades/" . $id_grade_max_r . ".gif\" /></td><td align='center'>$xp_max_r</td><td align='center'>$pv_max_r</td><td align='center'>$pm_max_r</td><td align='center'>$perception_max_r</td><td align='center'>$recup_max_r</td><td align='center'>$pa_max_r</td><td align='center'>$kill_max_r</td><td align='center'>$pnj_max_r</td></tr>";
	echo "		</tbody>";
	echo "	</table>";
	echo "</div>";
}

if(isset($_GET['training']) && $_GET['training'] == 'ok'){
	echo "<div align=\"center\"><h2><font color=darkred>Entrainement</font></h2></div>";
	
	echo "<div align=\"center\">";
	echo "	<a class='btn btn-warning' href=\"index.php\">Retour Accueil</a>";
	echo "	<a class='btn btn-warning' href=\"jouer.php\">Retour au jeu</a>";
	echo "</div>";
	echo "<br/>";
	
	echo "<div align=\"center\">";
	echo "	<a class='btn btn-primary' href=\"classement.php?grade\">Haut gradés</a>";
	echo "	<a class='btn btn-primary' href=\"classement.php\">Experience</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?dernier_tombe=ok\">Derniers tombés</a>";
	echo "</div>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=1\">Machines à tuer</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=2\">Habitués des hopitaux</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=3\">Chasseurs</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=4\">Grosses fortunes</a>";
	echo "</center>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?titre=ok\">Les Titres</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?training=ok\">Les pros de l'entrainement</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?super=ok\">Les Supermans</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?stats=ok\">Les Statistiques de chaque camps</a>";
	echo "</center>";
	echo "<br/>";
	
	$sql = "SELECT perso_as_entrainement.id_perso, nom_perso, niveau_entrainement, clan 
			FROM perso_as_entrainement, perso 
			WHERE perso_as_entrainement.id_perso=perso.id_perso 
			AND perso.id_perso>'10'
			ORDER BY niveau_entrainement DESC LIMIT 50";
	$res = $mysqli->query($sql);
	
	echo "<div class='table-responsive'>";
	echo "	<table class='table table-bordered table-hover sortable' style='width:100%'>";
	echo "		<thead>";
	echo "			<tr>";
	echo "				<th>Nom</th>";
	echo "				<th>niveau entrainement</th>";
	echo "				<th>Gains surprise</th>";
	echo "			</tr>";
	echo "		</thead>";
	echo "		<tbody>";
	
	while ($t = $res->fetch_assoc()){
		
		$nom 		= $t['nom_perso'];
		$niveau_e 	= $t['niveau_entrainement'];
		$camp 		= $t['clan'];
		
		if($camp == '1'){
			$color = 'blue';
		}
		if($camp == '2'){
			$color = 'red';
		}
	
		echo "		<tr><td align='center'><font color=$color>$nom</font></td><td align='center'>$niveau_e</td><td align='center'>&nbsp;</td></tr>";
	}
	
	echo "		</tbody>";
	echo "	</table>";
	echo "</div>";
}

if(isset($_GET['dernier_tombe']) && $_GET['dernier_tombe'] == 'ok'){
	
	echo "<div align=\"center\"><h2><font color=darkred>Derniers tombés</font></h2></div>";
	
	echo "<div align=\"center\">";
	echo "	<a class='btn btn-warning' href=\"index.php\">Retour Accueil</a>";
	echo "	<a class='btn btn-warning' href=\"jouer.php\">Retour au jeu</a>";
	echo "</div>";
	echo "<br/>";
	
	echo "<div align=\"center\">";
	echo "	<a class='btn btn-primary' href=\"classement.php?grade\">Haut gradés</a>";
	echo "	<a class='btn btn-primary' href=\"classement.php\">Experience</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?dernier_tombe=ok\">Derniers tombés</a>";
	echo "</div>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=1\">Machines à tuer</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=2\">Habitués des hopitaux</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=3\">Chasseurs</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=4\">Grosses fortunes</a>";
	echo "</center>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?titre=ok\">Les Titres</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?training=ok\">Les pros de l'entrainement</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?super=ok\">Les Supermans</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?stats=ok\">Les Statistiques de chaque camps</a>";
	echo "</center>";
	echo "<br/>";
	
	$sql = "SELECT UNIX_TIMESTAMP(date_capture) as date_capture, id_perso_capture, camp_perso_capture, id_perso_captureur, camp_perso_captureur FROM dernier_tombe
			ORDER BY date_capture DESC LIMIT 50";
	$res = $mysqli->query($sql);

	echo "<div class='table-responsive'>";
	echo "	<table class='table table-bordered table-hover sortable' style='width:100%'>";
	echo "		<thead>";
	echo "			<tr>";
	echo "				<th style='text-align:center'><font color=darkred>Date de capture</font></th>";
	echo "				<th style='text-align:center'><font color=darkred>... a été capturé</font></th>";
	echo "				<th style='text-align:center'><font color=darkred>Grade</font></th>";
	echo "				<th style='text-align:center'><font color=darkred>par ...</font></th>";
	echo "				<th style='text-align:center'><font color=darkred>Grade</font></th>";
	echo "			</tr>";
	echo "		</thead>";
	
	echo "		<tbody>";
	
	while ($t = $res->fetch_assoc()){
		
		$date_capture_o 	= $t['date_capture'];
		$id_perso_a	= $t['id_perso_capture'];
		$camp_perso_a = $t['camp_perso_capture'];
		$id_perso_b 	= $t['id_perso_captureur'];
		$camp_perso_b	= $t['camp_perso_captureur'];
		
		$date_capture = date('Y-m-d H:i:s', $date_capture_o);

		$sql = "SELECT nom_perso, nom_grade, grades.id_grade FROM perso, perso_as_grade, grades WHERE perso.id_perso = perso_as_grade.id_perso AND perso_as_grade.id_grade = grades.id_grade AND perso.id_perso=".$id_perso_a;
		$res2 = $mysqli->query($sql);
		$t2 = $res2->fetch_assoc();

		$nom_perso_a	= $t2["nom_perso"];
		$nom_grade_a	= $t["nom_grade"];
		$id_grade_a	= $t2["id_grade"];
		$couleur_camp_a = couleur_clan($camp_perso_a);

		// cas particuliers grouillot
		if ($id_grade_a == 101)
			$id_grade_a = "1.1";
		else if ($id_grade_a == 102)
			$id_grade_a = "1.2";

		$nom_perso_b = "";
		$grade_b = "";
		$couleur_camp_b = couleur_clan($camp_perso_b);
		if ($id_perso_b != 0 && $id_perso_b < 50000) {
			$sql = "SELECT nom_perso, nom_grade, grades.id_grade FROM perso, perso_as_grade, grades WHERE perso.id_perso = perso_as_grade.id_perso AND perso_as_grade.id_grade = grades.id_grade AND perso.id_perso=".$id_perso_b;
			$res2 = $mysqli->query($sql);
			$t2 = $res2->fetch_assoc();

			$nom_perso_b	= $t2["nom_perso"];
			$nom_grade_b	= $t["nom_grade"];
			$id_grade_b	= $t2["id_grade"];

			if ($id_grade_b == 101)
				$id_grade_b = "1.1";
			else if ($id_grade_b == 102)
				$id_grade_b = "1.2";
			$grade_b = '<img src="../images/grades/'.$id_grade_b.'.gif" /> '.$nom_grade_b;
		} else if ($id_perso_b != 0 && $id_perso_b < 200000) {
			$nom_perso_b = "Canon";
		} else if ($id_perso_b != 0) {
			$sql = "SELECT nom_pnj FROM instance_pnj JOIN pnj ON instance_pnj.id_pnj=pnj.id_pnj WHERE idInstance_pnj=$id_perso_b";
			$res2 = $mysqli->query($sql);
			$t2 = $res2->fetch_assoc();
			$nom_perso_b = $t2['nom_pnj'];
		} else {
			$grade_b = "";
		}
		
		echo "			<tr>";
		echo "				<td align=center>".$date_capture."</td>";
		echo "				<td align=center><font color=$couleur_camp_a>".$nom_perso_a."</font> [<a href=\"evenement.php?infoid=".$id_perso_a."\">" .$id_perso_a. "</a>]</td>";
		echo "				<td align='left'><img src=\"../images/grades/" . $id_grade_a . ".gif\" /> ".$t['nom_grade']."</td>";
		echo "				<td align=center><font color=$couleur_camp_b>".$nom_perso_b."</font> [<a href=\"evenement.php?infoid=".$id_perso_b."\">" .$id_perso_b. "</a>]</td>";
		echo "				<td align='left'>$grade_b</td>";
		echo "			</tr>";
	}
	
	echo "		</tbody>";
	echo "	</table>";
	echo "</div>";
}


if(!isset($_GET["top"]) && !isset($_GET["titre"]) && !isset($_GET["stats"]) && !isset($_GET["super"]) && !isset($_GET["training"]) && !isset($_GET["dernier_tombe"])) {
	
	echo "<div align=\"center\"><h2><font color=darkred>Classement Global</font></h2></div>";
	
	echo "<div align=\"center\">";
	echo "	<a class='btn btn-warning' href=\"index.php\">Retour Accueil</a>";
	echo "	<a class='btn btn-warning' href=\"jouer.php\">Retour au jeu</a>";
	echo "</div>";
	echo "<br/>";
	
	echo "<div align=\"center\">";
	echo "	<a class='btn btn-primary' href=\"classement.php?grade\">Haut gradés</a>";
	echo "	<a class='btn btn-primary' href=\"classement.php\">Experience</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?dernier_tombe=ok\">Derniers tombés</a>";
	echo "</div>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=1\">Machines à tuer</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=2\">Habitués des hopitaux</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=3\">Chasseurs</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?top=ok&classement=4\">Grosses fortunes</a>";
	echo "</center>";
	echo "<br/>";

	echo "<center>";
	echo "	<a class='btn btn-info' href=\"classement.php?titre=ok\">Les Titres</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?training=ok\">Les pros de l'entrainement</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?super=ok\">Les Supermans</a>";
	echo "	<a class='btn btn-info' href=\"classement.php?stats=ok\">Les Statistiques de chaque camps</a>";
	echo "</center>";
	echo "<br/>";

	$show_xp = true;
	$order_by = "xp_perso";
	$limit = 50;
	if (!isset($_GET["grade"])) {
		$type_perso = isset($_GET["type_perso"]) ? $_GET["type_perso"] : 'tous';
		$id_type_perso = get_id_type_perso($type_perso);
		echo '<div align=center><form method="GET">';
		echo '<input type="radio" id="tous" name="type_perso" value="tous" onclick="this.form.submit();" '.($type_perso == 'tous' ? 'checked' : '').'> <label for="tous">Tous</label>';
		echo '<input type="radio" id="chef" name="type_perso" value="chef" onclick="this.form.submit();" '.($type_perso == 'chef' ? 'checked' : '').'> <label for="chef">Chef</label>';
		echo '<input type="radio" id="cav_lourde" name="type_perso" value="cav_lourde" onclick="this.form.submit();" '.($type_perso == 'cav_lourde' ? 'checked' : '').'> <label for="cav_lourde">Cavalerie lourde</label>';
		echo '<input type="radio" id="infanterie" name="type_perso" value="infanterie" onclick="this.form.submit();" '.($type_perso == 'infanterie' ? 'checked' : '').'> <label for="infanterie">Infanterie</label>';
		echo '<input type="radio" id="soigneur" name="type_perso" value="soigneur" onclick="this.form.submit();" '.($type_perso == 'soigneur' ? 'checked' : '').'> <label for="soigneur">Soigneur</label>';
		echo '<input type="radio" id="artillerie" name="type_perso" value="artillerie" onclick="this.form.submit();" '.($type_perso == 'artillerie' ? 'checked' : '').'> <label for="artillerie">Artillerie</label>';
		echo '<input type="radio" id="chien" name="type_perso" value="chien" onclick="this.form.submit();" '.($type_perso == 'chien' ? 'checked' : '').'> <label for="chien">Chien</label>';
		echo '<input type="radio" id="cav_legere" name="type_perso" value="cav_legere" onclick="this.form.submit();" '.($type_perso == 'cav_legere' ? 'checked' : '').'> <label for="cav_legere">Cavalerie légère</label>';
		echo '</form></div>';
	} else {
		$order_by = "id_grade";
		$limit = 50000;
		$show_xp = false;
		$id_type_perso = 1;
	}
	
	// recuperation des valeurs en excluant les persos pnj
	$sql = "SELECT perso.id_perso, nom_perso, xp_perso, clan, nom_grade, grades.id_grade FROM perso, perso_as_grade, grades 
			WHERE perso.id_perso = perso_as_grade.id_perso 
			AND perso_as_grade.id_grade = grades.id_grade
			AND perso.id_perso >= '100'
			".($id_type_perso ? "AND type_perso=$id_type_perso" : "")."
			ORDER BY ".$order_by." DESC LIMIT ".$limit;
	$res = $mysqli->query($sql);
	
	
	echo "<div class='table-responsive'>";
	echo "	<table class='table table-bordered table-hover sortable' style='width:100%'>";
	echo "		<thead>";
	echo "			<tr>";
	echo "				<th style='text-align:center'><font color=darkred>Position</font></th>";
	echo "				<th style='text-align:center'><font color=darkred>Nom</font></th>";
	echo "				<th style='text-align:center' data-defaultsign='_19'><font color=darkred>Matricule</font></th>";
	if ($show_xp)
		echo "				<th style='text-align:center'><font color=darkred>XP</font></th>";
	echo "				<th style='text-align:center' data-defaultsign='_19'><font color=darkred>Grade</font></th>";
	echo "			</tr>";
	echo "		</thead>";
	
	echo "		<tbody>";
	
	$cc = 0;
	
	while ($t2 = $res->fetch_assoc()){
		
		$id_camp = $t2["clan"];
		
		if($id_camp == "1"){
			$couleur_camp = "blue";
		}
		if($id_camp == "2"){
			$couleur_camp = "red";
		}
		
		$id_grade_perso = $t2['id_grade'];
		
		// cas particuliers grouillot
		if ($id_grade_perso == 101) {
			$id_grade_perso = "1.1";
		}
		if ($id_grade_perso == 102) {
			$id_grade_perso = "1.2";
		}
		
		$cc++;
		
		echo "			<tr>";
		echo "				<td align=center>$cc</td>";
		echo "				<td align=center><font color=$couleur_camp>".$t2['nom_perso']."</font></td>";
		echo "				<td align='center'><a href=\"evenement.php?infoid=".$t2['id_perso']."\">" .$t2['id_perso']. "</a></td>";
		if ($show_xp)
			echo "				<td align=center>".$t2['xp_perso']."</td>";
		echo "				<td align='center' data-value='".$id_grade_perso."'><img src=\"../images/grades/" . $id_grade_perso . ".gif\" /> ".$t2['nom_grade']."</td>";
		echo "			</tr>";
	}
	
	echo "		</tbody>";
	echo "	</table>";
	echo "</div>";
}
?>
		</div>
		
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script src="https://drvic10k.github.io/bootstrap-sortable/Scripts/bootstrap-sortable.js"></script>
		
	</body>
</html>
