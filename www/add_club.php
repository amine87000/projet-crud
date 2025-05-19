<?php
// Connexion à la base de données
$conn = new mysqli('db', 'root', 'root', 'test');

// Vérifie si la connexion échoue
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère le nom du club et l'ID du championnat
    $club_name = $conn->real_escape_string($_POST['club_name']);
    $champ_id = (int)$_POST['champ_id'];

    // Insérer le nouveau club dans la base de données
    $query = "INSERT INTO clubs (nom, championnat_id) VALUES ('$club_name', $champ_id)";

    if ($conn->query($query)) {
        // Redirige vers la page de modification du championnat après ajout
        header("Location: edit_championnat.php?id=$champ_id");
        exit();
    } else {
        echo "Erreur lors de l'ajout du club : " . $conn->error;
    }
}

$conn->close();
?>
