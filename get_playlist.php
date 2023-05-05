<?php
include ('pdo_connect.php');
include ('headers.php');

$playlist_id = 1; // Playlist ID testikäyttöön tämä haettas frontista 

if (isset($_GET["playlist_id"])) {
    $playlist_id = $_GET["playlist_id"];
}

$stmt = $pdo->prepare("
    SELECT tracks.Name as track_name, tracks.Composer as track_composer
    FROM playlist_track
    JOIN tracks ON playlist_track.TrackId = tracks.TrackId
    JOIN albums ON tracks.AlbumId = albums.AlbumId
    JOIN artists ON albums.ArtistId = artists.ArtistId
    WHERE playlist_track.PlaylistId = :playlist_id
");
$stmt->execute(array(':playlist_id' => $playlist_id));
$tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($tracks) > 0) {
    echo "<ul>";
    foreach ($tracks as $track) {
        echo "<li>{$track['track_name']} <br>{$track['track_composer']}</li>";
    }
    echo "</ul>";
} else {
    echo "No tracks found for playlist ID $playlist_id.";
}
