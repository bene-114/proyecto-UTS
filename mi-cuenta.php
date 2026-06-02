<?php
session_start();
include 'includes/header.php';
include 'includes/navbar.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?redirect=mi-cuenta.php');
    exit();
}

require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$mensaje = '';
$tipo_mensaje = '';

// Obtener datos actuales del usuario
$query = "SELECT id, nombre_completo, email, fecha_registro, rol FROM usuarios WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Procesar actualización de datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_POST['actualizar_perfil'])) {
        $nuevo_nombre = $_POST['nombre_completo'] ?? '';
        $nuevo_email = $_POST['email'] ?? '';
        
        // Validaciones
        $errores = [];
        
        if (empty($nuevo_nombre)) {
            $errores[] = "El nombre no puede estar vacío";
        }
        
        if (!filter_var($nuevo_email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El correo electrónico no es válido";
        }
        
        // Verificar si el email ya existe (y no es el suyo)
        $check_query = "SELECT id FROM usuarios WHERE email = :email AND id != :id";
        $check_stmt = $db->prepare($check_query);
        $check_stmt->bindParam(':email', $nuevo_email);
        $check_stmt->bindParam(':id', $_SESSION['usuario_id']);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            $errores[] = "El correo electrónico ya está registrado por otro usuario";
        }
        
        if (empty($errores)) {
            $update_query = "UPDATE usuarios SET nombre_completo = :nombre, email = :email WHERE id = :id";
            $update_stmt = $db->prepare($update_query);
            $update_stmt->bindParam(':nombre', $nuevo_nombre);
            $update_stmt->bindParam(':email', $nuevo_email);
            $update_stmt->bindParam(':id', $_SESSION['usuario_id']);
            
            if ($update_stmt->execute()) {
                $_SESSION['usuario_nombre'] = $nuevo_nombre;
                $_SESSION['usuario_email'] = $nuevo_email;
                
                $mensaje = "Perfil actualizado correctamente";
                $tipo_mensaje = "success";
                
                // Recargar datos
                $usuario['nombre_completo'] = $nuevo_nombre;
                $usuario['email'] = $nuevo_email;
            } else {
                $mensaje = "Error al actualizar el perfil";
                $tipo_mensaje = "danger";
            }
        } else {
            $mensaje = implode("<br>", $errores);
            $tipo_mensaje = "danger";
        }
    }
    
    if (isset($_POST['cambiar_password'])) {
        $password_actual = $_POST['password_actual'] ?? '';
        $password_nuevo = $_POST['password_nuevo'] ?? '';
        $password_confirmar = $_POST['password_confirmar'] ?? '';
        
        $errores = [];
        
        // Verificar contraseña actual
        $pass_query = "SELECT password FROM usuarios WHERE id = :id";
        $pass_stmt = $db->prepare($pass_query);
        $pass_stmt->bindParam(':id', $_SESSION['usuario_id']);
        $pass_stmt->execute();
        $pass_data = $pass_stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!password_verify($password_actual, $pass_data['password'])) {
            $errores[] = "La contraseña actual es incorrecta";
        }
        
        if (strlen($password_nuevo) < 6) {
            $errores[] = "La nueva contraseña debe tener al menos 6 caracteres";
        }
        
        if ($password_nuevo !== $password_confirmar) {
            $errores[] = "Las contraseñas no coinciden";
        }
        
        if (empty($errores)) {
            $nuevo_hash = password_hash($password_nuevo, PASSWORD_DEFAULT);
            
            $update_pass = "UPDATE usuarios SET password = :password WHERE id = :id";
            $update_pass_stmt = $db->prepare($update_pass);
            $update_pass_stmt->bindParam(':password', $nuevo_hash);
            $update_pass_stmt->bindParam(':id', $_SESSION['usuario_id']);
            
            if ($update_pass_stmt->execute()) {
                $mensaje = "Contraseña actualizada correctamente";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "Error al actualizar la contraseña";
                $tipo_mensaje = "danger";
            }
        } else {
            $mensaje = implode("<br>", $errores);
            $tipo_mensaje = "danger";
        }
    }
}

// Obtener estadísticas del usuario
$stats_query = "SELECT 
    COUNT(*) as total_fichas,
    SUM(CASE WHEN estado = 'aprobado' THEN 1 ELSE 0 END) as fichas_aprobadas,
    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as fichas_pendientes
    FROM fichas_pago 
    WHERE usuario_id = :usuario_id";
$stats_stmt = $db->prepare($stats_query);
$stats_stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
$stats_stmt->execute();
$stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
?>

<style>
    .cuenta-header {
        background: linear-gradient(135deg, var(--verde-utselva), var(--verde-oscuro));
        color: white;
        padding: 60px 0;
        margin-bottom: 40px;
    }
    
    .perfil-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }
    
    .perfil-avatar {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, var(--verde-claro), var(--verde-utselva));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 3rem;
        border: 4px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .info-label {
        color: var(--verde-utselva);
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .info-value {
        background: #f8f9fa;
        padding: 10px 15px;
        border-radius: 8px;
        margin-bottom: 15px;
    }
    
    .stat-box {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
    }
    
    .nav-tabs .nav-link {
        color: var(--verde-utselva);
        font-weight: 600;
    }
    
    .nav-tabs .nav-link.active {
        color: var(--verde-oscuro);
        border-bottom: 3px solid var(--verde-utselva);
    }
    
    .tab-content {
        background: white;
        padding: 30px;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .requisito-item {
        padding: 10px;
        border-bottom: 1px dashed #dee2e6;
    }
    
    .requisito-item:last-child {
        border-bottom: none;
    }
    
    .requisito-item i {
        color: var(--verde-utselva);
        margin-right: 10px;
    }
    
    @media (max-width: 768px) {
        .perfil-avatar {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }
    }
</style>

<section class="cuenta-header">
    <div class="container text-center">
        <h1><i class="bi bi-person-circle"></i> MI CUENTA</h1>
        <p class="lead">Administra tu información personal</p>
    </div>
</section>

<section class="py-4">
    <div class="container">
        
        <?php if($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show">
                <?php echo $mensaje; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Columna izquierda - Información del perfil -->
            <div class="col-lg-4 mb-4">
                <div class="perfil-card text-center">
                    <div class="perfil-avatar">
                        <i class="bi bi-person"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($usuario['nombre_completo']); ?></h3>
                    <p class="text-muted">
                        <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($usuario['email']); ?>
                    </p>
                    
                    <hr>
                    
                    <div class="text-start">
                        <div class="info-label">Rol</div>
                        <div class="info-value">
                            <?php if($usuario['rol'] == 'admin'): ?>
                                <span class="badge bg-danger">Administrador</span>
                            <?php else: ?>
                                <span class="badge bg-success">Aspirante</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="info-label">Fecha de registro</div>
                        <div class="info-value">
                            <i class="bi bi-calendar"></i> 
                            <?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?>
                        </div>
                        
                        <div class="info-label">Resumen de actividad</div>
                        <div class="row g-2 mt-2">
                            <div class="col-4">
                                <div class="text-center p-2 bg-light rounded">
                                    <strong><?php echo $stats['total_fichas'] ?? 0; ?></strong>
                                    <small class="d-block">Fichas</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center p-2 bg-light rounded">
                                    <strong><?php echo $stats['fichas_aprobadas'] ?? 0; ?></strong>
                                    <small class="d-block">Aprobadas</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center p-2 bg-light rounded">
                                    <strong><?php echo $stats['fichas_pendientes'] ?? 0; ?></strong>
                                    <small class="d-block">Pendientes</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="mis-fichas.php" class="btn btn-outline-success">
                            <i class="bi bi-files"></i> Ver mis fichas
                        </a>
                        <a href="pago-ficha.php#subir" class="btn btn-success">
                            <i class="bi bi-upload"></i> Subir nueva ficha
                        </a>
                    </div>
                </div>
                
                <!-- Requisitos pendientes (opcional) -->
                <div class="perfil-card mt-4">
                    <h5><i class="bi bi-check2-square"></i> Requisitos para admisión</h5>
                    <div class="requisito-item">
                        <i class="bi bi-<?php echo $stats['fichas_aprobadas'] > 0 ? 'check-circle-fill text-success' : 'circle'; ?>"></i>
                        Pago de ficha ($400)
                    </div>
                    <div class="requisito-item">
                        <i class="bi bi-circle"></i>
                        Acta de nacimiento
                    </div>
                    <div class="requisito-item">
                        <i class="bi bi-circle"></i>
                        Certificado de bachillerato
                    </div>
                    <div class="requisito-item">
                        <i class="bi bi-circle"></i>
                        CURP
                    </div>
                    <div class="requisito-item">
                        <i class="bi bi-circle"></i>
                        2 fotografías tamaño infantil
                    </div>
                </div>
            </div>
            
            <!-- Columna derecha - Pestañas de edición -->
            <div class="col-lg-8">
                <div class="perfil-card">
                    <ul class="nav nav-tabs" id="miCuentaTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="perfil-tab" data-bs-toggle="tab" data-bs-target="#perfil" type="button" role="tab">
                                <i class="bi bi-pencil-square"></i> Editar Perfil
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                                <i class="bi bi-key"></i> Cambiar Contraseña
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="preferencias-tab" data-bs-toggle="tab" data-bs-target="#preferencias" type="button" role="tab">
                                <i class="bi bi-bell"></i> Preferencias
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="miCuentaTabsContent">
                        <!-- Pestaña Editar Perfil -->
                        <div class="tab-pane fade show active" id="perfil" role="tabpanel">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="nombre_completo" class="form-label">Nombre completo</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="nombre_completo" 
                                           name="nombre_completo" 
                                           value="<?php echo htmlspecialchars($usuario['nombre_completo']); ?>" 
                                           required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo electrónico</label>
                                    <input type="email" 
                                           class="form-control" 
                                           id="email" 
                                           name="email" 
                                           value="<?php echo htmlspecialchars($usuario['email']); ?>" 
                                           required>
                                </div>
                                
                                <button type="submit" name="actualizar_perfil" class="btn btn-success">
                                    <i class="bi bi-save"></i> Guardar cambios
                                </button>
                            </form>
                        </div>
                        
                        <!-- Pestaña Cambiar Contraseña -->
                        <div class="tab-pane fade" id="password" role="tabpanel">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="password_actual" class="form-label">Contraseña actual</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_actual" 
                                           name="password_actual" 
                                           required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_nuevo" class="form-label">Nueva contraseña</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_nuevo" 
                                           name="password_nuevo" 
                                           minlength="6"
                                           required>
                                    <div class="form-text">Mínimo 6 caracteres</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="password_confirmar" class="form-label">Confirmar nueva contraseña</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmar" 
                                           name="password_confirmar" 
                                           required>
                                </div>
                                
                                <button type="submit" name="cambiar_password" class="btn btn-warning">
                                    <i class="bi bi-key"></i> Cambiar contraseña
                                </button>
                            </form>
                        </div>
                        
                        <!-- Pestaña Preferencias -->
                        <div class="tab-pane fade" id="preferencias" role="tabpanel">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label class="form-label">Notificaciones por correo</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="notif_ficha" checked>
                                        <label class="form-check-label" for="notif_ficha">
                                            Cuando mi ficha sea aprobada/rechazada
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="notif_eventos" checked>
                                        <label class="form-check-label" for="notif_eventos">
                                            Eventos y convocatorias de la universidad
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="notif_promociones">
                                        <label class="form-check-label" for="notif_promociones">
                                            Ofertas educativas y promociones
                                        </label>
                                    </div>
                                </div>
                                
                                <button type="submit" name="guardar_preferencias" class="btn btn-success" disabled>
                                    <i class="bi bi-save"></i> Guardar preferencias
                                </button>
                                <small class="text-muted ms-2">(Funcionalidad próximamente)</small>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Información adicional de seguridad -->
                <div class="alert alert-warning mt-4">
                    <i class="bi bi-shield-lock"></i>
                    <strong>Seguridad:</strong>
                    <p class="mb-0 mt-2">Recomendamos cambiar tu contraseña periódicamente y no compartir tus datos de acceso con nadie.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Validación de contraseñas en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const passwordNuevo = document.getElementById('password_nuevo');
    const passwordConfirmar = document.getElementById('password_confirmar');
    
    if (passwordConfirmar) {
        passwordConfirmar.addEventListener('input', function() {
            if (this.value !== passwordNuevo.value) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
            }
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>