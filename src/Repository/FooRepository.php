<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Foo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Foo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Foo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Foo[]    findAll()
 * @method Foo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FooRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Foo::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('foo')
            ->orderBy('foo.id')
            ->getQuery();
    }

    /**
     * @param string $q
     *
     * @return Collection|Foo[]
     */
    public function typeaheadSearch($q) {
        throw new \RuntimeException("Not implemented yet.");
        $qb = $this->createQueryBuilder('foo');
        $qb->andWhere('foo.column LIKE :q');
        $qb->orderBy('foo.column', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }

    
}
