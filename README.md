# Sagas MP3
> This project is in French. If you don't speak french, please go away ;)

Ce repository contient les données permettant l'affichage de trois sagas sur [sagas.neamar.fr](https://sagas.neamar.fr):

* [Reflets d'Acide](https://sagas.neamar.fr/Reflets)
* [Adoprixtoxis](https://sagas.neamar.fr/Adoprixtoxis/)
* [Xantah](https://sagas.neamar.fr/Xantah)

Il s'agit d'un site [Jekyll](https://jekyllrb.com), que vous pouvez facilement tester en local avec `jekyll s`.


Voici un exemple de fichier valide pour un épisode :

```
---
layout: text
title: TEST
saga: Reflets
---

INTERLOCUTEUR : Quelque chose

Chapitre 1 - Autre chose

LUI : Parle...
sur deux lignes.
ELLE : ParlesREF:Une explication
sur deux lignes
EUX : Parle
sur
plusieurs
lignes
TOUS : (en chœur) parle
et reparleREF: ce n'est pas
en une seule ligne non plus
```

Utilisez `REF:` pour une référence, et `JDM:` pour un jeu de mots.
Il est aussi possible d'utiliser `TRI` en fin de phrase pour indiquer une tricheuliade.
