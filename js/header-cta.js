/**
 * Header CTA Management
 *
 * Gestion du CTA dans le header avec injection intelligente et preview en temps réel
 */

class HeaderCTA {
    constructor() {
        this.ctaElement = null;
        this.headerContainer = null;
        this.isInjected = false;

        this.init();
    }

    init() {
        // Attendre que le DOM soit chargé
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        this.findHeaderContainer();
        this.injectCTA();
        this.initCustomizerPreview();
        this.initAccessibility();
        this.initAnalytics();
    }

    findHeaderContainer() {
        // Chercher le conteneur de header dans l'ordre de priorité
        const selectors = [
            '.header .header-container',
            '.site-header .container',
            '.header-container',
            '.site-header',
            '.header',
            'header'
        ];

        for (const selector of selectors) {
            this.headerContainer = document.querySelector(selector);
            if (this.headerContainer) {
                console.log('Header container found:', selector);
                break;
            }
        }

        if (!this.headerContainer) {
            console.warn('Header container not found for CTA injection');
        }
    }

    injectCTA() {
        if (!this.headerContainer || this.isInjected) return;

        // Récupérer le HTML du CTA depuis le script PHP
        const ctaData = this.getCTAData();
        if (!ctaData) return;

        // Créer l'élément CTA
        this.createCTAElement(ctaData);

        // Trouver la meilleure position pour l'injection
        this.findBestInsertionPoint();

        // Marquer comme injecté
        this.isInjected = true;
        this.headerContainer.classList.add('has-cta');

        // Déclencher un événement personnalisé
        document.dispatchEvent(new CustomEvent('headerCTA:injected', {
            detail: { element: this.ctaElement }
        }));
    }

    getCTAData() {
        // Récupérer les données depuis les variables localisées WordPress
        if (typeof squarechilliHeaderCTA !== 'undefined') {
            return squarechilliHeaderCTA;
        }

        // Fallback: chercher dans un élément script
        const dataScript = document.querySelector('script[data-header-cta]');
        if (dataScript) {
            try {
                return JSON.parse(dataScript.textContent);
            } catch (e) {
                console.error('Error parsing CTA data:', e);
            }
        }

        return null;
    }

    createCTAElement(data) {
        const container = document.createElement('div');
        container.className = 'header-cta';

        const button = document.createElement('a');
        button.href = data.url || '#';
        button.className = this.buildButtonClasses(data);

        if (data.target) {
            button.target = '_blank';
            button.rel = 'noopener noreferrer';
        }

        // Ajouter l'icône si présente
        if (data.icon) {
            const iconSpan = document.createElement('span');
            iconSpan.className = 'header-cta-btn__icon';
            iconSpan.innerHTML = data.icon;
            button.appendChild(iconSpan);
        }

        // Ajouter le texte
        const textSpan = document.createElement('span');
        textSpan.className = 'header-cta-btn__text';
        textSpan.textContent = data.text || 'Nous contacter';
        button.appendChild(textSpan);

        container.appendChild(button);
        this.ctaElement = container;
    }

    buildButtonClasses(data) {
        const classes = [
            'header-cta-btn',
            'btn',
            `btn--${data.style || 'primary'}`,
            `btn--${data.size || 'medium'}`
        ];

        if (data.hide_mobile) {
            classes.push('header-cta-btn--hide-mobile');
        }

        return classes.join(' ');
    }

    findBestInsertionPoint() {
        if (!this.ctaElement || !this.headerContainer) return;

        // Stratégies d'insertion par ordre de priorité
        const strategies = [
            () => this.insertAfterMenu(),
            () => this.insertAtEnd(),
            () => this.insertBeforeMenu()
        ];

        for (const strategy of strategies) {
            if (strategy()) {
                console.log('CTA inserted using strategy:', strategy.name);
                break;
            }
        }
    }

    insertAfterMenu() {
        const menuSelectors = [
            '.main-menu',
            '.primary-menu',
            'nav',
            '.navigation',
            '.menu'
        ];

        for (const selector of menuSelectors) {
            const menu = this.headerContainer.querySelector(selector);
            if (menu && menu.parentNode === this.headerContainer) {
                menu.parentNode.insertBefore(this.ctaElement, menu.nextSibling);
                return true;
            }
        }
        return false;
    }

    insertAtEnd() {
        if (this.headerContainer) {
            this.headerContainer.appendChild(this.ctaElement);
            return true;
        }
        return false;
    }

    insertBeforeMenu() {
        const menuSelectors = [
            '.main-menu',
            '.primary-menu',
            'nav'
        ];

        for (const selector of menuSelectors) {
            const menu = this.headerContainer.querySelector(selector);
            if (menu && menu.parentNode === this.headerContainer) {
                menu.parentNode.insertBefore(this.ctaElement, menu);
                return true;
            }
        }
        return false;
    }

    initCustomizerPreview() {
        if (typeof wp === 'undefined' || !wp.customize) return;

        // Texte du bouton
        wp.customize('squarechilli_header_cta_text', (value) => {
            value.bind((newValue) => {
                const textElement = this.ctaElement?.querySelector('.header-cta-btn__text');
                if (textElement) {
                    textElement.textContent = newValue;
                }
            });
        });

        // Style du bouton
        wp.customize('squarechilli_header_cta_style', (value) => {
            value.bind((newValue) => {
                const button = this.ctaElement?.querySelector('.header-cta-btn');
                if (button) {
                    // Supprimer les anciennes classes de style
                    button.classList.remove('btn--primary', 'btn--secondary', 'btn--outline', 'btn--ghost');
                    // Ajouter la nouvelle classe
                    button.classList.add(`btn--${newValue}`);
                }
            });
        });

        // Taille du bouton
        wp.customize('squarechilli_header_cta_size', (value) => {
            value.bind((newValue) => {
                const button = this.ctaElement?.querySelector('.header-cta-btn');
                if (button) {
                    button.classList.remove('btn--small', 'btn--medium', 'btn--large');
                    button.classList.add(`btn--${newValue}`);
                }
            });
        });

        // Activation/désactivation
        wp.customize('squarechilli_header_cta_enabled', (value) => {
            value.bind((newValue) => {
                if (newValue && !this.isInjected) {
                    this.injectCTA();
                } else if (!newValue && this.isInjected) {
                    this.removeCTA();
                }
            });
        });
    }

    initAccessibility() {
        if (!this.ctaElement) return;

        const button = this.ctaElement.querySelector('.header-cta-btn');
        if (button) {
            // Améliorer l'accessibilité
            button.setAttribute('role', 'button');

            // Navigation au clavier
            button.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    button.click();
                }
            });
        }
    }

    initAnalytics() {
        if (!this.ctaElement) return;

        const button = this.ctaElement.querySelector('.header-cta-btn');
        if (button) {
            button.addEventListener('click', (e) => {
                this.trackCTAClick(button);
            });
        }
    }

    trackCTAClick(button) {
        const data = {
            text: button.querySelector('.header-cta-btn__text')?.textContent || '',
            url: button.href,
            style: this.getButtonStyle(button),
            location: 'header'
        };

        // Google Analytics 4
        if (typeof gtag !== 'undefined') {
            gtag('event', 'header_cta_click', {
                'cta_text': data.text,
                'cta_url': data.url,
                'cta_style': data.style,
                'cta_location': data.location
            });
        }

        // Google Tag Manager
        if (typeof dataLayer !== 'undefined') {
            dataLayer.push({
                'event': 'header_cta_click',
                'cta_data': data
            });
        }

        // Console log pour le développement
        console.log('Header CTA clicked:', data);

        // Événement personnalisé
        document.dispatchEvent(new CustomEvent('headerCTA:click', { detail: data }));
    }

    getButtonStyle(button) {
        const classList = Array.from(button.classList);
        const styleClass = classList.find(cls => cls.startsWith('btn--'));
        return styleClass ? styleClass.replace('btn--', '') : 'unknown';
    }

    removeCTA() {
        if (this.ctaElement && this.ctaElement.parentNode) {
            this.ctaElement.parentNode.removeChild(this.ctaElement);
            this.isInjected = false;
            this.headerContainer?.classList.remove('has-cta');

            // Déclencher un événement
            document.dispatchEvent(new CustomEvent('headerCTA:removed'));
        }
    }

    // API publique
    refresh() {
        this.removeCTA();
        this.injectCTA();
    }

    hide() {
        if (this.ctaElement) {
            this.ctaElement.style.display = 'none';
        }
    }

    show() {
        if (this.ctaElement) {
            this.ctaElement.style.display = '';
        }
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', () => {
    window.HeaderCTAInstance = new HeaderCTA();
});

// API globale
window.HeaderCTA = HeaderCTA;
