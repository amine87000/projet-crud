<?php
session_start();

// Connexion à la base de données
$conn = new mysqli('db', 'root', 'root', 'test');

// Vérifie si la connexion échoue
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Requête pour récupérer les championnats
$query = "SELECT * FROM championnats";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $championnats = [];
    while ($row = $result->fetch_assoc()) {
        $championnats[] = $row;
    }
} else {
    $championnats = [];
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Projet Football CRUD</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="logo">Mon Projet CRUD Football</a>
            
            <div class="nav-links">
                <?php if(isset($_SESSION['user'])): ?>
                    <a href="championnat.php" class="nav-link">Championnats</a>
                    <a href="logout.php" class="nav-link">Déconnexion</a>
                <?php else: ?>
                    <div class="dropdown">
                        <button class="dropbtn">Menu <i class="fas fa-chevron-down"></i></button>
                        <div class="dropdown-content">
                            <a href="register.php" class="dropdown-link"><i class="fas fa-user-plus"></i> S'inscrire</a>
                            <a href="login.php" class="dropdown-link"><i class="fas fa-sign-in-alt"></i> Se connecter</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <button class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Gestion des Championnats de Football</h1>
            <p class="hero-subtitle">Explorez les plus grands championnats européens</p>
            
            <?php if(!isset($_SESSION['user'])): ?>
                <div class="auth-buttons">
                    <a href="register.php" class="cta-btn primary">Inscription <i class="fas fa-user-plus"></i></a>
                    <a href="login.php" class="cta-btn secondary">Connexion <i class="fas fa-sign-in-alt"></i></a>
                </div>
            <?php else: ?>
                <a href="championnat.php" class="cta-btn primary">Accéder aux Championnats <i class="fas fa-arrow-right"></i></a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Championnats Preview (visible uniquement après connexion) -->
    <?php if(isset($_SESSION['user'])): ?>
    <section class="championnats-section">
        <div class="container">
            <h2>Les 5 Grands Championnats</h2>
            <div class="championnats-list">
                <!-- Exemple de championnat - Les données doivent venir de votre base de données -->
                <div class="championnat-card">
                    <div class="championnat-header">
                        <img src="premier-league.png" alt="Premier League">
                        <h3>Premier League</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="clubs-list">
                        <ul>
                            <li>Manchester City</li>
                            <li>Liverpool</li>
                            <li>Chelsea</li>
                            <!-- Ajouter d'autres clubs -->
                        </ul>
                    </div>
                </div>
                <!-- Répéter pour les autres championnats -->
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Championnats list for non-logged users -->
    <?php if (!isset($_SESSION['user'])): ?>
    <section class="championnats-section">
        <div class="container">
            <h2>Les Championnats Disponibles</h2>
            <div class="championnats-list">
                <?php foreach ($championnats as $champ): ?>
                    <div class="championnat-card">
                        <h3><?= htmlspecialchars($champ['nom']) ?></h3>
                        <p>
                            <a href="login.php">Connectez-vous pour voir les détails</a>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <script>
        $(document).ready(function() {
            // Menu hamburger
            $('.hamburger').click(function() {
                $(this).toggleClass('active');
                $('.nav-links').toggleClass('active');
            });

            // Dropdown des championnats
            $('.championnat-header').click(function() {
                $(this).parent().toggleClass('active');
                $(this).next('.clubs-list').slideToggle(300);
            });

            // Animation du menu dropdown
            $('.dropbtn').click(function(e) {
                e.preventDefault();
                $(this).parent().toggleClass('active');
                $('.dropdown-content').slideToggle(300);
            });

            // Fermer les menus en cliquant ailleurs
            $(document).click(function(e) {
                if (!$(e.target).closest('.nav-container').length) {
                    $('.nav-links').removeClass('active');
                    $('.hamburger').removeClass('active');
                    $('.dropdown-content').slideUp(300);
                }
            });
        });
    </script>
</body>
</html>
