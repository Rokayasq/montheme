function add_eye_icon() {
    const figures = document.querySelectorAll('.images .entry-content');

    // Iterate through each element with the class 'wp-block-image'
    Array.from(figures).forEach(figure => {
        figure.addEventListener('mouseover', () => {
            const eyeIcon = figure.querySelector(".fa-eye");
            eyeIcon.style.opacity = '1';
            eyeIcon.style.display = 'inline-block'
        });

        figure.addEventListener('mouseout', () => {
            const eyeIcon = figure.querySelector(".fa-eye");
            eyeIcon.style.opacity = '0';
            eyeIcon.style.display = 'none'
        });
    }
);}

function add_full_screen() {
    const figures = document.querySelectorAll('.images .entry-content');

    Array.from(figures).forEach(figure => {
        figure.addEventListener('mouseover', () => {
            const fullIcon = figure.querySelector(".fa-solid");
            fullIcon.style.opacity = '1';
            fullIcon.style.display = 'inline-block'
        });

        figure.addEventListener('mouseout', () => {
            const fullIcon = figure.querySelector(".fa-solid");
            fullIcon.style.opacity = '0';
            fullIcon.style.display = 'none'
        });
    });
}

function add_cat_ref() {
    const figures = document.querySelectorAll('.images .entry-content');

    Array.from(figures).forEach(figure => {
        figure.addEventListener('mouseover', () => {
            const CatRefIcon = figure.querySelector(".cat-ref");
            CatRefIcon.style.opacity = '1';
            CatRefIcon.style.display = 'block'
        });

        figure.addEventListener('mouseout', () => {
            const CatRefIcon = figure.querySelector(".cat-ref");
            CatRefIcon.style.opacity = '0';
            CatRefIcon.style.display = 'none'
        });
    }
);}

// Affichage de l'icone oeil
document.addEventListener('DOMContentLoaded', add_eye_icon);

// affichage de l'icone full screen
document.addEventListener('DOMContentLoaded', add_full_screen) ;

// affichage Cat & Ref
document.addEventListener('DOMContentLoaded', add_cat_ref) ;

// LIGHTBOX

/**
 * @property {HTMLElement} element
 * @property {string[]} images Chemins des images de la lighbox
 * @property {string} url image actuellement affichée
*/
class Lightbox {
    static init() {
        console.log("INIT INSIDE")
        var lightbox_initialized = false;
        document.addEventListener('DOMContentLoaded', () => {
            console.log("Init the lighbot for the first time")
            this.init_lightbox(".images")
            lightbox_initialized = true
        });

        if (lightbox_initialized == false){
            console.log("Clicked on Charger plus !!! ")
            this.init_lightbox(".photos");
        }
        
    }

    static init_lightbox(image_group_class){
        const links =Array.from(document.querySelectorAll(image_group_class+' .entry-content .fa-solid'));  
            const images =Array.from(document.querySelectorAll(image_group_class+' .entry-content > figure > img'));   
            console.log("images",images) 
            const gallery= images.map( link=> link.getAttribute('src'))   
            console.log("gallery",gallery)     
            links.forEach(link => {
                console.log(link)
                link.addEventListener('click', e => {
                    e.preventDefault();
                    console.log(e.currentTarget.querySelector("img"))
                    const Url = e.currentTarget.parentElement.parentElement.querySelector("img").getAttribute('src');
                    new Lightbox(Url, gallery);
                    console.log(Url)
                })
            });
    }

    /**
     * @param {string} url URL de l'image
     */
    constructor(url, images){
        this.element = this.buildDOM(url)
        this.images = images
        document.body.appendChild(this.element)
    }

    /**
     * @param {string} url URL de l'image
     */
    loadImage (url) {
        this.url = null
        const image = new Image()
        const container = this.element.querySelector('.lightbox__container')
        const loader = document.createElement('div')
        loader.classList.add('lightbox__loader')
        container.innerHTML = ''
        container.appendChild(loader)
        image.onload = () => {
        container.removeChild(loader)
        container.appendChild(image)
        this.url = url
        }
        image.src = url
    }

    /**
     * Ferme la lightbox
     * @param {MouseEvent} e
     */
    close (e) {
        console.log('closing')
        e.preventDefault()
        // const element = document.querySelector('.lightbox')
        this.element.classList.add('fadeOut')
        window.setTimeout(() => {
            this.element.parentElement.removeChild(this.element)
        },500)
    }

     /**
     * @param {MouseEvent} e
     */
    next (e) {
        e.preventDefault()
        let i = this.images.findIndex(image => image === this.url)
        console.log("Current Image", i, this.images[i])
        if (i === this.images.length - 1) {
          i = -1
        }
        console.log("Current Image", i, this.images[i])
        console.log("Loading Image",i+1, this.images[i + 1])
        this.loadImage(this.images[i + 1])
    }

    /**
     * @param {MouseEvent} e
     */
    prev  (e) {
        e.preventDefault()
        let i = this.images.findIndex(image => image === this.url)
        if (i === 0) {
          i = this.images.length
        }
        this.loadImage(this.images[i - 1])
    }

    /**
     * @param {string} url URL de l'image
     * @return {HTMLElement}
     */
    buildDOM(url) { 
        console.log("Biulding Dom")        
        const dom = document.createElement('div');
        console.log("Add Lightbox")
        dom.classList.add('lightbox');
        console.log("Change inner HTML")
        dom.innerHTML = `
            <button class="lightbox__close">Fermer</button>
            <button class="lightbox__next">Suivant</button>
            <button class="lightbox__prev">Précédent</button>
            <div class="lightbox__container">
                <img src="${url}" alt="">
            </div>
        `;
        dom.querySelector('.lightbox__close').addEventListener('click', this.close.bind(this));
        console.log("Add Event Listeners 2")
        dom.querySelector('.lightbox__next').addEventListener('click', this.next.bind(this));
        console.log("Add Event Listeners 3")
        dom.querySelector('.lightbox__prev').addEventListener('click', this.prev.bind(this));
        console.log("Return dom")
        this.url = url
        return dom;
    }
}

Lightbox.init()



