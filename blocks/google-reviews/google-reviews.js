/**
 * Google Reviews Block Scripts
 */
(function($) {
    'use strict';

    /**
     * Initialise le slider Swiper pour les avis Google
     */
    function initGoogleReviewsSlider() {
        // Vérifier si Swiper est disponible
        if (typeof Swiper === 'undefined') {
            console.warn('Swiper not loaded');
            return;
        }

        // Initialiser le slider
        const reviewsSliders = document.querySelectorAll('.google-reviews-slider');

        reviewsSliders.forEach(slider => {
            new Swiper(slider, {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: false,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.right',
                    prevEl: '.left',
                },
                breakpoints: {
                    // Quand la largeur de la fenêtre est >= 768px
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 30
                    },
                    // Quand la largeur de la fenêtre est >= 992px
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 40
                    }
                }
            });
        });
    }

    /**
     * Initialisation de Masonry pour l'affichage en mosaïque
     */
    function initMasonryLayout() {
        // Vérifier si la bibliothèque imagesLoaded est disponible
        if (typeof imagesLoaded === 'undefined') {
            // Fallback si imagesLoaded n'est pas disponible
            const masonryGrids = document.querySelectorAll('.google-reviews-masonry');
            masonryGrids.forEach(grid => {
                const items = grid.querySelectorAll('.review-card');
                items.forEach(item => {
                    item.style.display = 'block';
                });
            });
            return;
        }

        // Attendre que toutes les images soient chargées
        const masonryGrids = document.querySelectorAll('.google-reviews-masonry');
        masonryGrids.forEach(grid => {
            imagesLoaded(grid, function() {
                // Vérifier si Masonry est disponible
                if (typeof Masonry === 'undefined') {
                    return;
                }

                new Masonry(grid, {
                    itemSelector: '.review-card',
                    columnWidth: '.review-card',
                    percentPosition: true
                });
            });
        });
    }

    /**
     * Initialiser tous les blocs Google Reviews
     */
    function initGoogleReviewsBlocks() {
        // Initialiser les sliders
        initGoogleReviewsSlider();

        // Initialiser les layouts Masonry
        initMasonryLayout();
    }

    // Initialiser au chargement de la page
    $(document).ready(function() {
        // Charger Swiper si nécessaire
        if (document.querySelector('.google-reviews-slider') && typeof Swiper === 'undefined') {
            const swiperCss = document.createElement('link');
            swiperCss.rel = 'stylesheet';
            swiperCss.href = 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css';
            document.head.appendChild(swiperCss);

            const swiperJs = document.createElement('script');
            swiperJs.src = 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js';
            swiperJs.onload = function() {
                initGoogleReviewsSlider();
            };
            document.body.appendChild(swiperJs);
        } else {
            initGoogleReviewsSlider();
        }

        // Charger imagesLoaded et Masonry si nécessaire
        if (document.querySelector('.google-reviews-masonry')) {
            if (typeof imagesLoaded === 'undefined') {
                const imagesLoadedJs = document.createElement('script');
                imagesLoadedJs.src = 'https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js';
                document.body.appendChild(imagesLoadedJs);
            }

            if (typeof Masonry === 'undefined') {
                const masonryJs = document.createElement('script');
                masonryJs.src = 'https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js';
                masonryJs.onload = function() {
                    if (typeof imagesLoaded !== 'undefined') {
                        initMasonryLayout();
                    } else {
                        document.addEventListener('DOMContentLoaded', initMasonryLayout);
                    }
                };
                document.body.appendChild(masonryJs);
            } else {
                initMasonryLayout();
            }
        }
    });

    // Réinitialiser les blocs lors de l'édition dans Gutenberg
    if (window.acf) {
        window.acf.addAction('render_block_preview/type=google-reviews', function() {
            initGoogleReviewsBlocks();
        });
    }

})(jQuery);
