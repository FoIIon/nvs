<center>
    <table border=0 align="center" width=100%>
        <tr>
            <td align="center" width=<?php echo $pourc_icone; ?>><a href="profil.php" target='_blank'><img width=88 height=92 border=0 src="../images/<?php echo $image_profil; ?>" alt="profil"></a></td>
            <td align="center" width=<?php echo $pourc_icone; ?>><a href="evenement.php" target='_blank'><img width=88 height=92 border=0 src="../images/<?php echo $image_evenement; ?>" alt="evenement"></a></td>
            <td align="center" width=<?php echo $pourc_icone; ?>><a href="sac.php" target='_blank'><img width=88 height=92 border=0 src="../images/<?php echo $image_sac; ?>" alt="sac"></a></td>
            <td align="center" width=<?php echo $pourc_icone; ?>><a href="carte2.php" target='_blank'><img width=88 height=92 border=0 src="../images/carte2.png" alt="mini map"></a></td>
            <?php
            if ($type_perso != 6) {
            ?>
            <td align="center" width=<?php echo $pourc_icone; ?>><a href="messagerie.php" target='_blank'><img width=88 height=92 border=0 src="../images/<?php echo $image_messagerie; ?>" alt="messagerie"></a></td>
            <?php
            }
            ?>
            <td align="center" width=<?php echo $pourc_icone; ?>><a href="classement.php" target='_blank'><img width=88 height=92 border=0 src="../images/classement2.png" alt="classement"></a></td>
            <?php
            if ($type_perso != 6) {
            ?>
            <td align="center" width=<?php echo $pourc_icone; ?>>
                <a href="compagnie.php" target='_blank'><img width=88 height=92 border=0 src="../images/<?php echo $image_compagnie; ?>" alt="compagnie"></a>
            </td>
            <?php
            }
            if ($nb_em) {
            ?>
            <td align="center" width=<?php echo $pourc_icone; ?>><a href="etat_major.php" target='_blank'><img width=117 height=89 border=0 src="../images/<?php echo $image_em; ?>" alt="etat major"></a></td>
            <?php	
            }
            ?>
        </tr>
        <tr>
            <td align="center" width=<?php echo $pourc_icone; ?>><a href="profil.php" target='_blank'><img width=83 height=16 border=0 src="../images/profil_titrev2.png"></a> <?php if($bonus_perso < 0){ echo "<span class='badge badge-pill badge-danger' title='malus de défense dû aux attaques'>$bonus_perso</span>";} ?></td>
            <td align="center" width=<?php echo $pourc_icone; ?>><a href="evenement.php" target='_blank'><img width=83 height=16 border=0 src="../images/evenement_titrev2.png"></a></td>
            <td align="center" width=<?php echo $pourc_icone; ?>><a href="sac.php" target='_blank'><img width=83 height=16 border=0 src="../images/sac_titrev2.png"></a></td>
            <?php 
            $sql_mes = "SELECT count(id_message) as nb_mes from message_perso where id_perso='$id_perso' and lu_message='0' AND supprime_message='0'";
            $res_mes = $mysqli->query($sql_mes);
            $t_mes = $res_mes->fetch_assoc();
            
            $nb_nouveaux_mes = $t_mes["nb_mes"];
            ?>
            <td align="center" width=<?php echo $pourc_icone; ?>><a href="carte2.php" target='_blank'><img width=83 height=16 border=0 src="../images/carte_titrev2.png"></a></td>
            <?php
            if ($type_perso != 6) {
            ?>
            <td align="center" width=<?php echo $pourc_icone; ?>>
                <a href="messagerie.php" target='_blank'><img width=83 height=16 border=0 src="../images/messagerie_titrev2.png"></a>
                <?php 
                if($nb_nouveaux_mes) { 
                    echo "<span class='badge badge-pill badge-danger'>$nb_nouveaux_mes</span>"; 
                } 
                ?>
            </td>
            <?php
            }
            ?>
            <td align="center" width=<?php echo $pourc_icone; ?>><a href="classement.php" target='_blank'><img width=83 height=16 border=0 src="../images/classement_titrev2.png"></a></td>
            <?php
            if ($type_perso != 6) {
            ?>
            <td align="center" width=<?php echo $pourc_icone; ?>>
                <a href="compagnie.php" target='_blank'><img width=83 height=16 border=0 src="../images/compagnie_titrev2.png"></a>
                <?php
                if ($nb_demandes_adhesion_compagnie) {
                    echo "<span class='badge badge-pill badge-success'>$nb_demandes_adhesion_compagnie</span>";
                }
                
                if ($nb_demandes_depart_compagnie) {
                    echo "<span class='badge badge-pill badge-danger'>$nb_demandes_depart_compagnie</span>";
                }
                
                if ($nb_demandes_emprunt_compagnie) {
                    echo "<span class='badge badge-pill badge-warning'>$nb_demandes_emprunt_compagnie</span>";
                }
                ?>
            </td>
            <?php
            }
            if ($nb_em) {
            ?>
            <td align="center" width=<?php echo $pourc_icone; ?>>
                <a href="etat_major.php" target='_blank'><img width=83 height=16 border=0 src="../images/em_titrev2.png" alt="etat major"></a>
                <?php
                if ($nb_compagnie_attente_em) {
                    echo "<br/><font color=red><b>$nb_compagnie_attente_em</b> compagnie(s) en attente de validation</font>";
                }
                ?>
            </td>
            <?php
            }
            ?>
        </tr>
        <tr>
            <td colspan='7' align='center'>&nbsp;</td>
        </tr>
       <!-- <tr>
            <td colspan='1' align='center'>Rafraîchir la page : <a href='jouer.php'><img border=0 src='../images/refreshv2.png' alt='refresh' /></a></td>
            <td colspan='1' align='center'>Envoyer un MP à sa visu : <a href=\"nouveau_message.php?visu=ok&camp=".$clan_perso."\" target='_blank'><img class='img-fluid' src='../images/Ecrire.png' data-toggle='tooltip' data-placement='top' title='Envoyer un message aux persos de son camp dans sa visu' border=0 width='38' height='38'/></a></td>
            <td colspan='1' align='center'>Crier très fort :<a href=\"nouveau_message.php?visu=ok\" target='_blank'><img class='img-fluid' src='../images/porte_voix.png' data-toggle='tooltip' data-placement='top' title='Envoyer un message à tous les persos dans sa visu' border=0 width='38' height='27' /></a></td>
        </tr>-->
    </table>
</center>
