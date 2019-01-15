select film.id_genre as id_genre, ifnull(genre.name, '') as name_genre, ifnull(film.id_distrib, '') as id_distrib, ifnull(distrib.name, '') as distrib_name, film.title as title from film inner join genre on ifnull(film.id_genre, '') = genre.id_genre inner join distrib on ifnull(film.id_distrib, '') = distrib.id_distrib where film.id_genre between 4 and 8;
