/**
 * CTA Animation and Interaction
 *
 * Gestion des animations et interactions pour le CTA personnalisé
 */

document.addEventListener('DOMContentLoaded', function() {

    // Animation au scroll pour le CTA
    function initCTAScrollAnimation() {
        const ctaElements = document.querySelectorAll('.abyssenergy-cta');

        if (ctaElements.length === 0) return;

        // Vérifier si les animations sont activées
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            ctaElements.forEach(cta => cta.classList.add('is-visible'));
            return;
        }

        // Observer pour les animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '50px 0px -50px 0px'
        });

        ctaElements.forEach(cta => {
            observer.observe(cta);
        });
    }

    // Preview en temps réel dans le Customizer
    function initCustomizerPreview() {
        if (typeof wp !== 'undefined' && wp.customize) {

            // Titre
            wp.customize('abyssenergy_cta_title', function(value) {
                value.bind(function(newValue) {
                    const titleElement = document.querySelector('.abyssenergy-cta__title');
                    if (titleElement) {
                        titleElement.textContent = newValue;
                    }
                });
            });

            // Sous-titre
            wp.customize('abyssenergy_cta_subtitle', function(value) {
                value.bind(function(newValue) {
                    const subtitleElement = document.querySelector('.abyssenergy-cta__subtitle');
                    if (subtitleElement) {
                        subtitleElement.textContent = newValue;
                    }
                });
            });

            // Texte du bouton principal
            wp.customize('abyssenergy_cta_button_text', function(value) {
                value.bind(function(newValue) {
                    const buttonElement = document.querySelector('.abyssenergy-cta__button');
                    if (buttonElement) {
                        buttonElement.textContent = newValue;
                    }
                });
            });

            // Texte du bouton secondaire
            wp.customize('abyssenergy_cta_secondary_text', function(value) {
                value.bind(function(newValue) {
                    const secondaryElement = document.querySelector('.abyssenergy-cta__secondary');
                    if (secondaryElement) {
                        secondaryElement.textContent = newValue;
                    }
                });
            });
        }
    }

    // Analytics et tracking (optionnel)
    function initCTATracking() {
        const ctaButtons = document.querySelectorAll('.abyssenergy-cta__button, .abyssenergy-cta__secondary');

        ctaButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Google Analytics 4
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'cta_click', {
                        'cta_text': this.textContent.trim(),
                        'cta_url': this.href,
                        'cta_type': this.classList.contains('abyssenergy-cta__button') ? 'primary' : 'secondary'
                    });
                }

                // Google Tag Manager
                if (typeof dataLayer !== 'undefined') {
                    dataLayer.push({
                        'event': 'cta_click',
                        'cta_text': this.textContent.trim(),
                        'cta_url': this.href,
                        'cta_type': this.classList.contains('abyssenergy-cta__button') ? 'primary' : 'secondary'
                    });
                }

                // Console log pour le développement
                console.log('CTA clicked:', {
                    text: this.textContent.trim(),
                    url: this.href,
                    type: this.classList.contains('abyssenergy-cta__button') ? 'primary' : 'secondary'
                });
            });
        });
    }

    // Effet parallaxe subtil (optionnel)
    function initCTAParallax() {
        const ctaElements = document.querySelectorAll('.abyssenergy-cta');

        if (ctaElements.length === 0) return;

        // Vérifier si les animations sont activées
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        function updateParallax() {
            ctaElements.forEach(cta => {
                const rect = cta.getBoundingClientRect();
                const isVisible = rect.top < window.innerHeight && rect.bottom > 0;

                if (isVisible) {
                    const scrolled = window.pageYOffset;
                    const rate = scrolled * -0.1;
                    const ctaBackground = cta.querySelector('::before');

                    if (ctaBackground) {
                        cta.style.transform = `translate3d(0, ${rate}px, 0)`;
                    }
                }
            });
        }

        // Throttle pour les performances
        let ticking = false;
        function requestTick() {
            if (!ticking) {
                requestAnimationFrame(updateParallax);
                ticking = true;
                setTimeout(() => ticking = false, 16); // ~60fps
            }
        }

        window.addEventListener('scroll', requestTick, { passive: true });
    }

    // Initialisation
    initCTAScrollAnimation();
    initCustomizerPreview();
    initCTATracking();
    // initCTAParallax(); // Décommentez pour activer l'effet parallaxe
});

// Export pour usage externe si nécessaire
window.abyssenergyCTA = {
    init: function() {
        // Réinitialiser les animations après changement de contenu
        const event = new CustomEvent('abyssenergy:cta:init');
        document.dispatchEvent(event);
    }
};
