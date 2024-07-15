# Comment contribuer ?
Avant toute chose, vous devrez vous créer un compte sur le site [Github](https://github.com/signup). Pas d'inquiétude, c'est gratuit.
Une fois le compte créé, vérifiez que vous êtes bien connecté.

1. Sélectionnez la saga que vous voulez éditer:
  * [Adoprixtoxis](https://github.com/Neamar/sagas-mp3/tree/master/Adoprixtoxis/)
  * [Reflets d'Acide](https://github.com/Neamar/sagas-mp3/tree/master/Reflets/)
  * [Xantah](https://github.com/Neamar/sagas-mp3/tree/master/Xantah/)
3. Cliquez sur le nom de l'épisode que vous souhaitez modifier. La page qui s'ouvre va afficher le texte brut de l'épisode.
5. Cliquez sur l'icône en forme de crayon à droite du texte. Si celle-ci est grisée, c'est que vous n'êtes pas connecté au site.
6. Modifiez le texte:
    * Le format d'une ligne doit être "PERSONNE QUI PARLE : Texte", avec une espace avant et après le caractère ":"
    * Pour ajouter une référence, après le texte, ajoutez simplement `REF:` suivi de votre texte.
       * Par exemple: `VOIX OFF : Adoprixtoxis, la planète mystérieuse... épisode premier : le naufrageREF:Au passage, sachez que le nom "Adoprixtoxis" n'a aucun sens caché, il s'agit juste d'une suite de lettres tapées plus ou moins au hasard sur un clavier.`
    * Pour ajouter un jeu de mots, utilisez `JDM:` au lieu de `REF:`
    * Pour ajouter une Tricheliade (utile pour Reflets d'Acide uniquement), finissez le texte par `TRI` en majuscules.
7. Cliquez ensuite sur "Propose file change"
8. Cliquez sur le gros bouton vert "Create pull request"
9. Cliquez une nouvelle fois sur le gros bouton vert "Create pull request".
10. C'est prêt ! Vous recevrez un email une fois votre proposition acceptée. Il peut se passer un peu de temps entre l'acceptation et l'apparition du nouveau texte sur le site.


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
ELLE : Parle REF:Une explication
sur deux lignes
EUX : Parlent
sur
plusieurs
lignes
TOUS : (en chœur) parlent
et reparlent REF: ce n'est pas
en une seule ligne non plus
```

Utilisez `REF:` pour une référence, et `JDM:` pour un jeu de mots.
Il est aussi possible d'utiliser `TRI` en fin de phrase pour indiquer une tricheliade.
