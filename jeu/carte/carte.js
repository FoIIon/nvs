const pixel_size = 5;
const pixel_distance = 1;

// couleurs perso_carte brouillard
const noir 							= 'rgb(0, 0, 0)'; // noir
const brouillard_general			= noir;
const couleur_vert 					= 'rgb(10, 254, 10)'; // vert bien voyant
const couleur_perso_clan1 			= 'rgb(10, 10, 254)'; // bleu bien voyant
const couleur_perso_clan2 			= 'rgb(254, 10, 10)'; // rouge bien voyant
const couleur_bat_clan1 			= 'rgb(75, 75, 254)'; // bleu batiments
const couleur_bat_clan2 			= 'rgb(254, 75, 75)'; // rouge batiments
const couleur_bat_neutre			= 'rgb(130, 130, 130)'; // gris batiments
const couleur_rail					= 'rgb(200, 200, 200)'; // gris rails
const couleur_brouillard_plaine		= 'rgb(208, 192, 122)'; // Chamois
const couleur_brouillard_eau		= 'rgb(187, 174, 152)'; // Gr�ge
const couleur_brouillard_montagne	= 'rgb(47, 27, 12)'; // Cachou
const couleur_brouillard_colinne	= 'rgb(133, 109, 77)'; // Bistre
const couleur_brouillard_desert		= 'rgb(225, 206, 154)'; // Vanille
const couleur_brouillard_foret		= 'rgb(97, 77, 26)'; // Vanille

// couleurs hors brouillard
const plaine 	                    = 'rgb(129, 156, 84)'; // vert clair
const colline 	                    = 'rgb(96, 110, 70)'; // 
const montagne 	                    = 'rgb(134, 118, 89)'; // marron foncé
const desert 	                    = 'rgb(215, 197, 101)'; // jaune foncé (penchant vers le marron)
const neige 		                = 'rgb(232, 248, 248)'; // blanc
const marecage 	                    = 'rgb(169, 177, 166)'; // gris
const foret 		                = 'rgb(60, 86, 33)'; // vert foncé
const eau 		                    = 'rgb(92, 191, 207)'; // bleu clair
const eau_p 		                = 'rgb(39, 141, 227)'; // bleu foncé

const bataillon = document.getElementById('bataillon');
bataillon.addEventListener('change', (event)=>{
    if (event.currentTarget.checked) {
       // alert('checked');
    } else {
       // alert('not checked');
    }
});
const inputId = document.getElementById('idInput');
const canvas = document.getElementById('map');
const ctx = canvas.getContext('2d');
const image = new Image();
image.onload = drawMap;
image.src = 'carte.png';

var brouillard;
var visible;

function drawMap(){
    canvas.width = 201 * pixel_size + 199 * pixel_distance;
    canvas.height = 201 * pixel_size + 199 * pixel_distance;

    ctx.drawImage(this, 0, 0, this.width, this.height);

    canvas.addEventListener('mousemove', function(e){checkMousePos(canvas, inputId, e);}, false);
    
    //map en noir
    ctx.fillStyle = noir;
    ctx.fillRect((0), (((0))), canvas.width, canvas.height);

    //affichage du brouillard
    getBrouillard();

    //affichage de ce qui est visible
    getHorsBrouillard();

    console.log(bataillon);
    console.log(bataillon.checked);
}

function drawBrouillard(){
    
		
    Object.keys(brouillard).forEach(function(k){
        //console.log(k + ' - ' + data[k]);
        let x 			= brouillard[k]["x_carte"];
		let y 			= brouillard[k]["y_carte"];
		let fond		= brouillard[k]["fond_carte"];
		let couleur_brouillard = "";

		if (fond == '3.gif') {
			// Montagne
			couleur_brouillard = couleur_brouillard_montagne;
		}
		else if (fond == '2.gif') {
			// Colinne
			couleur_brouillard = couleur_brouillard_colinne;
		}
		else if (fond == '4.gif') {
			// Desert
			couleur_brouillard = couleur_brouillard_desert;
		}
		else if (fond == '7.gif') {
			// Foret
			couleur_brouillard = couleur_brouillard_foret;
		}
		else if (fond == '8.gif' || fond == '9.gif' || fond == '6.gif' 
				|| fond == 'b5b.png' || fond == 'b5r.png') {
			// eau ou ponts
			couleur_brouillard = couleur_brouillard_eau;
		}
		else {
			// plaine et autres
			couleur_brouillard = couleur_brouillard_plaine;
		}
		ctx.fillStyle = couleur_brouillard;
        ctx.fillRect(((x*3)-1), (((600-(y*3)))-4), 3, 3);
		//imagefilledrectangle ($perso_carte, (($x*3)-1), (((600-($y*3)))-1), (($x*3)+1), (((600-($y*3)))+1), $couleur_brouillard);
    });
    console.log(brouillard);
    //for 
}

function drawHorsBrouillard(){
    Object.keys(visible).forEach(function(k){
        //console.log(k + ' - ' + data[k]);
        let x 			= visible[k]["x_carte"];
		let y 			= visible[k]["y_carte"];
		let fond		= visible[k]["fond_carte"];
		let couleur = "";

		if (fond == '3.gif') {
			// Montagne
			couleur = montagne;
		}
		else if (fond == '2.gif') {
			// Colinne
			couleur = colline;
		}
		else if (fond == '4.gif') {
			// Desert
			couleur = desert;
		}
        else if (fond == '5.gif') {
			// Neige
			couleur = neige;
		}
        else if (fond == '6.gif') {
			// Marécage
			couleur = marecage;
		}
		else if (fond == '7.gif') {
			// Foret
			couleur = foret;
		}
        else if (fond == 'b5b.png' || fond == 'b5r.png') {
			// pont
			couleur = couleur_bat_neutre;
		}
		else if (fond == '8.gif') {
			// eau 
			couleur = eau;
		}else if(fond == '9.gif'){
            couleur = eau_p;
        }
		else {
			// plaine et autres
			couleur = plaine;
		}
		ctx.fillStyle = couleur;
        ctx.fillRect(((x*3)-1), (((600-(y*3)))-4), 3, 3);
		//imagefilledrectangle ($perso_carte, (($x*3)-1), (((600-($y*3)))-1), (($x*3)+1), (((600-($y*3)))+1), $couleur_brouillard);
    });
}

function getBrouillard(){
    $.ajax({
        method: "POST",
        url: "functions_carte.php",
        data:{
            "function":"brouillard"
        },
        success: function(data){
            brouillard = data;
            drawBrouillard();
        },
        error: function(error_data){
            console.log("Endpoint GET request error");
            console.log(error_data)
        }
    });
}

function getHorsBrouillard(){
    $.ajax({
        method: "POST",
        url: "functions_carte.php",
        data:{
            "function":"hors_brouillard"
        },
        success: function(data){
            visible = data;
            drawHorsBrouillard();
        },
        error: function(error_data){
            console.log("Endpoint GET request error");
            console.log(error_data)
        }
    });
}

function checkMousePos(canvas, inputId, e) {
    
    var x = e.offsetX;
    var y = e.offsetY;
    var pos = [];
    
    pos['x'] 	= Math.floor(x / 3);
    pos['y'] 	= Math.floor((canvas.height-y) / 3);
    pos['xy'] 	= pos['x'] +','+ pos['y'];
    
    inputId.value = pos['xy'];
}