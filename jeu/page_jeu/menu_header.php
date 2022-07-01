<?php $id_joueur_camp 	= $_SESSION["ID_joueur"];
        $date_serveur = new DateTime(null, new DateTimeZone('Europe/Paris'));
				
				$date_dla = date('d-m-Y H:i:s', $n_dla);
        // recuperation des infos du perso
        $sql_camp = "SELECT clan FROM perso WHERE idJoueur_perso='$id_joueur_camp'";
        $res_camp = $mysqli->query($sql_camp);
        $t_perso_camp = $res_camp->fetch_assoc();
            
        $id_joueur_perso_camp 	= $t_perso_camp["clan"];
        $discord_link;        
        if($id_joueur_perso_camp == 1){
                $discord_link = 'https://discord.gg/95fKNwT8Vh';
        } else if ($id_joueur_perso_camp == 2){
            $discord_link = 'https://discord.gg/zE9knsyRGr';
        } ?>
<div class="bg-secondary">
	<div class="container">
   		<div class="row ">
   		 
        <nav class="col navbar navbar-dark  navbar-expand  ">
        <div class="collapse navbar-collapse" id="navbarSupportedContent2">
              <a class="navbar-brand  align-bottom " href="">
              	<img src="../images/accueil/banniere.jpg" class="d-inline-block  align-bottom" loading="lazy" alt='banniere Nord VS Sud' width=150 height=63>
              </a>
              	
                </div>
                <div class="navbar-nav ml-auto d-block"  style="padding-top: 1em"> 
                  <img src='../images/clock.png' alt='horloge' width='25' height='25'/> Heure serveur : <b><span id=tp1><?php echo ''.$date_serveur->format('H:i:s');?></span></b>
                  <br/>
                  <span>Prochain tour :  "<?php echo ''.$date_dla; ?>"</span>
                </div>
        	</nav>
        	
     	</div>
     	<div class="row">
   		 
        <nav class="col navbar navbar-dark  navbar-expand-md">
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                  <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Déconnexion</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"  href="#" id="dropdown04" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Communication</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown04">
                      <a class="dropdown-item" href="http://www.forum.persee.ovh/" target="_blank">Forum</a>
                      <a class="dropdown-item" href="https://discord.gg/SpZ87fYZeZ" target="_blank">Discord commun</a>
                      <a class="dropdown-item" href="<?php echo $discord_link; ?> "  target="_blank">Discord camp</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="question_anim.php">Question aux anims</a>
                      <a class="dropdown-item" href="capture.php">Déclarer une capture</a>
                    </div>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown05" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Le jeu</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown05">
                      <a class="dropdown-item" href="../regles/regles.php">Règles</a>
                      <a class="dropdown-item" href="../faq.php">FAQ</a>
                    </div>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="missions.php">Missions</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="visu.php">Visu</a>
                  </li>
                  <!-- Show only on smaller screens-->
                  <li class="nav-item dropdown 	d-none d-sm-block d-md-none">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown07" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">A trier</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown07">
                      <a class="dropdown-item" href="../profil.php">Profil</a>
                      <a class="dropdown-item" href="../evenement.php">Evenement</a>
                      <a class="dropdown-item" href="../sac.php">Sac</a>
                      <a class="dropdown-item" href="../carte2.php">Carte</a>
                      <a class="dropdown-item" href="../messagerie.php">Messagerie</a>
                      <a class="dropdown-item" href="../classement.php">Classement</a>
                      <a class="dropdown-item" href="../compagnie.php">Compagnie</a>
                    </div>
                  </li>
                  <?php
                      //show only if the user has some management permissions
                      (redac_perso($mysqli, $id_perso) || anim_perso($mysqli, $id_perso) || $admin) ? require_once("menu_header_gestion.php") : "";

                  ?>
                </ul>
              </div>
        	</nav>
        	
     	</div>
	</div>
</div>