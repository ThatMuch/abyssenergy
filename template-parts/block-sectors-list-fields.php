<?php

/**
 * Champs ACF pour le bloc "Sectors List"
 */

if (function_exists('acf_add_local_field_group')) :

	acf_add_local_field_group(array(
		'key' => 'group_sectors_list',
		'title' => 'Paramètres de la liste de secteurs',
		'fields' => array(
			array(
				'key' => 'field_sectors_list_title',
				'label' => 'Titre',
				'name' => 'title',
				'type' => 'text',
				'instructions' => 'Titre principal du bloc.',
				'default_value' => 'Nos secteurs',
			),
			array(
				'key' => 'field_sectors_list_selection_type',
				'label' => 'Type de sélection',
				'name' => 'selection_type',
				'type' => 'select',
				'instructions' => 'Comment souhaitez-vous sélectionner les secteurs ?',
				'choices' => array(
					'all' => 'Tous les secteurs',
					'specific' => 'Secteurs spécifiques',
				),
				'default_value' => 'all',
			),
			array(
				'key' => 'field_sectors_list_specific_sectors',
				'label' => 'Sélectionner des secteurs spécifiques',
				'name' => 'specific_sectors',
				'type' => 'post_object',
				'instructions' => 'Sélectionnez les secteurs que vous souhaitez afficher.',
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_sectors_list_selection_type',
							'operator' => '==',
							'value' => 'specific',
						),
					),
				),
				'post_type' => array('sector'),
				'multiple' => true,
				'return_format' => 'id',
			),
			array(
				'key' => 'field_sectors_list_posts_per_page',
				'label' => 'Nombre de secteurs à afficher',
				'name' => 'posts_per_page',
				'type' => 'number',
				'instructions' => 'Combien de secteurs souhaitez-vous afficher ? Laisser -1 pour tous les afficher.',
				'default_value' => -1,
				'min' => -1,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_sectors_list_selection_type',
							'operator' => '==',
							'value' => 'all',
						),
					),
				),
			),
			array(
				'key' => 'field_sectors_list_order',
				'label' => 'Ordre',
				'name' => 'order',
				'type' => 'select',
				'instructions' => 'Dans quel ordre souhaitez-vous afficher les secteurs ?',
				'choices' => array(
					'ASC' => 'Ascendant',
					'DESC' => 'Descendant',
				),
				'default_value' => 'ASC',
			),
			array(
				'key' => 'field_sectors_list_orderby',
				'label' => 'Trier par',
				'name' => 'orderby',
				'type' => 'select',
				'instructions' => 'Sur quel critère souhaitez-vous trier les secteurs ?',
				'choices' => array(
					'menu_order' => 'Ordre personnalisé',
					'title' => 'Titre',
					'date' => 'Date de publication',
				),
				'default_value' => 'menu_order',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'acf/sectors-list',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));

endif;
