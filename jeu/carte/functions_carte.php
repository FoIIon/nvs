<?php



require_once("../../fonctions.php");
if(isset($_POST['function']) && isset($_POST['type'])){
    if(strcmp($_POST["type"], "player") == 0){

        $json_data = file_get_contents('carte_sql.json');
        $sqlPropertiesObj = json_decode($json_data);

        switch($_POST['function']){
            case 'brouillard':{
                header('Content-Type: application/json');
                echo json_encode(getBrouillard(getJsonProperty($sqlPropertiesObj, 'brouillard'), 2));
            }break;
            case 'playersSideCharts' :{
                if(paramsIsSet()){
                    $params = json_decode($_POST['params'], true);//true to return an array
                    header('Content-Type: application/json');
                    echo json_encode(exec_sql_with_max_days(getJsonProperty($sqlPropertiesObj, 'listAllPlayersSideCharts'), $params['activeFor']));
                }
                
            }break;
            case 'playersGrouillotsCharts' :{
                if(paramsIsSet()){
                    $params = json_decode($_POST['params'], true);//true to return an array
                    header('Content-Type: application/json');
                    echo json_encode(exec_sql_with_max_days(getJsonProperty($sqlPropertiesObj, 'listAllPlayersGrouillotCharts'), $params['activeFor']));
                }
                
            }break;
            case 'playersGradeCharts' :{
                if(paramsIsSet()){
                    $params = json_decode($_POST['params'], true);//true to return an array
                    header('Content-Type: application/json');
                    echo json_encode(exec_sql_with_max_days(getJsonProperty($sqlPropertiesObj, 'listAllPlayersGradeCharts'), $params['activeFor']));
                }
                
            }break;
            
            case 'pgPieChart':{
                if(paramsIsSet()){
                    $params = json_decode($_POST['params'], true);//true to return an array
                    header('Content-Type: application/json');
                    echo json_encode(exec_sql_with_max_days(getJsonProperty($sqlPropertiesObj, 'listAllPgCharts'), $params['activeFor']));
                }
            }break;
        }
    }else if(strcmp($_POST["type"], "arme") == 0){

        $json_data = file_get_contents('statistiques_sql.json');
        $sqlPropertiesObj = json_decode($json_data);

        switch($_POST['function']){
            case 'listAll':{
                header('Content-Type: application/json');
                echo json_encode(exec_sql(getJsonProperty($sqlPropertiesObj, 'listAllArmes')));
            }break;
        }
    }
}

//Fonction qui vérifie qu'un paramètre a bien été reçu
function paramsIsSet(){
    if(isset($_POST['params'])){
        return true;
    }else{
        throw new Exception('No parameter received');
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

//Brouillard de guerre
function getBrouillard($sql, $camp){
    $mysqli = db_connexion();
    $stmt = $mysqli->prepare($sql);
    $brouillard_duration = BROUILLARD_DE_GUERRE_S;
    $stmt->bind_param('iiii', $camp, $camp, $camp, $brouillard_duration);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res->fetch_all(MYSQLI_ASSOC);
    
}

//Pour les requêtes qui ne nécessitent pas de paramètres
function exec_sql($sql){
    $mysqli = db_connexion();
    $sql = $sql;
    $res = $mysqli->query($sql);
    return $res->fetch_all(MYSQLI_ASSOC);
}

function log_file($type, $text){
    //Something to write to txt log
    $log  = $type.' '.date("F j, Y, g:i a")." : ".$text.PHP_EOL;
    //Save string to log, use FILE_APPEND to append.
    file_put_contents('log.log', $log, FILE_APPEND);
}