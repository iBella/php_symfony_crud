<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
        $this->manager = $registry->getManager();
    }

    /**
    * @return User[] Returns an array of User objects
    */
    public function findAll()
    {
        return $this->manager->getRepository(User::class)->findAll();
    }

    /**
    * @return User Finds an entity by its primary key / identifier.
    */
    public function findById(int $id)
    {
        return $this->manager->getRepository(User::class)->find($id);
    }

    /**
    * @return User Returns an User objects
    */
    public function persist(User $user){
        $this->manager->persist($user);
        $this->manager->flush();
        return $user;
    }

    /**
    * @return User Remove an User objects
    */
    public function remove(User $user){
        $this->manager->remove($user);
        $this->manager->flush();
    }

}
