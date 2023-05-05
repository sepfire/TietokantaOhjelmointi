<?php
include 'pdo_connect.php';
include 'headers.php';
// artisti mikä poistetaan. Normaalisti haettas arvo inputista $S_post['artist_id'] metodilla
$artist_id = 3;

try {
    $pdo->beginTransaction();

    // Poista tiedot invoice_items-taulusta, jotka viittaavat artistin kappaleisiin
    $stmt = $pdo->prepare('DELETE FROM invoice_items WHERE TrackId IN 
    (SELECT TrackId FROM tracks JOIN albums ON tracks.AlbumId = albums.AlbumId WHERE albums.ArtistId = :artist_id)');
    $stmt->execute(['artist_id' => $artist_id]);
    
    // Poistetaan tiedot playlist_track-taulusta, jotka viittaavat artistin kappaleisiin
    $stmt = $pdo->prepare('DELETE FROM playlist_track WHERE TrackId IN (SELECT TrackId FROM tracks JOIN albums ON tracks.AlbumId = albums.AlbumId WHERE albums.ArtistId = :artist_id)');
    $stmt->execute(['artist_id' => $artist_id]);

    // Poista artistin kappaleet
    $stmt = $pdo->prepare('DELETE FROM tracks WHERE AlbumId IN 
    (SELECT AlbumId FROM albums WHERE ArtistId = :artist_id)');
    $stmt->execute(['artist_id' => $artist_id]);
    
    // Poista artistin albumit
    $stmt = $pdo->prepare('DELETE FROM albums WHERE ArtistId = :artist_id');
    $stmt->execute(['artist_id' => $artist_id]);

    // Poista artisti
    $stmt = $pdo->prepare('DELETE FROM artists WHERE ArtistId = :artist_id');
    $stmt->execute(['artist_id' => $artist_id]);
    
    // Tallenna muutokset
    $pdo->commit();
    echo 'Artisti poistettiin onnistuneesti.';

    // Virheenkäsittely
} catch (Exception $e) {
    $pdo->rollback();
    echo 'Virhe artistia poistettaessa: ' . $e->getMessage();
}
?>
