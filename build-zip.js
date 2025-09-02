/**
 * Script pour créer une archive zip du thème WordPress
 * Utilisé pour l'importation facile dans WordPress
 */

const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');
const bestzip = require('bestzip');
const packageJson = require('./package.json');

// Configuration
const themeName = 'abyssenergy';
const version = packageJson.version;
const zipName = `${themeName}-v${version}.zip`;
const tempDir = '.temp-zip-build';

// Liste des fichiers et dossiers à inclure
const include = [
  '*.php',
  'style.css',
  'style.min.css',
  'style.min.css.map',
  'screenshot.png',
  'README.md',
  'acf-json',
  'css',
  'images',
  'js',
  'template-parts'
];

// Liste des fichiers et dossiers à exclure
const exclude = [
  'node_modules',
  '.git',
  '.github',
  '.gitignore',
  '.sass-cache',
  '*.sh',
  '*.zip',
  'package-lock.json',
  'browsersync.js',
  'dev-server.js',
  'build-zip.js',
  'clean_css.py',
  'package.json',
  'scss'
];

// Fonctions utilitaires
function ensureDirectoryExistence(dirPath) {
  if (!fs.existsSync(dirPath)) {
    fs.mkdirSync(dirPath, { recursive: true });
  }
}

function copyFiles() {
  // Créer le répertoire temporaire
  ensureDirectoryExistence(tempDir);

  // Copier les fichiers selon les patterns d'inclusion
  include.forEach(pattern => {
    try {
      // Utiliser cp avec glob patterns
      const command = `cp -R ${pattern} ${tempDir}/`;
      execSync(command, { stdio: 'inherit' });
      console.log(`✅ Fichiers copiés: ${pattern}`);
    } catch (error) {
      console.log(`⚠️ Pas de fichiers correspondant à: ${pattern}`);
    }
  });

  // Compiler le CSS final avant de créer l'archive
  console.log('🔄 Compilation des styles...');
  execSync('npm run build', { stdio: 'inherit' });

  // S'assurer que le style.css compilé est copié
  execSync(`cp style.css ${tempDir}/`, { stdio: 'inherit' });
  execSync(`cp style.min.css ${tempDir}/`, { stdio: 'inherit' });
  if (fs.existsSync('style.min.css.map')) {
    execSync(`cp style.min.css.map ${tempDir}/`, { stdio: 'inherit' });
  }
}

async function createZip() {
  console.log(`🔄 Création de l'archive ${zipName}...`);

  try {
    await bestzip({
      source: '*',
      destination: `./${zipName}`,
      cwd: tempDir
    });
    console.log(`✅ Archive créée avec succès: ${zipName}`);

    // Vérifier si le fichier existe
    const zipPath = `./${zipName}`;
    if (fs.existsSync(zipPath)) {
      console.log(`✅ L'archive est disponible à: ${zipPath}`);
    } else {
      console.log(`⚠️ L'archive a été créée mais n'a pas pu être localisée.`);
    }

    // Nettoyer le répertoire temporaire
    execSync(`rm -rf ${tempDir}`, { stdio: 'inherit' });

    console.log(`
📦 Thème WordPress empaqueté avec succès!
📁 Fichier: ${zipName}
🔢 Version: ${version}

✅ Ce fichier peut maintenant être importé dans WordPress via l'interface d'administration.
`);
  } catch (error) {
    console.error('❌ Erreur lors de la création de l\'archive:', error);
    // Nettoyer en cas d'erreur
    execSync(`rm -rf ${tempDir}`, { stdio: 'inherit' });
  }
}

// Exécution principale
(async function() {
  console.log(`
🚀 Création d'une archive WordPress pour le thème ${themeName} v${version}
🔍 Exclusion des fichiers de développement
⏳ Veuillez patienter...
`);

  // Nettoyage préalable
  if (fs.existsSync(tempDir)) {
    execSync(`rm -rf ${tempDir}`, { stdio: 'inherit' });
  }

  copyFiles();
  await createZip();
})();
