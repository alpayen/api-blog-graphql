<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture {
	public function load( ObjectManager $manager ) {


		$nbOfAuthor = 5;
		$authors    = [];


		$nbOfCategories = 5;
		$categories    = [];

		$nbOfArticles = 25;
		$articles = [];

		$nbOfComments = 250;


		for ( $i = 0; $i < $nbOfAuthor; $i ++ ) {
			$author = new Author();
			$author->setFirstname( "Firstname $i" );
			$author->setLastname( "Lastname $i" );

			$manager->persist($author);

			$authors[] = $author;

		}


		for ( $i = 0; $i < $nbOfCategories; $i ++ ) {
			$category = new Category();
			$category->setName( "Category n°$i" );

			$manager->persist($category);

			$categories[] = $category;

		}


		for ( $i = 0; $i < $nbOfArticles; $i ++ ) {
			$article = new Article();
			$article->setTitle( "Article n°$i" );
			$article->setContent( "Content for article n°$i" );

			$article->setCategory($categories[rand(0, $nbOfCategories-1)]);
			$article->setAuthor($authors[rand(0, $nbOfAuthor-1)]);

			$manager->persist($article);

			$articles[] = $article;

		}


		for ( $i = 0; $i < $nbOfComments; $i ++ ) {
			$comment = new Comment();
			$comment->setContent( "Content for comment n°$i" );

			$comment->setArticle($articles[rand(0, $nbOfArticles-1)]);

			$manager->persist($comment);

		}

		$manager->flush();
	}
}
