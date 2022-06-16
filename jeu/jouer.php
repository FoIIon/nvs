<?php
@session_start();

require_once("../fonctions.php");
require_once("f_carte.php");
require_once("f_combat.php");
require_once("f_popover.php");

$mysqli = db_connexion();

include ('../nb_online.php');

date_default_timezone_set('Europe/Paris');

require_once("page_jeu/logique_batiment.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Nord VS Sud</title>
		
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=0.3, shrink-to-fit=no">
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		
		<link href="../style2.css" rel="stylesheet" type="text/css">
		
	</head>

	<body background='../images/background.jpg'>
		<main class="flex-shrink-0" >
			<div class="container" style="padding:0!important"  >
				<?php
				$date_serveur = new DateTime(null, new DateTimeZone('Europe/Paris'));
				
				$date_dla = date('d-m-Y H:i:s', $n_dla);
				
				if (anim_perso($mysqli, $id_perso)) {
					// Récupération des demandes sur la gestion des compagnies
					$sql = "SELECT * FROM compagnie_demande_anim, compagnies 
							WHERE compagnie_demande_anim.id_compagnie = compagnies.id_compagnie
							AND compagnies.id_clan='$clan_p'";
					$res = $mysqli->query($sql);
					$nb_demandes_gestion_compagnie = $res->num_rows;
					
					// Récupération des demandes sur la gestion des persos 
					$sql = "(SELECT perso_demande_anim.* FROM perso_demande_anim, perso
							WHERE perso_demande_anim.id_perso = perso.id_perso
							AND perso.clan = '$clan_p'
							AND perso_demande_anim.type_demande = 1)
							UNION ALL
							(SELECT perso_demande_anim.* FROM perso_demande_anim, perso
							WHERE perso_demande_anim.id_perso = perso.idJoueur_perso
							AND perso.clan = '$clan_p'
							AND perso.chef = '1'
							AND perso_demande_anim.type_demande > 1)
							";
					$res = $mysqli->query($sql);
					$nb_demandes_gestion_perso = $res->num_rows;
					
					// Récupération du nombre de questions / remontées anims en attente de réponse
					$sql = "SELECT id FROM anim_question WHERE id_camp='$clan_p' AND status='0'";
					$res = $mysqli->query($sql);
					$nb_questions_anim = $res->num_rows;
					
					// Récupération du nombre de remontées de capture RP non traitées
					$sql = "SELECT id FROM anim_capture WHERE statut='0'";
					$res = $mysqli->query($sql);
					$nb_captures_anim = $res->num_rows;
					
					$nb_demande_a_traiter = $nb_demandes_gestion_compagnie + $nb_demandes_gestion_perso + $nb_questions_anim + $nb_captures_anim;
				}
				
				// Récupération du nombre de missions actives
				$sql_ma = "SELECT id_mission, nom_mission, texte_mission, recompense_thune, recompense_xp, recompense_pc, nombre_participant, date_debut_mission, date_fin_mission 
						FROM missions WHERE date_debut_mission IS NOT NULL AND (date_fin_mission IS NULL OR date_fin_mission >= CURDATE())
						AND camp_mission='$clan_p'";
				$res_ma = $mysqli->query($sql_ma);
				$nb_missions_actives = $res_ma->num_rows;
				
				?>
				<div class="row">
					<div class="col">
						<?php
						//affichage de l'heure serveur et de nouveau tour
						require_once("page_jeu/tableau_menu.php");
						//fin affichage heure server et de nouveau tour
						?>
					</div>
				</div>
				<?php
				$sql_info = "SELECT xp_perso, pc_perso, pv_perso, pvMax_perso, pa_perso, paMax_perso, pi_perso, pm_perso, pmMax_perso, recup_perso, protec_perso, type_perso, x_perso, y_perso, perception_perso, bonusPerception_perso, bonusRecup_perso, bonusPA_perso, bonusPM_perso, bonus_perso, charge_perso, chargeMax_perso, image_perso, message_perso, clan, bataillon FROM perso WHERE ID_perso ='$id_perso'"; 
				$res_info = $mysqli->query($sql_info);
				$t_perso2 = $res_info->fetch_assoc();
				
				$x_perso 				= $t_perso2["x_perso"];
				$y_perso 				= $t_perso2["y_perso"];
				$image_perso 			= $t_perso2["image_perso"];
				$perc 					= $t_perso2["perception_perso"] + $t_perso2["bonusPerception_perso"];
				$pa_perso 				= $t_perso2["pa_perso"];
				$paMax_perso 			= $t_perso2["paMax_perso"];
				$pi_perso 				= $t_perso2["pi_perso"];
				$xp_perso 				= $t_perso2["xp_perso"];
				$pc_perso 				= $t_perso2["pc_perso"];
				$pv_perso 				= $t_perso2["pv_perso"];
				$pvMax_perso 			= $t_perso2["pvMax_perso"];
				$pm_perso_tmp			= $t_perso2["pm_perso"];
				$pmMax_perso_tmp 		= $t_perso2["pmMax_perso"];
				$perception_perso 		= $t_perso2["perception_perso"];
				$bonusPerception_perso 	= $t_perso2["bonusPerception_perso"];
				$bonusPA_perso			= $t_perso2["bonusPA_perso"];
				$recup_perso 			= $t_perso2["recup_perso"];
				$bonusRecup_perso		= $t_perso2["bonusRecup_perso"];
				$bonusPM_perso			= $t_perso2["bonusPM_perso"];
				$protec_perso 			= $t_perso2["protec_perso"];
				$bonus_perso 			= $t_perso2["bonus_perso"];
				$type_perso 			= $t_perso2["type_perso"];
				$bataillon_perso 		= $t_perso2["bataillon"];
				$message_perso			= $t_perso2["message_perso"];
				$charge_perso			= $t_perso2["charge_perso"];
				$chargeMax_perso		= $t_perso2["chargeMax_perso"];
				
				// Bonus recup batiment
				$bonus_recup_bat 		= get_bonus_recup_bat_perso($mysqli, $id_perso);
				$bonus_recup_terrain 	= get_bonus_recup_terrain_perso($mysqli, $x_perso, $y_perso);
				
				$bonusRecup_perso += $bonus_recup_bat;
				$bonusRecup_perso += $bonus_recup_terrain;
				
				// Si perso chien
				if ($type_perso == 6) {
					
					// Récupération des coordonnées du chef
					$sql = "SELECT x_perso, y_perso FROM perso WHERE idJoueur_perso='$id_joueur_perso' AND chef=1";
					$res = $mysqli->query($sql);
					$t_coord_chef = $res->fetch_assoc();
					
					$x_perso_chef = $t_coord_chef['x_perso'];
					$y_perso_chef = $t_coord_chef['y_perso'];
					
					if (abs($x_perso_chef - $x_perso) > 15 || abs($y_perso_chef - $y_perso) > 15) {
						$bonusPerception_perso -= 3;
						$perc -= 3;
					}					
				}
				
				if (in_bat($mysqli, $id_perso)) {
											
					$id_instance_bat_perso = in_bat($mysqli, $id_perso);
					
					$sql_b = "SELECT batiment.id_batiment, nom_batiment, taille_batiment, nom_instance FROM batiment, instance_batiment 
							WHERE instance_batiment.id_batiment = batiment.id_batiment
							AND instance_batiment.id_instanceBat = '$id_instance_bat_perso'";
					$res_b = $mysqli->query($sql_b);
					$t_b = $res_b->fetch_assoc();
					
					$id_bat_perso 			= $t_b['id_batiment'];
					$nom_bat_perso			= $t_b['nom_batiment'];
					$taille_bat_perso		= $t_b['taille_batiment'];
					$nom_instance_bat_perso	= $t_b['nom_instance'];
				}
				
				// calcul malus pm
				$malus_pm_charge = getMalusCharge($charge_perso, $chargeMax_perso);
				if ($malus_pm_charge == 100) {
					$malus_pm = -$pmMax_perso;
				}
				else {
					$malus_pm = $malus_pm_charge;
				}
				
				$pmMax_perso 	= $pmMax_perso_tmp + $bonusPM_perso;
				$pm_perso 		= $pm_perso_tmp + $malus_pm;
				
				$clan_perso = $t_perso2["clan"];				
				
				if($clan_perso == 1){
					$clan = 'rond_b.png';
					$couleur_clan_perso = 'blue';
					
					$image_profil 		= "profil_nord4.png";
					$image_sac 			= "sac_nord2.png";
					$image_compagnie 	= "compagnie_nord2.png";
					$image_evenement 	= "evenement_nord.png";
					$image_messagerie 	= "messagerie_nord.png";
					$image_em 			= "em_nord2.png";
					
					if ($id_perso >= 100) {
						$sql = "UPDATE $carte SET vue_nord='1' 
								WHERE x_carte >= $x_perso - $perc AND x_carte <= $x_perso + $perc
								AND y_carte >= $y_perso - $perc AND y_carte <= $y_perso + $perc";
						$mysqli->query($sql);
					}
				}
				if($clan_perso == 2){
					$clan = 'rond_r.png';
					$couleur_clan_perso = 'red';
					
					$image_profil 		= "profil_sud4.png";
					$image_sac 			= "sac_sud2.png";
					$image_compagnie 	= "compagnie_sud2.png";
					$image_evenement 	= "evenement_sud.png";
					$image_messagerie 	= "messagerie_sud.png";
					$image_em 			= "em_sud2.png";
					
					if ($id_perso >= 100) {
						$sql = "UPDATE $carte SET vue_sud='1' 
								WHERE x_carte >= $x_perso - $perc AND x_carte <= $x_perso + $perc
								AND y_carte >= $y_perso - $perc AND y_carte <= $y_perso + $perc";
						$mysqli->query($sql);
					}
				}
				
				// récupération du grade du perso 
				$sql_grade = "SELECT perso_as_grade.id_grade, nom_grade FROM perso_as_grade, grades WHERE perso_as_grade.id_grade = grades.id_grade AND id_perso='$id_perso'";
				$res_grade = $mysqli->query($sql_grade);
				$t_grade = $res_grade->fetch_assoc();
				
				$id_grade_perso 	= $t_grade["id_grade"];
				$nom_grade_perso 	= $t_grade["nom_grade"];
				
				// cas particuliers grouillot
				if ($id_grade_perso == 101) {
					$id_grade_perso = "1.1";
				}
				if ($id_grade_perso == 102) {
					$id_grade_perso = "1.2";
				}
				
				$nom_compagnie_perso = "";
				$nb_demandes_adhesion_compagnie = 0;
				$nb_demandes_emprunt_compagnie	= 0;
				$nb_demandes_depart_compagnie	= 0;
				
				// recuperation de l'id de la compagnie du perso
				$sql_groupe = "SELECT id_compagnie from perso_in_compagnie where id_perso='$id_perso' AND (attenteValidation_compagnie='0' OR attenteValidation_compagnie='2')";
				$res_groupe = $mysqli->query($sql_groupe);
				$t_groupe = $res_groupe->fetch_assoc();
				$nb = $res_groupe->num_rows;
				
				$id_compagnie = $nb ? $t_groupe['id_compagnie'] : 0;
				$genie_compagnie_perso	= 0;
								
				if($id_compagnie){
					
					// Recuperation des infos sur la compagnie (dont le nom)
					$sql_groupe2 = "SELECT * FROM compagnies WHERE id_compagnie='$id_compagnie'";
					$res_groupe2 = $mysqli->query($sql_groupe2);
					$t_groupe2 = $res_groupe2->fetch_assoc();
					
					$nom_compagnie_perso 		= addslashes($t_groupe2['nom_compagnie']);
					$image_compagnie_perso		= $t_groupe2['image_compagnie'];
					$genie_compagnie_perso		= $t_groupe2['genie_civil'];
					$id_parent_compagnie_perso	= $t_groupe2['id_parent'];
					
					if (isset($id_parent_compagnie_perso)) {
						
						$sql_p = "SELECT nom_compagnie FROM compagnies WHERE id_compagnie='$id_parent_compagnie_perso'";
						$res_p = $mysqli->query($sql_p);
						$t_p = $res_p->fetch_assoc();
						
						$nom_compagnie_mere = addslashes($t_p['nom_compagnie']);
						
						$nom_compagnie_perso = $nom_compagnie_mere." - ".$nom_compagnie_perso;
						
					}
					
					// Quel est le poste du perso dans la compagnie ?
					$sql = "SELECT poste_compagnie FROM perso_in_compagnie WHERE id_compagnie='$id_compagnie' AND id_perso='$id_perso'";
					$res = $mysqli->query($sql);
					$t = $res->fetch_assoc();
					
					$poste_perso_compagnie = $t['poste_compagnie'];
					
					// Chef ou Recruteur
					if ($poste_perso_compagnie == 1 || $poste_perso_compagnie == 4) {
						
						// Vérifier nouvelles demandes d'adhésion
						$sql = "SELECT id_perso FROM perso_in_compagnie WHERE id_compagnie='$id_compagnie' AND attenteValidation_compagnie='1'";
						$res = $mysqli->query($sql);
						$nb_demandes_adhesion_compagnie = $res->num_rows;
						
						// Vérifier nouvelles demandes de départ
						$sql = "SELECT id_perso FROM perso_in_compagnie WHERE id_compagnie='$id_compagnie' AND attenteValidation_compagnie='2'";
						$res = $mysqli->query($sql);
						$nb_demandes_depart_compagnie = $res->num_rows;
					}
					
					// Chef ou Trésorier
					if ($poste_perso_compagnie == 1 || $poste_perso_compagnie == 3) {
						
						// Vérifier nouvelles demandes d'emprunt
						$sql = "SELECT banque_compagnie.id_perso FROM banque_compagnie, perso, perso_in_compagnie 
								WHERE banque_compagnie.id_perso = perso.id_perso 
								AND perso.id_perso = perso_in_compagnie.id_perso
								AND perso_in_compagnie.id_compagnie='$id_compagnie' 
								AND demande_emprunt='1'";
						$res = $mysqli->query($sql);
						$nb_demandes_emprunt_compagnie = $res->num_rows;
						
					}
				}
				else {
					$image_compagnie_perso = "";
				}
				
				// Le perso est-il membre de l'etat major de son camp ?
				$sql_em = "SELECT * FROM perso_in_em WHERE id_perso='$id_perso' AND camp_em='$clan_perso'";
				$res_em = $mysqli->query($sql_em);
				$nb_em = $res_em->num_rows;
				
				if ($nb_em) {
					$pourc_icone = "12%";
					
					// Verifier nombre compagnies en attente de validation
					$sql = "SELECT * FROM em_creer_compagnie WHERE camp='$clan_perso'";
					$res = $mysqli->query($sql);
					$nb_compagnie_attente_em = $res->num_rows;
					
				} else if ($type_perso == 6) {
					$pourc_icone = "20%";
				} else {
					$pourc_icone = "14%";
				}
				
				// Récupération de tous les persos du joueur
				$sql = "SELECT id_perso, nom_perso, chef FROM perso WHERE idJoueur_perso='$id_joueur_perso' ORDER BY id_perso";
				$res = $mysqli->query($sql);
				
				// init vide
				$nom_perso_chef = "";
				
				?>
				<div class="row">
					<div class="col">
						<!-- Début du tableau d'information-->
						<?php require_once("page_jeu/tableau_information.php"); ?>
						<!--Fin du tableau d'information-->
					</div>
				</div>
				<div class="row">
					<div class="col">
						<!-- Début menu-->
						<?php require_once("page_jeu/menu.php"); ?>
						<!-- Fin menu -->
					</div>
				</div>
				<?php
				echo "<center><font color='red'>".$erreur."</font></center>";
				if (isset($mess) && trim($mess) != "") {
					echo "<center><font color='green'><b>".$mess."</b></font></center>";
				}
				
				
				?>
				</br>
					<!-- logique deplacement -->
					<?php require_once("page_jeu/logique_ramassage.php" ); ?>
					<!-- Fin logique deplacement -->
					<div class="row flex-nowrap" >
						<div class="col-4 flex-nowrap" >
							 <!--tableau agir  style="background: red;"-->
							<?php require_once("page_jeu/tableau_agir.php" ); ?>
							<!-- Fin tableau agir -->
							<br />
<<<<<<< Updated upstream
							
							<table border="2" style="background-color: palevioletred;" width="100%">
								<tr>
									<td colspan='3' bgcolor="lightgrey" align='center'><b>Caractéristiques de combat</b></td>
								</tr>
								<tr>
									<td width='20%'></td>
									<?php 
									if ($type_perso != 5) { 
									?>
									<td width='40%' align='center'><b>Rapproché</b></td>
									<?php 
									}
									
									if ($type_perso != 6 && $type_perso != 4) { 
									?>
									<td width='40%' align='center' nowrap="nowrap"><b>A distance</b></td>
									<?php 
									}
									else if ($type_perso == 4) {
									?>
									<td width='40%' align='center'><b>Rapproché</b></td>
									<?php 	
									}
									?>
								</tr>
								<tr>
									<td><b>Armes</b></td>
									<?php 
									if ($type_perso != 5) { 
									?>
									<td align='center' nowrap="nowrap"><?php echo $nom_arme_cac; ?></td>
									<?php 
									}
									
									if ($type_perso != 6 && $type_perso != 4) { 
									?>
									<td align='center' nowrap="nowrap"><?php echo $nom_arme_dist; ?></td>
									<?php 
									}
									else if ($type_perso == 4) {
									?>
									<td align='center' nowrap="nowrap"><?php echo $nom_arme_cac2; ?></td>
									<?php 	
									}
									?>
								</tr>
								<tr>
									<td nowrap="nowrap"><b>Coût en PA</b></td>
									<?php 
									if ($type_perso != 5) { 
									?>
									<td align='center'><?php echo $coutPa_arme_cac; ?></td>
									<?php 
									}
									
									if ($type_perso != 6 && $type_perso != 4) { 
									?>
									<td align='center'><?php echo $coutPa_arme_dist; if (possede_lunette_visee($mysqli, $id_perso)) { echo " (+2)"; } ?></td>
									<?php 
									}
									else if ($type_perso == 4) {
									?>
									<td align='center' nowrap="nowrap"><?php echo $coutPa_arme_cac2; ?></td>
									<?php 	
									}
									?>
								</tr>
								<tr>
									<?php 
									if ($type_perso != 4) { 
									?>
									<td><b>Dégâts</b></td>
									<?php 
									} else {
									?>
									<td><b>Soins</b></td>
									<?php 
									}
									?>
									<?php 
									if ($type_perso != 5) { 
									?>
									<td align='center'><?php echo $degats_arme_cac; ?></td>
									<?php 
									}
									
									if ($type_perso != 6 && $type_perso != 4) { 
									?>
									<td align='center'><?php echo $degats_arme_dist; ?></td>
									<?php 
									}
									else if ($type_perso == 4) {
									?>
									<td align='center' nowrap="nowrap"><?php echo $degats_arme_cac2; ?></td>
									<?php 	
									}
									?>
								</tr>
								<tr>
									<td><b>Portée</b></td>
									<?php 
									if ($type_perso != 5) { 
									?>
									<td align='center'><?php echo $porteeMax_arme_cac; ?></td>
									<?php 
									}
									
									if ($type_perso != 6 && $type_perso != 4) { 
									?>
									<td align='center'><?php echo $porteeMax_arme_dist; ?></td>
									<?php 
									}
									else if ($type_perso == 4) {
									?>
									<td align='center' nowrap="nowrap"><?php echo $porteeMax_arme_cac2; ?></td>
									<?php 	
									}
									?>
								</tr>
								<tr>
									<td><b>Précision</b></td>
									<?php 
									if ($type_perso != 5) { 
									?>
									<td align='center'><?php echo $precision_arme_cac . "%"; ?></td>
									<?php 
									}
									
									if ($type_perso != 6 && $type_perso != 4) { 
									?>
									<td align='center'>
										<?php 
										echo $precision_arme_dist . "%";
										
										$bonus_precision_objet = getBonusPrecisionDistObjet($mysqli, $id_perso);
										
										if ($bonus_precision_objet != 0) {
											echo " (+".$bonus_precision_objet."%)"; 
										}
										
										?>
									</td>
									<?php 
									}
									else if ($type_perso == 4) {
									?>
									<td align='center' nowrap="nowrap"><?php echo $precision_arme_cac2 . "%"; ?></td>
									<?php 	
									}
									?>
								</tr>
								<?php 
								if ($type_perso == 5 && $degatZone_arme_dist) { 
								?>
								<tr>
									<td><b>Spécial</b></td>
									<td colspan='2'>
										<center>Dégâts de zone
										<?php 
										if ($id_arme_dist == 13) {
											echo "<br>Bonus de dégâts sur bâtiments";
										}
										?>
										</center>
									</td>
								</tr>
								<?php 
								}
								?>
								<tr>
									<form method="post" action="agir.php" target='_main'>
									<?php 
									if ($type_perso != 4) { 
									?>
									<td><input type="submit" value="Attaquer"></td>
									<?php 
									} else {
									?>
									<td><input type="submit" value="Soigner"></td>
									<?php 
									}
									if ($type_perso != 5) { 
									?>
									<td>
										<select name='id_attaque_cac' style="width: -moz-available;">
											<option value="personne">Personne</option>
											<?php
											// Soigneur
											if ($type_perso == 4) {
												
												while($t_cible_portee_cac = $res_portee_cac->fetch_assoc()) {
													
													$id_cible_cac = $t_cible_portee_cac["idPerso_carte"];
													
													if ($id_cible_cac < 50000) {
														
														// Un autre perso
														$sql = "SELECT nom_perso, pv_perso, pvMax_perso, bonus_perso, clan FROM perso WHERE id_perso='$id_cible_cac'";
														$res = $mysqli->query($sql);
														$tab = $res->fetch_assoc();
														
														$nom_cible_cac 		= $tab["nom_perso"];
														$pv_cible_cac		= $tab["pv_perso"];
														$pv_max_cible_cac	= $tab["pvMax_perso"];
														$bonus_cible_cac	= $tab["bonus_perso"];
														$camp_cible_cac		= $tab["clan"];
														
														$couleur_clan_cible = couleur_clan($camp_cible_cac);
														
														if ($id_arme_cac == 10) {
															// seringue
															// On affiche que les persos blessés
															if ($pv_cible_cac < $pv_max_cible_cac) {
																echo "<option style=\"color:". $couleur_clan_cible ."\" value='".$id_cible_cac.",".$id_arme_cac."'>".$nom_cible_cac." (mat. ".$id_cible_cac.")</option>";
															}
														} else if ($id_arme_cac == 11) {
															// bandage
															// On affiche que les persos avec malus
															if ($bonus_cible_cac < 0) {
																echo "<option style=\"color:". $couleur_clan_cible ."\" value='".$id_cible_cac.",".$id_arme_cac."'>".$nom_cible_cac." (mat. ".$id_cible_cac.")</option>";
															}
														}
													} else if ($id_cible_cac >= 200000) {
														
														// Un PNJ
														$sql = "SELECT nom_pnj, pv_i, pvMax_pnj FROM pnj, instance_pnj WHERE pnj.id_pnj = instance_pnj.id_pnj AND idInstance_pnj = '$id_cible_cac'";
														$res = $mysqli->query($sql);
														$tab = $res->fetch_assoc();
														
														$nom_cible_cac 		= $tab["nom_pnj"];
														$pv_cible_cac		= $tab["pv_i"];
														$pv_max_cible_cac	= $tab["pvMax_pnj"];
														
														if ($pv_cible_cac < $pv_max_cible_cac) {
															echo "<option style=\"color:grey\" value='".$id_cible_cac.",".$id_arme_cac."'>".$nom_cible_cac." (mat. ".$id_cible_cac.")</option>";
														}														
													} else {
														// Un Batiment => on ne veut pas l'afficher !
													}
												}
											}
											else {
												// Impossible d'attaquer au CaC quand on est dans un train
												if (!in_train($mysqli, $id_perso)) {
												
													while($t_cible_portee_cac = $res_portee_cac->fetch_assoc()) {
														
														$id_cible_cac = $t_cible_portee_cac["idPerso_carte"];
														
														if ($id_cible_cac < 50000) {
															
															// Un autre perso
															$sql = "SELECT nom_perso, clan FROM perso WHERE id_perso='$id_cible_cac'";
															$res = $mysqli->query($sql);
															$tab = $res->fetch_assoc();
															
															$nom_cible_cac 	= $tab["nom_perso"];
															$camp_cible_cac	= $tab["clan"];
															
															$couleur_clan_cible = couleur_clan($camp_cible_cac);
															
														} else if ($id_cible_cac >= 200000) {
															
															// Un PNJ
															$sql = "SELECT nom_pnj FROM pnj, instance_pnj WHERE pnj.id_pnj = instance_pnj.id_pnj AND idInstance_pnj = '$id_cible_cac'";
															$res = $mysqli->query($sql);
															$tab = $res->fetch_assoc();
															
															$nom_cible_cac = $tab["nom_pnj"];
															
															$couleur_clan_cible = "grey";
															
														} else {
															
															// Un Batiment
															$sql = "SELECT nom_batiment, nom_instance, camp_instance FROM batiment, instance_batiment WHERE batiment.id_batiment = instance_batiment.id_batiment AND id_instanceBat = '$id_cible_cac'";
															$res = $mysqli->query($sql);
															$tab = $res->fetch_assoc();
															
															$nom_cible_cac = $tab["nom_batiment"];
															if ($tab["nom_instance"] != "") {
																$nom_cible_cac .= " ".$tab["nom_instance"];
															}
															
															$camp_cible_cac	= $tab["camp_instance"];
															
															$couleur_clan_cible = couleur_clan($camp_cible_cac);
														}
														
														echo "<option style=\"color:". $couleur_clan_cible ."\" value='".$id_cible_cac.",".$id_arme_cac."'>".$nom_cible_cac." (mat. ".$id_cible_cac.")</option>";
													}
												}
											}
											?>
										</select>
									</td>
									<?php 
									}
									
									if ($type_perso != 6 && $type_perso != 4) {
									?>
									<td>
										<select name='id_attaque_dist' style="width: -moz-available;">
											<option value="personne">Personne</option>
											<?php
											if (!isset($id_bat_perso) || (isset($id_bat_perso) && $id_bat_perso != 10)) {
												while($t_cible_portee_dist = $res_portee_dist->fetch_assoc()) {
													
													$id_cible_dist = $t_cible_portee_dist["idPerso_carte"];
													$id_instance_in_bat = in_bat($mysqli,$id_perso);

													if ($id_cible_dist != $id_instance_in_bat) {
														
														if ($id_cible_dist < 50000) {

															// Un autre perso
															$sql = "SELECT nom_perso, clan FROM perso WHERE id_perso='$id_cible_dist'";
															$res = $mysqli->query($sql);
															$tab = $res->fetch_assoc();
															
															$nom_cible_dist = $tab["nom_perso"];
															$camp_cible_cac	= $tab["clan"];
																
															$couleur_clan_cible = couleur_clan($camp_cible_cac);
															
														} else if ($id_cible_dist >= 200000) {
															
															// Un PNJ
															$sql = "SELECT nom_pnj FROM pnj, instance_pnj WHERE pnj.id_pnj = instance_pnj.id_pnj AND idInstance_pnj = '$id_cible_dist'";
															$res = $mysqli->query($sql);
															$tab = $res->fetch_assoc();
															
															$nom_cible_dist = $tab["nom_pnj"];
															
															$couleur_clan_cible = "grey";
															
														} else {
														
															// Un Batiment
															$sql = "SELECT nom_batiment, nom_instance, camp_instance FROM batiment, instance_batiment WHERE batiment.id_batiment = instance_batiment.id_batiment AND id_instanceBat = '$id_cible_dist'";
															$res = $mysqli->query($sql);
															$tab = $res->fetch_assoc();
															
															$nom_cible_dist = $tab["nom_batiment"];
															if ($tab["nom_instance"] != "") {
																$nom_cible_dist .= " ".$tab["nom_instance"];
															}
															
															$camp_cible_dist	= $tab["camp_instance"];
														
															$couleur_clan_cible = couleur_clan($camp_cible_dist);
														}
														
														echo "<option style=\"color:". $couleur_clan_cible ."\" value='".$id_cible_dist.",".$id_arme_dist."'>".$nom_cible_dist." (mat. ".$id_cible_dist.")</option>";
													}
												}
											}
											?>
										</select>
									</td>
									<?php 
									}
									else if ($nb_cac > 1 && $type_perso == '4') {
										$res_portee_cac2 = resource_liste_cibles_a_portee_attaque($mysqli, 'carte', $id_perso, $porteeMin_arme_cac, $porteeMax_arme_cac, $perc_att, 'cac');
									?>
									<td>
										<select name='id_attaque_cac2' style="width: -moz-available;">
											<option value="personne">Personne</option>
											<?php
											// Soigneur
											if ($type_perso == 4) {
												
												while($t_cible_portee_cac = $res_portee_cac2->fetch_assoc()) {
													
													$id_cible_cac = $t_cible_portee_cac["idPerso_carte"];
													
													if ($id_cible_cac < 50000) {
														
														// Un autre perso
														$sql = "SELECT nom_perso, pv_perso, pvMax_perso, bonus_perso, clan FROM perso WHERE id_perso='$id_cible_cac'";
														$res = $mysqli->query($sql);
														$tab = $res->fetch_assoc();
														
														$nom_cible_cac 		= $tab["nom_perso"];
														$pv_cible_cac		= $tab["pv_perso"];
														$pv_max_cible_cac	= $tab["pvMax_perso"];
														$bonus_cible_cac	= $tab["bonus_perso"];
														$camp_cible_cac		= $tab["clan"];
														
														$couleur_clan_cible = couleur_clan($camp_cible_cac);
														
														if ($id_arme_cac2 == 10) {
															// seringue
															// On affiche que les persos blessés
															if ($pv_cible_cac < $pv_max_cible_cac) {
																echo "<option style=\"color:". $couleur_clan_cible ."\" value='".$id_cible_cac.",".$id_arme_cac2."'>".$nom_cible_cac." (mat. ".$id_cible_cac.")</option>";
															}
														} else if ($id_arme_cac2 == 11) {
															// bandage
															// On affiche que les persos avec malus
															if ($bonus_cible_cac < 0) {
																echo "<option style=\"color:". $couleur_clan_cible ."\" value='".$id_cible_cac.",".$id_arme_cac2."'>".$nom_cible_cac." (mat. ".$id_cible_cac.")</option>";
															}
														}
													} else if ($id_cible_cac >= 200000) {
														
														// Un PNJ
														$sql = "SELECT nom_pnj, pv_i, pvMax_pnj FROM pnj, instance_pnj WHERE pnj.id_pnj = instance_pnj.id_pnj AND idInstance_pnj = '$id_cible_cac'";
														$res = $mysqli->query($sql);
														$tab = $res->fetch_assoc();
														
														$nom_cible_cac 		= $tab["nom_pnj"];
														$pv_cible_cac		= $tab["pv_i"];
														$pv_max_cible_cac	= $tab["pvMax_pnj"];
														
														if ($pv_cible_cac < $pv_max_cible_cac) {
															echo "<option style=\"color:grey\" value='".$id_cible_cac.",".$id_arme_cac2."'>".$nom_cible_cac." (mat. ".$id_cible_cac.")</option>";
														}														
													} else {
														// Un Batiment => on ne veut pas l'afficher !
													}
												}
											}
											else {
												// Impossible d'attaquer au CaC quand on est dans un train
												if (!in_train($mysqli, $id_perso)) {
												
													while($t_cible_portee_cac = $res_portee_cac->fetch_assoc()) {
														
														$id_cible_cac = $t_cible_portee_cac["idPerso_carte"];
														
														if ($id_cible_cac < 50000) {
															
															// Un autre perso
															$sql = "SELECT nom_perso, clan FROM perso WHERE id_perso='$id_cible_cac'";
															$res = $mysqli->query($sql);
															$tab = $res->fetch_assoc();
															
															$nom_cible_cac 	= $tab["nom_perso"];
															$camp_cible_cac	= $tab["clan"];
															
															$couleur_clan_cible = couleur_clan($camp_cible_cac);
															
														} else if ($id_cible_cac >= 200000) {
															
															// Un PNJ
															$sql = "SELECT nom_pnj FROM pnj, instance_pnj WHERE pnj.id_pnj = instance_pnj.id_pnj AND idInstance_pnj = '$id_cible_cac'";
															$res = $mysqli->query($sql);
															$tab = $res->fetch_assoc();
															
															$nom_cible_cac = $tab["nom_pnj"];
															
															$couleur_clan_cible = "grey";
															
														} else {
															
															// Un Batiment
															$sql = "SELECT nom_batiment FROM batiment, instance_batiment WHERE batiment.id_batiment = instance_batiment.id_batiment AND id_instanceBat = '$id_cible_cac'";
															$res = $mysqli->query($sql);
															$tab = $res->fetch_assoc();
															
															$nom_cible_cac = $tab["nom_batiment"];
															
															$couleur_clan_cible = "black";
														}
														
														echo "<option style=\"color:". $couleur_clan_cible ."\" value='".$id_cible_cac.",".$id_arme_cac."'>".$nom_cible_cac." (mat. ".$id_cible_cac.")</option>";
													}
												}
											}
											?>
										</select>
									</td>
									<?php
									}
									?>
									</form>
								</tr>
							</table>
							
							<br />
							
							<?php
							if ($afficher_rosace) {
							?>
							<table border='2' width="100%">
								<tr>
									<td background='../images/background.jpg'>
										<!--Création du tableau du choix du deplacement-->
										<table border=0 align='center'>
											<tr>
												<td colspan='5' align='center'>
												<img src='../images/Se_Deplacer.png' />
												</td>
											</tr>
											<form action="jouer.php" method="post">  
											<tr>
												<td rowspan='3'><img src='../images/tribal1.png' /></td>
												<?php 
												if (in_train($mysqli, $id_perso)) {
													$id_train = in_train($mysqli, $id_perso);
												}
												
												if(in_bat($mysqli, $id_perso)){
												?>
													<td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=1"><img border=0 src="../fond_carte/fleche1.png"></a></td>
													<td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=2"><img border=0 src="../fond_carte/fleche2.png"></a></td>
													<td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=3"><img border=0 src="../fond_carte/fleche3.png"></a></td>
												<?php
												}
												else if (isset($id_train) && $id_train > 0) {
												?>
													<td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=1"><img border=0 src="../fond_carte/fleche1.png"></a></td>
													<td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=2"><img border=0 src="../fond_carte/fleche2.png"></a></td>
													<td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=3"><img border=0 src="../fond_carte/fleche3.png"></a></td>
												<?php
												}
												else {
												?>
													<td><a href="jouer.php?mouv=1"><img border=0 src="../fond_carte/fleche1.png"></a></td>
													<td><a href="jouer.php?mouv=2"><img border=0 src="../fond_carte/fleche2.png"></a></td>
													<td><a href="jouer.php?mouv=3"><img border=0 src="../fond_carte/fleche3.png"></a></td>
												<?php 
												}
												?>
												<td rowspan='3'><img src='../images/tribal2.png' /></td>
											</tr>
											<tr>
												<?php 
												if(in_bat($mysqli, $id_perso)){
												?>
													<td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=4"><img border=0 src="../fond_carte/fleche4.png"></a></td>
													<td><center><b>Sortir</b></center></td>
													<td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=5"><img border=0 src="../fond_carte/fleche5.png"></a></td>
												<?php
												}
												else if (isset($id_train) && $id_train > 0) {
												?>
													<td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=4"><img border=0 src="../fond_carte/fleche4.png"></a></td>
													<td><center><b>Sauter</b></center></td>
													<td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=5"><img border=0 src="../fond_carte/fleche5.png"></a></td>
												<?php
												}
												else {
												?>
												<td><a href="jouer.php?mouv=4"><img border=0 src="../fond_carte/fleche4.png"></a></td>
												<td>&nbsp; </td>
												<td><a href="jouer.php?mouv=5"><img border=0 src="../fond_carte/fleche5.png"></a></td>
												<?php 
												}
												?>
											</tr>
											<tr>
												<?php 
												if(in_bat($mysqli, $id_perso)){
												?>
													<td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=6"><img border=0 src="../fond_carte/fleche6.png"></a></td>
													<td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=7"><img border=0 src="../fond_carte/fleche7.png"></a></td>
													<td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=8"><img border=0 src="../fond_carte/fleche8.png"></a></td>
												<?php
												}
												else if (isset($id_train) && $id_train > 0) {
												?>
													<td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=6"><img border=0 src="../fond_carte/fleche6.png"></a></td>
													<td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=7"><img border=0 src="../fond_carte/fleche7.png"></a></td>
													<td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=8"><img border=0 src="../fond_carte/fleche8.png"></a></td>
												<?php
												}
												else {
												?>
													<td><a href="jouer.php?mouv=6"><img border=0 src="../fond_carte/fleche6.png"></a></td>
													<td><a href="jouer.php?mouv=7"><img border=0 src="../fond_carte/fleche7.png"></a></td>
													<td><a href="jouer.php?mouv=8"><img border=0 src="../fond_carte/fleche8.png"></a></td>
												<?php 
												}
												?>
											</tr>
											</form>
										</table>
										<!--Fin du tableau du choix du deplacement-->
									</td>
								</tr>
							</table>
							<?php
							}
							?>
						</td>
						
						<td valign="top">
							<table style="border:1px solid black; border-collapse: collapse;">
								<tr>
									<td>
				
				<?php
				//<!--Génération de la carte-->
				$perc_carte = $perc;
				if ($perc_carte < 0) {
					$perc_carte = 0;
				}
				
				// recuperation des données de la carte
				$sql = "SELECT x_carte, y_carte, fond_carte, occupee_carte, image_carte, idPerso_carte FROM $carte 
						WHERE x_carte >= $x_perso - $perc_carte 
						AND x_carte <= $x_perso + $perc_carte 
						AND y_carte <= $y_perso + $perc_carte 
						AND y_carte >= $y_perso - $perc_carte 
						ORDER BY y_carte DESC, x_carte";
				$res = $mysqli->query($sql);
				$tab = $res->fetch_assoc();		
				
				// calcul taille table
				$taille_table = ($perception_perso + $bonusPerception_perso) * 2 + 2;
				$taille_table = $taille_table * 40;
				
				echo "<table border='".$cadrillage."' width='".$taille_table."' height='".$taille_table."' align='center' cellspacing='0' cellpadding='0' style='text-align: center;' >";
				
				//affichage des abscisses
				echo "	<tr>
							<td width='40' heigth='40' background=\"../images/background.jpg\" align='center'>y \ x</td>";  
				
				for ($i = $x_perso - $perc_carte; $i <= $x_perso + $perc_carte; $i++) {
					if ($i == $x_perso)
						echo "<th style='min-width:40px;' height='40' background=\"../images/background3.jpg\">$i</th>";
					else
						echo "<th style='min-width:40px;' height='40' background=\"../images/background.jpg\">$i</th>";
				}
				
				echo "	</tr>";
				
				for ($y = $y_perso + $perc_carte; $y >= $y_perso - $perc_carte; $y--) {
					
					echo "<tr align=\"center\" >";
					
					if ($y == $y_perso) {
						echo "<th style='min-width:40px;' height='40' background=\"../images/background3.jpg\">$y</th>";
					}
					else {
						echo "<th style='min-width:40px;' height='40' background=\"../images/background.jpg\">$y</th>";
					}
					
					for ($x = $x_perso - $perc_carte; $x <= $x_perso + $perc_carte; $x++) {
						
						//les coordonnées sont dans les limites
						if ($x >= X_MIN && $y >= Y_MIN && $x <= $X_MAX && $y <= $Y_MAX) { 
						
							//--------------------------
							//coordonnées du perso
							if ($x == $x_perso && $y == $y_perso){
								
								// verification s'il y a un objet sur cette case
								$sql_o = "SELECT id_objet FROM objet_in_carte WHERE x_carte='$x' AND y_carte='$y'";
								$res_o = $mysqli->query($sql_o);
								$nb_o = $res_o->num_rows;
								
								if($clan_perso == '1'){
									$image_profil 	= "Nord.gif";
								}
								if($clan_perso == '2'){
									$image_profil 	= "Sud.gif";
								}
								
								$fond_im = $tab["fond_carte"];
								$nom_terrain = get_nom_terrain($fond_im);
								
								echo "<td width=40 height=40 background=\"../fond_carte/".$fond_im."\">";
								echo "	<div width=40 height=40 style=\"position: relative;\">";
								echo "		<div tabindex='0' style=\"position: absolute;bottom: -2px;text-align: center; width: 100%;font-weight: bold;\"
													data-toggle='popover'
													data-trigger='focus'
													data-html='true' 
													data-placement='bottom' ";
											
								// TITLE POPOVER
								echo "			title=\"<div><img src='../images/".$image_profil."' width='20' height='20'><img alt='".$nom_grade_perso."' title='".$nom_grade_perso."' src='../images/grades/" . $id_grade_perso . ".gif' width='20' height='20'> <a href='evenement.php?infoid=".$id_perso."' target='_blank'>".$nom_perso." [".$id_perso."]</a></div> ";					
								
								afficher_infos_compagnie($nom_compagnie_perso, $image_compagnie_perso);
								
								if (!in_bat($mysqli,$id_perso)) {
									
									if (!in_train($mysqli,$id_perso)) {
										afficher_infos_non_bat_non_train($fond_im, $nom_terrain, $nb_o);
									}
									else {
										afficher_infos_in_train($mysqli, $id_perso);
									}
								}
								else {
									afficher_infos_in_bat($mysqli, $id_perso);
								}
								echo "<div><u>Message du jour</u> :<br />".$message_perso."</div>";
								
								echo "\" ";
								
								// DATA CONTENT POPOVER
								echo "			data-content=\"";
								
								afficher_liens_objet($nb_o, $x, $y);
								afficher_liens_rail_genie($genie_compagnie_perso, $fond_im);
								
								if (in_bat($mysqli,$id_perso)) {
									
									afficher_liens_in_bat($mysqli, $id_perso);
									
								}
								else if (prox_bat($mysqli, $x_perso, $y_perso, $id_perso)) {
									
									afficher_liens_prox_bat($mysqli, $id_perso, $x_perso, $y_perso, $type_perso);
									
								}
								echo "\" >" . $id_perso . "</div>";
								
								echo "		<img tabindex='0' class=\"\" border=0 src=\"../images_perso/$dossier_img_joueur/$image_perso\" width=40 height=40 
													data-toggle='popover'
													data-trigger='focus'
													data-html='true' 
													data-placement='bottom' ";
								// TITLE POPOVER
								echo "			title=\"<div><img src='../images/".$image_profil."' width='20' height='20'><img alt='".$nom_grade_perso."' title='".$nom_grade_perso."' src='../images/grades/" . $id_grade_perso . ".gif' width='20' height='20'> <a href='evenement.php?infoid=".$id_perso."' target='_blank'>".$nom_perso." [".$id_perso."]</a></div>";
								
								afficher_infos_compagnie($nom_compagnie_perso, $image_compagnie_perso);
								
								if (!in_bat($mysqli,$id_perso)) {
									
									if (!in_train($mysqli,$id_perso)) {
										afficher_infos_non_bat_non_train($fond_im, $nom_terrain, $nb_o);
									}
									else {
										afficher_infos_in_train($mysqli, $id_perso);
									}
								}
								else {
									afficher_infos_in_bat($mysqli, $id_perso);
								}
								echo "<div><u>Message du jour</u> :<br />".$message_perso."</div>";
								
								echo "\" ";
								// DATA CONTENT POPOVER
								echo "			data-content=\"";
								
								afficher_liens_objet($nb_o, $x, $y);
								afficher_liens_rail_genie($genie_compagnie_perso, $fond_im);
								
								if (in_bat($mysqli,$id_perso)) {
									
									afficher_liens_in_bat($mysqli, $id_perso);
									
								}
								else if (prox_bat($mysqli, $x_perso, $y_perso, $id_perso)) {
									
									afficher_liens_prox_bat($mysqli, $id_perso, $x_perso, $y_perso, $type_perso);
									
								}
								echo "\" ";
								echo " />";
								echo "	</div>";
								echo "</td>";
							}
							else {
								if ($tab["occupee_carte"]){
									
									//------------------------------------
									// Traitement PNJ
									if($tab['idPerso_carte'] >= 200000){
										
										$idI_pnj = $tab['idPerso_carte'];
										$fond_im = $tab["fond_carte"];
												
										$nom_terrain = get_nom_terrain($fond_im);
										
										// recuperation du type de pnj
										$sql_im = "SELECT instance_pnj.id_pnj, nom_pnj FROM instance_pnj, pnj WHERE instance_pnj.id_pnj = pnj.id_pnj AND idInstance_pnj='$idI_pnj'";
										$res_im = $mysqli->query($sql_im);
										$t_im = $res_im->fetch_assoc();
										
										$id_pnj_im 	= $t_im["id_pnj"];
										$nom_pnj_im	= $t_im["nom_pnj"];
										
										$im_pnj="pnj".$id_pnj_im."t.png";
										
										$dossier_pnj = "images/pnj";

										echo "	<td width=40 height=40 background=\"../fond_carte/".$fond_im."\">"; 
										echo "		<img tabindex='0' border=0 src=\"../".$dossier_pnj."/".$im_pnj."\" width=40 height=40 
															data-toggle='popover' 
															data-trigger='focus' 
															data-html='true' 
															data-placement='bottom' 
															title=\"<div><a href='evenement.php?infoid=".$idI_pnj."' target='_blank'>".$nom_pnj_im." [".$idI_pnj."]</a></div><div><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain."</div>\" >";
										echo "	</td>";
									}
									else {
										//-------------------------
										//  traitement Batiment
										if($tab['idPerso_carte'] >= 50000 && $tab['idPerso_carte'] < 200000){
											
											$idI_bat = $tab['idPerso_carte'];
											
											// recuperation du type de bat et du camp
											$sql_im = "SELECT instance_batiment.id_batiment, camp_instance, nom_instance, nom_batiment
														FROM instance_batiment, batiment 
														WHERE instance_batiment.id_batiment = batiment.id_batiment
														AND id_instanceBat='$idI_bat'";
											$res_im = $mysqli->query($sql_im);
											$t_im = $res_im->fetch_assoc();
											
											$type_bat 	= $t_im["id_batiment"];
											$camp_bat 	= $t_im["camp_instance"];
											$nom_i_bat	= $t_im["nom_instance"];
											$nom_bat	= $t_im["nom_batiment"];
											
											if($camp_bat == '1'){
												$camp_bat2 		= 'bleu';
												$image_profil 	= "Nord.gif";
											}
											if($camp_bat == '2'){
												$camp_bat2 		= 'rouge';
												$image_profil 	= "Sud.gif";
											}
											
											$blason="mini_blason_".$camp_bat2.".gif";

											echo "<td width=40 height=40 background=\"../fond_carte/".$tab["fond_carte"]."\">";
											echo "	<img tabindex='0' border=0 src=\"../images_perso/".$tab["image_carte"]."\" width=40 height=40 
														data-toggle='popover'
														data-trigger='focus'
														data-html='true' 
														data-placement='bottom' ";
											echo "		title=\"<div><img src='../images/".$image_profil."' width='20' height='20'> <a href='evenement.php?infoid=".$idI_bat."' target='_blank'>".$nom_bat." ".$nom_i_bat." [".$idI_bat."]</a></div>\"";
											echo "		data-content=\"";
											if (in_bat($mysqli,$id_perso)) {
									
												$id_instance_in_bat = in_bat($mysqli,$id_perso);
												
												if ($idI_bat == $id_instance_in_bat) {
												
													echo "<div><a href='batiment.php?bat=".$id_instance_in_bat."' target='_blank'>Accéder à la page du bâtiment</a></div> ";
													echo "<div><a href='action.php?bat=".$idI_bat."&reparer=ok'>Réparer ce bâtiment (5PA)</a></div> ";
												}
											}
											else if(prox_instance_bat($mysqli, $x_perso, $y_perso, $idI_bat) && $type_bat != 12) {
												
												echo "<div><a href='action.php?bat=".$idI_bat."&reparer=ok'>Réparer ce bâtiment (5PA)</a></div> ";
												
												if (!nation_perso_bat($mysqli, $id_perso, $idI_bat)) {
													if(batiment_vide($mysqli, $idI_bat) && batiment_pv_capturable($mysqli, $idI_bat) && $type_bat != 1 && $type_bat != 5 && $type_bat != 7 && $type_bat != 10 && $type_bat != 11 && $type_bat == 2 && $type_perso == 3){
														echo "<div><a href='jouer.php?bat=".$idI_bat."&bat2=".$type_bat."'>Capturer ce bâtiment</a></div>";
													}
												}
												else {
													if($type_bat != 1 && $type_bat != 5 && $type_bat != 10){
														if (($type_bat == 2 && ($type_perso == 3 || $type_perso == 4 || $type_perso == 6)) || $type_bat != 2 ) {
															echo "<div><a href='jouer.php?bat=".$idI_bat."&bat2=".$type_bat."'>Entrer dans ce bâtiment</a></div>";
														}
													}
												}
											}
											echo "\">";		
											echo "</td>";
										}
										else {
									
											if($tab['image_carte'] == "murt.png"){
												//positionement du mur
												echo "<td width=40 height=40 background=\"../fond_carte/".$tab["fond_carte"]."\"> <img border=0 src=\"../images_perso/".$tab["image_carte"]."\" width=40 height=40 onMouseOver=\"AffBulle('<img src=../images/murs/mur.jpeg>')\" onMouseOut=\"HideBulle()\" title=\"mur\"></td>";
											}
											else {
												
												$id_perso_im 	= $tab['idPerso_carte'];
												$fond_im 		= $tab["fond_carte"];
												
												$nom_terrain 	= get_nom_terrain($fond_im);
												$cout_pm 		= cout_pm($fond_im);
												
												//recuperation du type de perso (image)
												$sql_perso_im = "SELECT * FROM perso WHERE id_perso='$id_perso_im'";
												$res_perso_im = $mysqli->query($sql_perso_im);
												$t_perso_im = $res_perso_im->fetch_assoc();
												
												$im_perso 	= $t_perso_im["image_perso"];
												$nom_ennemi = $t_perso_im['nom_perso'];
												$id_ennemi 	= $t_perso_im['id_perso'];
												$clan_e 	= $t_perso_im['clan'];
												$message_e	= $t_perso_im['message_perso'];
												
												if($clan_e == 1){
													$clan_ennemi 	= 'rond_b.png';
													$couleur_clan_e = 'blue';
													$image_profil 	= "Nord.gif";
												}
												if($clan_e == 2){
													$clan_ennemi 	= 'rond_r.png';
													$couleur_clan_e = 'red';
													$image_profil 	= "Sud.gif";
												}
												
												// récupération du grade du perso 
												$sql_grade = "SELECT perso_as_grade.id_grade, nom_grade FROM perso_as_grade, grades WHERE perso_as_grade.id_grade = grades.id_grade AND id_perso='$id_ennemi'";
												$res_grade = $mysqli->query($sql_grade);
												$t_grade = $res_grade->fetch_assoc();
												
												$id_grade_ennemi 	= $t_grade["id_grade"];
												$nom_grade_ennemi 	= $t_grade["nom_grade"];
												
												// cas particuliers grouillot
												if ($id_grade_ennemi == 101) {
													$id_grade_ennemi = "1.1";
												}
												if ($id_grade_ennemi == 102) {
													$id_grade_ennemi = "1.2";
												}
												
												// recuperation de l'id de la compagnie 
												$sql_groupe = "SELECT id_compagnie from perso_in_compagnie where id_perso='$id_perso_im' AND (attenteValidation_compagnie='0' OR attenteValidation_compagnie='2')";
												$res_groupe = $mysqli->query($sql_groupe);
												$t_groupe = $res_groupe->fetch_assoc();
												$nb = $res_groupe->num_rows;

												$id_groupe = $nb ? $t_groupe['id_compagnie'] : 0;
												
												$nom_compagnie = '';
												
												if($id_groupe){
													
													// recuperation des infos sur la compagnie (dont le nom)
													$sql_groupe2 = "SELECT * FROM compagnies WHERE id_compagnie='$id_groupe'";
													$res_groupe2 = $mysqli->query($sql_groupe2);
													$t_groupe2 = $res_groupe2->fetch_assoc();
													
													$nom_compagnie 		= addslashes($t_groupe2['nom_compagnie']);
													$id_compagnie 		= $t_groupe2['id_compagnie'];
													$image_compagnie	= $t_groupe2['image_compagnie'];
													
												}
												
												if(isset($nom_compagnie) && trim($nom_compagnie) != ''){
													
													echo "<td width=40 height=40 background=\"../fond_carte/".$fond_im."\">";
													echo "	<div width=40 height=40 style=\"position: relative;\">";
													
													//--- Div matricule perso
													echo "		<div tabindex='0' data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' style=\"position: absolute;bottom: -2px;text-align: center; width: 100%;font-weight: bold;\" ";
													// Title popover
													echo "			title=\"<div><img src='../images/".$image_profil."' width='20' height='20'><img alt='".$nom_grade_ennemi."' title='".$nom_grade_ennemi."' src='../images/grades/" . $id_grade_ennemi . ".gif' width='20' height='20'> <a href='evenement.php?infoid=".$id_ennemi."' target='_blank'>".$nom_ennemi." [".$id_ennemi."]</a></div><div><a href='compagnie.php?id_compagnie=".$id_compagnie."&voir_compagnie=ok' target='_blank'>";
													if (trim($image_compagnie) != "" && $image_compagnie != "0") {
														echo "<img src='".$image_compagnie."' width='20' height='20'>";
													}
													echo " ".stripslashes($nom_compagnie)."</a></div>";
													if ($nom_terrain == "Pont") {
														
														$sql_p = "SELECT id_instanceBat FROM instance_batiment WHERE x_instance='$x' AND y_instance='$y'";
														$res_p = $mysqli->query($sql_p);
														$t_p = $res_p->fetch_assoc();
														
														$idIBat = $t_p['id_instanceBat'];
														
														echo "<div><a href='evenement.php?infoid=".$idIBat."'><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain." [".$idIBat."]</a></div>";
													}
													else {
														echo "<div><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain."</div>";
													}
													echo "<div><u>Message du jour</u> :<br />".$message_e."</div>\" ";
													// data content popover
													echo "			data-content=\"<div><a href='nouveau_message.php?pseudo=".$nom_ennemi."' target='_blank'>Envoyer un message</a></div>";
													
													afficher_lien_bouculade($x, $x_perso, $y, $y_perso, $cout_pm);
													
													echo "			\" >" . $id_ennemi . "</div>";
													
													//--- Image perso
													echo "		<img tabindex='0' border=0 src=\"../images_perso/$dossier_img_joueur/".$tab["image_carte"]."\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' ";
													// Title popover
													echo "			title=\"<div><img src='../images/".$image_profil."' width='20' height='20'><img alt='".$nom_grade_ennemi."' title='".$nom_grade_ennemi."' src='../images/grades/" . $id_grade_ennemi . ".gif' width='20' height='20'> <a href='evenement.php?infoid=".$id_ennemi."' target='_blank'>".$nom_ennemi." [".$id_ennemi."]</a></div><div><a href='compagnie.php?id_compagnie=".$id_compagnie."&voir_compagnie=ok' target='_blank'>";
													if (trim($image_compagnie) != "" && $image_compagnie != "0") {
														echo "<img src='".$image_compagnie."' width='20' height='20'>";
													}				
													echo " ".stripslashes($nom_compagnie)."</a></div>";
													if ($nom_terrain == "Pont") {
														
														$sql_p = "SELECT id_instanceBat FROM instance_batiment WHERE x_instance='$x' AND y_instance='$y'";
														$res_p = $mysqli->query($sql_p);
														$t_p = $res_p->fetch_assoc();
														
														$idIBat = $t_p['id_instanceBat'];
														
														echo "<div><a href='evenement.php?infoid=".$idIBat."'><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain." [".$idIBat."]</a></div>";
													}
													else {
														echo "<div><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain."</div>";
													}
													echo "<div><u>Message du jour</u> :<br />".$message_e."</div>\" ";
													// Data content popover
													echo "			data-content=\"<div><a href='nouveau_message.php?pseudo=".$nom_ennemi."' target='_blank'>Envoyer un message</a></div>";
													
													afficher_lien_bouculade($x, $x_perso, $y, $y_perso, $cout_pm);
													
													echo "			\" />";
													echo "	</div>";
													echo "</td>";
												}
												else {
													echo "<td width=40 height=40 background=\"../fond_carte/".$fond_im."\">";
													
													//--- Div matricule perso
													echo "	<div width=40 height=40 style=\"position: relative;\">";
													echo "		<div tabindex='0' data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' style=\"position: absolute;bottom: -2px;text-align: center; width: 100%;font-weight: bold;\" ";
													// Title Popover
													echo "			title=\"<div><img src='../images/".$image_profil."' width='20' height='20'><img alt='".$nom_grade_ennemi."' title='".$nom_grade_ennemi."' src='../images/grades/" . $id_grade_ennemi . ".gif' width='20' height='20'> <a href='evenement.php?infoid=".$id_ennemi."' target='_blank'>".$nom_ennemi." [".$id_ennemi."]</a></div>";
													echo "<div><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain."</div>";
													echo "<div><u>Message du jour</u> :<br />".$message_e."</div>\" ";
													
													echo "			data-content=\"<div><a href='nouveau_message.php?pseudo=".$nom_ennemi."' target='_blank'>Envoyer un message</a></div>";
													
													afficher_lien_bouculade($x, $x_perso, $y, $y_perso, $cout_pm);
													
													echo "			\" ";
													echo "		>" . $id_ennemi . "</div>";
													
													//--- Image perso
													echo "		<img tabindex='0' border=0 src=\"../images_perso/$dossier_img_joueur/".$tab["image_carte"]."\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' ";
													// Title popover
													echo "			title=\"<div><img src='../images/".$image_profil."' width='20' height='20'><img alt='".$nom_grade_ennemi."' title='".$nom_grade_ennemi."' src='../images/grades/" . $id_grade_ennemi . ".gif' width='20' height='20'> <a href='evenement.php?infoid=".$id_ennemi."' target='_blank'>".$nom_ennemi." [".$id_ennemi."]</a></div><div><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain."</div>";
													echo "<div><u>Message du jour</u> :<br />".$message_e."</div>\" ";
													echo "			data-content=\"<div><a href='nouveau_message.php?pseudo=".$nom_ennemi."' target='_blank'>Envoyer un message</a></div>";
													
													afficher_lien_bouculade($x, $x_perso, $y, $y_perso, $cout_pm);
													
													echo "			\" />";
													echo "	</div>";
													echo "</td>";
												}
											}
										}
									}
								}
								else {
									
									//------------------------------------------------------------
									//  traitement Batiment qui occupe pas une case comme le pont
									if($tab['idPerso_carte'] >= 50000 && $tab['idPerso_carte'] < 200000){
										
										$idI_bat = $tab['idPerso_carte'];
											
										// recuperation du type de bat et du camp
										$sql_im = "SELECT instance_batiment.id_batiment, camp_instance, nom_instance, nom_batiment
													FROM instance_batiment, batiment 
													WHERE instance_batiment.id_batiment = batiment.id_batiment
													AND id_instanceBat='$idI_bat'";
										$res_im = $mysqli->query($sql_im);
										$t_im = $res_im->fetch_assoc();
										
										$type_bat 	= $t_im["id_batiment"];
										$camp_bat 	= $t_im["camp_instance"];
										$nom_i_bat	= $t_im["nom_instance"];
										$nom_bat	= $t_im["nom_batiment"];
										
										$fond_carte = $tab["fond_carte"];
										
										$cout_pm = cout_pm($fond_carte);
										
										afficher_popover_pont($x, $x_perso, $y, $y_perso, $fond_carte, $idI_bat, $nom_bat, $cout_pm);
									}
									else {
										
										$fond_im 			= $tab["fond_carte"];
												
										$nom_terrain 		= get_nom_terrain($fond_im);
										$cout_pm_terrain 	= cout_pm($fond_im);
										
										// verification s'il y a un objet sur cette case
										$sql_o = "SELECT id_objet FROM objet_in_carte WHERE x_carte='$x' AND y_carte='$y'";
										$res_o = $mysqli->query($sql_o);
										$nb_o = $res_o->num_rows;
										
										$sql_case = "SELECT valid_case FROM joueur WHERE id_joueur='$id_joueur_perso'";
										$res_case = $mysqli->query($sql_case);
										$t = $res_case->fetch_assoc();
										$valid_case = $t['valid_case'];
										
										if (in_bat($mysqli, $id_perso)) {
											
											$taille_case = ceil($taille_bat_perso / 2);
											
											afficher_popover_in_bat($x, $x_perso, $y, $y_perso, $taille_case, $fond_im, $nb_o, $nom_terrain, $id_bat_perso);
										}
										else {
										
											if($y > $y_perso+1 || $y < $y_perso-1 || $x > $x_perso+1 || $x < $x_perso-1) {
												if($nb_o){
													echo "<td width=40 height=40 background=\"../fond_carte/".$tab["fond_carte"]."\">";
													echo "	<img border=0 src=\"../fond_carte/o1.gif\" width=40 height=40 data-toggle='tooltip' data-placement='top' title='objets à ramasser'/>";
													echo "</td>";
												}
												else {										
													echo "<td width=40 height=40> <img border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40></td>";
												}
											}
											else {
												if($y == $y_perso+1 && $x == $x_perso+1){
													if($nb_o){
														echo "<td width=40 height=40 background=\"../fond_carte/".$fond_im."\">";
														echo "	<img tabindex='0' border=0 src=\"../fond_carte/o1.gif\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' title=\"<div>Objets à ramasser</div>\" data-content=\"<div><a href='jouer.php?mouv=3'>Se déplacer</a></div><div><a href='jouer.php?ramasser=voir&x=$x&y=$y'>Voir la liste des objets à terre</a></div>\" >";
														echo "</td>";
													}
													else {	
														echo "<td width=40 height=40>";
														if ($valid_case || is_case_rail($fond_im)) {
															echo "	<img tabindex='0' border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' ";
															echo "			title=\"<div><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain." - ".$cout_pm_terrain." PM</div>\" ";
															echo "			data-content=\"<div><a href='jouer.php?mouv=3'>Se déplacer</a></div>\" >";
														}
														else {
															echo "	<a href=\"jouer.php?mouv=3\">";
															echo "		<img tabindex='0' border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40>";
															echo "	</a>";
														}
														echo "</td>";
													}
												}
												if($y == $y_perso-1 && $x == $x_perso+1){
													if($nb_o){
														echo "<td width=40 height=40 background=\"../fond_carte/".$fond_im."\">";
														echo "	<img tabindex='0' border=0 src=\"../fond_carte/o1.gif\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' title=\"<div>Objets à ramasser</div>\" data-content=\"<div><a href='jouer.php?mouv=8'>Se déplacer</a></div><div><a href='jouer.php?ramasser=voir&x=$x&y=$y'>Voir la liste des objets à terre</a></div>\" >";
														echo "</td>";
													}
													else {
														echo "<td width=40 height=40>";
														if ($valid_case || is_case_rail($fond_im)) {
															echo "	<img tabindex='0' border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' ";
															echo "			title=\"<div><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain." - ".$cout_pm_terrain." PM</div>\" ";
															echo "			data-content=\"<div><a href='jouer.php?mouv=8'>Se déplacer</a></div>\" >";
														}
														else {
															echo "	<a href=\"jouer.php?mouv=8\"><img border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40></a>";
														}
														echo "</td>";
													}
												}
												if($y == $y_perso && $x == $x_perso+1){
													if($nb_o){
														echo "<td width=40 height=40 background=\"../fond_carte/".$fond_im."\">";
														echo "	<img tabindex='0' border=0 src=\"../fond_carte/o1.gif\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' title=\"<div>Objets à ramasser</div>\" data-content=\"<div><a href='jouer.php?mouv=5'>Se déplacer</a></div><div><a href='jouer.php?ramasser=voir&x=$x&y=$y'>Voir la liste des objets à terre</a></div>\" >";
														echo "</td>";
													}
													else {	
														echo "<td width=40 height=40>";
														if ($valid_case || is_case_rail($fond_im)) {
															echo "	<img tabindex='0' border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' ";
															echo "			title=\"<div><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain." - ".$cout_pm_terrain." PM</div>\" ";
															echo "			data-content=\"<div><a href='jouer.php?mouv=5'>Se déplacer</a></div>\" >";
														}
														else {
															echo "<a href=\"jouer.php?mouv=5\"><img border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40></a>";
														}
														echo "</td>";
													}
												}
												if($y == $y_perso && $x == $x_perso-1) {
													if($nb_o){
														echo "<td width=40 height=40 background=\"../fond_carte/".$fond_im."\">";
														echo "	<img tabindex='0' border=0 src=\"../fond_carte/o1.gif\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' title=\"<div>Objets à ramasser</div>\" data-content=\"<div><a href='jouer.php?mouv=4'>Se déplacer</a></div><div><a href='jouer.php?ramasser=voir&x=$x&y=$y'>Voir la liste des objets à terre</a></div>\" >";
														echo "</td>";
													}
													else {	
														echo "<td width=40 height=40>";
														if ($valid_case || is_case_rail($fond_im)) {
															echo "	<img tabindex='0' border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' ";
															echo "			title=\"<div><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain." - ".$cout_pm_terrain." PM</div>\" ";
															echo "			data-content=\"<div><a href='jouer.php?mouv=4'>Se déplacer</a></div>\" >";
														}
														else {
															echo "<a href=\"jouer.php?mouv=4\"><img border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40></a>";
														}
														echo "</td>";
													}
												}
												if($y == $y_perso+1 && $x == $x_perso-1) {
													if($nb_o){
														echo "<td width=40 height=40 background=\"../fond_carte/".$fond_im."\">";
														echo "	<img tabindex='0' border=0 src=\"../fond_carte/o1.gif\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' title=\"<div>Objets à ramasser</div>\" data-content=\"<div><a href='jouer.php?mouv=1'>Se déplacer</a></div><div><a href='jouer.php?ramasser=voir&x=$x&y=$y'>Voir la liste des objets à terre</a></div>\" >";
														echo "</td>";
													}
													else {	
														echo "<td width=40 height=40>";
														if ($valid_case || is_case_rail($fond_im)) {
															echo "	<img tabindex='0' border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' ";
															echo "			title=\"<div><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain." - ".$cout_pm_terrain." PM</div>\" ";
															echo "			data-content=\"<div><a href='jouer.php?mouv=1'>Se déplacer</a></div>\" >";
														}
														else {
															echo "<a href=\"jouer.php?mouv=1\"><img border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40></a>";
														}
														echo "</td>";
													}
												}
												if($y == $y_perso-1 && $x == $x_perso-1) {
													if($nb_o){
														echo "<td width=40 height=40 background=\"../fond_carte/".$fond_im."\">";
														echo "	<img tabindex='0' border=0 src=\"../fond_carte/o1.gif\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' title=\"<div>Objets à ramasser</div>\" data-content=\"<div><a href='jouer.php?mouv=6'>Se déplacer</a></div><div><a href='jouer.php?ramasser=voir&x=$x&y=$y'>Voir la liste des objets à terre</a></div>\" >";
														echo "</td>";
													}
													else {	
														echo "<td width=40 height=40>";
														if ($valid_case || is_case_rail($fond_im)) {
															echo "	<img tabindex='0' border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' ";
															echo "			title=\"<div><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain." - ".$cout_pm_terrain." PM</div>\" ";
															echo "			data-content=\"<div><a href='jouer.php?mouv=6'>Se déplacer</a></div>\" >";
														}
														else {
															echo "<a href=\"jouer.php?mouv=6\"><img border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40></a>";
														}
														echo "</td>";
													}
												}
												if($y == $y_perso+1 && $x == $x_perso) {
													if($nb_o){
														echo "<td width=40 height=40 background=\"../fond_carte/".$fond_im."\">";
														echo "	<img tabindex='0' border=0 src=\"../fond_carte/o1.gif\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' title=\"<div>Objets à ramasser</div>\" data-content=\"<div><a href='jouer.php?mouv=2'>Se déplacer</a></div><div><a href='jouer.php?ramasser=voir&x=$x&y=$y'>Voir la liste des objets à terre</a></div>\" >";
														echo "</td>";
													}
													else {	
														echo "<td width=40 height=40>";
														if ($valid_case || is_case_rail($fond_im)) {
															echo "	<img tabindex='0' border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' ";
															echo "			title=\"<div><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain." - ".$cout_pm_terrain." PM</div>\" ";
															echo "			data-content=\"<div><a href='jouer.php?mouv=2'>Se déplacer</a></div>\" >";
														}
														else {
															echo "<a href=\"jouer.php?mouv=2\"><img border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40></a>";
														}
														echo "</td>";
													}
												}
												if($y == $y_perso-1 && $x == $x_perso) {
													if($nb_o){
														echo "<td width=40 height=40 background=\"../fond_carte/".$fond_im."\">";
														echo "	<img tabindex='0' border=0 src=\"../fond_carte/o1.gif\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' title=\"<div>Objets à ramasser</div>\" data-content=\"<div><a href='jouer.php?mouv=7'>Se déplacer</a></div><div><a href='jouer.php?ramasser=voir&x=$x&y=$y'>Voir la liste des objets à terre</a></div>\" >";
														echo "</td>";
													}
													else {	
														echo "<td width=40 height=40>";
														if ($valid_case || is_case_rail($fond_im)) {
															echo "	<img tabindex='0' border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40 data-toggle='popover' data-trigger='focus' data-html='true' data-placement='bottom' ";
															echo "			title=\"<div><img src='../fond_carte/".$fond_im."' width='20' height='20'> ".$nom_terrain." - ".$cout_pm_terrain." PM</div>\" ";
															echo "			data-content=\"<div><a href='jouer.php?mouv=7'>Se déplacer</a></div>\" >";
														}
														else {
															echo "<a href=\"jouer.php?mouv=7\"><img border=0 src=\"../fond_carte/".$fond_im."\" width=40 height=40></a>";
														}
														echo "</td>";
													}
												}
											}
										}
									}
								}
							}
							$tab = $res->fetch_assoc();
						}
						else //les coordonnées sont hors limites
							echo "<td width=40 height=40><img border=0 width=40 height=40 src=\"../fond_carte/decorO.jpg\"></td>";
					}
					echo "</tr>";
				}
				?>
								</table>
							</td>
						</tr>
					</table>
				</td>
				<!--Fin de la génération de la carte-->
				
				<?php
				if($config == '2'){
					echo "</tr><tr>";
				}
				?>
				
				<!--Debut tableau des actions -->
				<td valign="top">
					<table style="border:1px solid black; border-collapse: collapse;">
						<tr>
							<td>
								<table border="0" cellspacing="0" cellpadding="0" style:no-padding>
									<tr>
										<td background='../images/background.jpg' align='center' valign='top'colspan='2'>
											<img src='../images/Action.png' border='0'/>
											<form method='post' action='action.php'>
												<select name='liste_action'>
													<option value="invalide" selected>-- -- -- -- -- -- - Choisir une action - -- -- -- -- -- --</option>
													<?php
													
													// Action d'entrainement
													if($pa_perso >= 10){
														echo "<option value=\"65\">Entrainement (10 PA)</option>";
													}
													
													// Action Déposer Objet
													if($pa_perso >= 1){
														echo "<option value=\"110\">Deposer objet (1 PA)</option>";
														echo "<option value=\"139\">Donner objet (1 PA)</option>";
													}
													
													// Actions selon le type d'unité
													
													// Cavalerie et cavalerie lourde
													if (verif_charge_pm($type_perso, $pm_perso) && !in_train($mysqli, $id_perso) && !in_bat($mysqli, $id_perso)) {
														// Charge = 999
														echo '<option value="999">Charger (tous les PA)</option>';
													}
													
													$sql = "SELECT action.id_action, nom_action, coutPa_action, reflexive_action
															FROM perso_as_competence, competence_as_action, action 
															WHERE id_perso='$id_perso' 
															AND perso_as_competence.id_competence=competence_as_action.id_competence 
															AND competence_as_action.id_action=action.id_action
															AND passif_action = '0'
															ORDER BY nom_action";
													$res = $mysqli->query($sql);
													
													while ($t_ac = $res->fetch_assoc()) {
														
														$id_ac 		= $t_ac["id_action"];
														$cout_PA 	= $t_ac["coutPa_action"];
														$nom_ac 	= $t_ac["nom_action"];
														$ref_ac		= $t_ac["reflexive_action"];
													
														if ($cout_PA == -1){
															$cout_PA = $paMax_perso;
														}
														
														if (!in_train($mysqli, $id_perso) && !in_bat($mysqli, $id_perso)) {
															if ($cout_PA <= $pa_perso){
																if ($id_ac == 1 && $pm_perso >= $pmMax_perso) {
																	echo "<option value=\"$id_ac\">".$nom_ac." (Tous les PA/PM)</option>";;
																}
																else if ($id_ac == 147) {
																	echo "<option value=\"$id_ac\">".$nom_ac." (". $cout_PA . "PA à 8PA)</option>";;
																}
																else {
																	echo "<option value=\"$id_ac\">".$nom_ac." (". $cout_PA . "PA)</option>";;
																}
															}
														}
														else {
															if ($ref_ac) {
																if ($cout_PA <= $pa_perso){
																	if ($id_ac == 1 && $pm_perso >= $pmMax_perso) {
																		echo "<option value=\"$id_ac\">".$nom_ac." (". $cout_PA . "pa)</option>";;
																	}
																	else if ($id_ac != 1) {
																		echo "<option value=\"$id_ac\">".$nom_ac." (". $cout_PA . "pa)</option>";;
																	}
																}
															}
														}
													}
													?>
													<option value="invalide">-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- --</option>
												</select>
												<input type='submit' name='action' value='ok' />
											</form>
											<?php 
											echo $mess_bat;
											
											if (is_objet_a_terre($mysqli, $x_perso, $y_perso)) {
												echo "<center><font color = blue>~~<a href=\"jouer.php?ramasser=ok\">Ramasser les objets à terre (1 PA)</a>~~</font></center>";
												echo "<center><font color = blue>~~<a href=\"jouer.php?ramasser=voir\">Voir la liste des objets à terre</a>~~</font></center>";
											}
											
											// recuperation des données de la carte
											$sql = "SELECT fond_carte FROM $carte 
													WHERE x_carte = $x_perso 
													AND y_carte = $y_perso";
											$res = $mysqli->query($sql);
											$tab = $res->fetch_assoc();
											
											$fond_carte_perso = $tab['fond_carte'];
											
											afficher_liens_rail_genie($genie_compagnie_perso, $fond_carte_perso);
											
											?>
										</td>
									</tr>
									<tr>
										<td height='5' background='../images/background.jpg' colspan='2' align='center'>
											<img src='../images/barre.png' />
										</td>
									</tr>
									<tr>
										<td background='../images/background.jpg'>
											<table border='0'>
												<tr>
													<td>
														<img src='../images/Id.png' />
													</td>
													<td valign='top'>
														<form method="post" action="evenement.php" target='_blank'>
															<input type="text" maxlength="6" size="6" name="id_info" value="" style="background-image:url('../images/background3.jpg');">
															<input type="submit" value="Plus d'infos">
														</form>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td background='../images/background.jpg' align='left' colspan='3'>
											<?php 
											echo "<div><a href=\"nouveau_message.php?visu=ok&camp=".$clan_perso."\" target='_blank'><img class='img-fluid' src='../images/Ecrire.png' data-toggle='tooltip' data-placement='top' title='Envoyer un message aux persos de son camp dans sa visu' border=0 /><font face='Playball' size='5'><b>Envoyer un MP à sa visu</b></font></a></div>";
											echo "<div><a href=\"nouveau_message.php?visu=ok\" target='_blank'><img class='img-fluid' src='../images/porte_voix.png' data-toggle='tooltip' data-placement='top' title='Envoyer un message à tous les persos dans sa visu' border=0 width='100' height='80' /><font face='Playball' size='5'><b>Crier très fort</b></font></a></div>";
											?>
										</td>
									</tr>
									<tr>
										<td background='../images/background.jpg' colspan='2' align='center'>
											<img src='../images/barre.png' />
										</td>
									</tr>
								</table>
							</tr>
						</td>
					</table>
				</td>
			</tr>
		</table>
	<?php
			}
		}
	}
	else {
		header("Location:../index.php");
	}
	?>			
=======
							<!-- Rosace de deplacement -->
							<?php require_once("page_jeu/rosace_deplacement.php" ); ?>
							<!-- Fin rosace de deplacement -->
							<!--Debut tableau des actions -->
							<?php require_once("page_jeu/tableau_action.php" ); ?>
							<!-- Fin tableau des actions-->
						</div>
						
						<div class="col-8">
							<div class="row" >
								<div class="col-4">
									<div class="text-center justify-content-center"><a href="jouer.php">Rafraîchir la page : <img class='img-fluid' src='../images/refreshv2.png'  width='38' height='38'/></a></div>
								</div>
								<div class="col-4">
									<div class="text-center justify-content-center"><a href="nouveau_message.php?visu=ok&camp=".$clan_perso target='_blank'>Envoyer un MP à sa visu : <img class='img-fluid' src='../images/Ecrire.png' data-toggle='tooltip' data-placement='top' title='Envoyer un message aux persos de son camp dans sa visu' width='38' height='38'/></a></div>
								</div>
								<div class="col-4">
									<div class="text-center justify-content-center"><a href="nouveau_message.php?visu=ok" target='_blank'>Crier très fort : <img class='img-fluid' src='../images/porte_voix.png' data-toggle='tooltip' data-placement='top' title='Envoyer un message à tous les persos dans sa visu' width='38' height='27' /></a></div>
								</div>
							</div>
							<!-- Carte de jeu -->
							<?php require_once("page_jeu/containeur_carte.php" ); ?>
							<!-- Fin Carte de jeu-->
										
						</div>
					</div>
			</div>
			</main>
>>>>>>> Stashed changes
		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		
		<script>
		$(function () {
			$('[data-toggle="tooltip"]').tooltip();
			$('[data-toggle="popover"]').popover(); 
		})
		
		function openNav() {
			if(document.getElementById("mySidebar").style.width == "" || document.getElementById("mySidebar").style.width == "0px") {
				document.getElementById("mySidebar").style.width = "250px";
				document.getElementById("boutonChat").style.marginLeft = "250px";
			} else {
				document.getElementById("mySidebar").style.width = "0";
				document.getElementById("boutonChat").style.marginLeft= "0";
			}
		}

		function closeNav() {
			document.getElementById("mySidebar").style.width = "0";
			document.getElementById("boutonChat").style.marginLeft= "0";
		}
		</script>
	</body>
</html>

