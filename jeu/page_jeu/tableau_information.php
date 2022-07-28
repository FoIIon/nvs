
<!-- Début du tableau d'information-->

    
<form method='post'  action='jouer.php'>
    <div class="dropdown gy-5">
        <button class="btn btn-secondary dropdown-toggle"  type="button" data-bs-toggle="dropdown" aria-expanded="false">
            
            <img src="../images_perso/<?php echo $image_perso?>" width=40 height=40 >
            <?php echo '  -  '.$nom_perso .'['.$id_perso.']  -  ' ?>
            <img src="../images/grades/<?php echo $id_grade_perso?>.gif" width=40 height=40>
        
            
        </button>
        <ul class="dropdown-menu">
            <?php 
                        
                while($t_liste_perso = $res->fetch_assoc()) {
                    $id_perso_liste 	= $t_liste_perso["id_perso"];
                    $nom_perso_liste 	= $t_liste_perso["nom_perso"];
                    $image_perso_liste 	= $t_liste_perso["image_perso"];
                    $chef_perso			= $t_liste_perso["chef"];
                    if ($chef_perso) {
                        $nom_perso_chef = $nom_perso_liste;
                    }
                    if($nom_perso!=$nom_perso_liste){
                        echo '<li>
                                <button class="dropdown-item" type="submit" name="liste_perso" value='.$id_perso_liste.'>
                                    <img src="../images_perso/'.$image_perso_liste.'" width=30 height=30>  -  '.$nom_perso_liste .'['.$id_perso_liste.']
                                </button>
                            </li>';
                    }
                }
            ?>
        </ul>
    </div>
</form> 
<table data-toggle="table"  background='../images/background.jpg' width=100%>
    <tr>
        <td align=center><b>Bataillon : </b><?php echo "<a href=\"bataillon.php?id_bataillon=$id_joueur_perso\" target='_blank'>" . $bataillon_perso . "</a>"; ?></td>
        <td align=center><b>Compagnie : </b><?php echo "<a href=\"compagnie.php\" target='_blank'>" . stripslashes($nom_compagnie_perso) . "</a>"; ?></td>
    </tr>
</table>
<!--Fin du tableau d'information-->