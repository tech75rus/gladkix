<?php

namespace App\Tag\Repository;

use App\Tag\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function save(Tag $tag, bool $flush = false): void
    {
            $this->getEntityManager()->persist($tag);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
    }

    public function remove(Tag $tag, bool $flush = false): void
    {
            $this->getEntityManager()->remove($tag);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
    }
}
