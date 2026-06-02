<?php
session_start();
include 'includes/header.php';

// Redirigir si ya está logueado
if (isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'config/database.php';

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        $query = "SELECT id, nombre_completo, email, password, rol FROM usuarios WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() == 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (password_verify($password, $usuario['password'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre_completo'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_rol'] = $usuario['rol'];
                
                // Redirigir a página anterior o index
                $redirect = $_GET['redirect'] ?? 'index.php';
                header("Location: $redirect");
                exit();
            } else {
                $mensaje = "Contraseña incorrecta";
                $tipo_mensaje = "danger";
            }
        } else {
            $mensaje = "Usuario no encontrado";
            $tipo_mensaje = "danger";
        }
    } else {
        $mensaje = "Por favor completa todos los campos";
        $tipo_mensaje = "danger";
    }
}
?>

<!-- Navbar simple para login -->
<nav class="navbar navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="img/img2.jpg" alt="UT Selva" height="100">
            
        </a>
    </div>
</nav>
<div class="nav-spacer"></div>

<section class="login-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <div class="text-center mb-4">
                        <img src="img/logo.jpg" alt="UT Selva" height="100">
                        <h3 class="mt-3">Iniciar Sesión</h3>
                    </div>
                    
                    <?php if($mensaje): ?>
                        <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show">
                            <?php echo $mensaje; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="recordar">
                            <label class="form-check-label" for="recordar">Recordarme</label>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">INICIAR SESIÓN</button>
                        
                        <div class="text-center mt-3">
                            <a href="registro.php">¿No tienes cuenta? Regístrate</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer simple -->
<footer class="bg-dark text-white text-center py-3">
    <div class="container">
        <p class="mb-0">Universidad Tecnológica de la Selva - Unidad Benemérito</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>