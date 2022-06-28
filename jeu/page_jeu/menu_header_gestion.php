<li class="nav-item dropdown <?= ($nb_demande_a_traiter != null && $nb_demande_a_traiter > 0) ? 'bg-danger' : '' ;?>">
    <a class="nav-link dropdown-toggle" href="#" id="dropdown06" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Gestion</a>
    <div class="dropdown-menu" aria-labelledby="dropdown06">
        <?= 
        //Redacteur
        redac_perso($mysqli, $id_perso) ?  "<a class='dropdown-item' href='redacteur.php'>Rédaction</a>" : ""; 
        ?>
        <?php 
        //Animation
        if(anim_perso($mysqli, $id_perso)) {
            $show_number_of_demand="";
            $nb_demande_a_traiter > 0 ? $show_number_of_demand = "<span class='badge bg-danger'>".$nb_demande_a_traiter."</span>" : "";
            echo "<a class='dropdown-item' href='animation.php'>Animation ".$show_number_of_demand."</a>";
        }
        ?>
        <?= 
        //Admin
        $admin ?  "<a class='dropdown-item' href='admin_nvs.php'>Admin</a>" : ""; 
        ?>
    </div>
</li>