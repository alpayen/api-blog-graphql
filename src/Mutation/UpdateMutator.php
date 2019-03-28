<?php

namespace App\Mutation;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Serializer\Serializer;


final class UpdateMutator implements MutationInterface, AliasedInterface {
	private $em;

	/**
	 * @param EntityManagerInterface $em
	 * @param Serializer $serializer
	 */
	public function __construct( EntityManagerInterface $em) {
		$this->em = $em;
	}

	/**
	 * @param string $entityName
	 * @param int $id
	 * @param array $object
	 *
	 * @return array
	 *
	 * @throws \Exception
	 */
	public function resolve(string $entityName, int $id,  array $object ) {
		$entityName = "\App\Entity\\$entityName";
		$entity     = $this->em->getRepository( $entityName )->find( $id );

		if ( ! $entity ) {
			throw new \Exception( "Unknown $entityName with id : $id" );
		}


		foreach ( $object as $key => $property ) {
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

		return [ 'content' => $entity];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getAliases(): array {
		return [
			'resolve' => 'UpdateAction',
		];
	}
}