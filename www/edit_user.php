<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('db', 'root', 'root', 'test');
$id = $_GET['id'];
$user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET username='$username', password='$password', role='$role' WHERE id=$id");
    } else {
        $conn->query("UPDATE users SET username='$username', role='$role' WHERE id=$id");
    }

    $conn->close();
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'utilisateur</title>
    <link rel="stylesheet" href="admin_style.css">
</head>
<body>
    <div class="admin-container">
        <h1>Modifier l'utilisateur</h1>
        <form method="post">
            <label>Nom d'utilisateur</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
            <input type="password" name="password">

            <label>Rôle</label>
            <select name="role" required>
                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>Utilisateur</option>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Administrateur</option>
            </select>

            <button type="submit">Enregistrer</button>
            <a href="admin.php" class="btn-secondary">Retour</a>
        </form>
    </div>
</body>
</html>
