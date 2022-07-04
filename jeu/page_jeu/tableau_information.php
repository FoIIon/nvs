<!-- Début du tableau d'information-->
<table data-toggle="table"  background='../images/background.jpg' width=100%>
    <tr>
        <td width=120>
            <center>
                <div width=40 height=40 style="position: relative;">
                    <div style="position: absolute;bottom: 0;text-align: center; width: 100%;font-weight: bold;">
                        <?php echo $id_perso; ?>
                    </div>
                    <img src="../images_perso/<?php echo "$image_perso";?>" width=40 height=40>
                </div>
            </center>
        </td>
        <td align=center>
            <form method='post' action='jouer.php'>
                <b>Nom : </b><select name='liste_perso' onchange="this.form.submit()">
                <?php 
                while($t_liste_perso = $res->fetch_assoc()) {
                    
                    $id_perso_liste 	= $t_liste_perso["id_perso"];
                    $nom_perso_liste 	= $t_liste_perso["nom_perso"];
                    $chef_perso			= $t_liste_perso["chef"];
                    
                    if ($chef_perso) {
                        $nom_perso_chef = $nom_perso_liste;
                    }
                    
                    echo "<option value='$id_perso_liste'";
                    if ($id_perso == $id_perso_liste) {
                        echo " selected";
                    }
                    echo ">$nom_perso_liste [$id_perso_liste]</option>";
                }
                ?>
                </select>
            </form>
        </td>
        <td align=center><b>Grade : <a href="grades.php" target='_blank'></b><?php echo $nom_grade_perso; ?>
            <img alt="<?php echo $nom_grade_perso; ?>" title="<?php echo $nom_grade_perso; ?>" src="../images/grades/<?php echo $id_grade_perso . ".gif";?>" width=40 height=40></a>
        </td>
    </tr>
    <tr>
        <td align=center><b>Chef : </b><?php echo $nom_perso_chef; ?></td>
        <td align=center><b>Bataillon : </b><?php echo "<a href=\"bataillon.php?id_bataillon=$id_joueur_perso\" target='_blank'>" . $bataillon_perso . "</a>"; ?></td>
        <td align=center><b>Compagnie : </b><?php echo "<a href=\"compagnie.php\" target='_blank'>" . stripslashes($nom_compagnie_perso) . "</a>"; ?></td>
    </tr>
</table>
<!--Fin du tableau d'information-->