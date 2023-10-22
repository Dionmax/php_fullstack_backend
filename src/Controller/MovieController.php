<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/movies')]
class MovieController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', methods: 'GET')]
    public function getMovies(MovieRepository $movieRepository)
    {
        $data = $movieRepository->findAll();

        return new JsonResponse(
            [
                $data
            ],
            Response::HTTP_OK
        );
    }

    #[Route('/{id}', methods: 'GET')]
    public function getMovie(MovieRepository $movieRepository, int $id)
    {
        $data = $movieRepository->findMovie($id);

        if (array_key_exists(0, $data))
            return new JsonResponse(
                [
                    $data[0]
                ],
                Response::HTTP_OK,
            );
        else
            return new JsonResponse(
                [],
                Response::HTTP_NOT_FOUND,
            );
    }

    #[Route('', methods: 'POST')]
    public function addMovie(MovieRepository $movieRepository, Request $request)
    {

        $movie = new Movie();

        $form = $this->createForm(MovieType::class, $movie);

        $form->submit($request->toArray());

        $this->entityManager->persist($movie);

        $this->entityManager->flush();

        return new JsonResponse(
            [],
            Response::HTTP_OK,
        );
    }

    #[Route('/{id}', methods: 'PUT')]
    public function updateMovie(MovieRepository $movieRepository, int $id, Request $request)
    {
        $movie = $movieRepository->find($id);

        if (is_null($movie)) {
            return new JsonResponse(
                [],
                Response::HTTP_NOT_FOUND,
            );
        }

        $form = $this->createForm(MovieType::class, $movie, array('method' => 'PUT'));

        $form->submit($request->toArray());

        $this->entityManager->persist($movie);

        $this->entityManager->flush();

        return new JsonResponse(
            [],
            Response::HTTP_OK,
        );

    }

    /**
     * @throws Exception
     */
    #[Route('/{id}', methods: 'DELETE')]
    public function deleteMovie(MovieRepository $movieRepository, int $id)
    {
        $data = $movieRepository->findMovie($id);

        if (array_key_exists(0, $data)) {
            $movieRepository->deleteMovie($data[0]['id']);

            return new JsonResponse(
                [],
                Response::HTTP_NO_CONTENT,
            );
        } else {
            return new JsonResponse(
                [],
                Response::HTTP_NOT_FOUND,
            );
        }
    }

    //For debug only
    #[Route('/resetdatabase')]
    public function resetDatabase(MovieRepository $movieRepository)
    {

        $csvFile = fopen('../data/movielist.csv', 'r');

        fgetcsv($csvFile);

        $data = array();

        array_shift($data);

        while ($row = fgetcsv($csvFile)) {
            $raw = explode(';', $row[0]);

            if (array_key_exists(4, $raw) and $raw[4] == 'yes')
                $raw[4] = true;

            $data[] = $raw;
        }

        fclose($csvFile);

        $movieRepository->deleteAndInsertData($data);

        return new JsonResponse(
            [
                'data' => $movieRepository->findAll()
            ],
            Response::HTTP_OK
        );
    }

    //For debug only
    #[Route('/phpinfoextensions')]
    public function phpinfoExtensions(MovieRepository $movieRepository): JsonResponse
    {
        return new JsonResponse(
            [
                phpinfo(INFO_MODULES)
            ],
            Response::HTTP_OK
        );
    }
}