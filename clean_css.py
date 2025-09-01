#!/usr/bin/env python3
"""
Script de nettoyage des fichiers CSS.
Ce script supprime les commentaires superflus et les lignes vides dans les fichiers CSS générés.
"""

import os
import re
import sys

def clean_css_file(file_path):
    """Nettoie un fichier CSS en supprimant les commentaires et lignes vides superflues."""
    if not os.path.exists(file_path):
        print(f"Le fichier {file_path} n'existe pas.")
        return False

    print(f"Nettoyage du fichier CSS : {file_path}")

    # Lire le contenu du fichier
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Compter les caractères avant le nettoyage
    original_size = len(content)

    # Supprimer les commentaires CSS /* ... */ sauf les commentaires importants commençant par /*!
    content = re.sub(r'/\*(?!\!)[^*]*\*+(?:[^*/][^*]*\*+)*/', '', content)

    # Supprimer les lignes vides multiples
    content = re.sub(r'\n\s*\n', '\n\n', content)

    # Supprimer les espaces superflus
    content = re.sub(r'[ \t]+', ' ', content)
    content = re.sub(r' *\n *', '\n', content)
    content = re.sub(r' *\{ *', ' {', content)
    content = re.sub(r' *\} *', '}', content)
    content = re.sub(r' *: *', ': ', content)
    content = re.sub(r' *; *', ';', content)

    # Compter les caractères après le nettoyage
    cleaned_size = len(content)

    # Calculer la réduction en pourcentage
    reduction = (original_size - cleaned_size) / original_size * 100 if original_size > 0 else 0

    print(f"Taille originale : {original_size} caractères")
    print(f"Taille après nettoyage : {cleaned_size} caractères")
    print(f"Réduction : {reduction:.2f}%")

    # Écrire le contenu nettoyé dans le fichier
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)

    return True

def main():
    """Fonction principale du script."""
    # Fichiers CSS à nettoyer
    css_files = ['style.min.css', 'style.css']

    cleaned_files = 0
    for css_file in css_files:
        if os.path.exists(css_file):
            if clean_css_file(css_file):
                cleaned_files += 1

    if cleaned_files > 0:
        print(f"\n✅ {cleaned_files} fichier(s) CSS nettoyé(s) avec succès !")
    else:
        print("\n⚠️ Aucun fichier CSS n'a été nettoyé.")

if __name__ == "__main__":
    main()
