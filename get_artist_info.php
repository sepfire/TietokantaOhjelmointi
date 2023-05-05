<?php
include 'pdo_connect.php';
include 'headers.php';

// Haetaan artistin tiedot artist_id:n perusteella
$artist_id = 90;

// Haetaan artistin nimi
$query = "SELECT Name FROM artists WHERE ArtistId = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$artist_id]);
$artist = $stmt->fetch(PDO::FETCH_ASSOC);

// Haetaan artistin albumit ja kappaleet
$query = "SELECT albums.Title as album_title, tracks.Name as track_name
          FROM albums
          JOIN tracks ON tracks.AlbumId = albums.AlbumId
          WHERE albums.ArtistId = ?
          ORDER BY albums.Title";
$stmt = $pdo->prepare($query);
$stmt->execute([$artist_id]);
$albums = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Luodaan tulos JSON-muodossa
$result = array(
    "artist" => $artist['Name'],
    "albums" => array(),
);
$current_album = null;
foreach ($albums as $album) {
    // Jos albumia ei ole vielä käsitelty, lisätään se tuloksiin
    if ($current_album !== $album['album_title']) {
        $result['albums'][] = array(
            "album_title" => $album['album_title'],
            "tracks" => array($album['track_name']),
        );
        $current_album = $album['album_title'];
    // Muussa tapauksessa lisätään kappale albumin tietueeseen
    } else {
        end($result['albums']);
        $key = key($result['albums']);
        $result['albums'][$key]['tracks'][] = $album['track_name'];
    }
}
if (empty($result)) {
    echo "No results found";
    exit();
}
echo json_encode($result);
?>
