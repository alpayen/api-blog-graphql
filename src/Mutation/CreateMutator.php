<?php

namespace App\Mutation;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;


final class CreateMutator implements MutationInterface, AliasedInterface {
	private $em;

	/**
	 * @param EntityManagerInterface $em
	 */
	public function __construct( EntityManagerInterface $em ) {
		$this->em = $em;
	}

	/**
	 * @param string $entity
	 * @param array $args
	 *
	 * @return array
	 *
	 * @throws \Exception
	 */
	public function resolve( string $entity, array $args ) {
		$entity = "\App\Entity\\$entity";
		$entity = new $entity();
		foreach ( $args as $key => $property ) {
			if ( strpos( $key, '_id' ) !== false ) {
				$key = str_replace( "_id", "", $key );
				$relatedEntityClassName = "\App\Entity\\" . ucfirst( $key );
				$relatedEntity          = $this->em->getRepository( $relatedEntityClassName )->find( $property );

				if ( $relatedEntity === null ) {
					throw new \Exception( "Unknown $relatedEntityClassName with id : $property" );
				}
				$property = $relatedEntity;
			}
			$method_name = "set" . ucfirst( $key );
			if ( method_exists( $entity, $method_name ) ) {
				$entity->$method_name( $property );
			}
		}

		$this->em->persist( $entity );
		$this->em->flush();

		return [ 'content' => 'ok' ];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getAliases(): array {
		return [
			'resolve' => 'CreateAction',
		];
	}
}