# Comment contribuer?

1. Sélectionnez la saga que vous voulez éditer: [Adoprixtoxis](https://github.com/Neamar/sagas-mp3/tree/master/Adoprixtoxis/), [Reflets](https://github.com/Neamar/sagas-mp3/tree/master/Reflets/), ou [Xantah](https://github.com/Neamar/sagas-mp3/tree/master/Xantah/)
2. Cliquez sur l'épisode que vous souhaitez modifier.
3. Si nécessaire, cliquez sur "Sign up" pour vous inscrire sur Github (utile pour savoir qui propose des modifications)
4. Cliquez sur l'icone en forme de crayon
5. Modifiez votre texte:
    * Le format d'une ligne doit être "PERSONNE QUI PARLE : Texte", avec une espace avant et après le caractère ":"
    * Pour ajouter une référence, après le texte, ajoutez simplement `REF:` suivi de votre texte. Par exemple: `VOIX OFF : Adoprixtoxis, la planète mystérieuse... épisode premier : le naufrageREF:Au passage, sachez que le nom "Adoprixtoxis" n'a aucun sens caché, il s'agit juste d'une suite de lettres tapées plus ou moins au hasard sur un clavier.`
    * Pour ajouter un jeu de mot, utilisez `JDM:` au lieu de `REF:`
    * Pour ajouter une tricheliade (utile pour Reflets d'Acide uniquement), finissez le texte par `TRI`.
6. Cliquez ensuite sur "Propose file change"
7. Cliquez sur le gros bouton vert "Create pull request"
7. Cliquez une nouvelle fois sur le gros bouton vert "Create pull request"
8. C'est prêt! Vous recevrez un email une fois votre proposition acceptée. Il peut se passer un peu de temps entre l'acceptation et l'apparition du nouveau texte sur le site.


----
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
