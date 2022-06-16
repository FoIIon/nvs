<?php 
    // Traitement voir objets à terre
    if(isset($_GET['ramasser']) && $_GET['ramasser'] == "voir"){
        
        $affichage_objets = true;
        
        if (isset($_GET['x']) && isset($_GET['y']) && trim($_GET['x']) != "" && trim($_GET['y']) != "") {
            
            $x = $_GET['x'];
            $y = $_GET['y'];
            
            $verifx = preg_match("#^[0-9]*[0-9]$#i","$x");
            $verify = preg_match("#^[0-9]*[0-9]$#i","$y");
            
            // verif si le perso est bien à côté
            $verif_prox = prox_coffre($mysqli, $x, $y, $x_perso, $y_perso);
            
            if ($verifx && $verify && $verif_prox) {
            
                $sql = "SELECT type_objet, id_objet, nb_objet FROM objet_in_carte WHERE x_carte='$x' AND y_carte='$y'";
                $res = $mysqli->query($sql);
            }
            else {
                $affichage_objets = false;
                
                // Tentative de triche !
                $text_triche = "Le perso $id_perso a essayé de jouer avec les paramètres pour voir les objets à ramasser !";
                
                $sql = "INSERT INTO tentative_triche (id_perso, texte_tentative) VALUES ('$id_perso', '$text_triche')";
                $mysqli->query($sql);
            }
        }
        else {
            $sql = "SELECT type_objet, id_objet, nb_objet FROM objet_in_carte WHERE x_carte='$x_perso' AND y_carte='$y_perso'";
            $res = $mysqli->query($sql);
        }
        
        if ($affichage_objets) {
        
            echo "<center>";
            echo "<b>Liste des objets à terre</b>";
            echo "	<table border='1' width='50%'>";
            echo "		<tr>";
            echo "			<th style='text-align:center'>Nom objet</th><th style='text-align:center'>Quantité</th>";
            echo "		</tr>";
            
            while ($t = $res->fetch_assoc()) {
                
                $type_objet = $t['type_objet'];
                $id_objet 	= $t['id_objet'];
                $nb_objet	= $t['nb_objet'];
                
                // Récupération du nom de l'objet 
                // Thunes
                if ($type_objet == '1') {
                    $nom_objet = "Thune";
                    if ($nb_objet > 1) {
                        $nom_objet = $nom_objet."s";
                    }
                }
                
                // Objets
                if ($type_objet == '2') {
                    $sql_obj = "SELECT nom_objet FROM objet WHERE id_objet='$id_objet'";
                    $res_obj = $mysqli->query($sql_obj);
                    $t_obj = $res_obj->fetch_assoc();
                    
                    $nom_objet = $t_obj['nom_objet'];
                }
                
                // Armes
                if ($type_objet == '3') {
                    $sql_obj = "SELECT nom_arme FROM arme WHERE id_arme='$id_objet'";
                    $res_obj = $mysqli->query($sql_obj);
                    $t_obj = $res_obj->fetch_assoc();
                    
                    $nom_objet = $t_obj['nom_arme'];
                }
                
                echo "		<tr>";
                echo "			<td align='center'>" . $nom_objet . "</td><td align='center'>" . $nb_objet . "</td>";
                echo "		</tr>";
            }
            
            echo "	</table>";
            echo "</center>";
        }
    }
    
    // Récupération de l'arme de CaC équipé sur le perso
    $sql = "SELECT arme.id_arme, nom_arme, porteeMin_arme, porteeMax_arme, coutPa_arme, degatMin_arme, valeur_des_arme, precision_arme, degatZone_arme 
            FROM arme, perso_as_arme
            WHERE arme.id_arme = perso_as_arme.id_arme
            AND porteeMax_arme = 1
            AND perso_as_arme.est_portee = '1'
            AND id_perso = '$id_perso'";
    $res = $mysqli->query($sql);
    $nb_cac = $res->num_rows;
    
    if ($nb_cac > 1) {
        $i = 1;
        
        while ($t_cac = $res->fetch_assoc()) {
            
            if ($i == 1) {
                $id_arme_cac			= $t_cac["id_arme"];
                $nom_arme_cac 			= $t_cac["nom_arme"];
                $porteeMin_arme_cac 	= $t_cac["porteeMin_arme"];
                $porteeMax_arme_cac 	= $t_cac["porteeMax_arme"];
                $coutPa_arme_cac 		= $t_cac["coutPa_arme"];
                $degatMin_arme_cac 		= $t_cac["degatMin_arme"];
                $valeur_des_arme_cac 	= $t_cac["valeur_des_arme"];
                $precision_arme_cac 	= $t_cac["precision_arme"];
                $degatZone_arme_cac 	= $t_cac["degatZone_arme"];
                
                $degats_arme_cac = $degatMin_arme_cac."D".$valeur_des_arme_cac;
            }
            else {
                $id_arme_cac2			= $t_cac["id_arme"];
                $nom_arme_cac2 			= $t_cac["nom_arme"];
                $porteeMin_arme_cac2 	= $t_cac["porteeMin_arme"];
                $porteeMax_arme_cac2 	= $t_cac["porteeMax_arme"];
                $coutPa_arme_cac2		= $t_cac["coutPa_arme"];
                $degatMin_arme_cac2 	= $t_cac["degatMin_arme"];
                $valeur_des_arme_cac2 	= $t_cac["valeur_des_arme"];
                $precision_arme_cac2 	= $t_cac["precision_arme"];
                $degatZone_arme_cac2 	= $t_cac["degatZone_arme"];
                
                $degats_arme_cac2 = $degatMin_arme_cac2."D".$valeur_des_arme_cac2;
            }
            
            $i++;
        }
    }
    else {
        $t_cac = $res->fetch_assoc();
        
        if ($t_cac != NULL) {
            $id_arme_cac			= $t_cac["id_arme"];
            $nom_arme_cac 			= $t_cac["nom_arme"];
            $porteeMin_arme_cac 	= $t_cac["porteeMin_arme"];
            $porteeMax_arme_cac 	= $t_cac["porteeMax_arme"];
            $coutPa_arme_cac 		= $t_cac["coutPa_arme"];
            $degatMin_arme_cac 		= $t_cac["degatMin_arme"];
            $valeur_des_arme_cac 	= $t_cac["valeur_des_arme"];
            $precision_arme_cac 	= $t_cac["precision_arme"];
            $degatZone_arme_cac 	= $t_cac["degatZone_arme"];
        } else {
            $id_arme_cac			= 1000;
            $nom_arme_cac 			= "Poings";
            $porteeMin_arme_cac 	= 1;
            $porteeMax_arme_cac 	= 1;
            $coutPa_arme_cac 		= 3;
            $degatMin_arme_cac 		= 4;
            $valeur_des_arme_cac 	= 6;
            $precision_arme_cac 	= 30;
            $degatZone_arme_cac 	= 0;
        }
        
        $degats_arme_cac = $degatMin_arme_cac."D".$valeur_des_arme_cac;
    }
    
    // Récupération de la liste des persos à portée d'attaque arme CaC
    $perc_att = $perc;
    if ($perc_att <= 0) {
        $perc_att = 1;
    }
    $res_portee_cac = resource_liste_cibles_a_portee_attaque($mysqli, 'carte', $id_perso, $porteeMin_arme_cac, $porteeMax_arme_cac, $perc_att, 'cac');
    
    // Récupération de l'arme à distance sur le perso
    $sql = "SELECT arme.id_arme, nom_arme, porteeMin_arme, porteeMax_arme, coutPa_arme, degatMin_arme, valeur_des_arme, precision_arme, degatZone_arme 
            FROM arme, perso_as_arme
            WHERE arme.id_arme = perso_as_arme.id_arme
            AND porteeMax_arme > 1
            AND perso_as_arme.est_portee = '1'
            AND id_perso = '$id_perso'";
    $res = $mysqli->query($sql);
    $t_dist = $res->fetch_assoc();
    
    if ($t_dist != NULL) {
        $id_arme_dist 			= $t_dist["id_arme"];
        $nom_arme_dist 			= $t_dist["nom_arme"];
        $porteeMin_arme_dist 	= $t_dist["porteeMin_arme"];
        $porteeMax_arme_dist 	= $t_dist["porteeMax_arme"];
        $coutPa_arme_dist 		= $t_dist["coutPa_arme"];
        $degatMin_arme_dist 	= $t_dist["degatMin_arme"];
        $valeur_des_arme_dist 	= $t_dist["valeur_des_arme"];
        $precision_arme_dist 	= $t_dist["precision_arme"];
        $degatZone_arme_dist 	= $t_dist["degatZone_arme"];
    } else {
        $id_arme_dist			= 2000;
        $nom_arme_dist 			= "Cailloux";
        $porteeMin_arme_dist 	= 1;
        $porteeMax_arme_dist 	= 2;
        $coutPa_arme_dist 		= 3;
        $degatMin_arme_dist 	= 5;
        $valeur_des_arme_dist 	= 6;
        $precision_arme_dist 	= 25;
        $degatZone_arme_dist 	= 0;
    }
    
    $degats_arme_dist = $degatMin_arme_dist."D".$valeur_des_arme_dist;
    
    // Récupération de la liste des persos à portée d'attaque arme dist
    $res_portee_dist = resource_liste_cibles_a_portee_attaque($mysqli, 'carte', $id_perso, $porteeMin_arme_dist, $porteeMax_arme_dist, $perc_att, 'dist');
    
    // background='../images/background_html.jpg'
?>