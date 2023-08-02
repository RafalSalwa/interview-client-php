<?php

namespace App\Repository;

use App\Entity\OAuth2UserConsent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OAuth2UserConsent>
 *
 * @method OAuth2UserConsent|null find($id, $lockMode = null, $lockVersion = null)
 * @method OAuth2UserConsent|null findOneBy(array $criteria, array $orderBy = null)
 * @method OAuth2UserConsent[]    findAll()
 * @method OAuth2UserConsent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OAuth2UserConsentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OAuth2UserConsent::class);
    }

    public function add(OAuth2UserConsent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OAuth2UserConsent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}