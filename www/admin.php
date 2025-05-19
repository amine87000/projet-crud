<?php
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$conn = new mysqli('db', 'root', 'root', 'test');

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if (isset($_GET['delete_user'])) {
    $userId = (int) $_GET['delete_user'];
    $conn->query("DELETE FROM users WHERE id = $userId");
    header("Location: admin.php");
    exit();
}

if (isset($_GET['delete_champ'])) {
    $champId = (int) $_GET['delete_champ'];
    $conn->query("DELETE FROM championnats WHERE id = $champId");
    header("Location: admin.php");
    exit();
}

$users = $conn->query("SELECT * FROM users");
$championnats = $conn->query("SELECT * FROM championnats");

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panneau d'administration</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-container">
        <h1>Panneau d'administration</h1>
        <div class="nav">
            <a href="championnat.php">Voir les championnats</a>
            <a href="logout.php">Déconnexion</a>
        </div>

        <h2>Utilisateurs</h2>
        <a class="btn-add" href="add_user.php">➕ Ajouter un utilisateur</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= $user['role'] ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>">Modifier</a>
                        <a href="?delete_user=<?= $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>Championnat</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($champ = $championnats->fetch_assoc()): ?>
                <tr>
                    <td><?= $champ['id'] ?></td>
                    <td><?= htmlspecialchars($champ['nom']) ?></td>
                    <td>
                        <a href="edit_championnat.php?id=<?= $champ['id'] ?>">Modifier</a>
                        <a href="?delete_champ=<?= $champ['id'] ?>" onclick="return confirm('Supprimer ce championnat ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
