# Blog graphql symfony

## Features:

> L'API vient avec des migrations et des fixtures pour la base de données
> Le CRUD est mise en place sur les objets:
>   - Catégories
>   - Auteurs
>   - Articles
>   - Commentaires

## Spécifités téchniques : 

> Les resolvers marchent de manière classique, un par objet lié à son type correspondant. 
> Pour les mutations les choses se corsent un peu. 

> J'ai rendu les mutations les plus globales possible, partant du principe qu'en écrire une par type était un peu trop verbeu
> Du coup, je demande pour activer une mutation un paramètre de type String avec lequel je récupère le nom de mon entité,
> un ID au besoin et un Input du bon type. Ainsi, j'ai pu créer seulement 3 mutations (Create, Update, Delete) qui sont transversalent à tout les objets de l'API.


