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
<html lang="fr">
	<head>
		<title>Nord VS Sud</title>
		
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- Bootstrap CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
		
		<link href="../style2.css" rel="stylesheet" type="text/css">
		<link href="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.css" rel="stylesheet">


	</head>

	<body background='../images/body_background.jpg'>
		<header>
			<?php include("page_jeu/menu_header.php"); ?>
		</header>
		<main class="flex-shrink-0" >
			<div class="container"  style="padding:0!important"  >
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
					<div class="row py-2" >
						<div class="col-lg-4 mx-auto" style="background: red;">
							 <!--tableau agir  style="background: red;"-->
							<?php require_once("page_jeu/tableau_agir.php" ); ?>
							<!-- Fin tableau agir -->
							<br />
							<!-- Rosace de deplacement -->
							<?php require_once("page_jeu/rosace_deplacement.php" ); ?>
							<!-- Fin rosace de deplacement -->
							<!--Debut tableau des actions -->
							<br />
							<?php require_once("page_jeu/tableau_action.php" ); ?>
							<!-- Fin tableau des actions-->
						</div>
						
						<div class="col-lg-8 mx-auto" style="background: blue;">
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
		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
		<script src="https://unpkg.com/bootstrap-table@1.20.2/dist/bootstrap-table.min.js"></script>
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

