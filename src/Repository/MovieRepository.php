<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function insertData($data): void
    {

        $em = $this->getEntityManager();

        $batchSize = 20;
        for ($i = 1; $i < sizeof($data); ++$i) {
            $movie = new Movie();
            $movie->setYear($data[$i][0]);
            $movie->setTitle($data[$i][1]);
            $movie->setStudios(array_key_exists(2, $data[$i]) ? $data[$i][2] : '');
            $movie->setProducers(array_key_exists(3, $data[$i]) ? $data[$i][3] : '');
            $movie->setWinner(array_key_exists(4, $data[$i]) ? $data[$i][4] : false);

            $em->persist($movie);

            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear();
            }
        }

        $em->flush();
        $em->clear();
    }

    /**
     * @throws Exception
     */
    public function deleteData(): void
    {
        $sql = <<<SQL
                delete from movie where true;
                SQL;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        $stmt->executeQuery();
    }

    public function deleteMovie($id): void
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->delete('App:Movie', 'm')
            ->where($qb->expr()->eq('m.id', ':id'))
            ->setParameter(':id', $id)
            ->getQuery()
            ->execute();
    }

    public function updateMovie($id): void
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->update('App:Movie', 'm')
            ->where($qb->expr()->eq('m.id', ':id'));

    }

    /**
     * @throws Exception
     */
    public function deleteAndInsertData($data): void
    {
        $this->deleteData();
        $this->insertData($data);
    }

    /**
     * @throws Exception
     */
    public function findAll(): array
    {
        $sql = <<<SQL
                select * from movie order by id desc;
                SQL;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function findMovie($id, $lockMode = null, $lockVersion = null): array|null
    {
        $sql = <<<SQL
                select * from movie where id == :id;
                SQL;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        return $stmt->executeQuery([':id' => $id])->fetchAllAssociative();
    }
}
