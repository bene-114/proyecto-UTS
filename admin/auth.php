<?php
session_start();
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

// Buscar admin en la base de datos
$query = "SELECT * FROM usuarios WHERE email = :email AND rol = 'admin'";
$stmt = $db->prepare($query);
$stmt->bindParam(':email', $usuario);
$stmt->execute();

if ($stmt->rowCount() == 1) {
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if (password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nombre'] = $admin['nombre_completo'];
        header('Location: dashboard.php');
        exit();
    }
}

header('Location: login.php?error=1');