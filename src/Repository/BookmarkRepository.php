<?php

namespace App\Repository;

use App\Entity\Bookmark;
use App\Model\LessonInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Bookmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookmark[]    findAll()
 * @method Bookmark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookmarkRepository extends ServiceEntityRepository implements BookmarkRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bookmark::class);
    }

    public function getAllForLesson(LessonInterface $lesson): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.lesson = :lesson')
            ->setParameter('lesson', $lesson)
            ->getQuery()
            ->getResult()
            ;
    }
}
