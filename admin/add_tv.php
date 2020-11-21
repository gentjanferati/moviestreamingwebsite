<?php 
include('conn.php');
include('some_functions.php');

if(isset($_POST['tmdb_id'])){
    $tmdb_id = $_POST['tmdb_id'];

    $json = file_get_contents('https://api.themoviedb.org/3/tv/'.$tmdb_id.'?api_key=29b41875fd9cc24c70edbf57405c2458&append_to_response=videos,external_ids,credits,images');
    $data = json_decode($json);

    //Add background [backdrop]
    //Add directors

    $title = replaceAccents($data->name);
    $imdbID = $data->external_ids->imdb_id;
    $description = replaceAccents($data->overview);
    $releaseDate = $data->first_air_date;
    $poster = $data->poster_path;
    $background = $data->images->backdrops[0]->file_path;
    $trailer = $data->videos->results[0]->key;
    $duration = $data->episode_run_time[0];
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

    $sql = "INSERT IGNORE INTO media (tmdbID, imdbID, tv, title, description, year, releaseDate, slug, genre, poster,background,url, imdbRating, trailer, duration, views)
                    VALUES ('$tmdb_id', '$imdbID', '1', '$title', '$description', '$year', '$releaseDate', '$slug', '$genres', '$poster','$background', null, '$imdbRating', '$trailer', '$duration', '0')";
    if($conn->query($sql)) {
        echo 'TV Show '.$title.' Added Successfully<br>';

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
                    echo "Actor ".$name." Added Successfully To Tv Show";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
                $i++;
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        $directors = $data->created_by;
        $i=0;
        foreach($directors as $d) {
            if($i>=3){
            break;
            }
            $directors_id = $d->id;
            $name = replaceAccents($d->name);
            $photo = $d->profile_path;
            $sql = "INSERT IGNORE INTO directors(directorID,name,profile_photo)
                                    VALUES('$directors_id','$name','$photo')";

            if($conn->query($sql)){
                echo "Director ".$name." Created Successfully.<br>";
                $i++;
                $sql ="INSERT IGNORE INTO directs(directorID,tmdbID)
                                VALUES('$directors_id','$tmdb_id')";
                if($conn->query($sql)){
                    echo "Director ".$name." Added To Movie Successfully.<br>";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }           
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Add Seasons
        $seasons = $data->seasons;
        foreach($seasons as $s) {
            if($s->air_date == null || strtotime($s->air_date) >= strtotime(date("Y-m-d")) ) {continue;} // Only Released Seasons Only
            $season = $s->season_number;
            if($season == 0) { continue; } //Don't include season 0
            
            $sql = "INSERT IGNORE INTO season (tmdbID,season,poster) VALUES ('$tmdb_id', '$season', '$s->poster_path')";
            if($conn->query($sql)) {
                echo 'Season '.$season.' Added Successfully.<br>';
                $json = file_get_contents('https://api.themoviedb.org/3/tv/'.$tmdb_id.'/season/'.$season.'?api_key=29b41875fd9cc24c70edbf57405c2458');
                $data = json_decode($json);

                //Add Episodes
                $episodes = $data->episodes;
                foreach($episodes as $e) {
                    $episode = $e->episode_number;
                    $slug = slugify($title).'-'.$season.'-'.$episode;
                    $ep_title = replaceAccents($e->name);
                    $description = replaceAccents($e->overview);
                    $airdate = $e->air_date;

                    if($airdate == null || strtotime($airdate) >= strtotime(date("Y-m-d"))) {break;} // Only Released Episodes Only
                    
                    $sql = "INSERT IGNORE INTO 
                            episode (tmdbID, season, episode, slug, title, url, description, airdate) 
                            VALUES ('$tmdb_id','$season','$episode','$slug','$ep_title',null,'$description','$airdate')";
                    if ($conn->query($sql)) {
                        echo "Episode ".$episode."Created Successfully.<br>";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        echo $conn->error;
    }
} else {
    echo 'Error';
}
?>