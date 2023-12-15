<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Likes;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Likes>
 *
 * @method Likes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Likes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Likes[]    findAll()
 * @method Likes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Likes::class);
    }
    public function countLikesPerUser(User $user): array
    {
        $userLikes = $this->createQueryBuilder('l')
            ->select('l')
            ->leftJoin('l.user', 'u')
            ->where('u = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $userLikes;
    }
    // public function countTweetLikes(): array
    // {
    //     return $this->createQueryBuilder('t')
    //         ->select('t.id, COUNT(l.id) as likeCount')
    //         ->leftJoin('t.likes', 'l')
    //         ->groupBy('t.id')
    //         ->getQuery()
    //         ->getResult();
    // }
    //    /**
    //     * @return Likes[] Returns an array of Likes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Likes
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
