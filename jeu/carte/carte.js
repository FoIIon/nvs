// couleurs perso_carte
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

function drawMap(){
    canvas.width = 603;
    canvas.height = 603;

    ctx.drawImage(this, 0, 0, this.width, this.height);

    canvas.addEventListener('mousemove', function(e){checkMousePos(canvas, inputId, e);}, false);
    getBrouillard();
    console.log(bataillon);
    console.log(bataillon.checked);
}

function drawBrouillard(data){
    Object.keys(data).forEach(function(k){
        //console.log(k + ' - ' + data[k]);
        let x 			= data[k]["x_carte"];
		let y 			= data[k]["y_carte"];
		let fond		= data[k]["fond_carte"];
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
    console.log(data);
    //for 
}

function getBrouillard(){
    $.ajax({
        method: "POST",
        url: "functions_carte.php",
        data:{
            "type" :"player",
            "function":"brouillard"
        },
        success: drawBrouillard,
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