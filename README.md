# php_fullstack_backend

## Requirements
PHP +8.2 installed is required to work, with the PDO sqlite extension activated.

![image](https://github.com/Dionmax/php_fullstack_backend/assets/28550074/ed88e65f-c160-4552-92a5-1b0d90da25b0)

#### There is a route to do this check: http://localhost:8000/movies/phpinfoextensions


## Start
To start the project, run: `symfony server:start` inside the project folder

A message will appear:

![image](https://github.com/Dionmax/php_fullstack_backend/assets/28550074/f33e3ff2-cba7-4718-87b7-ca3acf2efd68)

#### This number indicates where the synfony server is running, if it is not 127.0.0.1:8000 (localhost:8000), please change the postman environment variables (the postman workspace will be available [here](https://github.com/Dionmax/php_fullstack_backend/tree/master/data)).


## Database
To change the database, change the `movielist.csv` file in the project's data folder, keeping the same name.

Then via the api call the route `http://localhost:8000/movies/resetdatabase`

It was done this way to make it easier to change the database.

The CSV is not being verified and I expect columns in the format: year, title, studios, producers, winner.
