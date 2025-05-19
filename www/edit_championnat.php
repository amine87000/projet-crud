<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('db', 'root', 'root', 'test');
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$champId = $_GET['id'] ?? null;

if ($champId) {
    $champQuery = $conn->query("SELECT * FROM championnats WHERE id = $champId");
    $champ = $champQuery->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_club']) && !empty($_POST['nom_club'])) {
            $nom_club = $conn->real_escape_string($_POST['nom_club']);
            $conn->query("INSERT INTO clubs (nom, championnat_id) VALUES ('$nom_club', $champId)");
        } elseif (isset($_POST['delete_club'])) {
            $clubId = $_POST['club_id'];
            $conn->query("DELETE FROM clubs WHERE id = $clubId");
        }
    }

    $clubs = $conn->query("SELECT * FROM clubs WHERE championnat_id = $champId");
} else {
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Championnat</title>
    <link rel="stylesheet" href="style-form.css">
</head>
<body>
    <div class="container">
        <h2>Modifier le championnat : <?= htmlspecialchars($champ['nom']) ?></h2>
        <form method="POST">
            <input type="text" name="nom_club" placeholder="Nom du club à ajouter">
            <button type="submit" name="add_club">Ajouter le club</button>
        </form>

        <h3>Clubs déjà présents :</h3>
        <ul>
            <?php while ($club = $clubs->fetch_assoc()): ?>
                <li>
                    <?= htmlspecialchars($club['nom']) ?>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="club_id" value="<?= $club['id'] ?>">
                        <button type="submit" name="delete_club">Supprimer</button>
                    </form>
                </li>
            <?php endwhile; ?>
        </ul>

        <a href="admin.php" class="btn-back">← Retour à l'administration</a>
    </div>
</body>
</html>
