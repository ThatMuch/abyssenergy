<?php

/**
 * Template part pour démontrer l'utilisation des multiselect personnalisés
 */
?>

<div class="multiselect-example-container">
	<h3>Exemple de Multiselect</h3>

	<div class="form-group">
		<label for="simple-select">Sélecteur simple</label>
		<select id="simple-select" name="simple-select" class="abyss-select">
			<option value="">Sélectionnez une option</option>
			<option value="option1">Option 1</option>
			<option value="option2">Option 2</option>
			<option value="option3">Option 3</option>
		</select>
	</div>

	<div class="form-group">
		<label for="multi-select">Sélecteur multiple</label>
		<select id="multi-select" name="multi-select[]" class="abyss-multiselect" multiple data-search="true">
			<option value="option1">Option 1</option>
			<option value="option2">Option 2</option>
			<option value="option3">Option 3</option>
			<option value="option4">Option 4</option>
			<option value="option5">Option 5</option>
			<option value="option6">Option 6</option>
			<option value="option7">Option 7</option>
			<option value="option8">Option 8</option>
		</select>
	</div>

	<div class="form-group">
		<label for="grouped-multi-select">Sélecteur multiple avec groupes</label>
		<select id="grouped-multi-select" name="grouped-multi-select[]" class="abyss-multiselect" multiple data-search="true">
			<optgroup label="Groupe 1">
				<option value="group1-option1">Groupe 1 - Option 1</option>
				<option value="group1-option2">Groupe 1 - Option 2</option>
				<option value="group1-option3">Groupe 1 - Option 3</option>
			</optgroup>
			<optgroup label="Groupe 2">
				<option value="group2-option1">Groupe 2 - Option 1</option>
				<option value="group2-option2">Groupe 2 - Option 2</option>
				<option value="group2-option3">Groupe 2 - Option 3</option>
			</optgroup>
		</select>
	</div>
</div>

<script>
	// Ce script s'exécutera après que le DOM soit chargé
	document.addEventListener('DOMContentLoaded', function() {
		// Le script multiselect.js recherchera automatiquement tous les éléments avec la classe .abyss-multiselect
		// et les transformera en multiselects personnalisés
	});
</script>
