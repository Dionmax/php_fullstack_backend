<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
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
        for ($i = 0; $i < sizeof($data); ++$i) {
            $movie = new Movie();
            $movie->setYear($data[$i][0]);
            $movie->setTitle($data[$i][1]);
            $movie->setStudios(array_key_exists(2, $data[$i]) ? $data[$i][2] : '');
            $movie->setProducers(array_key_exists(3, $data[$i]) ? $data[$i][3] : '');
            $movie->setWinner(array_key_exists(4, $data[$i]) ? $data[$i][4] : '');

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

    /**
     * @throws Exception
     */
    public function deleteAndInsertData($data): void
    {
        $this->deleteData();
        $this->insertData($data);
    }

    public function findAll($param = []): array
    {
        $sql = "select * from movie where 1 = 1";


        if (array_key_exists('winner', $param) && $param['winner'] != null) {
            $sql .= ' and winner = ' . $param['winner'];
        }

        if (array_key_exists('year', $param) && $param['year'] != null) {
            $sql .= ' and year = ' . $param['year'];
        }

        if (array_key_exists('page', $param) && array_key_exists('size', $param)) {
            $sql .= ' limit ' . $param['size'] . ' offset ' . ($param['page'] - 1) * $param['size'];
        }

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);

        $data = array();

        $data['content'] = $stmt->executeQuery()->fetchAllAssociative();

        if (array_key_exists('page', $param) && array_key_exists('size', $param)) {
            $data['totalElements'] = $this->getEntityManager()->getConnection()->executeQuery('select count(*) from movie')->fetchOne();
            $data['totalPages'] = ceil($data['totalElements'] / $param['size']);
            $data['pageSize'] = $param['size'];
            $data['pageNumber'] = $param['page'];
        }

        return $data;
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
        $stmt->bindValue(':id', $id, ParameterType::INTEGER);
        return $stmt->executeQuery()->fetchAllAssociative();
    }

    public function findWinners()
    {
        $sql = <<<SQL
                select group_concat(year) as year,
                       group_concat(title) as title,
                       producers as producer
                from movie
                where producers in (select producers
                                    from (select producers,
                                                 count(*) as c
                                          from movie
                                          where winner = 1
                                          group by producers)
                                    where c > 1)
                and winner = 1
                group by producers
                order BY year desc
            SQL;

        $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
        return $stmt->executeQuery()->fetchAllAssociative();
    }
}
