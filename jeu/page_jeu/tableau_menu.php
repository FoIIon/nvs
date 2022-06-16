<table width=100% bgcolor='white' border=0>
    <?php
    echo "<tr>
            <td><img src='../images/clock.png' alt='horloge' width='25' height='25'/> Heure serveur : <b><span id=tp1>".$date_serveur->format('H:i:s')."</span></b></td>
            <td rowspan=2><img src='../images/accueil/banniere.jpg' alt='banniere Nord VS Sud' width=150 height=63 /></td>
            <td align=right> <a class='btn btn-danger' href=\"../logout.php\"><b>Déconnexion</b></a></td>
        </tr>";
    echo "<tr>";
    echo "	<td>Prochain tour :  ".$date_dla."</td>";
    echo "	<td align=right>";
    echo "		<a class='btn btn-info' href=\"../regles/regles.php\" target='_blank'><b>Règles</b></a>";
    echo "		<a class='btn btn-info' href=\"../faq.php\" target='_blank'><b>FAQ</b></a>";
    echo "		<a class='btn btn-primary' href=\"http://www.forum.persee.ovh/\" target='_blank'><b>Forum</b></a>";
    if ($type_perso != 6) {
        echo "		<a class='btn btn-primary' href=\"question_anim.php\" target='_blank'><b>Questions Anim</b></a>";
        echo "		<a class='btn btn-primary' href=\"capture.php\" target='_blank'><b>Déclarer une capture</b></a>";
    }
    echo "		<a class='btn btn-warning' href=\"missions.php\" target='_blank'><b>Missions ";
    if ($nb_missions_actives > 0) {
        echo "<span class='badge badge-success'>".$nb_missions_actives."</span>";
    }
    echo "</b></a>";
    
    // Redacteur
    if(redac_perso($mysqli, $id_perso)) { 
        echo " <a class='btn btn-warning' href='redacteur.php'>Redaction</a>"; 
    }
    
    // Animation
    if(anim_perso($mysqli, $id_perso)) { 
        echo " <a class='btn btn-warning' href='animation.php'>Animation <span class='badge badge-danger' title='".$nb_demande_a_traiter." demandes en attente'>";
        if ($nb_demande_a_traiter > 0) {
            echo $nb_demande_a_traiter;
        }
        echo "</span></a>";
    }
    
    // Admin
    if($admin) {
        echo " <a class='btn btn-warning' href='admin_nvs.php'>Admin</a>";
    }
    
    // Ajout Jacklegende du 23/04 - lien Discord et de la visu
    echo " <a class='btn btn-info' href='https://discord.gg/SpZ87fYZeZ' target='_blank'>Discord Commun</a>";
            
    $id_joueur_camp 	= $_SESSION["ID_joueur"];
        
    // recuperation des infos du perso
    $sql_camp = "SELECT clan FROM perso WHERE idJoueur_perso='$id_joueur_camp'";
    $res_camp = $mysqli->query($sql_camp);
    $t_perso_camp = $res_camp->fetch_assoc();
        
    $id_joueur_perso_camp 	= $t_perso_camp["clan"];
            
    if($id_joueur_perso_camp == 1){
            echo " <a class='btn btn-info' href='https://discord.gg/95fKNwT8Vh' target='_blank'>Discord nord </a>";
    } else if ($id_joueur_perso_camp == 2){
            echo " <a class='btn btn-info' href='https://discord.gg/zE9knsyRGr' target='_blank'>Discord sud</a>";
    }
        
    echo " <a class='btn btn-info' href='visu.php' target='_blank'>Visu</a>";
    //fin d'ajout lien Discord et de la visu
    
    echo "	</td>";
    echo "</tr>";
    ?>
</table>