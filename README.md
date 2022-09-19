# Plugin multipleMedia for Dotclear

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
