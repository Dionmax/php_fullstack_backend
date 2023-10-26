<?php

namespace App\Tests\Controller;

use App\Controller\MovieController;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\Tests\Models\Cache\Client;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function Webmozart\Assert\Tests\StaticAnalysis\object;

class MovieControllerTest extends WebTestCase
{
//    private $container;
//    private $movieController;
//    private $movieRepository;

    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient([], [
            'HTTP_HOST' => 'localhost:8000',
        ]);
    }

    public function testGetWinnerMinMaxMovies()
    {
        $this->client->jsonRequest('GET', '/api/movies/winners');
        $response = $this->client->getResponse();

        $data = '
            {
                "min": {
                    "producer": "Producer 1",
                    "interval": 0,
                    "previousWin": "2000",
                    "followingWin": "2000"
                },
                "max": {
                    "producer": "Producer 2",
                    "interval": 1,
                    "previousWin": "2005",
                    "followingWin": "2006"
                }
            }';

        self::assertEquals(json_decode($response->getContent()), json_decode($data));
    }

    public function testGetMovies(): void
    {
        $this->client->jsonRequest('GET', '/api/movies/all');
        $response = $this->client->getResponse();

        $data = '[
                    [
                        {
                            "id": 40,
                            "year": 2000,
                            "title": "Movie 1",
                            "studios": "Studio A",
                            "producers": "Producer 1",
                            "winner": 1
                        },
                        {
                            "id": 39,
                            "year": 2000,
                            "title": "Movie 2",
                            "studios": "Studio B",
                            "producers": "Producer 1",
                            "winner": 1
                        },
                        {
                            "id": 38,
                            "year": 2000,
                            "title": "Movie 3",
                            "studios": "Studio C",
                            "producers": "Producer 1",
                            "winner": 1
                        },
                        {
                            "id": 37,
                            "year": 2004,
                            "title": "Movie 4",
                            "studios": "Studio D",
                            "producers": "Producer 1",
                            "winner": 1
                        },
                        {
                            "id": 36,
                            "year": 2004,
                            "title": "Movie 5",
                            "studios": "Studio E",
                            "producers": "Producer 1",
                            "winner": 1
                        },
                        {
                            "id": 35,
                            "year": 2005,
                            "title": "Movie 6",
                            "studios": "Studio F",
                            "producers": "Producer 2",
                            "winner": 1
                        },
                        {
                            "id": 34,
                            "year": 2006,
                            "title": "Movie 7",
                            "studios": "Studio G",
                            "producers": "Producer 2",
                            "winner": 1
                        },
                        {
                            "id": 33,
                            "year": 2007,
                            "title": "Movie 8",
                            "studios": "Studio H",
                            "producers": "Producer 2",
                            "winner": 1
                        },
                        {
                            "id": 32,
                            "year": 2008,
                            "title": "Movie 9",
                            "studios": "Studio I",
                            "producers": "Producer 2",
                            "winner": 1
                        },
                        {
                            "id": 31,
                            "year": 2009,
                            "title": "Movie 10",
                            "studios": "Studio J",
                            "producers": "Producer 2",
                            "winner": 1
                        },
                        {
                            "id": 30,
                            "year": 2010,
                            "title": "Movie 11",
                            "studios": "Studio K",
                            "producers": "Producer 11",
                            "winner": 1
                        },
                        {
                            "id": 29,
                            "year": 2011,
                            "title": "Movie 12",
                            "studios": "Studio L",
                            "producers": "Producer 12",
                            "winner": 1
                        },
                        {
                            "id": 28,
                            "year": 2012,
                            "title": "Movie 13",
                            "studios": "Studio M",
                            "producers": "Producer 13",
                            "winner": 1
                        },
                        {
                            "id": 27,
                            "year": 2013,
                            "title": "Movie 14",
                            "studios": "Studio N",
                            "producers": "Producer 14",
                            "winner": 1
                        },
                        {
                            "id": 26,
                            "year": 2014,
                            "title": "Movie 15",
                            "studios": "Studio O",
                            "producers": "Producer 15",
                            "winner": 1
                        },
                        {
                            "id": 25,
                            "year": 2015,
                            "title": "Movie 16",
                            "studios": "Studio P",
                            "producers": "Producer 16",
                            "winner": 1
                        },
                        {
                            "id": 24,
                            "year": 2016,
                            "title": "Movie 17",
                            "studios": "Studio Q",
                            "producers": "Producer 17",
                            "winner": 1
                        },
                        {
                            "id": 23,
                            "year": 2017,
                            "title": "Movie 18",
                            "studios": "Studio R",
                            "producers": "Producer 18",
                            "winner": 1
                        },
                        {
                            "id": 22,
                            "year": 2018,
                            "title": "Movie 19",
                            "studios": "Studio S",
                            "producers": "Producer 19",
                            "winner": 1
                        },
                        {
                            "id": 21,
                            "year": 2019,
                            "title": "Movie 20",
                            "studios": "Studio T",
                            "producers": "Producer 20",
                            "winner": 1
                        }
                    ]
                ]';

        self::assertEquals(json_decode($response->getContent()), json_decode($data));
    }

    public function testGetMovie()
    {
        $this->client->jsonRequest('GET', '/api/movies/all');
        $response1 = $this->client->getResponse();

        $first = array(json_decode($response1->getContent(), true))[0][0][0];

        $this->client->jsonRequest('GET', '/api/movies/movie/' . $first['id']);
        $response2 = $this->client->getResponse();

        self::assertEquals(json_decode($response2->getContent(), true)[0], $first);

    }
}