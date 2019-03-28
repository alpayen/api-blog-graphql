<?php

namespace App\Mutation;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;

use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;


final class DeleteMutator implements MutationInterface, AliasedInterface {
	private $em;

	/**
	 * @param EntityManagerInterface $em
	 */
	public function __construct( EntityManagerInterface $em ) {
		$this->em = $em;
	}

	/**
	 * @param string $entityName
	 * @param integer $id
	 *
	 * @return array
	 *
	 * @throws \Exception
	 */
	public function resolve( string $entityName, int $id ) {
		$entityName = "\App\Entity\\$entityName";
		$entity     = $this->em->getRepository( $entityName )->find( $id );

		if ( ! $entity ) {
			throw new \Exception( "Unknown $entityName with id : $id" );
		}

		$this->em->remove( $entity );
		$this->em->flush();

		return [ 'content' => 'ok' ];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getAliases(): array {
		return [
			'resolve' => 'DeleteAction',
		];
	}
}