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

>Les Mutations Successes renvoient au besoin le type d'entité de la mutation pour récupérer et traiter la data inséerée ou modifiée

### Pour tester : 

#### GET

````
{
  Articles{
    title,
    comments{
      content
    },
    category{
      name
    },
    author{
      lastname,
      articles{
        id
      }
    }
  },
  
  Article(id:1){
    title
  },
  Category(id:1){
    articles{
      title
    }
  },
  Author(id:1){
    lastname
  },
  Comment(id:1){
    content
  }
}
````


#### CREATE
 
>  - Mutation : 
````
mutation NewArticle($classNameArticle: String!, $article: ArticleInput!){
  NewArticle(entityName : $classNameArticle,input : $article){
    content{
      id
    }
  }
}

````

> - Query variables
```
{
  "classNameArticle": "Article",
  "article": {
    "title":"Titre de l'article",
    "content":"Contenu de l'article",
    "category_id":1,
    "author_id": 1
  }
}
```

#### UPDATE
 
>  - Mutation : 
````
mutation UpdateArticle($classNameArticle: String!,$id: Int, $article: ArticleInput!){
  UpdateArticle(entityName : $classNameArticle,id: $id, input : $article){
    content{
      id,
      title,
      content
    }
  }
}
````

> - Query variables
```
{
  "classNameArticle": "Article",
  "id" : 54,
  "article": {
    "title":"Titre de l'article updaté",
    "content":"Contenu de l'article updaté",
    "category_id":6,
    "author_id": 23
  }
}
```

#### DELETE
 
>  - Mutation : 
````
mutation DeleteArticle($classNameArticle: String!, $id: Int){
  DeleteArticle(entityName : $classNameArticle,id : $id){
    content
  }
}


````

> - Query variables
```
{
  "classNameArticle": "Article",
	"id":1
}
```
