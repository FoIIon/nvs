<table data-toggle="table" style="border:0px; background-color: cornflowerblue; " width="100%">
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
                    
                    echo "<a tabindex='0' href='#' data-bs-toggle='popover' data-bs-trigger='focus' data-bs-placement='top' data-bs-html='true' data-bs-content=".$texte_tooltip.'">'.$perception_final_perso.'</a>';
                    
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
                    
                    echo $pa_perso . " / <a tabindex='0' href='#' data-bs-toggle='popover' data-bs-trigger='focus' data-bs-placement='top' data-bs-html='true' data-bs-content=".$texte_tooltip.'">'. $paMax_final_perso.'</a>';
                    
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
                    echo "<a tabindex='0' href='#' data-bs-toggle='popover' data-bs-trigger='focus' data-bs-placement='top' data-bs-html='true' data-bs-content=".$texte_tooltip_pm.'">' . $pm_perso  . "</a> / <a tabindex='0' href='#' data-bs-toggle='popover' data-bs-trigger='focus' data-bs-placement='top' data-bs-html='true' data-bs-content=".$texte_tooltip_pmMax.'">' . $pmMax_perso . '</a>';
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
                    
                    echo "<a tabindex='0' href='#' data-bs-toggle='popover' data-bs-trigger='focus' data-bs-placement='top' data-bs-html='true' data-bs-content=".$texte_tooltip.'">'.$recup_final.'</a>';
                    
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
                    
                    echo "<a tabindex='0' href='#' data-bs-toggle='popover' data-bs-trigger='focus' data-bs-placement='top' data-bs-html='true' data-bs-content=".$texte_tooltip.'">';
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