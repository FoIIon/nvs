<?php
session_start();
require_once("../../fonctions.php");

$mysqli = db_connexion();

$joueur = new StdClass();
$sqlPropertiesObj;

if(isset($_SESSION["id_perso"])){
    
    $id_perso = $_SESSION['id_perso'];
	
	// recuperation de l'id et du clan du chef
	$sql = "SELECT p.id_perso, p.clan, p.bataillon, comp.nom_compagnie FROM perso p LEFT JOIN perso_in_compagnie pic ON pic.id_perso = p.id_perso LEFT JOIN compagnies comp ON comp.id_compagnie = pic.id_compagnie WHERE p.id_perso=$id_perso";
	$res = $mysqli->query($sql);
	$t_chef = $res->fetch_assoc();
	
	$joueur->id 		= $t_chef["id_perso"];
	$joueur->clan 		= $t_chef["clan"];
	$joueur->compagnie 	= $t_chef["nom_compagnie"];
    $joueur->bataillon  = $t_chef["bataillon"];

    
}else{
//gerer non connecte    
   // exit();
    $joueur->id 		= 2;
	$joueur->clan 		= 2;
    $joueur->compagnie 	= 'TIG-RES';
    $joueur->bataillon  = 'Général du Sud';
}

if(isset($_POST['function'])){

    $json_data = file_get_contents('carte_sql.json');
    $sqlPropertiesObj = json_decode($json_data);

    switch($_POST['function']){
        case 'get_map':{
            header('Content-Type: application/json');
            $json_map = get_json_map($sqlPropertiesObj, $joueur);
            echo $json_map;
            break;
        }
    }
}

//Fonction qui récupère le sql du fichier json
function getJsonProperty($json, $property){
    if(isset($json->$property)){
        return $json->$property;
    }else{
        throw new Exception("Property : " . $property . " does not exist");
    }
}

//fonction qui créé le json de la map
function get_json_map($sqlPropertiesObj, $joueur){
    $sql_clan = get_sql_clan($joueur);
    $carte_array = array();
    $cases_deja_vues = get_cases_deja_vues(getJsonProperty($sqlPropertiesObj, 'cases_deja_vues').$sql_clan);
    
    foreach ($cases_deja_vues as $case) {
        
        $carte_array[$case['id']]=array(
            'x'     =>  $case["x_carte"],
            'y'     =>  $case["y_carte"],
            'fond'  =>  $case["fond_carte"]
        );
    }
    $brouillard = get_brouillard(getJsonProperty($sqlPropertiesObj, 'brouillard'), $joueur->clan);
    foreach ($brouillard as $case) {
        $carte_array[$case['id']]['brouillard']=array(
            'valeur'=>  '1'
        );
    }
    
    $visible = get_visibles(getJsonProperty($sqlPropertiesObj, 'visible').$sql_clan, $joueur->clan);
    foreach ($visible as $case) {
        if ($case["idPerso_carte"] < 50000 && $case["idPerso_carte"] > 0){
            if($joueur->clan == $case["clan"]){
                $carte_array[$case['id']]['joueur']=array(
                    'id'        => $case["idPerso_carte"],
                    'image'     => $case["image_carte"],
                    'camp'      => $case["clan"]         
                );
                if($joueur->bataillon == $case["bataillon"]){
                    $carte_array[$case['id']]['joueur']['bataillon'] = trim($case["bataillon"]);
                }
                if(isSet($case["nom_compagnie"]) && $joueur->compagnie == $case["nom_compagnie"]){
                    $carte_array[$case['id']]['joueur']['compagnie'] = trim($case["nom_compagnie"]);
                }
            }else{
                $carte_array[$case['id']]['joueur']=array(
                    'camp'  =>  $case["clan"]
                );
            }
        }else if ($case["idPerso_carte"] >= 200000){
            $carte_array[$case['id']]['pnj']=array(
                'id'    =>  $case["idPerso_carte"],
                'image' =>  $case["image_carte"]
            );
        }else if ($case["idPerso_carte"] < 200000 && $case["idPerso_carte"] >= 50000){
            $image;
            if($case["nom_batiment"] == 'Pont'){
                $image = $case["fond_carte"];
            }else{
                $image = $case["image_carte"];
            }
            $carte_array[$case['id']]['batiment']=array(
                'id'        =>  $case["idPerso_carte"],
                'image'     =>  $image,
                'camp'      =>  $case["camp_instance"],
                'nom'       =>  $case["nom_batiment"]
            );
        }
    }

    $persos_in_batiment = get_persos_in_batiments(getJsonProperty($sqlPropertiesObj, 'persos_dans_batiments'), $joueur->clan);
    foreach ($persos_in_batiment as $case) {
        $case_joueur = array(
            'id'        => $case["id_perso"],
            'camp'      => $case["clan"]         
        );
        if($joueur->bataillon == $case["bataillon"]){
            $case_joueur["bataillon"]=trim($case["bataillon"]);
        }
        if(isSet($case["nom_compagnie"]) && $joueur->compagnie == $case["nom_compagnie"]){
            $case_joueur["compagnie"] = trim($case["nom_compagnie"]);
        }
        
        $carte_array[$case['id']]['joueur'][]=$case_joueur;
        
    }
    return json_encode($carte_array);
}



//bout de sql à ajouter aux requetes en fonction du clan du joueur
function get_sql_clan($joueur){
    
    if($joueur->clan == '1'){
        return ' vue_nord = 1';
    }else if ($joueur->clan == '2'){
        return ' vue_sud = 1';
    }
    return '';
}

//Brouillard de guerre
function get_brouillard($sql, $camp){
    $mysqli = db_connexion();
    $stmt = $mysqli->prepare($sql);
    $brouillard_duration = BROUILLARD_DE_GUERRE_S;
    $stmt->bind_param('iiii', $camp, $camp, $camp, $brouillard_duration);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
    
}

//Visibles
function get_visibles($sql, $camp){
    $mysqli = db_connexion();
    $stmt = $mysqli->prepare($sql);
    $brouillard_duration = BROUILLARD_DE_GUERRE_S;
    $stmt->bind_param('iiii', $camp, $camp, $camp, $brouillard_duration);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
    
}

//case déjà vues
function get_cases_deja_vues($sql){
    $mysqli = db_connexion();
    $res = $mysqli->query($sql);
    return $res->fetch_all(MYSQLI_ASSOC);
    
}

//persos du meme camp dans les batiments
function get_persos_in_batiments($sql, $camp){
    $mysqli = db_connexion();
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $camp);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
    
}