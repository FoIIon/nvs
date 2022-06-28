
                <table  data-toggle="table"  cellspacing="0" cellpadding="0" style:no-padding width="100%">
                    <tr>
                        <td background='../images/background.jpg' align='center' valign='top'colspan='2'>
                            <img src='../images/Action.png'/>
                            <form method='post' action='action.php'>
                                <select name='liste_action'>
                                    <option value="invalide" selected> -- -- - Choisir une action - -- --</option>
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
                                    if (($type_perso == 1 || $type_perso == 2 || $type_perso == 7) && $pm_perso >= 4 && !in_train($mysqli, $id_perso) && !in_bat($mysqli, $id_perso)) {
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
                        <td background='../images/background.jpg' colspan='2' align='center'>
                            <img src='../images/barre.png' />
                        </td>
                    </tr>
                </table>

<!--</tr>
</table>-->