tech used- php, mysql,phpmyadmin,xampp(apache server), rapidAPI- online movie database API- 500 requests/month.

place project in files -> xampp/ htdocs/
save as: film-website
phymyadmin- http://localhost/phymyadmin
create database: websitedb
import the database file: database/websitedb.sql
project in browser: http://localhost/film-website/
demo pages:
register: http://localhost/film-website/auth/register.php
login:http://localhost/film-website/auth/login.php
search films: http://localhost/film-website/backend/search_demo.php
browse genres: http://localhost/film-website/backend/genre_demo.php
film overview example: http://localhost/film-website/backend/film_overview.php?id=tt0120338
add review: http://localhost/film-website/backend/add_review.php


conventions: 
id=movie id
l= movie title
y=release year
i.imageUrl= movie poster image
qid= type of result e.g. movie, tv series
e.g. title:movie.l ,Poster: movie.i.imageUrl, Year:movie.y, Movie ID:movie.id