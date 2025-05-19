<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Connexion à la base de données
$conn = new mysqli('db', 'root', 'root', 'test');
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Pagination
$championnatsParPage = 3;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $championnatsParPage;

// Nombre total de championnats
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM championnats");
$totalChampionnat = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalChampionnat / $championnatsParPage);

// Récupération des championnats avec LIMIT
$championnats = $conn->query("SELECT * FROM championnats LIMIT $championnatsParPage OFFSET $offset");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Championnats</title>
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

<!-- Section principale -->
<div class="container">
    <h2>Liste des Championnats</h2>

    <div class="championnats-list">
        <?php while ($champ = $championnats->fetch_assoc()): ?>
            <div class="championnat-card">
                <?php
                // Affichage du bon logo selon le nom
                $logoMap = [
                    'Ligue 1' => 'ligue1.jpg',
                    'La Liga' => 'liga.jpg',
                    'Premier League' => 'premierleague.png',
                    'Serie A' => 'seriea.jpg',
                    'Bundesliga' => 'bundesliga.jpg'
                ];
                $nom = htmlspecialchars($champ['nom']);
                $logo = isset($logoMap[$nom]) ? $logoMap[$nom] : 'default.png';
                ?>
                <img src="assets/<?= $logo ?>" alt="<?= $nom ?>" class="championnat-logo">
                <h3><?= $nom ?></h3>
                <a href="championnat_detail.php?id=<?= $champ['id'] ?>" class="btn-view-clubs">Voir les clubs</a>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=1">&laquo;</a>
            <a href="?page=<?= $page - 1 ?>">&lt;</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>">&gt;</a>
            <a href="?page=<?= $totalPages ?>">&raquo;</a>
        <?php endif; ?>
    </div>

    <a href="index.php" class="btn-back">← Retour à l'accueil</a>
</div>

</body>
</html>
