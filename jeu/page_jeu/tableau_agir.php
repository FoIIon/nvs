
        
            <table style="border:0px; background-color: cornflowerblue; min-width: 375px;" width="100%">
                <tr>
                    <td align='right'><b>PV</b></td>
                    <td align='center'><?php $pourc = affiche_jauge($pv_perso, $pvMax_perso); echo "".round($pourc)."% ou $pv_perso/$pvMax_perso"; ?></td>
                </tr>
            </table>
        
            <table style="border:0px; background-color: cornflowerblue; min-width: 375px;" width="100%">
                <tr style="width: 100%;">
                    <td style="width: 40%;">
                        <table border="2" bordercolor="white" style="width: 100%;"> <!-- border-collapse:collapse -->
                            <tr>
                                <td><b>XP</b></td>
                                <td><?php echo $xp_perso; ?>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>XPI</b></td>
                                <td><?php echo $pi_perso; ?>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>PC</b></td>
                                <td><?php echo $pc_perso; ?>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                    
                    <td style="width: 30%;">
                        <table border="2" bordercolor="white" style="width: 100%;">
                            <tr>
                                <td><b>Perception</b></td>
                                <td align='center'>
                                <?php 
                                
                                $texte_tooltip = "Base : ".$perception_perso."";
                                if($bonusPerception_perso != 0) {
                                    if ($bonusPerception_perso < 0) {
                                        $texte_tooltip .= " <b>(";
                                    } else {
                                        $texte_tooltip .= " <b>(+";
                                    }
                                    $texte_tooltip .= $bonusPerception_perso . ")</b>"; 
                                }
                                
                                $perception_final_perso = $perception_perso + $bonusPerception_perso;
                                
                                echo '<a tabindex="0" href="#" data-toggle="popover" data-trigger="focus" data-placement="top" data-html="true" data-content="'.$texte_tooltip.'">'.$perception_final_perso.'</a>';
                                
                                ?>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>PA</b></td>
                                <td align='center' nowrap="nowrap">
                                <?php 
                                
                                $texte_tooltip = "Base max : ".$paMax_perso."";
                                if ($bonusPA_perso != 0) {
                                    if ($bonusPA_perso < 0) {
                                        $texte_tooltip .= " <b>(";
                                    } else {
                                        $texte_tooltip .= " <b>(+";
                                    }
                                    $texte_tooltip .= $bonusPA_perso . ")</b>"; 
                                }
                                
                                $paMax_final_perso = $paMax_perso + $bonusPA_perso;
                                
                                echo $pa_perso . ' / <a tabindex="0" href="#" data-toggle="popover" data-trigger="focus" data-placement="top" data-html="true" data-content="'.$texte_tooltip.'">'. $paMax_final_perso.'</a>';
                                
                                ?>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>PM</b></td>
                                <td align='center' nowrap="nowrap"><?php 
                                
                                $texte_tooltip_pm = "Base : ".$pm_perso_tmp;
                                
                                if ($malus_pm_charge != 0) {
                                    $texte_tooltip_pm .= " <b>(";
                                    $texte_tooltip_pm .= "charge : ";
                                    $texte_tooltip_pm .= $malus_pm_charge;
                                    $texte_tooltip_pm .= ")</b>";
                                }
                                
                                $texte_tooltip_pmMax = "Base max : ".$pmMax_perso_tmp."";
                                
                                if ($bonusPM_perso != 0) {
                                    $texte_tooltip_pmMax .= " <b>(";
                                    
                                    if ($bonusPM_perso != 0) {
                                        
                                        $texte_tooltip_pmMax .= "objets : ";
                                        
                                        if ($bonusPM_perso < 0) {
                                            $texte_tooltip_pmMax .= $bonusPM_perso;
                                        }
                                        else {
                                            $texte_tooltip_pmMax .= "+".$bonusPM_perso;
                                        }
                                    }
                                    
                                    $texte_tooltip_pmMax .= ")</b>";
                                }
                                echo '<a tabindex="0" href="#" data-toggle="popover" data-trigger="focus" data-placement="top" data-html="true" data-content="'.$texte_tooltip_pm.'">' . $pm_perso  . '</a> / <a tabindex="0" href="#" data-toggle="popover" data-trigger="focus" data-placement="top" data-html="true" data-content="'.$texte_tooltip_pmMax.'">' . $pmMax_perso . '</a>';
                                ?>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                    
                    <td style="width: 30%;">
                        <table border="2" bordercolor="white" style="width: 100%;">
                            <tr>
                                <td><b>Protection</b></td>
                                <td align='center'><?php echo $protec_perso; ?>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><b>Récuperation</b></td>
                                <td align='center' nowrap="nowrap">
                                <?php 
                                $texte_tooltip = "Base : ".$recup_perso."";
                                
                                if($bonusRecup_perso != 0) {
                                    if ($bonusRecup_perso < 0) {
                                        $texte_tooltip .= " <b>(";
                                    } else {
                                        $texte_tooltip .= " <b>(+";
                                    }
                                    $texte_tooltip .= $bonusRecup_perso . ")</b>"; 
                                }
                                
                                $recup_final = $recup_perso + $bonusRecup_perso;
                                
                                echo '<a tabindex="0" href="#" data-toggle="popover" data-trigger="focus" data-placement="top" data-html="true" data-content="'.$texte_tooltip.'">'.$recup_final.'</a>';
                                
                                ?>&nbsp;</td>
                            </tr>
                            <tr>
                                <td nowrap="nowrap"><b>Défense</b></td>
                                <td align='center' nowrap="nowrap"><?php 
                                
                                $texte_tooltip = "Base : ".$bonus_perso."";
                                
                                $bonus_defense = getBonusDefenseObjet($mysqli, $id_perso);
                                
                                $bonus_defense_bat = get_bonus_defense_instance_bat($mysqli, $id_perso);
                                
                                // recuperation des données de la carte
                                $sql = "SELECT fond_carte FROM $carte 
                                        WHERE x_carte = $x_perso
                                        AND y_carte = $y_perso";
                                $res = $mysqli->query($sql);
                                $tab = $res->fetch_assoc();
                                
                                $fond_carte_perso = $tab['fond_carte'];
                                
                                $bonus_defense_terrain_cac = get_bonus_defense_terrain($fond_carte_perso, 1);
                                $bonus_defense_terrain_dist = get_bonus_defense_terrain($fond_carte_perso, 2);
                                
                                $bonus_final_cac = $bonus_perso + $bonus_defense + $bonus_defense_terrain_cac + $bonus_defense_bat;
                                $bonus_final_dist = $bonus_perso + $bonus_defense + $bonus_defense_terrain_dist + $bonus_defense_bat;
                                
                                echo '<a tabindex="0" href="#" data-toggle="popover" data-trigger="focus" data-placement="top" data-html="true" data-content="'.$texte_tooltip.'">';
                                if ($bonus_final_cac == $bonus_final_dist) {
                                    echo $bonus_final_cac.'</a>';
                                }
                                else {
                                    echo 'Cac : '.$bonus_final_cac.' - Dist : '.$bonus_final_dist.'</a>';
                                }
                                ?>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            
            <br />
            
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

