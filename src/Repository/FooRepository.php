<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Repository;

use App\Entity\Foo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method null|Foo find($id, $lockMode = null, $lockVersion = null)
 * @method null|Foo findOneBy(array $criteria, array $orderBy = null)
 * @method Foo[]    findAll()
 * @method Foo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FooRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Foo::class);
    }

    /**
     * @return Query
     */
    public function indexQuery() {
        return $this->createQueryBuilder('foo')
            ->orderBy('foo.id')
            ->getQuery()
        ;
    }

    /**
     * @param string $q
     *
     * @return Collection|Foo[]
     */
    public function typeaheadSearch($q) {
        throw new RuntimeException('Not implemented yet.');
        $qb = $this->createQueryBuilder('foo');
        $qb->andWhere('foo.column LIKE :q');
        $qb->orderBy('foo.column', 'ASC');
        $qb->setParameter('q', "{$q}%");

        return $qb->getQuery()->execute();
    }
}
