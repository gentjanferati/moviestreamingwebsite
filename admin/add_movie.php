<?php 
include('conn.php');
include('some_functions.php');

if(isset($_POST['tmdb_id'])){
    $tmdb_id = $_POST['tmdb_id'];

    $json = file_get_contents('https://api.themoviedb.org/3/movie/'.$tmdb_id.'?api_key=29b41875fd9cc24c70edbf57405c2458&append_to_response=trailers,credits,images');
    $data = json_decode($json);

    $title = replaceAccents($data->title);
    $imdbID = $data->imdb_id;
    $description = replaceAccents($data->overview);
    $releaseDate = $data->release_date;
    $poster = $data->poster_path;
    $background = $data->images->backdrops[0]->file_path;
    $trailer = $data->trailers->youtube[0]->source;
    $duration = $data->runtime;
    $year = substr($releaseDate,0,4);
    $slug = slugify($title).'-'.$year;
    $genres = [];
    foreach($data->genres as $g) {
        array_push($genres,$g->id);
    }
    $genres = implode(',', $genres);
    $info = file_get_contents('https://www.imdb.com/title/'.$imdbID);
    preg_match('/<span itemprop="ratingValue">(.*?)<\/span>/s',$info, $imdbRating);
    $imdbRating = $imdbRating[1]; // 0 -> with tags | 1 -> no tags just text

    $sql = "INSERT IGNORE INTO media (tmdbID, imdbID, tv, title, description, year, releaseDate, slug, genre, poster, background, url, imdbRating, trailer, duration, views)
                    VALUES ('$tmdb_id', '$imdbID', '0', '$title', '$description', '$year', '$releaseDate', '$slug', '$genres', '$poster', '$background', null, '$imdbRating', '$trailer', '$duration', '0')";

    if($conn->query($sql)) {
        echo 'Movie '.$title.' Created Successfully<br>';

        //Actors
        $actors = $data->credits->cast;
        $i = 0;
        foreach($actors as $a) {
            if($i>=10) {
                break;
            }
            $actorID = $a->id;
            $name = replaceAccents($a->name);
            $photo = $a->profile_path;
            $characterName = replaceAccents($a->character);
            $sql = "INSERT IGNORE INTO actors(actorID,name,profile_photo)
                    VALUES ('$actorID','$name','$photo')";
            if($conn->query($sql)){
                echo "Actor ".$name." Added Successfully<br>";
                $sql = "INSERT IGNORE INTO acts(actorID,tmdbID,characterName)
                        VALUES('$actorID','$tmdb_id','$characterName')";
                if($conn->query($sql)){
                    echo "Actor ".$name." Added Successfully To Movie<br>";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
                $i++;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        //Directors
        $i=0;
        foreach($data->credits->crew as $d) {
            if($i<3) {
                // Get Only Directing Crew
                if($d->department != 'Directing') { continue; } 
                $name = replaceAccents($d->name);
                // Add Directors In Directors DB
                $sql = "INSERT IGNORE INTO directors(directorID,name,profile_photo) VALUES ('$d->id', '$name', '$d->profile_path')";
                if ($conn->query($sql)) {
                    echo "Director ".$name." Created Successfully.<br>";
                    //Add Director To Movie
                    $sql = "INSERT IGNORE INTO directs(directorID,tmdbID) VALUES ('$d->id', '$tmdb_id')";
                    if ($conn->query($sql)) {
                        echo "Director ".$name." Added To Movie Successfully.<br>";
                        $i+=1;
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                break;
            }
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}


?>