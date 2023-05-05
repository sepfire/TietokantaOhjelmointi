<?php
include 'pdo_connect.php';
include 'headers.php';

$artist_name = "Matti Pitkänen2";
$album_title = "JumputiJumputi";
$track_name = "Humpparalli";

try{
  // aloitetaan transaktio
    $pdo->beginTransaction();

    //lisätään artisti
      $query = "INSERT INTO artists (Name) VALUES (?)";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$artist_name]);

    //haetaan artistin id
      $artist_id = $pdo->lastInsertId();

    //lisätään albumi
      $query = "INSERT INTO albums (Title, ArtistId) VALUES (?, ?)";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$album_title, $artist_id]);

    //haetaan albumin id
      $album_id = $pdo->lastInsertId();

    //lisätään kappale
    $query = "INSERT INTO tracks (Name, AlbumId, MediaTypeId) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$track_name, $album_id, 1]);
    //vahvistetaan transaktio
      $pdo->commit();
      echo "Artist added successfully";
  } catch (PDOException $e) {
    //perutaan transaktio
    $pdo->rollback();
    echo "Error adding artist: " . $e->getMessage();
  }