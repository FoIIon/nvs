<table data-toggle="table" border="2" style="background-color: palevioletred;" width="100%">
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
            <select id="cac" name='id_attaque_cac' style="max-width: 135px;white-space: normal;text-overflow: ellipsis;">
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
            <select id="dist" name='id_attaque_dist' style="max-width: 135px;white-space: normal;text-overflow: ellipsis;">
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
            <select id="cac2" name='id_attaque_cac2' style="max-width: 135px;white-space: normal;text-overflow: ellipsis;">
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