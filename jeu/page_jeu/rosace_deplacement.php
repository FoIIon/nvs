<?php
    if ($afficher_rosace) {
    ?>
    <table border='2' width="100%">
        <tr>
            <td background='../images/background.jpg'>
                <!--Création du tableau du choix du deplacement-->
                <table border=0 align='center'>
                    <tr>
                        <td colspan='5' align='center'>
                        <img src='../images/Se_Deplacer.png' />
                        </td>
                    </tr>
                    <form action="jouer.php" method="post">  
                    <tr>
                        <td rowspan='3'><img src='../images/tribal1.png' /></td>
                        <?php 
                        if (in_train($mysqli, $id_perso)) {
                            $id_train = in_train($mysqli, $id_perso);
                        }
                        
                        if(in_bat($mysqli, $id_perso)){
                        ?>
                            <td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=1"><img border=0 src="../fond_carte/fleche1.png"></a></td>
                            <td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=2"><img border=0 src="../fond_carte/fleche2.png"></a></td>
                            <td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=3"><img border=0 src="../fond_carte/fleche3.png"></a></td>
                        <?php
                        }
                        else if (isset($id_train) && $id_train > 0) {
                        ?>
                            <td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=1"><img border=0 src="../fond_carte/fleche1.png"></a></td>
                            <td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=2"><img border=0 src="../fond_carte/fleche2.png"></a></td>
                            <td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=3"><img border=0 src="../fond_carte/fleche3.png"></a></td>
                        <?php
                        }
                        else {
                        ?>
                            <td><a href="jouer.php?mouv=1"><img border=0 src="../fond_carte/fleche1.png"></a></td>
                            <td><a href="jouer.php?mouv=2"><img border=0 src="../fond_carte/fleche2.png"></a></td>
                            <td><a href="jouer.php?mouv=3"><img border=0 src="../fond_carte/fleche3.png"></a></td>
                        <?php 
                        }
                        ?>
                        <td rowspan='3'><img src='../images/tribal2.png' /></td>
                    </tr>
                    <tr>
                        <?php 
                        if(in_bat($mysqli, $id_perso)){
                        ?>
                            <td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=4"><img border=0 src="../fond_carte/fleche4.png"></a></td>
                            <td><center><b>Sortir</b></center></td>
                            <td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=5"><img border=0 src="../fond_carte/fleche5.png"></a></td>
                        <?php
                        }
                        else if (isset($id_train) && $id_train > 0) {
                        ?>
                            <td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=4"><img border=0 src="../fond_carte/fleche4.png"></a></td>
                            <td><center><b>Sauter</b></center></td>
                            <td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=5"><img border=0 src="../fond_carte/fleche5.png"></a></td>
                        <?php
                        }
                        else {
                        ?>
                        <td><a href="jouer.php?mouv=4"><img border=0 src="../fond_carte/fleche4.png"></a></td>
                        <td>&nbsp; </td>
                        <td><a href="jouer.php?mouv=5"><img border=0 src="../fond_carte/fleche5.png"></a></td>
                        <?php 
                        }
                        ?>
                    </tr>
                    <tr>
                        <?php 
                        if(in_bat($mysqli, $id_perso)){
                        ?>
                            <td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=6"><img border=0 src="../fond_carte/fleche6.png"></a></td>
                            <td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=7"><img border=0 src="../fond_carte/fleche7.png"></a></td>
                            <td><a href="jouer.php?bat=<?php echo $id_bat; ?>&bat2=<?php echo $bat; ?>&out=ok&direction=8"><img border=0 src="../fond_carte/fleche8.png"></a></td>
                        <?php
                        }
                        else if (isset($id_train) && $id_train > 0) {
                        ?>
                            <td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=6"><img border=0 src="../fond_carte/fleche6.png"></a></td>
                            <td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=7"><img border=0 src="../fond_carte/fleche7.png"></a></td>
                            <td><a href="jouer.php?train=<?php echo $id_train; ?>&out=ok&direction=8"><img border=0 src="../fond_carte/fleche8.png"></a></td>
                        <?php
                        }
                        else {
                        ?>
                            <td><a href="jouer.php?mouv=6"><img border=0 src="../fond_carte/fleche6.png"></a></td>
                            <td><a href="jouer.php?mouv=7"><img border=0 src="../fond_carte/fleche7.png"></a></td>
                            <td><a href="jouer.php?mouv=8"><img border=0 src="../fond_carte/fleche8.png"></a></td>
                        <?php 
                        }
                        ?>
                    </tr>
                    </form>
                </table>
                <!--Fin du tableau du choix du deplacement-->
            </td>
        </tr>
    </table>
<?php
}
?>