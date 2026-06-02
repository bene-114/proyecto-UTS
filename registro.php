<?php
include 'includes/header.php';
include 'includes/navbar.php';

require_once 'config/database.php';

$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    
    $nombre = $_POST['nombre_completo'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validaciones
    $errores = [];
    
    if (empty($nombre)) {
        $errores[] = "El nombre completo es requerido";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Correo electrónico no válido";
    }
    
    if (strlen($password) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres";
    }
    
    if ($password !== $confirm_password) {
        $errores[] = "Las contraseñas no coinciden";
    }
    
    // Verificar si el email ya existe
    if (empty($errores)) {
        $query = "SELECT id FROM usuarios WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $errores[] = "El correo electrónico ya está registrado";
        }
    }
    
    if (empty($errores)) {
        // Hash de contraseña
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insertar usuario
        $query = "INSERT INTO usuarios (nombre_completo, email, password) 
                  VALUES (:nombre, :email, :password)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password_hash);
        
        if ($stmt->execute()) {
            $mensaje = "Registro exitoso. Ahora puedes iniciar sesión.";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al registrar. Intenta de nuevo.";
            $tipo_mensaje = "danger";
        }
    } else {
        $mensaje = implode("<br>", $errores);
        $tipo_mensaje = "danger";
    }
}
?>

<section class="page-header">
    <div class="container">
        <h1>REGISTRO DE ASPIRANTES</h1>
        <p>Completa tus datos para iniciar tu proceso de admisión</p>
    </div>
</section>

<section class="registro-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="registro-card">
                    <div class="card-header">
                        <h3 class="text-center mb-0">Formulario de Registro</h3>
                    </div>
                    <div class="card-body">
                        
                        <?php if($mensaje): ?>
                            <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show">
                                <?php echo $mensaje; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" id="registroForm">
                            <div class="mb-3">
                                <label for="nombre_completo" class="form-label">
                                    <i class="bi bi-person"></i> Nombre Completo *
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nombre_completo" 
                                       name="nombre_completo" 
                                       required
                                       value="<?php echo $_POST['nombre_completo'] ?? ''; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i> Correo Electrónico *
                                </label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       required
                                       value="<?php echo $_POST['email'] ?? ''; ?>">
                                <div class="form-text">Usaremos este correo para comunicarnos contigo</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock"></i> Contraseña *
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password" 
                                       name="password" 
                                       required
                                       minlength="6">
                                <div class="form-text">Mínimo 6 caracteres</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">
                                    <i class="bi bi-lock"></i> Confirmar Contraseña *
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="confirm_password" 
                                       name="confirm_password" 
                                       required>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="terminos" required>
                                <label class="form-check-label" for="terminos">
                                    Acepto los <a href="#" data-bs-toggle="modal" data-bs-target="#terminosModal">términos y condiciones</a>
                                </label>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-check-circle"></i> REGISTRARME
                                </button>
                            </div>
                            
                            <div class="text-center mt-3">
                                <p>¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Términos y Condiciones -->
<div class="modal fade" id="terminosModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Términos y Condiciones</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Al registrarte en la Universidad Tecnológica de la Selva, aceptas:</p>
                <ul>
                    <li>Proporcionar información verídica y actualizada</li>
                    <li>Cumplir con los requisitos de admisión establecidos</li>
                    <li>Mantener un promedio mínimo de 7.0 durante tus estudios</li>
                    <li>Respetar el reglamento institucional</li>
                    <li>Los datos proporcionados serán tratados conforme a la Ley de Protección de Datos</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>