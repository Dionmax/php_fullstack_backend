<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Movie;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $moviesData = [
            [
                "year" => 2019,
                "title" => "Movie 20",
                "studios" => "Studio T",
                "producers" => "Producer 20",
                "winner" => 0
            ],
            [
                "year" => 2018,
                "title" => "Movie 19",
                "studios" => "Studio S",
                "producers" => "Producer 19",
                "winner" => 0
            ],
            [
                "year" => 2017,
                "title" => "Movie 18",
                "studios" => "Studio R",
                "producers" => "Producer 18",
                "winner" => 0
            ],
            [
                "year" => 2016,
                "title" => "Movie 17",
                "studios" => "Studio Q",
                "producers" => "Producer 17",
                "winner" => 0
            ],
            [
                "year" => 2015,
                "title" => "Movie 16",
                "studios" => "Studio P",
                "producers" => "Producer 16",
                "winner" => 0
            ],
            [
                "year" => 2014,
                "title" => "Movie 15",
                "studios" => "Studio O",
                "producers" => "Producer 15",
                "winner" => 0
            ],
            [
                "year" => 2013,
                "title" => "Movie 14",
                "studios" => "Studio N",
                "producers" => "Producer 14",
                "winner" => 0
            ],
            [
                "year" => 2012,
                "title" => "Movie 13",
                "studios" => "Studio M",
                "producers" => "Producer 13",
                "winner" => 0
            ],
            [
                "year" => 2011,
                "title" => "Movie 12",
                "studios" => "Studio L",
                "producers" => "Producer 12",
                "winner" => 0
            ],
            [
                "year" => 2010,
                "title" => "Movie 11",
                "studios" => "Studio K",
                "producers" => "Producer 11",
                "winner" => 0
            ],
            [
                "year" => 2009,
                "title" => "Movie 10",
                "studios" => "Studio J",
                "producers" => "Producer 2",
                "winner" => 0
            ],
            [
                "year" => 2008,
                "title" => "Movie 9",
                "studios" => "Studio I",
                "producers" => "Producer 2",
                "winner" => 1
            ],
            [
                "year" => 2007,
                "title" => "Movie 8",
                "studios" => "Studio H",
                "producers" => "Producer 2",
                "winner" => 1
            ],
            [
                "year" => 2006,
                "title" => "Movie 7",
                "studios" => "Studio G",
                "producers" => "Producer 2",
                "winner" => 1
            ],
            [
                "year" => 2005,
                "title" => "Movie 6",
                "studios" => "Studio F",
                "producers" => "Producer 2",
                "winner" => 1
            ],
            [
                "year" => 2004,
                "title" => "Movie 5",
                "studios" => "Studio E",
                "producers" => "Producer 1",
                "winner" => 1
            ],
            [
                "year" => 2004,
                "title" => "Movie 4",
                "studios" => "Studio D",
                "producers" => "Producer 1",
                "winner" => 1
            ],
            [
                "year" => 2000,
                "title" => "Movie 3",
                "studios" => "Studio C",
                "producers" => "Producer 1",
                "winner" => 1
            ],
            [
                "year" => 2000,
                "title" => "Movie 2",
                "studios" => "Studio B",
                "producers" => "Producer 1",
                "winner" => 1
            ],
            [
                "year" => 2000,
                "title" => "Movie 1",
                "studios" => "Studio A",
                "producers" => "Producer 1",
                "winner" => 1
            ]

            // Add more movies as needed
        ];

        foreach ($moviesData as $movieData) {
            $movie = new Movie();
            $movie->setYear($movieData['year']);
            $movie->setTitle($movieData['title']);
            $movie->setStudios($movieData['studios']);
            $movie->setProducers($movieData['producers']);
            $movie->setWinner($movieData['winner']);

            $manager->persist($movie);
        }

        $manager->flush();
    }
}