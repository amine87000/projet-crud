<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('db', 'root', 'root', 'test');
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$champId = $_GET['id'] ?? null;
if ($champId) {
    $champ = $conn->query("SELECT * FROM championnats WHERE id = $champId")->fetch_assoc();
    $clubs = $conn->query("SELECT * FROM clubs WHERE championnat_id = $champId");
} else {
    header('Location: championnat.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Championnat</title>
    <link rel="stylesheet" href="style-form.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="index.php" class="logo">⚽ Football CRUD</a>
        <div class="nav-links">
            <a href="championnat.php" class="nav-link">Championnat</a>
            <a href="logout.php" class="nav-link">Déconnexion</a>
        </div>
    </nav>

    <!-- Détails du Championnat -->
    <div class="container">
        <h2><?= htmlspecialchars($champ['nom']) ?> - Détails</h2>

        <div class="championnat-detail">
            <img src="images/<?= htmlspecialchars($champ['logo']) ?>" alt="<?= htmlspecialchars($champ['nom']) ?>" class="championnat-logo">
            <h3>Clubs Participants</h3>

            <ul class="clubs-list">
                <?php while ($club = $clubs->fetch_assoc()): ?>
                    <li><?= htmlspecialchars($club['nom']) ?></li>
                <?php endwhile; ?>
            </ul>
        </div>

        <a href="championnat.php" class="btn-back">← Retour à la liste des championnats</a>
    </div>

</body>
</html>
