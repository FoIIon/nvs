<?php
session_start();
require_once("../fonctions.php");

$mysqli = db_connexion();

include ('../nb_online.php');

if(isset($_SESSION["id_perso"])){
	
	$id_perso = $_SESSION['id_perso'];
	
	// recupération config jeu
	$admin = admin_perso($mysqli, $id_perso);
	
	if($admin){
		
		$mess_err 	= "";
		$mess 		= "";
		
		if(isset($_POST['acces_perso']) && $_POST['acces_perso'] != '') {
			
			$id_perso_a_acces = $_POST['acces_perso'];
			
		}
		
		if (isset($_POST['id_perso_acces_hid']) && isset($_POST['type_acces'])) {
			
			$id_perso_acces = $_POST['id_perso_acces_hid'];
			$type_acces		= $_POST['type_acces'];
			
			$sql = "SELECT idJoueur_perso, clan FROM perso WHERE id_perso='$id_perso_acces'";
			$res = $mysqli->query($sql);
			$t = $res->fetch_assoc();
			
			$id_joueur 		= $t['idJoueur_perso'];
			$camp_joueur	= $t['clan'];
			
			if ($type_acces == 'em') {
				$sql = "INSERT INTO perso_in_em (id_perso, camp_em) VALUES ('$id_perso_acces', '$camp_joueur')";
			}
			else if ($type_acces == 'anim') {
				$sql = "UPDATE joueur SET animateur='1' WHERE id_joueur='$id_joueur'";
			}
			else if ($type_acces == 'redac') {
				$sql = "UPDATE joueur SET redacteur='1' WHERE id_joueur='$id_joueur'";
			}
			
			$mysqli->query($sql);
		}
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Nord VS Sud</title>
		
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	</head>
	
	<body>
		<div class="container-fluid">
		
			<div class="row">
				<div class="col-12">

					<div align="center">
						<h2>Administration</h2>
					</div>
				</div>
			</div>
			
			<p align="center"><a class="btn btn-primary" href="jouer.php">Retour au jeu</a></p>
			
			<div class="row">
				<div class="col-12">
				
					<h3>Donner des accès à un perso</h3>
					
					<center><font color='red'><?php echo $mess_err; ?></font></center>
					<center><font color='blue'><?php echo $mess; ?></font></center>
					
					<form method='POST' action='admin_acces.php'>
					
						<select name="acces_perso">
						
							<?php
							$sql = "SELECT id_perso, nom_perso, x_perso, y_perso FROM perso ORDER BY id_perso ASC";
							$res = $mysqli->query($sql);
							
							while ($t = $res->fetch_assoc()) {
								
								$id_perso 	= $t["id_perso"];
								$nom_perso 	= $t["nom_perso"];
								$x_perso	= $t["x_perso"];
								$y_perso 	= $t["y_perso"];
								
								echo "<option value='".$id_perso."'>".$nom_perso." [".$id_perso."]</option>";
							}
							?>
						
						</select>
						
						<input type="submit" value="choisir">
						
					</form>
					
					<?php
					if (isset($id_perso_a_acces) && $id_perso_a_acces != 0) {
						
						echo "<form method='POST' action='admin_acces.php'>";
						echo "	<input type='text' value='".$id_perso_a_acces."' name='id_perso_acces' disabled>";
						echo "	<input type='hidden' value='".$id_perso_a_acces."' name='id_perso_acces_hid'>";
						echo "	<select name='type_acces'>";
						echo "		<option value='em'>Etat Major</option>";
						echo "		<option value='anim'>Animation</option>";
						echo "		<option value='redac'>Redacteur</option>";
						echo "	</select>";
						echo "	<input type='submit' value='Donner acces'>";
						echo "</form>";
					}
					?>
				</div>
			</div>
			
		</div>
		
		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</body>
</html>
<?php
	}
	else {
		// logout
		$_SESSION = array(); // On écrase le tableau de session
		session_destroy(); // On détruit la session
		
		header("Location:../index2.php");
	}
}
else{
	echo "<font color=red>Vous ne pouvez pas accéder à cette page, veuillez vous loguer.</font>";
}
?>