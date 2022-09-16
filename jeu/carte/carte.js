const pixel_size = 5;
const pixel_distance = 1;
const map_size = 201;

// couleurs perso_carte brouillard
const gris_brouillard 				= 'rgb(80, 80, 80)'; // noir
const blanc 				        = 'rgb(255, 255, 255)'; // blanc
const noir 							= 'rgb(0, 0, 0)'; // noir
const grey 							= 'rgb(125, 125, 125)'; // gris
const brouillard_general			= noir;
const couleur_vert 					= 'rgb(10, 254, 10)'; // vert bien voyant
const couleur_perso_clan1 			= 'rgb(10, 10, 254)'; // bleu bien voyant
const couleur_perso_clan2 			= 'rgb(254, 10, 10)'; // rouge bien voyant
const couleur_perso_defaut          = 'rgb(130, 20, 130)';// mauve 
const couleur_bat_clan1 			= 'rgb(75, 75, 254)'; // bleu batiments
const couleur_bat_clan2 			= 'rgb(254, 75, 75)'; // rouge batiments
const couleur_bat_neutre			= 'rgb(130, 130, 130)'; // gris batiments
const couleur_rail					= 'rgb(200, 200, 200)'; // gris rails
const couleur_brouillard_plaine		= 'rgb(208, 192, 122)'; // Chamois
const couleur_brouillard_eau		= 'rgb(187, 174, 152)'; // Gr�ge
const couleur_brouillard_marecage   = 'rgb(175, 176, 118)';
const couleur_brouillard_montagne	= 'rgb(47, 27, 12)'; // Cachou
const couleur_brouillard_colinne	= 'rgb(133, 109, 77)'; // Bistre
const couleur_brouillard_desert		= 'rgb(225, 206, 154)'; // Vanille
const couleur_brouillard_foret		= 'rgb(97, 77, 26)'; // Vanille

// couleurs hors brouillard
const couleur_plaine 	            = 'rgb(129, 156, 84)'; // vert clair
const couleur_colline 	            = 'rgb(96, 110, 70)'; // 
const couleur_montagne 	            = 'rgb(134, 118, 89)'; // marron foncé
const couleur_desert 	            = 'rgb(215, 197, 101)'; // jaune foncé (penchant vers le marron)
const couleur_neige 		        = 'rgb(232, 248, 248)'; // blanc
const couleur_marecage 	            = 'rgb(169, 177, 166)'; // gris
const couleur_foret 		        = 'rgb(60, 86, 33)'; // vert foncé
const couleur_eau 		            = 'rgb(92, 191, 207)'; // bleu clair
const couleur_eau_p 		        = 'rgb(39, 141, 227)'; // bleu foncé


const topographie_checkbox = document.getElementById('topographie');
topographie_checkbox.addEventListener('change', (event)=>{
    mapTiles.forEach(tile =>{
        tile.draw(canvas, ctx);
    });
});
const brouillard_checkbox = document.getElementById('brouillard');
brouillard_checkbox.addEventListener('change', (event)=>{
    mapTiles.forEach(tile =>{
        if(tile.brouillard!=undefined){
            tile.draw(canvas, ctx);
        }
    });
});
const batiments_checkbox = document.getElementById('batiments');
batiments_checkbox.addEventListener('change', (event)=>{
    mapTiles.forEach(tile =>{
        if(tile.batiment!=undefined){
            tile.draw(canvas, ctx);
        }
    });
});
const contraintes_batiments_checkbox = document.getElementById('contraintes_batiments');
contraintes_batiments_checkbox.addEventListener('change', (event)=>{
    mapTiles.forEach(tile =>{
        tile.draw(canvas, ctx);
    });
});
const bataillon_checkbox = document.getElementById('bataillon');
bataillon_checkbox.addEventListener('change', (event)=>{
    //comme le stroke déborde, on redessine le background
    drawBackground();
    mapTiles.forEach(tile =>{
        tile.draw(canvas, ctx);
    });
});
const compagnie_checkbox = document.getElementById('compagnie');
compagnie_checkbox.addEventListener('change', (event)=>{
    //comme le stroke déborde, on redessine le background
    drawBackground();
    mapTiles.forEach(tile =>{
        tile.draw(canvas, ctx);
    });
});

const inputId = document.getElementById('idInput');
const canvas = document.getElementById('map');
const ctx = canvas.getContext('2d');

class Case{
    couleur;
    couleur_brouillard;
    constructor(options={}){
        Object.assign(this, options);
        this.setCouleur();
		
    }

    draw(canvas, ctx){
        this.setCouleur();
        if(this.x==115 && this.y==120){
            console.log(this)
        }
        if(batiments_checkbox.checked && this.batiment != undefined){
            //on utilise l'image
            if(this.batiment.nom == 'Fort' || this.batiment.nom == 'Fortin' || this.batiment.nom == 'Gare' || this.batiment.nom == 'Hopital' || this.batiment.nom == 'Pont'|| this.batiment.nom == 'Train' || this.batiment.nom == 'Pénitencier' || this.batiment.nom == 'Point stratégique'){
                
                let me = this;
                if(this.batiment.nom == 'Point stratégique'){
                    
                    if(this.batiment.camp == 1){
                        this.couleur = couleur_bat_clan1;
                    }else if(this.batiment.camp == 2){
                        this.couleur = couleur_bat_clan2;
                    }else {
                        this.couleur = noir;
                    }
                    ctx.strokeStyle = this.couleur;
                    ctx.lineWidth = pixel_size/2;
                    ctx.strokeRect(this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
                }
                var img = new Image(pixel_size, pixel_size); //  Constructeur HTML5
                img.src = '../../images_perso/'+this.batiment.image;
                img.onload = function(){
                    ctx.drawImage(img, me.getX(canvas), me.getY(canvas), pixel_size, pixel_size);
                };
            }else{
                //on utilise une couleur
                if(this.batiment.camp == 1){
                    this.couleur = couleur_bat_clan1;
                }else if(this.batiment.camp == 2){
                    this.couleur = couleur_bat_clan2;
                }else {
                    this.couleur = couleur_bat_neutre;
                }
                ctx.fillStyle = this.couleur;
                ctx.fillRect(this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
            }
            
            if(this.joueur != undefined && compagnie_checkbox.checked){
                if (this.joueur.some(e => e.compagnie != undefined)) {
                    /* this.joueur contains the element we're looking for */
                    ctx.strokeStyle = blanc;
                    ctx.lineWidth = pixel_size/2;
                    ctx.strokeRect(this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
                }
            }
            if(this.joueur != undefined && bataillon_checkbox.checked){
                if (this.joueur.some(e => e.bataillon != undefined)) {
                    /* this.joueur contains the element we're looking for */
                    ctx.strokeStyle = 'orange';
                    ctx.lineWidth = pixel_size/2;
                    ctx.strokeRect(this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
                }
            }
        }else if(this.joueur != undefined && !Array.isArray(this.joueur)){
            if(this.joueur.camp == 1){
                this.couleur = couleur_perso_clan1;
            }else if(this.joueur.camp == 2){
                this.couleur = couleur_perso_clan2;
            }else {
                this.couleur = couleur_perso_defaut;
            }
            if(compagnie_checkbox.checked && this.joueur.compagnie != undefined){
                
                ctx.strokeStyle = blanc;
                ctx.lineWidth = pixel_size/2;
                ctx.strokeRect(this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
            }
            if(bataillon_checkbox.checked && this.joueur.bataillon != undefined){
                ctx.strokeStyle = 'orange';
                ctx.lineWidth = pixel_size/2;
                ctx.strokeRect(this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
            }
            ctx.fillStyle = this.couleur;
            ctx.lineWidth = pixel_size/2;
            ctx.fillRect(this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
            /*var img = new Image(pixel_size, pixel_size); //  Constructeur HTML5
            img.src = '../../images_perso/'+this.joueur.image;
            let x=this.x;
            let y=this.y;
            img.onload = function(){
                ctx.drawImage(img, this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
            };*/
            
        }else if(this.pnj != undefined){
            /*var img = new Image(pixel_size, pixel_size); //  Constructeur HTML5
            img.src = '../../images/pnj/'+this.pnj.image;
            let x=this.x;
            let y=this.y;
            img.onload = function(){
                ctx.drawImage(img, this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
            };*/
            ctx.fillStyle = noir;
            ctx.fillRect(this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
        }else if(this.brouillard != undefined && this.brouillard.valeur == 1 && brouillard_checkbox.checked){
            if(topographie.checked){
                ctx.fillStyle = this.couleur_brouillard;
            }else{
                ctx.fillStyle = couleur_brouillard_plaine;
            }
            ctx.fillRect(this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
        }else if(topographie_checkbox.checked){
            ctx.fillStyle = this.couleur;
            ctx.fillRect(this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
        }else if(contraintes_batiments_checkbox.checked){
            
        }else{
            ctx.fillStyle = grey;
            ctx.fillRect(this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
        }

    }

    getX(canvas){
        return (this.x*(pixel_size + pixel_distance));
    }

    getY(canvas){
        return (canvas.width-this.y*(pixel_size + pixel_distance));
    }

    setCouleur(){
        if (this.fond == '3.gif') {
			// Montagne
            this.couleur             = couleur_montagne;
            this.couleur_brouillard  = couleur_brouillard_montagne;
		}
		else if (this.fond == '2.gif') {
			// Colinne
            this.couleur             = couleur_colline;
			this.couleur_brouillard  = couleur_brouillard_colinne;
		}
		else if (this.fond == '4.gif') {
			// Desert
            this.couleur             = couleur_desert;
			this.couleur_brouillard  = couleur_brouillard_desert;
		}
		else if (this.fond == '6.gif') {
			// marécage
            this.couleur             = couleur_marecage;
			this.couleur_brouillard  = couleur_brouillard_marecage;
		}
		else if (this.fond == '7.gif') {
			// Foret
            this.couleur             = couleur_foret;
			this.couleur_brouillard  = couleur_brouillard_foret;
		}
        else if (this.fond == 'b5b.png' || this.fond == 'b5r.png' || this.fond == 'b5g.png') {
			// pont
			this.couleur             = couleur_bat_neutre;
            this.couleur_brouillard  = couleur_brouillard_eau;
		}
		else if (this.fond == '8.gif') {
			// eau 
			this.couleur             = couleur_eau;
            this.couleur_brouillard  = couleur_brouillard_eau;
		}else if(this.fond == '9.gif'){
            this.couleur             = couleur_eau_p;
            this.couleur_brouillard  = couleur_brouillard_eau;
        }else if(this.fond.includes('rail')){
            this.couleur             = couleur_rail;
            this.couleur_brouillard  = couleur_brouillard_plaine;
        }else {
			// plaine et autres
			this.couleur             = couleur_plaine;
            this.couleur_brouillard  = couleur_brouillard_plaine;
		}
    }
}


var map;
var mapTiles=[];

get_map();

function drawMap(){
    canvas.width = map_size * pixel_size + (map_size - 2) * pixel_distance;
    canvas.height = map_size * pixel_size + (map_size - 2) * pixel_distance;


    drawBackground();
    
    canvas.addEventListener('mousemove', function(e){checkMousePos(canvas, inputId, e);}, false);
    //affichage
    Object.keys(map).forEach(function(k){

        let tile = new Case(map[k]);
        mapTiles.push(tile);


		tile.draw(canvas, ctx);
        
    });

    map="";
}

function drawBackground(){
    //map en gris_brouillard
    ctx.fillStyle = gris_brouillard;
    ctx.fillRect((0), (0), canvas.width, canvas.height);
}
/*
function drawStar(ctx, centerX,centerY,arms,innerRadius,outerRadius,startAngle,fillStyle,strokeStyle,lineWidth) {
    startAngle = startAngle * Math.PI / 180  || 0;
    var step = Math.PI / arms,
        angle = startAngle
        ,hyp,x,y;
    ctx.strokeStyle = strokeStyle;
    ctx.fillStyle = fillStyle;
    ctx.lineWidth = lineWidth;
    ctx.beginPath();
    for (var i =0,len= 2 * arms; i <len; i++) {
      hyp = i & 1 ? innerRadius : outerRadius;
      x = centerX + Math.cos(angle) * hyp;
      y = centerY +Math.sin(angle) * hyp;
      angle+=step;
      ctx.lineTo(x, y);
    }
    ctx.closePath();
    fillStyle && ctx.fill();
    strokeStyle && ctx.stroke();
  }
*/
function get_map(){
    $.ajax({
        method: "POST",
        url: "functions_carte.php",
        data:{
            "function":"get_map"
        },
        success: function(data){
            map = data;
            drawMap();
        },
        error: function(error_data){
            console.log("Endpoint request error");
            console.log(error_data)
        }
    });
}


function checkMousePos(canvas, inputId, e) {
    
    var x = e.offsetX;
    var y = e.offsetY;
    var pos = [];
    pos['x'] 	= Math.floor(x/(pixel_size + pixel_distance));
    pos['y'] 	= Math.floor((canvas.width-y)/(pixel_size + pixel_distance));
    pos['xy'] 	= pos['x'] +','+ pos['y'];
    
    inputId.value = pos['xy'];
}