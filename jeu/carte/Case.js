
class Case{
    couleur;
    couleur_brouillard;
    constructor(options={}){
        Object.assign(this, options);
        this.setCouleur();
    }

    setTooltipContent(){
       $(canvas).attr('title', this.x + " - " + this.y).css('font-weight', 'bold');;
    }

    draw(canvas, ctx){
        this.setCouleur();
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
        }/*else if(contraintes_batiments_checkbox.checked){
            
        }*/else{
            ctx.fillStyle = grey;
            ctx.fillRect(this.getX(canvas), this.getY(canvas), pixel_size, pixel_size);
        }

    }

    getX(canvas){
        return (this.x*(pixel_size + pixel_distance)+pixel_distance);
    }

    getY(canvas){
        return (canvas.width-this.y*(pixel_size + pixel_distance)-pixel_size);
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
