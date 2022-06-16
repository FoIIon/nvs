<?php

$id_perso = 0;

// Traitement selection perso
if (isset($_POST["liste_perso"]) && $_POST["liste_perso"] != "") {
	
	if(isset($_SESSION["ID_joueur"])){
	
		$id_joueur 	= $_SESSION["ID_joueur"];
		$id_perso	= $_POST["liste_perso"];
		
		// recuperation des infos du perso
		$sql = "SELECT idJoueur_perso FROM perso WHERE id_perso='$id_perso'";
		$res = $mysqli->query($sql);
		$t_perso = $res->fetch_assoc();
			
		$id_joueur_perso 	= $t_perso["idJoueur_perso"];
			
		// Le perso appartient-il bien au joueur ?
		if ($id_joueur_perso == $id_joueur) {
			$id_perso = $_SESSION['id_perso'] = $_POST["liste_perso"];
		}
		else {
			// Tentative de triche !
			$text_triche = "Le joueur $id_joueur a essayé de prendre controle du perso $id_perso qui ne lui appartient pas !";
			
			$sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
			$mysqli->query($sql);
				
			$_SESSION = array(); // On écrase le tableau de session
			session_destroy(); // On détruit la session
				
			//redirection
			header("location:index.php");
		}
	
	} else {
		header("Location:../index.php"); 
	}
}

if(isset($_SESSION["id_perso"])){
	$id_perso = $_SESSION['id_perso'];
}

// recupération config jeu
$dispo = config_dispo_jeu($mysqli);
$admin = admin_perso($mysqli, $id_perso);

if($dispo == '1' || $admin){
	
	if(isset($_SESSION["id_perso"])){
		
		$id_perso = $_SESSION['id_perso'];
		$date = time();
		
		$page_acces = 'jouer.php';
		if ($_SERVER['QUERY_STRING'] != '') {
			$page_acces .= '?'.$_SERVER['QUERY_STRING'];
		}
		
		// acces_log
		$sql = "INSERT INTO acces_log (date_acces, id_perso, page) VALUES (NOW(), '$id_perso', '$page_acces')";
		$mysqli->query($sql);
		
		// Alerte si 10 refresh ou plus en 10 sec (déco ?)
		$sql = "SELECT COUNT(*) as count_log_10sec FROM acces_log WHERE id_perso='$id_perso' AND page = 'jouer.php' AND date_acces > (NOW() - INTERVAL 10 SECOND)";
		$res = $mysqli->query($sql);
		$t = $res->fetch_assoc();
		
		$count_log_10sec = $t['count_log_10sec'];
		
		if ($count_log_10sec >= 10) {
			// Est-ce qu'il y a déjà eu une alerte de ce type pour ce perso dans les 30 dernières secondes ?
			$sql = "SELECT COUNT(*) as nb_alerte_10sec FROM alerte_anim WHERE type_alerte='2' AND id_perso='$id_perso' AND date_alerte > (NOW() - INTERVAL 30 SECOND)";
			$res = $mysqli->query($sql);
			$t = $res->fetch_assoc();
			
			$nb_alerte_10sec = $t['nb_alerte_10sec'];
			
			if ($nb_alerte_10sec == 0) {
				$sql = "INSERT INTO alerte_anim (type_alerte, id_perso, raison_alerte, date_alerte) VALUES ('2', '$id_perso', 'Page de jeu - plus de 10 refresh en moins de 10 secondes : $count_log_10sec', NOW())";
				$mysqli->query($sql);
			}
		}
		
		// Alerte si 30 refresh ou plus en moins d'une minute
		$sql = "SELECT COUNT(*) as count_log_1min FROM acces_log WHERE id_perso='$id_perso' AND page = 'jouer.php' AND date_acces > (NOW() - INTERVAL 60 SECOND)";
		$res = $mysqli->query($sql);
		$t = $res->fetch_assoc();
		
		$count_log_1min = $t['count_log_1min'];
		
		if ($count_log_1min >= 30) {
			
			// Est-ce qu'il y a déjà eu une alerte de ce type pour ce perso dans les 3 dernière minutes ?
			$sql = "SELECT COUNT(*) as nb_alerte_1min FROM alerte_anim WHERE type_alerte='3' AND id_perso='$id_perso' AND date_alerte > (NOW() - INTERVAL 180 SECOND)";
			$res = $mysqli->query($sql);
			$t = $res->fetch_assoc();
			
			$nb_alerte_1min = $t['nb_alerte_1min'];
			
			if ($nb_alerte_1min == 0) {
				$sql = "INSERT INTO alerte_anim (type_alerte, id_perso, raison_alerte, date_alerte) VALUES ('3', '$id_perso', 'Page de jeu - plus de 30 refresh en moins de 1 minute : $count_log_1min', NOW())";
				$mysqli->query($sql);
			}
		}
		
		// TODO - Vérification 10 derniers logs d'accès, sont-il sur le même delta de temps ?
		
		
		$sql_joueur = "SELECT idJoueur_perso FROM perso WHERE id_perso='$id_perso'";
		$res_joueur = $mysqli->query($sql_joueur);
		$t_joueur = $res_joueur->fetch_assoc();
		
		$id_joueur_perso = $t_joueur["idJoueur_perso"];
		
		$sql_dla = "SELECT UNIX_TIMESTAMP(DLA_perso) as DLA, est_gele FROM perso WHERE idJoueur_perso='$id_joueur_perso' AND chef=1";
		$res_dla = $mysqli->query($sql_dla);
		$t_dla = $res_dla->fetch_assoc();
		
		$dla 		= $t_dla["DLA"];
		$est_gele 	= $t_dla["est_gele"];
	
		$sql = "SELECT pv_perso FROM perso WHERE id_perso='$id_perso'";
		$res = $mysqli->query($sql);
		$tpv = $res->fetch_assoc();
		
		$testpv = $tpv['pv_perso'];
		
		$config = '1';
		
		// verification si le perso est encore en vie
		if ($testpv <= 0) {
			// le perso est mort
			header("Location:../tour.php"); 
		}
		else { 
			// le perso est vivant
			// verification si nouveau tour ou gele
			if(nouveau_tour($date, $dla) || $est_gele) {
				if (isset($_GET['login']) && $_GET['login'] == 'ok') {
					header("Location:../tour.php?login=ok");
				}
				else {
					header("Location:../tour.php");
				}
			}
			else {
				$erreur = "";
				$mess = "";
				$mess_bat ="";
	
				if(isset($_SESSION["nv_tour"]) && $_SESSION["nv_tour"] == 1){
					echo "<center><font color=red><b>Nouveau tour</b></font></center>";
					$_SESSION["nv_tour"] = 0;
				}
				
				// recuperation des anciennes données du perso
				$sql = "SELECT idJoueur_perso, nom_perso, x_perso, y_perso, pm_perso, pmMax_perso, image_perso, pa_perso, perception_perso, recup_perso, bonusRecup_perso, bonusPM_perso, type_perso, paMax_perso, pv_perso, charge_perso, chargeMax_perso, DLA_perso, clan, perso_as_grade.id_grade, nom_grade 
						FROM perso, perso_as_grade, grades 
						WHERE perso_as_grade.id_perso = perso.id_perso
						AND perso_as_grade.id_grade = grades.id_grade
						AND perso.id_perso='$id_perso'";
				$res = $mysqli->query($sql);
				$t_perso1 = $res->fetch_assoc();
				
				$id_joueur_perso 	= $t_perso1["idJoueur_perso"];
				$nom_perso 			= $t_perso1["nom_perso"];
				$x_persoN 			= $t_perso1["x_perso"];
				$y_persoN 			= $t_perso1["y_perso"];
				$pm_perso 			= $t_perso1["pm_perso"];
				$pmMax_perso		= $t_perso1["pmMax_perso"];
				$dla_perso			= $t_perso1["DLA_perso"];
				$image_perso 		= $t_perso1["image_perso"];
				$bonusPM_perso_p 	= $t_perso1["bonusPM_perso"];
				$clan_p 			= $t_perso1["clan"];
				$type_perso			= $t_perso1["type_perso"];
				$pa_perso			= $t_perso1["pa_perso"];
				$perception_perso	= $t_perso1["perception_perso"];	
				$charge_perso		= $t_perso1["charge_perso"];
				$chargeMax_perso	= $t_perso1["chargeMax_perso"];
				$grade_perso 		= $t_perso1["id_grade"];
				$nom_grade_perso	= $t_perso1["nom_grade"];
				
				$sql = "SELECT UNIX_TIMESTAMP(DLA_perso) as DLA_perso FROM perso WHERE idJoueur_perso='$id_joueur_perso' AND chef=1";
				$res = $mysqli->query($sql);
				$t_c = $res->fetch_assoc();
				
				$n_dla 				= $t_c["DLA_perso"];
				
				// récupération de la couleur du camp
				$couleur_clan_p = couleur_clan($clan_p);
				
				$dossier_img_joueur = get_dossier_image_joueur($mysqli, $id_joueur_perso);
				
				// affichage rosace et bousculades
				$sql = "SELECT afficher_rosace, bousculade_deplacement FROM joueur WHERE id_joueur='$id_joueur_perso'";
				$res = $mysqli->query($sql);
				$t = $res->fetch_assoc();
				
				$afficher_rosace 	= $t['afficher_rosace'];
				$bousculade_dep		= $t['bousculade_deplacement'];
				$cadrillage			= 1;//$t['cadrillage'];
				
				$sql = "SELECT MAX(x_carte) as x_max, MAX(y_carte) as y_max FROM carte";
				$res = $mysqli->query($sql);
				$t = $res->fetch_assoc();
				
				$X_MAX = $t['x_max'];
				$Y_MAX  = $t['y_max'];
				
				$carte = "carte";
				
				if(isset($_GET['erreur'])){
					if ($_GET['erreur'] == 'competence') {
						$erreur .= 'competence indiponible pour le moment';
					}
					
					if ($_GET['erreur'] == 'prox_bat') {
						$erreur .= 'Vous devez vous trouver à proximité du bâtiment pour effectuer cette action';
					}
					
					if ($_GET['erreur'] == 'pa') {
						$erreur .= "Vous n'avez pas assez de PA";
					}
					
					if ($_GET['erreur'] == 'pm') {
						$erreur .= "Vous n'avez plus de pm !";
					}
				}
				
				if (isset($_GET['message'])) {
					$message = $_GET['message'];
					if ($message == 'gainPM') {
						$mess .= "Vous êtes en forme aujourd'hui, vous gagnez 1PM !";
					}
				}
				
				// calcul malus pm
				$malus_pm_charge = getMalusCharge($charge_perso, $chargeMax_perso);
				if ($malus_pm_charge == 100) {
					$malus_pm = -$pmMax_perso;
				}
				else {
					$malus_pm = $malus_pm_charge;
				}
				
				// traitement entrée dans un batiment
				if(isset($_GET["bat"])) {
					
					$id_inst = $_GET["bat"];
					
					// on veut sortir du batiment
					if(isset($_GET["out"]) && $_GET["out"] == "ok") {
					
						// verification que le perso est bien dans le batiment duquel il souhaite sortir...
						if($id_inst == in_bat($mysqli, $id_perso)){
							
							// verification des pm du perso
							if($pm_perso + $malus_pm >= 1){
								
								// Si on choisi de sortir avec une direction
								if (isset($_GET["direction"])) {
									
									if (isDirectionOK($_GET["direction"])) {
									
										$direction = $_GET["direction"];
											
										$sql_b = "SELECT batiment.id_batiment, nom_batiment, taille_batiment, nom_instance FROM batiment, instance_batiment 
												WHERE instance_batiment.id_batiment = batiment.id_batiment
												AND instance_batiment.id_instanceBat = '$id_inst'";
										$res_b = $mysqli->query($sql_b);
										$t_b = $res_b->fetch_assoc();
										
										$type_bat			= $t_b['id_batiment'];
										$nom_bat 			= $t_b['nom_batiment'];
										$taille_bat			= $t_b['taille_batiment'];
										$nom_instance_bat	= $t_b['nom_instance'];
										
										if ($type_bat != 10) {
										
											$taille_case = ceil($taille_bat / 2);
											
											$oc = 1;
											
											switch($direction){
												case 1: 
													// Haut gauche
													$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
															WHERE x_carte = $x_persoN - $taille_case AND y_carte = $y_persoN + $taille_case";
															
													break;
												case 2:
													// Haut
													$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
															WHERE x_carte = $x_persoN AND y_carte = $y_persoN + $taille_case";
															
													break;
												case 3:
													// Haut droite
													$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
															WHERE x_carte = $x_persoN + $taille_case AND y_carte = $y_persoN + $taille_case";
															
													break;
												case 4: 
													// Gauche
													$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
															WHERE x_carte = $x_persoN - $taille_case AND y_carte = $y_persoN";
															
													break;
												case 5: 
													// Droite
													$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
															WHERE x_carte = $x_persoN + $taille_case AND y_carte = $y_persoN";
															
													break;
												case 6: 
													// Bas gauche
													$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
															WHERE x_carte = $x_persoN - $taille_case AND y_carte = $y_persoN - $taille_case";
															
													break;
												case 7: 
													// Bas
													$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
															WHERE x_carte = $x_persoN AND y_carte = $y_persoN - $taille_case";
															
													break;
												case 8: 
													// Bas droite
													$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
															WHERE x_carte = $x_persoN + $taille_case AND y_carte = $y_persoN - $taille_case";
													
													break;
											}
											
											$res = $mysqli->query($sql);
											$t = $res->fetch_assoc();
											
											$oc 	= $t["occupee_carte"];
											
											if ($oc) {
												switch($direction){
													case 1: 
														// Haut gauche
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																		WHERE x_carte = $x_persoN - $i AND y_carte = $y_persoN + $taille_case";														
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
											
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
														
														if (!$oc) {
															break;
														}
														
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN - $taille_case AND y_carte = $y_persoN + $i";															
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
												
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
																
														break;
													case 2:
														// Haut
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN - $i AND y_carte = $y_persoN + $taille_case";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
														
														if (!$oc) {
															break;
														}
														
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN + $i AND y_carte = $y_persoN + $taille_case";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
																
														break;
													case 3:
														// Haut droite
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN + $i AND y_carte = $y_persoN + $taille_case";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
														
														if (!$oc) {
															break;
														}
														
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN + $taille_case AND y_carte = $y_persoN + $i";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
																
														break;
													case 4: 
														// Gauche
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN - $taille_case AND y_carte = $y_persoN + $i";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
														
														if (!$oc) {
															break;
														}
														
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN - $taille_case AND y_carte = $y_persoN - $i";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
																
														break;
													case 5: 
														// Droite
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN + $taille_case AND y_carte = $y_persoN + $i";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
														
														if (!$oc) {
															break;
														}
														
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN + $taille_case AND y_carte = $y_persoN - $i";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
																
														break;
													case 6: 
														// Bas gauche
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN - $i AND y_carte = $y_persoN - $taille_case";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
														
														if (!$oc) {
															break;
														}
														
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN - $taille_case AND y_carte = $y_persoN - $i";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
																
														break;
													case 7: 
														// Bas
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN + $i AND y_carte = $y_persoN - $taille_case";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
														
														if (!$oc) {
															break;
														}
														
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN - $i AND y_carte = $y_persoN - $taille_case";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
																
														break;
													case 8: 
														// Bas droite
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN + $i AND y_carte = $y_persoN - $taille_case";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
														
														if (!$oc) {
															break;
														}
														
														for ($i = 1; $i < $taille_case; $i++) {
															$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
																	WHERE x_carte = $x_persoN + $taille_case AND y_carte = $y_persoN - $i";
															$res = $mysqli->query($sql);
															$t = $res->fetch_assoc();
															
															$oc = $t["occupee_carte"];
															
															if (!$oc) {
																break;
															}
														}
														
														break;
												}
											}
											
											if (!$oc) {
												
												$xs 	= $t["x_carte"];
												$ys 	= $t["y_carte"];
												$fond 	= $t["fond_carte"];
												
												$cout_pm = cout_pm($fond);
												
												// verification des pm du perso
												if($pm_perso + $malus_pm >= $cout_pm){
												
													// mise a jour des coordonnees du perso et de ses pm
													$sql = "UPDATE perso SET x_perso = '$xs', y_perso = '$ys', pm_perso=pm_perso-$cout_pm WHERE id_perso = '$id_perso'";
													$mysqli->query($sql);
													
													$x_persoN = $xs;
													$y_persoN = $ys;
													
													// mise a jour des coordonnees du perso sur la carte et changement d'etat de la case
													$sql = "UPDATE $carte SET occupee_carte='1', image_carte='$image_perso' ,idPerso_carte='$id_perso' WHERE x_carte = '$xs' AND y_carte = '$ys'";
													$mysqli->query($sql);
													
													// mise a jour de la table perso_in_batiment
													$sql = "DELETE FROM perso_in_batiment WHERE id_perso='$id_perso'";
													$mysqli->query($sql);
													
													// mise a jour des evenements
													$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ('$id_perso','<font color=$couleur_clan_p><b>$nom_perso</b></font>','est sorti du batiment',NULL,'','en $xs/$ys',NOW(),'0')";
													$mysqli->query($sql);
													
													// mise a jour du bonus de perception
													$bonus_visu = get_malus_visu($fond) + getBonusObjet($mysqli, $id_perso);
													
													if(bourre($mysqli, $id_perso)){
														if(!endurance_alcool($mysqli, $id_perso)) {
															$malus_bourre = bourre($mysqli, $id_perso) * 3;
															$bonus_visu -= $malus_bourre;
														}
													}
													
													$sql = "UPDATE perso SET bonusPerception_perso=$bonus_visu WHERE id_perso='$id_perso'";
													$mysqli->query($sql);
													
													// maj carte brouillard de guerre
													$perception_final = $perception_perso + $bonus_visu;
													if ($id_perso >= 100) {
														if ($clan_p == 1) {
															$sql = "UPDATE $carte SET vue_nord='1' 
																	WHERE x_carte >= $x_persoN - $perception_final AND x_carte <= $x_persoN + $perception_final
																	AND y_carte >= $y_persoN - $perception_final AND y_carte <= $y_persoN + $perception_final";
															$mysqli->query($sql);
														}
														else if ($clan_p == 2) {
															$sql = "UPDATE $carte SET vue_sud='1' 
																	WHERE x_carte >= $x_persoN - $perception_final AND x_carte <= $x_persoN + $perception_final
																	AND y_carte >= $y_persoN - $perception_final AND y_carte <= $y_persoN + $perception_final";
															$mysqli->query($sql);
														}
													}
												}
												else {
													$erreur .= "Il faut posséder au moins ".$cout_pm." pm pour sortir de ce bâtiment dans cette direction";
												}
											}
											else {											
												$erreur .= "Impossible de sortir dans cette direction, la sortie est bloquée";
											}
										}
										else {
											$erreur .= "Impossible de sortir d'un pénitencier";
										}
									}
									else {
										$erreur .= "Direction de sorti du bâtiment incorrecte";
									}
								}
								else {
									$erreur .= "Une direction est nécessaire pour sortir du bâtiment";
								}
							}
							else {
								$erreur .= "Il faut posséder au moins 1pm pour sortir du bâtiment";
							}
						}
						else {
							$erreur .= "Vous n'êtes pas dans ce batiment donc vous ne pouvez pas essayer d'en sortir";
						}
					}
					else {
						// on veut rentrer dans le batiment
					
						// traitement du cas tour de visu et de la tour de garde où il ne peut y avoir qu'un seul perso dedans !
						if(isset($_GET["bat2"]) && ($_GET["bat2"] == 2 || $_GET["bat2"] == 3) && isset($_GET["bat"]) && $_GET["bat"]!="") {
						
							// Vérification que le perso soit pas déjà dans un bâtiment
							if(!in_bat($mysqli, $id_perso) && !in_train($mysqli, $id_perso)){
						
								// verification que l'instance du batiment existe
								if (existe_instance_bat($mysqli, $_GET["bat"])){
								
									if(verif_bat_instance($mysqli, $_GET["bat2"],$_GET["bat"])){
							
										// verification qu'on soit bien à côté du batiment
										if(prox_instance_bat($mysqli, $x_persoN, $y_persoN, $_GET["bat"])){
										
											// verification si il y a un perso dans la tour
											$sql = "SELECT id_perso FROM perso_in_batiment WHERE id_instanceBat=".$_GET["bat"]."";
											$res = $mysqli->query($sql);
											$nbp = $res->fetch_row();
											
											if($nbp[0] != 0){
												// si la tour est occupee
												$erreur .= "Vous ne pouvez pas entrer, la tour est déjà occupée";
											}
											else { // la tour est vide
											
												// verification que le perso a encore des pm
												if($pm_perso + $malus_pm >= 1){
													
													if ($type_perso == '6' || $type_perso == '4' || $type_perso == '3') {
													
														$entre_bat_ok = 1;
													
														// recuperation des coordonnees et infos du batiment dans lequel le perso entre
														$sql = "SELECT nom_instance, nom_instance, pv_instance, pvMax_instance, x_instance, y_instance, id_batiment, camp_instance FROM instance_batiment WHERE id_instanceBat=".$_GET["bat"]."";
														$res = $mysqli->query($sql);
														$coordonnees_instance = $res->fetch_assoc();
														
														$x_bat 				= $coordonnees_instance["x_instance"];
														$y_bat 				= $coordonnees_instance["y_instance"];
														$nom_bat 			= $coordonnees_instance["nom_instance"];
														$nom_instance 		= $coordonnees_instance["nom_instance"];
														$id_bat				= $coordonnees_instance["id_batiment"];
														$camp_bat			= $coordonnees_instance["camp_instance"];
														$pv_batiment		= $coordonnees_instance["pv_instance"];
														$pvMax_batiment		= $coordonnees_instance["pvMax_instance"];
														$id_inst_bat 		= $_GET["bat"];
														
														// Verification si le perso est de la même nation ou non que le batiment
														if(!nation_perso_bat($mysqli, $id_perso, $id_inst_bat)) {
															
															$pourc_pv_instance = ($pv_batiment / $pvMax_batiment) * 100;
													
															if ($pourc_pv_instance <= 80) {
															
																// Les chiens et soigneurs ne peuvent pas capturer de batiment
																if ($type_perso != '6' && $type_perso != '4') {
																	
																	// Les hopitaux ne peuvent être capturés
																	if ($id_bat != '7') {
															
																		// Capture du batiment, il devient de la nation du perso
																		$sql = "UPDATE instance_batiment, perso SET camp_instance=clan WHERE id_instanceBat='$id_inst_bat' AND id_perso='$id_perso'";
																		$mysqli->query($sql);
																		
																		$sql = "select clan from perso where id_perso='$id_perso'";
																		$res = $mysqli->query($sql);
																		$t_c = $res->fetch_assoc();
																		
																		$camp = $t_c["clan"];
																		
																		// MAJ camp canons
																		$sql = "UPDATE instance_batiment_canon SET camp_canon='$camp' WHERE id_instance_bat='$id_inst_bat'";
																		$mysqli->query($sql);
																		
																		if($camp == "1"){
																			$couleur_c 		= "b";
																		}
																		else if($camp == "2"){
																			$couleur_c 		= "r";
																		}
																		else if ($camp == "3") {
																			$couleur_c 		= "g";
																		}
																		
																		// Mise à jour de l'icone centrale sur la carte
																		$icone = "b".$id_bat."$couleur_c.png";
																		$sql = "UPDATE $carte SET image_carte='$icone' WHERE x_carte=$x_bat and y_carte=$y_bat";
																		$mysqli->query($sql);
																		
																		// mise a jour table evenement
																		$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ('$id_perso','<font color=$couleur_clan_p><b>$nom_perso</b></font>','a capturé','$id_inst_bat','le batiment $nom_bat','en $x_bat/$y_bat : Felicitation!',NOW(),'0')";
																		$mysqli->query($sql);
																		
																		if ($camp_bat == '1') {
																			$couleur_clan_bat = 'blue';
																		}
																		else if ($camp_bat == '2') {
																			$couleur_clan_bat = 'red';
																		}
																		else if ($camp_bat == '2') {
																			$couleur_clan_bat = 'green';
																		}
																		else {
																			$couleur_clan_bat = 'black';
																		}
																		
																		// maj CV
																		$sql = "INSERT INTO `cv` (IDActeur_cv, nomActeur_cv, gradeActeur_cv, IDCible_cv, nomCible_cv, gradeCible_cv, date_cv, special) VALUES ($id_perso,'<font color=$couleur_clan_p>$nom_perso</font>', '$nom_grade_perso', '$id_inst_bat','<font color=$couleur_clan_bat>Tour de Guêt $nom_bat</font>', NULL, NOW(), 8)";
																		$mysqli->query($sql);
																		
																		echo "<font color = red>Felicitation, vous venez de capturer un bâtiment ennemi !</font><br>";
																	}
																	else {
																		// Tentative de triche
																		$text_triche = "Tentative capture Hopital";
						
																		$sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
																		$mysqli->query($sql);
																		
																		$erreur .= "Les hopitaux ne peuvent pas être capturés !";
																	}
																}
																else {
																	$entre_bat_ok = 0;
																	
																	// Tentative de triche
																	$text_triche = "Tentative capture batiment avec type perso non autorisé";
					
																	$sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
																	$mysqli->query($sql);
																	
																	$erreur .= "Les chiens et les soigneurs ne peuvent pas capturer de bâtiments !";
																}
															}
															else {
																$entre_bat_ok = 0;
																
																$erreur .= "Le bâtiment n'est pas encore capturable, il faut descendre ses PV";
															}
														}
														
														if ($entre_bat_ok) {
														
															// mise a jour de la carte
															$sql = "UPDATE $carte SET occupee_carte='0', idPerso_carte=NULL, image_carte=NULL WHERE x_carte='$x_persoN' AND y_carte='$y_persoN'";
															$res = $mysqli->query($sql);
																
															// mise a jour des coordonnées du perso
															$sql = "UPDATE perso SET x_perso='$x_bat', y_perso='$y_bat', pm_perso=pm_perso-1 WHERE id_perso='$id_perso'";
															$res = $mysqli->query($sql);
																
															// insertion du perso dans la table perso_in_batiment
															$sql = "INSERT INTO `perso_in_batiment` VALUES ('$id_perso','$id_inst_bat')";
															$mysqli->query($sql);
															
															echo"<font color = blue>vous êtes entré(e) dans le bâtiment $id_inst_bat</font><br>";
																
															// mise a jour table evenement
															$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ('$id_perso','<font color=$couleur_clan_p><b>$nom_perso</b></font>','est entré dans le batiment $nom_bat $id_inst_bat',NULL,'','en $x_bat/$y_bat',NOW(),'0')";
															$mysqli->query($sql);
																
															// calcul du bonus de perception
															if($_GET["bat2"] == 2){
																$bonus_perc = 5;
															}
															
															// mise a jour du bonus de perception du perso
															$bonus_visu = $bonus_perc + getBonusObjet($mysqli, $id_perso);
															
															if(bourre($mysqli, $id_perso)){
																if(!endurance_alcool($mysqli, $id_perso)) {
																	$malus_bourre = bourre($mysqli, $id_perso) * 3;
																	$bonus_visu -= $malus_bourre;
																}
															}
															// maj bonus perception et -1 pm pour rentrer dans le batiment
															$sql = "UPDATE perso SET bonusPerception_perso=$bonus_visu WHERE id_perso='$id_perso'";
															$mysqli->query($sql);
																
															// mise a jour des coordonnees du perso pour les tests d'après
															$x_persoN = $x_bat;
															$y_persoN = $y_bat;
														}
													}
													else {
														// Tentative de triche
														$text_triche = "Tentative entrer batiment tour de guet avec type perso non autorisé";
		
														$sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
														$mysqli->query($sql);
														
														$erreur .= "Seul les infanteries, soigneurs et chiens peuvent monter dans la tour de guet";
													}
												}
												else {
													$erreur .= "Il faut posséder au moins 1PM pour entrer dans le bâtiment";
												}
											}
										}
										else {
											$erreur .= "Il faut être à côté du bâtiment pour y entrer";
										}
									}
									else {
										$erreur .= "Pas bien d'essayer de tricher...";
									}
								}
								else {
									$erreur .= "Le bâtiment n'existe pas";
								}
							}
							else {
								$erreur .= "Vous devez sortir du bâtiment dans lequel vous vous trouvez afin de rentrer dans un nouveau bâtiment";
							}
						}
						// traitement des autres cas
						else {
							if(isset($_GET["bat"]) && $_GET["bat"]!="" && isset($_GET["bat2"]) && $_GET["bat2"]!="" && $_GET["bat2"] != 1 && $_GET["bat2"] != 5 && $_GET["bat2"] != 10) {
								
								// Vérification que le perso soit pas déjà dans un bâtiment
								if(!in_bat($mysqli, $id_perso) && !in_train($mysqli, $id_perso)){
								
									// verification que l'instance du batiment existe
									if (existe_instance_bat($mysqli, $_GET["bat"])){
										
										if(verif_bat_instance($mysqli, $_GET["bat2"], $_GET["bat"])){
										
											// verification qu'on soit bien à côté du batiment
											if(prox_instance_bat($mysqli, $x_persoN, $y_persoN, $_GET["bat"])){
												
												// verification que le perso a encore des pm
												if($pm_perso + $malus_pm >= 1){
													
													//recuperation du nombre de persos dans le batiment
													$sql = "select id_perso from perso_in_batiment where id_instanceBat=".$_GET["bat"]."";
													$res = $mysqli->query($sql);
													$nb_perso_bat = $res->num_rows;
											
													// recuperation des coordonnees et des infos du batiment dans lequel le perso entre
													$sql = "SELECT nom_batiment, id_instanceBat, pv_instance, pvMax_instance, nom_instance, x_instance, y_instance, contenance_instance, instance_batiment.id_batiment, taille_batiment, camp_instance 
															FROM instance_batiment, batiment 
															WHERE instance_batiment.id_batiment = batiment.id_batiment
															AND id_instanceBat=".$_GET["bat"]."";
													$res = $mysqli->query($sql);
													$coordonnees_instance = $res->fetch_assoc();
													
													$x_bat 					= $coordonnees_instance["x_instance"];
													$y_bat 					= $coordonnees_instance["y_instance"];
													$nom_bat 				= $coordonnees_instance["nom_instance"];
													$nom_batiment			= $coordonnees_instance["nom_batiment"];
													$id_inst_bat 			= $coordonnees_instance["id_instanceBat"];
													$contenance_inst_bat 	= $coordonnees_instance["contenance_instance"];
													$camp_instance_bat		= $coordonnees_instance["camp_instance"];
													$id_bat					= $coordonnees_instance["id_batiment"];
													$taille_batiment		= $coordonnees_instance["taille_batiment"];
													$pv_batiment			= $coordonnees_instance["pv_instance"];
													$pvMax_batiment			= $coordonnees_instance["pvMax_instance"];
													
													// verification contenance batiment
													if($nb_perso_bat < $contenance_inst_bat){
														
														$entre_bat_ok = 1;
														
														// verification si le perso est de la même nation que le batiment
														if(!nation_perso_bat($mysqli, $id_perso, $id_inst_bat)) {
															
															$pourc_pv_instance = ($pv_batiment / $pvMax_batiment) * 100;
													
															if ($pourc_pv_instance <= 80) {
																
																// les chiens et soigneurs ne peuvent pas capturer de batiment
																if ($type_perso != '6' && $type_perso != '4') {
																	
																	// Les hopitaux et les gares ne peuvent être capturés
																	if ($id_bat != '7' && $id_bat != '11') {
															
																		// verification que le batiment est vide
																		if(batiment_vide($mysqli, $id_inst_bat)) {
																			
																			// capture du batiment, il devient de la nation du perso
																			$sql = "UPDATE instance_batiment, perso SET camp_instance=clan WHERE id_instanceBat='$id_inst_bat' AND id_perso='$id_perso'";
																			$mysqli->query($sql);
																				
																			$sql = "select clan from perso where id_perso='$id_perso'";
																			$res = $mysqli->query($sql);
																			$t_c = $res->fetch_assoc();
																			
																			$camp = $t_c["clan"];
																			
																			// MAJ camp canons
																			$sql = "UPDATE instance_batiment_canon SET camp_canon='$camp' WHERE id_instance_bat='$id_inst_bat'";
																			$mysqli->query($sql);
																			
																			if($camp == "1"){
																				$couleur_c 		= "b";
																				$image_canon_g 	= 'canonG_nord.gif';
																				$image_canon_d 	= 'canonD_nord.gif';
																			}
																			else if($camp == "2"){
																				$couleur_c 		= "r";
																				$image_canon_g 	= 'canonG_sud.gif';
																				$image_canon_d 	= 'canonD_sud.gif';
																			}
																			
																			$icone = "b".$id_bat."$couleur_c.png";
																			
																			if ($taille_batiment > 1) {
																				
																				$taille_search 	= floor($taille_batiment / 2);
																				$image_case_c	= $couleur_c.".png";
											
																				for ($x = $x_bat - $taille_search; $x <= $x_bat + $taille_search; $x++) {
																					for ($y = $y_bat - $taille_search; $y <= $y_bat + $taille_search; $y++) {
																						if ($x == $x_bat && $y == $y_bat) {
																							// Mise à jour de l'icone centrale
																							$sql = "UPDATE $carte SET image_carte='$icone' WHERE x_carte=$x_bat and y_carte=$y_bat";
																							$mysqli->query($sql);
																						}
																						else {
																							$sql = "UPDATE $carte SET image_carte='$image_case_c' WHERE x_carte='$x' AND y_carte='$y' AND image_carte NOT LIKE 'canon%'";
																							$mysqli->query($sql);
																						}
																					}
																				}
																				
																				// Mise à jour des icones de canon sur la carte
																				if ($id_bat == 8) {
																					// Fortin
																					// Canons Gauche
																					$sql = "UPDATE $carte SET image_carte='$image_canon_g' 
																							WHERE (x_carte=$x_bat - 1 AND y_carte=$y_bat - 1) 
																							OR (x_carte=$x_bat - 1 AND y_carte=$y_bat + 1)";
																					$mysqli->query($sql);
																					
																					// Canons Droit
																					$sql = "UPDATE $carte SET image_carte='$image_canon_d' 
																							WHERE (x_carte=$x_bat + 1 AND y_carte=$y_bat - 1) 
																							OR (x_carte=$x_bat + 1 AND y_carte=$y_bat + 1)";
																					$mysqli->query($sql);
																				}
																				else if ($id_bat == 9) {
																					// Fort
																					// Canons Gauche
																					$sql = "UPDATE $carte SET image_carte='$image_canon_g' 
																							WHERE (x_carte=$x_bat - 2 AND y_carte=$y_bat + 2) 
																							OR (x_carte=$x_bat - 2 AND y_carte=$y_bat) 
																							OR (x_carte=$x_bat - 2 AND y_carte=$y_bat - 2)";
																					$mysqli->query($sql);
																					
																					// Canons Droit
																					$sql = "UPDATE $carte SET image_carte='$image_canon_d' 
																							WHERE (x_carte=$x_bat + 2 AND y_carte=$y_bat + 2) 
																							OR (x_carte=$x_bat + 2 AND y_carte=$y_bat) 
																							OR (x_carte=$x_bat + 2 AND y_carte=$y_bat - 2)";
																					$mysqli->query($sql);
																				}
																				
																				// Mise à jour des respawn
																				$sql = "DELETE FROM perso_as_respawn WHERE id_instance_bat='$id_inst_bat'";
																				$mysqli->query($sql);
																			}
																			else {
																				// Mise à jour de l'icone centrale
																				$sql = "UPDATE $carte SET image_carte='$icone' WHERE x_carte=$x_bat and y_carte=$y_bat";
																				$mysqli->query($sql);
																			}
																				
																			// mise a jour table evenement
																			$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ('$id_perso','<font color=$couleur_clan_p><b>$nom_perso</b></font>','a capturé le batiment $nom_bat','$id_inst_bat','','en $x_bat/$y_bat : Felicitation!',NOW(),'0')";
																			$mysqli->query($sql);
																			
																			// Gain points de victoire
																			if ($id_bat == 9) {
																				// FORT -> 400
																				$gain_pvict = 400;
																				$nom_b = "FORT";
																			}
																			else if ($id_bat == 8) {
																				// FORTIN -> 100
																				$gain_pvict = 100;
																				$nom_b = "FORTIN";
																			}
																			else if ($id_bat == 11) {
																				// GARE -> 75
																				$gain_pvict = 75;
																				$nom_b = "GARE";
																			}
																			else if ($id_bat == 7) {
																				// HOPITAL -> 10
																				$gain_pvict = 10;
																				$nom_b = "HOPITAL";
																			}
																			else {
																				$gain_pvict = 0;
																			}
																			
																			if ($gain_pvict > 0) {
																				
																				// C'est une capture, gains X 1.5
																				$gain_pvict = floor($gain_pvict * 1.5);
																				
																				// MAJ stats points victoire
																				$sql = "UPDATE stats_camp_pv SET points_victoire = points_victoire + ".$gain_pvict." WHERE id_camp='$clan_p'";
																				$mysqli->query($sql);
																			
																				// Ajout de l'historique
																				$date = time();
																				$texte = addslashes("Pour la capture du bâtiment ".$nom_batiment." ".$nom_bat." [".$id_inst_bat."] par ".$nom_perso." [".$id_perso."]");
																				$sql = "INSERT INTO histo_stats_camp_pv (date_pvict, id_camp, gain_pvict, texte) VALUES (FROM_UNIXTIME($date), '$clan_p', '$gain_pvict', '$texte')";
																				$mysqli->query($sql);
																				
																			}
																			
																			if ($camp_instance_bat == '1') {
																				$couleur_clan_bat = 'blue';
																			}
																			else if ($camp_instance_bat == '2') {
																				$couleur_clan_bat = 'red';
																			}
																			else if ($camp_instance_bat == '2') {
																				$couleur_clan_bat = 'green';
																			}
																			else {
																				$couleur_clan_bat = 'black';
																			}
																			
																			// maj CV
																			$sql = "INSERT INTO `cv` (IDActeur_cv, nomActeur_cv, gradeActeur_cv, IDCible_cv, nomCible_cv, gradeCible_cv, date_cv, special) VALUES ($id_perso,'<font color=$couleur_clan_p>$nom_perso</font>', '$nom_grade_perso', '$id_inst_bat','<font color=$couleur_clan_bat>$nom_b $nom_bat</font>', NULL, NOW(), 8)";
																			$mysqli->query($sql);
																			
																			echo "<font color = red>Félicitation, vous venez de capturer un bâtiment ennemi !</font><br>";
																		} 
																		else {
																			$entre_bat_ok = 0;
																			
																			$erreur .= "Le bâtiment n'est pas vide et ne peut donc pas être capturé";
																		}
																	}
																	else {
																		$entre_bat_ok = 0;
																		
																		// Tentative de triche
																		$text_triche = "Tentative capture Hopital ou Gare";
						
																		$sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
																		$mysqli->query($sql);
																		
																		$erreur .= "Les hopitaux et les gares ne peuvent pas être capturés !";
																	}
																}
																else {
																	$entre_bat_ok = 0;
																	
																	// Tentative de triche
																	$text_triche = "Tentative capture batiment avec type perso non autorisé";
					
																	$sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
																	$mysqli->query($sql);
																	
																	$erreur .= "Les chiens et les soigneurs ne peuvent pas capturer de bâtiment";
																}
															}
															else {
																$entre_bat_ok = 0;
																
																$erreur .= "Le bâtiment n'est pas encore capturable, il faut descendre ses PV";
															}
														}
														
														if ($entre_bat_ok) {
													
															// mise a jour des coordonnées du perso sur la carte
															$sql = "UPDATE $carte SET occupee_carte='0', idPerso_carte=NULL, image_carte=NULL WHERE x_carte='$x_persoN' AND y_carte='$y_persoN'";
															$res = $mysqli->query($sql);
															
															// mise a jour des coordonnées du perso
															$sql = "UPDATE perso SET x_perso='$x_bat', y_perso='$y_bat' WHERE id_perso='$id_perso'";
															$res = $mysqli->query($sql);
															
															// insertion du perso dans la table perso_in_batiment
															$sql = "INSERT INTO `perso_in_batiment` VALUES ('$id_perso','$id_inst_bat')";
															$mysqli->query($sql);
															
															echo"<font color = blue>vous êtes entré(e) dans le bâtiment $nom_bat</font>";
															
															// mise a jour table evenement
															$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ('$id_perso','<font color=$couleur_clan_p><b>$nom_perso</b></font>','est entré dans le batiment $nom_bat $id_inst_bat',NULL,'','en $x_bat/$y_bat',NOW(),'0')";
															$mysqli->query($sql);
															
															// Partie Passage de grade chef
															if ($type_perso == 1 && ($id_bat == 8 || $id_bat == 9)) {
																
																// recup grade / pc chef
																$sql = "SELECT pc_perso, id_grade FROM perso, perso_as_grade WHERE perso.id_perso = perso_as_grade.id_perso AND perso.id_perso='$id_perso'";
																$res = $mysqli->query($sql);
																$t_chef = $res->fetch_assoc();
																
																$pc_perso_chef = $t_chef["pc_perso"];
																$id_grade_chef = $t_chef["id_grade"];
																
																// Verification passage de grade 
																$sql = "SELECT id_grade, nom_grade FROM grades WHERE pc_grade <= $pc_perso_chef AND pc_grade != 0 ORDER BY id_grade DESC LIMIT 1";
																$res = $mysqli->query($sql);
																$t_grade = $res->fetch_assoc();
																
																$id_grade_final 	= $t_grade["id_grade"];
																$nom_grade_final	= $t_grade["nom_grade"];
																
																if ($id_grade_chef < $id_grade_final) {
																		
																	// Passage de grade								
																	$sql = "UPDATE perso_as_grade SET id_grade='$id_grade_final' WHERE id_perso='$id_perso'";
																	$mysqli->query($sql);
																	
																	// mise a jour des evenements
																	$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ('$id_perso','<font color=$couleur_clan_p><b>$nom_perso</b></font>','a été promu <b>$nom_grade_final</b> !',NULL,'','',NOW(),'0')";
																	$mysqli->query($sql);
																	
																	// maj CV
																	$sql = "INSERT INTO `cv` (IDActeur_cv, nomActeur_cv, gradeActeur_cv, IDCible_cv, nomCible_cv, gradeCible_cv, date_cv, special) VALUES ($id_perso,'<font color=$couleur_clan_p>$nom_perso</font>', '$nom_grade_final', NULL, NULL, NULL, NOW(), 9)";
																	$mysqli->query($sql);
																}
															}
															
															$bonus_perc = 0;
															
															// mise a jour du bonus de perception du perso
															$bonus_visu = $bonus_perc + getBonusObjet($mysqli, $id_perso);
															
															if(bourre($mysqli, $id_perso)){
																if(!endurance_alcool($mysqli, $id_perso)) {
																	$malus_bourre = bourre($mysqli, $id_perso) * 3;
																	$bonus_visu -= $malus_bourre;
																}
															}
															
															// maj bonus perception et -1 pm pour l'entrée dans le batiment
															$sql = "UPDATE perso SET bonusPerception_perso=$bonus_visu, pm_perso=pm_perso-1 WHERE id_perso='$id_perso'";
															$mysqli->query($sql);
															
															// mise a jour des coordonnees du perso pour le test d'après
															$x_persoN = $x_bat;
															$y_persoN = $y_bat;
														}
													}
													else {
														$erreur .= "Le bâtiment est déjà rempli au maximum de sa capacité";
													}
												}
												else {
													$erreur .= "Il faut posséder au moins 1PM pour entrer dans le bâtiment";
												}
											}
											else {
												// Tentative de triche
												$text_triche = "Tentative pour entrer dans un bâtiment sans être à côté de celui-ci";
										
												$sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
												$mysqli->query($sql);
												
												$erreur .= "Il faut être à côté du bâtiment pour y entrer";
											}
										}
										else {
											$erreur .= "Pas bien d'essayer de tricher...";
										}
									}
									else {
										// Tentative de triche
										$text_triche = "Tentative entrer dans un natiment qui n existe pas...";
								
										$sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
										$mysqli->query($sql);
										
										$erreur .= "Le batiment n'existe pas";
									}
								}
								else {
									$erreur .= "Vous devez sortir du bâtiment dans lequel vous vous trouvez afin de rentrer dans un nouveau bâtiment";
								}
							}
						}
					}
				}
				
				// Traitement sortie
				if (isset($_GET['sortie'])) {
					
					// verification que le perso a encore des pm
					if($pm_perso + $malus_pm >= 1){
					
						$instance_bat = in_bat($mysqli, $id_perso);
						
						if($instance_bat){
							
							$coord_sortie = $_GET['sortie'];
							
							$t_coord = explode(',',$coord_sortie);
							
							if (count($t_coord) == 2) {
							
								$x_sortie = $t_coord[0];
								$y_sortie = $t_coord[1];
								
								$verif_x = preg_match("#^[0-9]*[0-9]$#i","$x_sortie");
								$verif_y = preg_match("#^[0-9]*[0-9]$#i","$y_sortie");
								
								if ($verif_x && $verif_y) {
									
									if (in_map($x_sortie, $y_sortie, $X_MAX, $Y_MAX)) {
										
										// Récupération x, y et taille batiment
										$sql = "SELECT x_instance, y_instance, taille_batiment, batiment.id_batiment FROM instance_batiment, batiment 
												WHERE instance_batiment.id_batiment = batiment.id_batiment
												AND id_instanceBat = '$instance_bat'";
										$res = $mysqli->query($sql);
										$t = $res->fetch_assoc();
										
										$id_bat		= $t['id_batiment'];
										$x_instance = $t['x_instance'];
										$y_instance = $t['y_instance'];
										$taille_bat = $t['taille_batiment'];
										
										// Cas particulier pénitencier
										if ($id_bat != 10) {
										
											$nb_case_bat = ceil($taille_bat / 2);
											
											if (($x_sortie == $x_instance + $nb_case_bat && $y_sortie >= $y_instance - $nb_case_bat && $y_sortie <= $y_instance + $nb_case_bat)
												|| ($x_sortie == $x_instance - $nb_case_bat && $y_sortie >= $y_instance - $nb_case_bat && $y_sortie <= $y_instance + $nb_case_bat)
												|| ($y_sortie == $y_instance + $nb_case_bat && $x_sortie >= $x_instance - $nb_case_bat && $x_sortie <= $x_instance + $nb_case_bat)
												|| ($y_sortie == $y_instance - $nb_case_bat && $x_sortie >= $x_instance - $nb_case_bat && $x_sortie <= $x_instance + $nb_case_bat)) {
												
												
												// recuperation des fonds
												$sql = "SELECT fond_carte, occupee_carte FROM $carte WHERE x_carte='$x_sortie' AND y_carte='$y_sortie'";
												$res_map = $mysqli->query ($sql);
												$t_carte1 = $res_map->fetch_assoc();
												
												$fond = $t_carte1["fond_carte"];
												$oc_c = $t_carte1["occupee_carte"];
												
												// On vérifie que la case n'est pas déjà occupée
												if (!$oc_c) {
													
													$cout_pm = cout_pm($fond);
													
													if ($pm_perso + $malus_pm >= $cout_pm) {
													
														// mise a jour des coordonnees du perso et de ses pm
														$sql = "UPDATE perso SET x_perso = '$x_sortie', y_perso = '$y_sortie', pm_perso=pm_perso-$cout_pm WHERE id_perso = '$id_perso'";
														$mysqli->query($sql);
														
														$x_persoN = $x_sortie;
														$y_persoN = $y_sortie;
														
														// mise a jour des coordonnees du perso sur la carte et changement d'etat de la case
														$sql = "UPDATE $carte SET occupee_carte='1', image_carte='$image_perso' ,idPerso_carte='$id_perso' WHERE x_carte = '$x_sortie' AND y_carte = '$y_sortie'";
														$mysqli->query($sql);
														
														// mise a jour de la table perso_in_batiment
														$sql = "DELETE FROM perso_in_batiment WHERE id_perso='$id_perso'";
														$mysqli->query($sql);
														
														// mise a jour des evenements
														$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ('$id_perso','<font color=$couleur_clan_p><b>$nom_perso</b></font>','est sorti du batiment',NULL,'','en $x_sortie/$y_sortie',NOW(),'0')";
														$mysqli->query($sql);
														
														// mise a jour du bonus de perception
														$bonus_visu = get_malus_visu($fond) + getBonusObjet($mysqli, $id_perso);
														
														if(bourre($mysqli, $id_perso)){
															if(!endurance_alcool($mysqli, $id_perso)) {
																$malus_bourre = bourre($mysqli, $id_perso) * 3;
																$bonus_visu -= $malus_bourre;
															}
														}
														
														$sql = "UPDATE perso SET bonusPerception_perso=$bonus_visu WHERE id_perso='$id_perso'";
														$mysqli->query($sql);
														
														// maj carte brouillard de guerre
														$perception_final = $perception_perso + $bonus_visu;
														if ($id_perso >= 100) {
															if ($clan_p == 1) {
																$sql = "UPDATE $carte SET vue_nord='1' 
																		WHERE x_carte >= $x_persoN - $perception_final AND x_carte <= $x_persoN + $perception_final
																		AND y_carte >= $y_persoN - $perception_final AND y_carte <= $y_persoN + $perception_final";
																$mysqli->query($sql);
															}
															else if ($clan_p == 2) {
																$sql = "UPDATE $carte SET vue_sud='1' 
																		WHERE x_carte >= $x_persoN - $perception_final AND x_carte <= $x_persoN + $perception_final
																		AND y_carte >= $y_persoN - $perception_final AND y_carte <= $y_persoN + $perception_final";
																$mysqli->query($sql);
															}
														}
													}
													else {
														$erreur .= "Vous n'avez pas assez de PM pour sortir du bâtiment sur cette case !";
													}
												}
												else {
													$erreur .= "La case de sortie est déjà occupée !";
												}
											}
											else {
												// Tentative de triche
												$text_triche = "Les coordonnées de sortie en paramètre ne correspondent pas à la sortie du batiment";
									
												$sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
												$mysqli->query($sql);
												
												$erreur .= "Paramètre incorrect !";
											}
										}
										else {
											// Tentative de triche
											$text_triche = "Tentative de sortie de pénitencier";
								
											$sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
											$mysqli->query($sql);
											
											$erreur .= "Votre tentative d'évasion s'est soldée par un echec, les gardes vous ont rattrapés et remis au cachot !";
										}
									}
									else {
										$erreur .= "Les coordonnées sont en dehors de la carte !";
									}
								}
								else {
									// Tentative de triche
									$text_triche = "Tentative modification parametre sortie, paramètre x ou y incorrect";
						
									$sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
									$mysqli->query($sql);
									
									$erreur .= "Paramètre incorrect !";
								}
							}
							else {
								// Tentative de triche
								$text_triche = "Tentative modification parametre sortie, nombre paramètres incorrect";
					
								$sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
								$mysqli->query($sql);
								
								$erreur .= "Paramètre incorrect !";
							}
						}
						else {
							// Tentative de triche
							$text_triche = "Tentative utilisation sortie alors que non dans batiment";
					
							$sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
							$mysqli->query($sql);
							
							$erreur .= "Vous ne pouvez pas utiliser cette fonction si vous n'êtes pas dans un bâtiment !";
						}
					}
					else {
						$erreur .= "Vous n'avez pas assez de PM pour sortir du bâtiment !";
					}
				}
				
				// On se trouve dans un batiment
				if(in_bat($mysqli, $id_perso)){
					
					// Récupération des infos sur l'instance du batiment dans lequel le perso se trouve
					$sql = "SELECT id_instanceBat, id_batiment, nom_instance, pv_instance, pvMax_instance FROM instance_batiment WHERE x_instance='$x_persoN' AND y_instance='$y_persoN'";
					$res = $mysqli->query($sql);
					$t = $res->fetch_assoc();
					
					$id_bat 	= $t["id_instanceBat"];
					$bat 		= $t["id_batiment"];
					$nom_ibat 	= $t["nom_instance"];
					$pv_bat		= $t['pv_instance'];
					$pvMax_bat	= $t['pvMax_instance'];
					
					//recuperation du nom du batiment
					$sql_n = "SELECT nom_batiment FROM batiment WHERE id_batiment = '$bat'";
					$res_n = $mysqli->query($sql_n);
					$t_n = $res_n->fetch_assoc();
					
					$nom_bat = $t_n["nom_batiment"];
					
					// Les chiens ne peuvent pas réparer les bâtiments
					if ($pv_bat < $pvMax_bat && $type_perso != '6') {
						$mess_bat .= "<center><font color = blue>~~<a href=\"action.php?bat=$id_bat&reparer=ok\" > reparer $nom_bat $nom_ibat [$id_bat] (5 PA)</a>~~</font></center>";
					}
					
					// cas particulier gare
					if ($bat == '11') {
						if ($clan_p == 1) {
							$mess_bat .= "<center><font color = blue>~~<a href=\"generer_plans_gares_nord.php?bat=$id_bat\" target='_blank'> accéder à la page du bâtiment $nom_bat $nom_ibat</a>~~</font></center>";
						}
						else if ($clan_p == 2) {
							$mess_bat .= "<center><font color = blue>~~<a href=\"generer_plans_gares_sud.php?bat=$id_bat\" target='_blank'> accéder à la page du bâtiment $nom_bat $nom_ibat</a>~~</font></center>";
						}
					}
					else {
						$mess_bat .= "<center><font color = blue>~~<a href=\"batiment.php?bat=$id_bat\" target='_blank'> accéder à la page du bâtiment $nom_bat $nom_ibat</a>~~</font></center>";
					}
					
					$bonus_perc = 0;
					
					// calcul du bonus/malus de perception
					if($bat == 2){
						// Tour de guet
						$bonus_perc += 5;
					}
					else if ($bat == 8 || $bat == 9 || $bat == 11) {
						// Fort / Fortin / Gare
						$bonus_perc += -1;
					}
					else if ($bat == 7 || $bat == 10) {
						// Hopital / Pénitencier
						$bonus_perc += -2;
					}
					
					// mise a jour du bonus de perception du perso
					$bonus_visu = $bonus_perc + getBonusObjet($mysqli, $id_perso);
					
				} else {
					
					$sql = "SELECT fond_carte FROM $carte WHERE x_carte=$x_persoN AND y_carte=$y_persoN";
					$res_map = $mysqli->query($sql);
					$t_carte1 = $res_map->fetch_assoc();
					
					$fond 			= $t_carte1["fond_carte"];
					
					$malus_fond = get_malus_visu($fond);
								
					// Les chiens ne perdent pas de perception en foret
					if ($malus_fond < 0 && $type_perso == 6) {
						$malus_fond = 0;
					}
								
					$bonus_visu = $malus_fond + getBonusObjet($mysqli, $id_perso);
				}
				
				if(bourre($mysqli, $id_perso)){
					if(!endurance_alcool($mysqli, $id_perso)) {
						$malus_bourre = bourre($mysqli, $id_perso) * 3;
						$bonus_visu -= $malus_bourre;
					}
				}
				
				$sql = "UPDATE perso SET bonusPerception_perso=$bonus_visu WHERE id_perso='$id_perso'";
				$mysqli->query($sql);
				
				// On se trouve dans un train
				if (in_train($mysqli, $id_perso)) {
					$mess_bat .= "<center><font color = blue><b>Vous êtes dans un train</b></font></center>";
					
					if (isset($_GET['train']) && isset($_GET['direction'])) {
						
						// on veut sortir du batiment
						if(isset($_GET["out"]) && $_GET["out"] == "ok") {
						
							$id_instance_train 	= $_GET['train'];
							$direction_saut		= $_GET['direction'];
							
							if (isDirectionOK($direction_saut)) {
							
								switch($direction_saut){
									case 1: 
										// Haut gauche
										$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
												WHERE x_carte = $x_persoN - 2 AND y_carte >= $y_persoN + 2";
										break;
									case 2:
										// Haut
										$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
												WHERE x_carte = $x_persoN AND y_carte = $y_persoN + 2";										
										break;
									case 3:
										// Haut droite
										$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
												WHERE x_carte = $x_persoN + 2 AND y_carte = $y_persoN + 2";								
										break;
									case 4: 
										// Gauche
										$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
												WHERE x_carte = $x_persoN - 2 AND y_carte = $y_persoN";									
										break;
									case 5: 
										// Droite
										$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
												WHERE x_carte = $x_persoN + 2 AND y_carte = $y_persoN";									
										break;
									case 6: 
										// Bas gauche
										$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
												WHERE x_carte = $x_persoN - 2 AND y_carte = $y_persoN - 2";										
										break;
									case 7: 
										// Bas
										$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
												WHERE x_carte = $x_persoN AND y_carte = $y_persoN - 2";										
										break;
									case 8: 
										// Bas droite
										$sql = "SELECT occupee_carte, x_carte, y_carte, fond_carte FROM $carte 
												WHERE x_carte = $x_persoN + 2 AND y_carte = $y_persoN - 2";
										break;
								}
								
								$res = $mysqli->query($sql);
								$t = $res->fetch_assoc();
								
								$oc 	= $t["occupee_carte"];
								$xs 	= $t["x_carte"];
								$ys 	= $t["y_carte"];
								$fond_c = $t["fond_carte"];
								
								if (!$oc && in_map($xs, $ys, $X_MAX, $Y_MAX) && !is_eau_p($fond_c)) {
									// On peut sauter
									
									// mise a jour du bonus de perception
									$bonus_visu = get_malus_visu($fond_c) + getBonusObjet($mysqli, $id_perso);
									
									if(bourre($mysqli, $id_perso)){
										if(!endurance_alcool($mysqli, $id_perso)) {
											$malus_bourre = bourre($mysqli, $id_perso) * 3;
											$bonus_visu -= $malus_bourre;
										}
									}
									
									// On supprime le perso du train 
									$sql = "DELETE FROM perso_in_train WHERE id_train='$id_instance_train' AND id_perso='$id_perso'";
									$mysqli->query($sql);
									
									// MAJ perso
									$sql = "UPDATE perso SET x_perso='$xs', y_perso='$ys', bonusPerception_perso=$bonus_visu, pv_perso=pv_perso/2 WHERE id_perso='$id_perso'";
									$mysqli->query($sql);
									
									// mise a jour des coordonnees du perso sur la carte et changement d'etat de la case
									$sql = "UPDATE $carte SET occupee_carte='1', image_carte='$image_perso' ,idPerso_carte='$id_perso' WHERE x_carte = '$xs' AND y_carte = '$ys'";
									$mysqli->query($sql);
									
									// mise a jour des evenements
									$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ('$id_perso','<font color=$couleur_clan_p><b>$nom_perso</b></font>','est sauté du train ',NULL,'','[<a href=\"evenement.php?infoid=$id_instance_train\">$id_instance_train</a>] en $xs/$ys | PV/2',NOW(),'0')";
									$mysqli->query($sql);
									
									// maj carte brouillard de guerre
									$perception_final = $perception_perso + $bonus_visu;
									if ($id_perso >= 100) {
										if ($clan_p == 1) {
											$sql = "UPDATE $carte SET vue_nord='1' 
													WHERE x_carte >= $xs - $perception_final AND x_carte <= $xs + $perception_final
													AND y_carte >= $ys - $perception_final AND y_carte <= $ys + $perception_final";
											$mysqli->query($sql);
										}
										else if ($clan_p == 2) {
											$sql = "UPDATE $carte SET vue_sud='1' 
													WHERE x_carte >= $xs - $perception_final AND x_carte <= $xs + $perception_final
													AND y_carte >= $ys - $perception_final AND y_carte <= $ys + $perception_final";
											$mysqli->query($sql);
										}
									}
								}
								else {
									// On ne peux pas sauter
									$erreur .= "Impossible de sauter du train dans cette direction";
								}
							}
							else {
								// TRICHE
							}
						}
					}
					
				}
				
				// Traitement ramasser objets à terre
				if(isset($_GET['ramasser']) && $_GET['ramasser'] == "ok"){
					
					if ($pa_perso >= 1) {
						
						// MAJ pa perso
						$sql = "UPDATE perso SET pa_perso=pa_perso-1 WHERE id_perso='$id_perso'";
						$mysqli->query($sql);
						
						$liste_ramasse = "";
						
						// récupération de la liste des objets à terre
						$sql = "SELECT type_objet, id_objet, nb_objet FROM objet_in_carte WHERE x_carte='$x_persoN' AND y_carte='$y_persoN'";
						$res = $mysqli->query($sql);
						
						while ($t = $res->fetch_assoc()) {
								
							$type_objet = $t['type_objet'];
							$id_objet	= $t['id_objet'];
							$nb_objet	= $t['nb_objet'];
							
							// Suppression de l'objet par terre
							$sql_d = "DELETE FROM objet_in_carte WHERE type_objet='$type_objet' AND id_objet='$id_objet' AND x_carte='$x_persoN' AND y_carte='$y_persoN'";
							$mysqli->query($sql_d);
							
							// Récupération poid objet
							// Thunes
							if ($type_objet == 1) {
								$poid_objet = 0;
								
								// Ajout de la thune au perso 
								$sql_t = "UPDATE perso SET or_perso=or_perso+$nb_objet WHERE id_perso='$id_perso'";
								$mysqli->query($sql_t);
								
								$liste_ramasse .= $nb_objet . " Thune";
								if ($nb_objet > 1) {
									$liste_ramasse .= "s";
								}
							}
							
							// Objet
							if ($type_objet == 2) {
								$sql_obj = "SELECT nom_objet, poids_objet FROM objet WHERE id_objet='$id_objet'";
								$res_obj = $mysqli->query($sql_obj);
								$t_obj = $res_obj->fetch_assoc();
								
								$nom_objet	= $t_obj['nom_objet'];
								$poid_objet = $t_obj['poids_objet'];
								
								for ($i = 0; $i < $nb_objet; $i++) {
									// Ajout de l'objet dans l'inventaire du perso
									$sql_o = "INSERT INTO perso_as_objet (id_perso, id_objet) VALUES ('$id_perso', '$id_objet')";
									$mysqli->query($sql_o);								
								}
								
								// calcul charge objets
								$charge_objets_total = $poid_objet * $nb_objet;
								
								// MAJ charge perso 
								$sql_c = "UPDATE perso SET charge_perso = charge_perso + $charge_objets_total WHERE id_perso='$id_perso'";
								$mysqli->query($sql_c);
								
								$liste_ramasse .= " -- ". $nb_objet . " " . $nom_objet;
							}
							
							// Arme 
							if ($type_objet == 3) {
								$sql_obj = "SELECT nom_arme, poids_arme FROM arme WHERE id_arme='$id_objet'";
								$res_obj = $mysqli->query($sql_obj);
								$t_obj = $res_obj->fetch_assoc();
								
								$nom_arme	= $t_obj['nom_arme'];
								$poid_objet = $t_obj['poids_arme'];
								
								for ($i = 0; $i < $nb_objet; $i++) {
									// Ajout de l'arme dans l'inventaire du perso
									$sql_a = "INSERT INTO perso_as_arme (id_perso, id_arme, est_portee) VALUES ('$id_perso', '$id_objet', '0')";
									$mysqli->query($sql_a);								
								}
								
								// calcul charge armes
								$charge_objets_total = $poid_objet * $nb_objet;
								
								// MAJ charge perso 
								$sql_c = "UPDATE perso SET charge_perso = charge_perso + $charge_objets_total WHERE id_perso='$id_perso'";
								$mysqli->query($sql_c);
								
								$liste_ramasse .= " -- ". $nb_objet . " " . $nom_arme;
							}
						}
						
						// mise a jour des evenements
						$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ('$id_perso','<font color=$couleur_clan_p><b>$nom_perso</b></font>','a ramassé des objets par terre ',NULL,'','en $x_persoN/$y_persoN : $liste_ramasse',NOW(),'0')";
						$mysqli->query($sql);
						
						echo "<center><font colot='blue'>Vous avez rammassé les objets suivants : ". $liste_ramasse ."</font></center><br>";
					}
					else {
						$erreur .= "Vous n'avez pas assez de PA pour rammasser les objets à terre.";
					}
				}
	
				// traitement des deplacements
				if (isset($_GET["mouv"])) {
					
					$mouv = $_GET["mouv"];
					
					$x_persoE = $t_perso1["x_perso"];
					$y_persoE = $t_perso1["y_perso"];
					$pm_perso = $t_perso1["pm_perso"];
					
					if (!in_bat($mysqli, $id_perso) && !in_train($mysqli, $id_perso)) {
					
						if (reste_pm($pm_perso + $malus_pm)) {
							
							//on modifie les coordonnées du perso suivant le deplacement qu'il a effectué
							switch($mouv){ 
								case 1: $x_persoN=$x_persoE-1; $y_persoN=$y_persoE+1; break;
								case 2: $x_persoN=$x_persoE; $y_persoN=$y_persoE+1; break;
								case 3: $x_persoN=$x_persoE+1; $y_persoN=$y_persoE+1; break;
								case 4: $x_persoN=$x_persoE-1; $y_persoN=$y_persoE; break;
								case 5: $x_persoN=$x_persoE+1; $y_persoN=$y_persoE; break;
								case 6: $x_persoN=$x_persoE-1; $y_persoN=$y_persoE-1; break;
								case 7: $x_persoN=$x_persoE; $y_persoN=$y_persoE-1; break;
								case 8: $x_persoN=$x_persoE+1; $y_persoN=$y_persoE-1; break;
							}
								
							$in_map = in_map($x_persoN, $y_persoN, $X_MAX, $Y_MAX);
							
							if ($in_map) {
								
								$sql = "SELECT occupee_carte, fond_carte, image_carte FROM $carte WHERE x_carte=$x_persoN AND y_carte=$y_persoN";
								$res_map = $mysqli->query($sql);
								$t_carte1 = $res_map->fetch_assoc();
								
								$case_occupee 	= $t_carte1["occupee_carte"];
								$fond 			= $t_carte1["fond_carte"];
								
								$cout_pm 	= cout_pm($fond);
		
								if (!is_eau_p($fond)) {
									
									if (!$case_occupee){
										
										if($pm_perso  + $malus_pm >= $cout_pm){
											
											$chance = rand(1,1000);
											
											if ($chance == 1) {
												
												// échec critique, le perso trébuche, perd 1PM et reste sur place
												$sql = "UPDATE perso SET pm_perso=pm_perso-1 WHERE id_perso='$id_perso'"; 
												$mysqli->query($sql);

												$erreur .= "<b>Vous avez trébuché, vous perdez 1PM !</b>";
												// mise a jour des évènements
												$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ($id_perso,'$nom_perso','a trébuché',NULL,'','en $x_persoN/$y_persoN',NOW(),'0')";
												$mysqli->query($sql);
											}
											else {
												
												// maj perso : mise à jour des pm et du bonus de perception
												$sql = "UPDATE perso SET pm_perso =$pm_perso-$cout_pm, bonusPerception_perso=$bonus_visu WHERE id_perso='$id_perso'"; 
												$mysqli->query($sql);
												
												//mise à jour des coordonnées du perso 
												$dep = "UPDATE perso SET x_perso=$x_persoN, y_perso=$y_persoN WHERE id_perso ='$id_perso'"; 
												$mysqli->query($dep);
												
												// maj carte perso
												$sql = "UPDATE $carte SET occupee_carte='0', image_carte=NULL, idPerso_carte=save_info_carte WHERE x_carte='$x_persoE' AND y_carte='$y_persoE'";
												$mysqli->query($sql);
												
												$sql = "UPDATE $carte SET occupee_carte='1', image_carte='$image_perso', idPerso_carte='$id_perso' WHERE x_carte='$x_persoN' AND y_carte='$y_persoN'"; 
												$mysqli->query($sql);
												
												// maj carte brouillard de guerre
												$perception_final = $perception_perso + $bonus_visu;
												if ($id_perso >= 100) {
													if ($clan_p == 1) {
														$sql = "UPDATE $carte SET vue_nord='1' 
																WHERE x_carte >= $x_persoN - $perception_final AND x_carte <= $x_persoN + $perception_final
																AND y_carte >= $y_persoN - $perception_final AND y_carte <= $y_persoN + $perception_final";
														$mysqli->query($sql);
													}
													else if ($clan_p == 2) {
														$sql = "UPDATE $carte SET vue_sud='1' 
																WHERE x_carte >= $x_persoN - $perception_final AND x_carte <= $x_persoN + $perception_final
																AND y_carte >= $y_persoN - $perception_final AND y_carte <= $y_persoN + $perception_final";
														$mysqli->query($sql);
													}
												}
												
												// maj evenement
												$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ($id_perso,'<font color=$couleur_clan_p><b>$nom_perso</b></font>','s\'est deplacé',NULL,'','en $x_persoN/$y_persoN',NOW(),'0')";
												$mysqli->query($sql);
												
												if ($chance == 1000) {
													// réussite critique : gain de 1PM
													$sql = "UPDATE perso SET pm_perso=pm_perso+1 WHERE id_perso='$id_perso'"; 
													$mysqli->query($sql);
													
													// mise a jour des évènements
													$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ($id_perso,'$nom_perso','est en forme aujourd\'hui !',NULL,'','',NOW(),'0')";
													$mysqli->query($sql);
													header("location:jouer.php?message=gainPM");
												}
												else {
													header("location:jouer.php");
												}
											}
										}
										else{
										
											$erreur .= "Vous n'avez pas assez de pm !";
											
											// verification si il y a un batiment a proximite du perso
											$mess_bat .= afficher_lien_prox_bat($mysqli, $x_persoE, $y_persoE, $id_perso, $type_perso);
										}
									}
									else {
									
										// Verification de qui / quoi occupe la case pour voir si on peut le bousculer
										$sql = "SELECT idPerso_carte FROM $carte WHERE x_carte='$x_persoN' AND y_carte='$y_persoN'";
										$res = $mysqli->query($sql);
										$t = $res->fetch_assoc();
										
										$idPerso_carte = $t['idPerso_carte'];
										
										// Batiment
										if ($idPerso_carte < 200000 && $idPerso_carte >= 50000) {
											$erreur .= "Cette case est déjà occupée par un batiment !";
										}
										else if ($idPerso_carte >= 200000) {
											// PNJ
											$erreur .= "Cette case est déjà occupée par un pnj !";
										} else {
											if ($bousculade_dep || isset($_GET['action_popup'])) {
											
												// Perso 
												// Récupération des informations du perso
												$sql = "SELECT clan, pm_perso, pa_perso, type_perso, image_perso, nom_perso FROM perso WHERE id_perso='$idPerso_carte'";
												$res = $mysqli->query($sql);
												$t = $res->fetch_assoc();
												
												$camp_perso_b 	= $t['clan'];
												$pm_perso_b		= $t['pm_perso'];
												$pa_perso_b		= $t['pa_perso'];
												$type_perso_b	= $t['type_perso'];
												$image_perso_b	= $t['image_perso'];
												$nom_perso_b	= $t['nom_perso'];
												$id_perso_b 	= $idPerso_carte;
												
												$couleur_clan_p_b = couleur_clan($camp_perso_b);
												
												// Calcul case cible bousculade
												switch($mouv){ 
													case 1: $x_persoB=$x_persoE-2; $y_persoB=$y_persoE+2; break;
													case 2: $x_persoB=$x_persoE; $y_persoB=$y_persoE+2; break;
													case 3: $x_persoB=$x_persoE+2; $y_persoB=$y_persoE+2; break;
													case 4: $x_persoB=$x_persoE-2; $y_persoB=$y_persoE; break;
													case 5: $x_persoB=$x_persoE+2; $y_persoB=$y_persoE; break;
													case 6: $x_persoB=$x_persoE-2; $y_persoB=$y_persoE-2; break;
													case 7: $x_persoB=$x_persoE; $y_persoB=$y_persoE-2; break;
													case 8: $x_persoB=$x_persoE+2; $y_persoB=$y_persoE-2; break;
												}
												
												// Est ce que le perso peut être bousculer par mon perso											
												
												// types perso compatible pour bousculade ?
												if (isTypePersoBousculable($type_perso, $type_perso_b)) {
												
													// Ai-je suffisamment de PA / PM pour effectuer la bousculade ?
													if($pm_perso  + $malus_pm >= $cout_pm && $pa_perso >= 3){
													
														// Case cible de la bousculade est-elle hors carte ?
														if (in_map($x_persoB, $y_persoB, $X_MAX, $Y_MAX)) {
															
															$sql = "SELECT occupee_carte, fond_carte, image_carte FROM $carte WHERE x_carte=$x_persoB AND y_carte=$y_persoB";
															$res_map = $mysqli->query($sql);
															$t_carteB = $res_map->fetch_assoc();
															
															$case_occupeeB 	= $t_carteB["occupee_carte"];
															$fondB 			= $t_carteB["fond_carte"];
															
															$cout_pmB 		= cout_pm($fondB);
															$bonus_visuB 	= get_malus_visu($fondB) + getBonusObjet($mysqli, $id_perso);
															
															// Case cible de la bousculade est-elle déjà occupée ?
															if (!$case_occupeeB) {
																// Case cible eau profonde ?
																if (!is_eau_p($fondB)) {
																	
																	// Même camp ou non ?
																	if ($camp_perso_b == $clan_p) {
																		// Même camp
																		// Si allié, mon allié possède t-il encore 1PA ?
																		if ($pa_perso_b >= 1) {
																			
																			// OK => On bouscule !
																			
																			//-------------------------------------
																			// On déplace en premier le bousculé 
																			$sql = "UPDATE perso SET pa_perso = $pa_perso_b-1, bonusPerception_perso=$bonus_visuB WHERE id_perso='$id_perso_b'"; 
																			$mysqli->query($sql);
																			
																			//mise à jour des coordonnées du perso 
																			$dep = "UPDATE perso SET x_perso=$x_persoB, y_perso=$y_persoB WHERE id_perso ='$id_perso_b'"; 
																			$mysqli->query($dep);
																			
																			// maj carte
																			$sql = "UPDATE $carte SET occupee_carte='0', image_carte=NULL, idPerso_carte=save_info_carte WHERE x_carte='$x_persoN' AND y_carte='$y_persoN'";
																			$mysqli->query($sql);
																			
																			$sql = "UPDATE $carte SET occupee_carte='1', image_carte='$image_perso_b', idPerso_carte='$id_perso_b' WHERE x_carte='$x_persoB' AND y_carte='$y_persoB'"; 
																			$mysqli->query($sql);
																		
																			//-----------------------
																			// On se déplace ensuite
																			$sql = "UPDATE perso SET pm_perso =$pm_perso-$cout_pm, pa_perso = $pa_perso-3, bonusPerception_perso=$bonus_visu WHERE id_perso='$id_perso'"; 
																			$mysqli->query($sql);
																			
																			//mise à jour des coordonnées du perso 
																			$dep = "UPDATE perso SET x_perso=$x_persoN, y_perso=$y_persoN WHERE id_perso ='$id_perso'"; 
																			$mysqli->query($dep);
																			
																			// maj carte
																			$sql = "UPDATE $carte SET occupee_carte='0', image_carte=NULL, idPerso_carte=save_info_carte WHERE x_carte='$x_persoE' AND y_carte='$y_persoE'";
																			$mysqli->query($sql);
																			
																			$sql = "UPDATE $carte SET occupee_carte='1', image_carte='$image_perso', idPerso_carte='$id_perso' WHERE x_carte='$x_persoN' AND y_carte='$y_persoN'"; 
																			$mysqli->query($sql);
																			
																			// maj carte brouillard de guerre
																			$perception_final = $perception_perso + $bonus_visu;
																			if ($id_perso >= 100) {
																				if ($clan_p == 1) {
																					$sql = "UPDATE $carte SET vue_nord='1' 
																							WHERE x_carte >= $x_persoN - $perception_final AND x_carte <= $x_persoN + $perception_final
																							AND y_carte >= $y_persoN - $perception_final AND y_carte <= $y_persoN + $perception_final";
																					$mysqli->query($sql);
																				}
																				else if ($clan_p == 2) {
																					$sql = "UPDATE $carte SET vue_sud='1' 
																							WHERE x_carte >= $x_persoN - $perception_final AND x_carte <= $x_persoN + $perception_final
																							AND y_carte >= $y_persoN - $perception_final AND y_carte <= $y_persoN + $perception_final";
																					$mysqli->query($sql);
																				}
																			}
																			
																			// maj evenement
																			$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ($id_perso,'<font color=$couleur_clan_p><b>$nom_perso</b></font>','a bousculé ',$id_perso_b,'<font color=$couleur_clan_p_b><b>$nom_perso_b</b></font>','en $x_persoB/$y_persoB',NOW(),'0')";
																			$mysqli->query($sql);
																			
																			header("location:jouer.php");
																			
																		} else {
																			$erreur .= "Votre allié ne possède plus suffisamment de PA pour être bousculer (demande 1 PA à votre allié) !";
																		}
																	} else {
																		// Camps différents
																		
																		// -------------
																		// - ANTI ZERK -
																		// -------------
																		$verif_anti_zerk = gestion_anti_zerk($mysqli, $id_perso);
																		
																		if ($verif_anti_zerk) {
																		
																			$chance_bouculade = mt_rand(0,100);
																			
																			$date_log = time();
																			
																			$sql = "INSERT INTO log (date_log, id_perso, type_action, pourcentage, message_log) 
																					VALUES (FROM_UNIXTIME($date_log), '$id_perso', 'Bousculade', '$chance_bouculade', '$id_perso a bousculé $id_perso_b')";
																			$mysqli->query($sql);
																				
																			if ($chance_bouculade <= 66) {
																			
																				// OK => On bouscule !
																				
																				//-------------------------------------
																				// On déplace en premier le bousculé 
																				// maj perso : mise à jour des pm et du bonus de perception
																				$sql = "UPDATE perso SET pm_perso = pm_perso-$cout_pmB, bonusPerception_perso=$bonus_visuB WHERE id_perso='$id_perso_b'"; 
																				$mysqli->query($sql);
																				
																				//mise à jour des coordonnées du perso 
																				$dep = "UPDATE perso SET x_perso=$x_persoB, y_perso=$y_persoB WHERE id_perso ='$id_perso_b'"; 
																				$mysqli->query($dep);
																				
																				// maj carte
																				$sql = "UPDATE $carte SET occupee_carte='0', image_carte=NULL, idPerso_carte=save_info_carte WHERE x_carte='$x_persoN' AND y_carte='$y_persoN'";
																				$mysqli->query($sql);
																				
																				$sql = "UPDATE $carte SET occupee_carte='1', image_carte='$image_perso_b', idPerso_carte='$id_perso_b' WHERE x_carte='$x_persoB' AND y_carte='$y_persoB'"; 
																				$mysqli->query($sql);
																				
																				//-----------------------
																				// On se déplace ensuite
																				$sql = "UPDATE perso SET pm_perso =$pm_perso-$cout_pm, pa_perso = $pa_perso-3, bonusPerception_perso=$bonus_visu WHERE id_perso='$id_perso'"; 
																				$mysqli->query($sql);
																				
																				//mise à jour des coordonnées du perso 
																				$dep = "UPDATE perso SET x_perso=$x_persoN, y_perso=$y_persoN WHERE id_perso ='$id_perso'"; 
																				$mysqli->query($dep);
																				
																				// maj carte
																				$sql = "UPDATE $carte SET occupee_carte='0', image_carte=NULL, idPerso_carte=save_info_carte WHERE x_carte='$x_persoE' AND y_carte='$y_persoE'";
																				$mysqli->query($sql);
																				
																				$sql = "UPDATE $carte SET occupee_carte='1', image_carte='$image_perso', idPerso_carte='$id_perso' WHERE x_carte='$x_persoN' AND y_carte='$y_persoN'"; 
																				$mysqli->query($sql);
																				
																				// maj carte brouillard de guerre
																				$perception_final = $perception_perso + $bonus_visu;
																				if ($id_perso >= 100) {
																					if ($clan_p == 1) {
																						$sql = "UPDATE $carte SET vue_nord='1' 
																								WHERE x_carte >= $x_persoN - $perception_final AND x_carte <= $x_persoN + $perception_final
																								AND y_carte >= $y_persoN - $perception_final AND y_carte <= $y_persoN + $perception_final";
																						$mysqli->query($sql);
																					}
																					else if ($clan_p == 2) {
																						$sql = "UPDATE $carte SET vue_sud='1' 
																								WHERE x_carte >= $x_persoN - $perception_final AND x_carte <= $x_persoN + $perception_final
																								AND y_carte >= $y_persoN - $perception_final AND y_carte <= $y_persoN + $perception_final";
																						$mysqli->query($sql);
																					}
																				}
																				
																				// maj evenement
																				$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ($id_perso,'<font color=$couleur_clan_p><b>$nom_perso</b></font>','a bousculé ',$id_perso_b,'<font color=$couleur_clan_p_b><b>$nom_perso_b</b></font>','en $x_persoB/$y_persoB',NOW(),'0')";
																				$mysqli->query($sql);
																				
																				//header("location:jouer.php");
																			}
																			else {
																				// MAJ pa perso
																				$sql = "UPDATE perso SET pa_perso = $pa_perso-3 WHERE id_perso='$id_perso'"; 
																				$mysqli->query($sql);
																				
																				// maj evenement
																				$sql = "INSERT INTO `evenement` (IDActeur_evenement, nomActeur_evenement, phrase_evenement, IDCible_evenement, nomCible_evenement, effet_evenement, date_evenement, special) VALUES ($id_perso,'<font color=$couleur_clan_p><b>$nom_perso</b></font>','a raté sa bousculade sur ',$id_perso_b,'<font color=$couleur_clan_p_b><b>$nom_perso_b</b></font>','',NOW(),'0')";
																				$mysqli->query($sql);
																					
																				$erreur .= "Vous avez raté votre bousculade et perdez 3PA";
																			}
																		}
																		else {
																			$erreur .= "Loi anti-zerk non respectée !";
																		}
																	}
																} else {
																	$erreur .= "Impossible de bousculer un perso dans de l'eau profonde !";
																}
															} else {
																$erreur .= "La case cible de la bousculade est déjà occupée !";
															}														
														} else {
															$erreur .= "Impossible de bousculer un perso hors map !";
														}
													} 
													else {
														$erreur .= "Vous n'avez pas assez de PA/PM pour bousculer un perso !";
													}
												} else {
													$erreur .= "Impossible de bousculer ce type de perso !";
												}
											}
											else {
												$erreur .= "Cette case des déjà occupée par un autre perso !";
											}
										}										
										
										// verification si il y a un batiment a proximite du perso
										$mess_bat .= afficher_lien_prox_bat($mysqli, $x_persoE, $y_persoE, $id_perso, $type_perso);
									}
								}
								else if (is_eau_p($fond)) {
								
									$erreur .= "Vous ne pouvez pas vous deplacer en eau profonde !";
									
									// verification si il y a un batiment a proximite du perso
									$mess_bat .= afficher_lien_prox_bat($mysqli, $x_persoE, $y_persoE, $id_perso, $type_perso);
								}
							}
							else if (!in_map($x_persoN, $y_persoN, $X_MAX, $Y_MAX)){
							
								$erreur .= "Vous ne pouvez pas vous déplacer sur cette case, elle est hors limites !";
								
								// verification si il y a un batiment a proximite du perso
								$mess_bat .= afficher_lien_prox_bat($mysqli, $x_persoE, $y_persoE, $id_perso, $type_perso);
							}
						}
						else if(!reste_pm($pm_perso + $malus_pm)){
							
							header("Location:jouer.php?erreur=pm");
						}
						else {
							// normalement impossible
							$erreur .= "Veuillez contacter l'administrateur si vous voyez ce message, merci";
						}
					}
					else {
						$erreur .= "Vous ne pouvez pas vous déplacer si vous êtes dans un bâtiment ou un train";
					}
				}
				else {
					if (!in_train($mysqli, $id_perso)) {
						// verification si il y a un batiment a proximite du perso
						$mess_bat .= afficher_lien_prox_bat($mysqli, $x_persoN, $y_persoN, $id_perso, $type_perso);
					}
				}
            
			}
		}
	}
	else {
		header("Location:../index.php");
	}
}
else {
	// logout
	$_SESSION = array(); // On écrase le tableau de session
	session_destroy(); // On détruit la session
	
	header("Location:../index2.php");
}
				
				?>	