# Plugin multipleMedia for Dotclear

[![Release](https://img.shields.io/github/v/release/franck-paul/multipleMedia)](https://github.com/franck-paul/multipleMedia/releases)
[![Date](https://img.shields.io/github/release-date/franck-paul/multipleMedia)](https://github.com/franck-paul/multipleMedia/releases)
[![Issues](https://img.shields.io/github/issues/franck-paul/multipleMedia)](https://github.com/franck-paul/multipleMedia/issues)
[![Dotaddict](https://img.shields.io/badge/dotaddict-official-green.svg)](https://plugins.dotaddict.org/dc2/details/multipleMedia)
[![License](https://img.shields.io/github/license/franck-paul/multipleMedia)](https://github.com/franck-paul/multipleMedia/blob/master/LICENSE)

Permet l'insertion multiple de média dans un billet avec l'éditeur dcLegacyEditor (bouton `<img src="icon.svg" alt="icon" style="width: 1.5em; background-color: #fff; border: 1px solid #ccc; padding: .25em; border-radius: .25em;" />` dans la barre d'outils)

Limitations actuelles :

* Seules les images sont insérées, même si d'autres média sont sélectionnés
* Les miniatures (format différent de l'original) sont réputées existantes (pas de contrôle d'existence des fichiers)

Évolutions envisagées :

* Ajout d'un plugin pour CKEditor
* Gestion des autres types de média (vidéo, audio, …)

Notes :

* Les paramètres d'insertion sont **initialement** ceux définis par défaut pour le blog
* Si un fichier `.mediadef` ou `.mediadef.json` est présent dans le répertoire il est utilisé pour **initialiser** les paramètres d'insertion
