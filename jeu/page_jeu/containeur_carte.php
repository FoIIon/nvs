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
                
    <!--Fin de la génération de la carte-->
    
    <?php
    if($config == '2'){
        echo "</tr><tr>";
    }
?>