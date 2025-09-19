document.addEventListener('DOMContentLoaded',function () {
  const buttons = document.body.querySelectorAll(".card-button");
  if (!buttons.length) return;
  const modal = document.body.querySelector(".card-modal");
	if (!modal) return;

	    /**
     * Sauvegarde la position de scroll actuelle
     */
    function saveScrollPosition() {
        scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
    }

	    /**
     * Restaure la position de scroll sauvegardée
     */
    function restoreScrollPosition() {
        window.scrollTo(0, scrollPosition);
    }

  buttons.forEach((button) => {
    button.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();

      // Afficher le modal
      showModal(modal);
    });
  });

  // Fermer le modal en cliquant sur le backdrop ou le bouton fermer
  modal.addEventListener("click", function (e) {
    if (e.target === modal) {
      hideModal(modal);
    }
  });

  // Événement spécifique pour le bouton de fermeture
  const closeButton = modal.querySelector(".modal-close");
  if (closeButton) {
    closeButton.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      hideModal(modal);
    });
  }

  // Fermer avec la touche Escape
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && modal.classList.contains("active")) {
      hideModal(modal);
    }
  });

	  /**
	   * Affiche le modal
	   */
	function showModal(modal) {
		saveScrollPosition();
		document.body.style.top = `-${scrollPosition}px`;
		console.log(scrollPosition);
		  modal.classList.add('active');
		  document.body.classList.add('modal-open');

		  // Focus sur le modal pour l'accessibilité
		  const closeButton = modal.querySelector('.modal-close');
		  if (closeButton) {
			  closeButton.focus();
		  }
	  }


	  /**
	   * Cache le modal
	   */
	  function hideModal(modal) {
		  modal.classList.remove('active');
		  document.body.classList.remove('modal-open');
		   document.body.classList.remove('modal-open');

        // Restaurer la position après avoir fermé le modal
        document.body.style.top = '';
        restoreScrollPosition();
	  }
});
