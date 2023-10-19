<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movies")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("")
     */
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

    /**
     * @Route("/resetdatabase")
     */
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

    /**
     * @Route("/phpinfoextensions")
     */
    public function phpinfoExtensions(MovieRepository $movieRepository)
    {
        return new JsonResponse(
            [
                phpinfo(INFO_MODULES)
            ],
            Response::HTTP_OK
        );
    }
}