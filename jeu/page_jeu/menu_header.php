<?php $id_joueur_camp 	= $_SESSION["ID_joueur"];
        
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
<div class="bg-dark">
	<div class="container">
   		<div class="row ">
   		 
        <nav class="col navbar navbar-dark  navbar-expand  ">
        <div class="collapse navbar-collapse" id="navbarSupportedContent2">
              <a class="navbar-brand  align-bottom " href="">
              	<img src="../images/accueil/banniere.jpg" class="d-inline-block  align-bottom" loading="lazy" alt='banniere Nord VS Sud' width=150 height=63>
              </a>
              
              	
                </div>
        	</nav>
        	
     	</div>
     	<div class="row">
   		 
        <nav class="col navbar navbar-dark  navbar-expand-lg">
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                    </div>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown05" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Le jeu</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown05">
                      <a class="dropdown-item" href="../regles/regles.php">Règles</a>
                      <a class="dropdown-item" href="../faq.php">FAQ</a>
                    </div>
                  </li>
                 
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">CIM</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown06">
                      <a class="dropdown-item" href="question_anim.php">Question aux anims</a>
                      <a class="dropdown-item" href="capture.php">Déclarer une capture</a>
                      <a class="dropdown-item" href="missions.php">Missions</a>
                    </div>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="visu.php">Visu</a>
                  </li>
                  <!-- Do not show on large screen -->
                  <li class="nav-item dropdown d-lg-none">
                    <a class="nav-link dropdown-toggle" href="<?php 
                      //if we are on local or on prod
                      if($_SERVER["SERVER_NAME"] == "localhost"){
                        echo "view/index.php";
                      }else{
                        echo "";   
                      }?>" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="flag-icon flag-icon-gb"> </span> English</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown09">
                        <a class="dropdown-item" href="<?php 
                      //if we are on local or on prod
                      if($_SERVER["SERVER_NAME"] == "localhost"){
                        echo "view/fr/index.php";
                      }else{
                        echo "fr";   
                      }?>"><span class="flag-icon flag-icon-fr"> </span>  French</a>
                    </div>
            	  </li>
                </ul>
              </div>
        	</nav>
        	
     	</div>
	</div>
</div>