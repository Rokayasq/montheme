// lightbox.js

document.addEventListener('DOMContentLoaded', function() {
    console.log("Script loaded successfully.");
    const figures = document.querySelectorAll('.images .entry-content');
    console.log(figures)
    // const eyeIcon = document.querySelector('.fa-eye');

    // Iterate through each element with the class 'wp-block-image'
    Array.from(figures).forEach(figure => {
        figure.addEventListener('mouseover', () => {
            console.log("Hover ON")

            const eyeIcon = figure.querySelector(".fa-eye");
            eyeIcon.style.opacity = '1';
            eyeIcon.style.display = 'inline-block'
        });

        figure.addEventListener('mouseout', () => {
            console.log("Hover OFF")
            const eyeIcon = figure.querySelector(".fa-eye");
            eyeIcon.style.opacity = '0';
            eyeIcon.style.display = 'none'
        });
    });
});
// Votre code lightbox ici
// LIGHTBOX
class Lightbox {
    static init() {
        const links = document.querySelectorAll('.photo-link');
        links.forEach(link => link.addEventListener('click', e => {
            e.preventDefault();
            const imageUrl = e.currentTarget.querySelector('img').getAttribute('src');
            new Lightbox(imageUrl);
        }));
    }


    /**
 * @param {string} url URL de l'image
 */

constructor(url){
    const element = this.buildDOM(url)
    document.body.appendChild(element)
}

/**
 * @param {string} url URL de l'image
 * @return {HTMLElement}
 */
buildDOM(url) {
    const dom = document.createElement('div')
    dom.classList.add('lightbox')
    dom.innerHTML = `  
        <button class="lightbox__close">Fermer</button>
        <button class="lightbox__next">Suivant</button>
        <button class="lightbox__prev">Précédent</button>
        <div class="lightbox__container">
            <img src="https://picsum.photos/1800/900" alt="">
        </div>
        `;
dom.querySelector('.lightbox__close').addEventListener('click', this.close.bind(this))
dom.querySelector('.lightbox__next').addEventListener('click', this.next.bind(this))
dom.querySelector('.lightbox__prev').addEventListener('click', this.prev.bind(this))
    return dom;
}

}


Lightbox.init()



