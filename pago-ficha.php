<?php
session_start();
include 'includes/header.php';
include 'includes/navbar.php';

// Verificar si el usuario está logueado para subir ficha
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

$mensaje = '';
$tipo_mensaje = '';

// Procesar subida de comprobante
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subir_ficha'])) {
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: login.php?redirect=pago-ficha.php');
        exit();
    }
    
    $usuario_id = $_SESSION['usuario_id'];
    $nombre = $_SESSION['usuario_nombre'];
    $email = $_SESSION['usuario_email'];
    
    // Procesar archivo
    if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] == 0) {
        $archivo = $_FILES['comprobante'];
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'pdf'];
        
        if (in_array($extension, $extensiones_permitidas)) {
            if ($archivo['size'] <= 5 * 1024 * 1024) { // 5MB máximo
                
                $nombre_archivo = 'ficha_' . $usuario_id . '_' . time() . '.' . $extension;
                $ruta_destino = 'uploads/fichas/' . $nombre_archivo;
                
                if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
                    // Guardar en base de datos
                    $query = "INSERT INTO fichas_pago (usuario_id, nombre_completo, email, comprobante_path) 
                              VALUES (:usuario_id, :nombre, :email, :ruta)";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':usuario_id', $usuario_id);
                    $stmt->bindParam(':nombre', $nombre);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':ruta', $ruta_destino);
                    
                    if ($stmt->execute()) {
                        $mensaje = "Comprobante subido exitosamente. Se notificará cuando sea revisado.";
                        $tipo_mensaje = "success";
                    }
                }
            } else {
                $mensaje = "El archivo no debe exceder los 5MB";
                $tipo_mensaje = "danger";
            }
        } else {
            $mensaje = "Solo se permiten archivos JPG, PNG o PDF";
            $tipo_mensaje = "danger";
        }
    }
}

// Consultar estado de fichas del usuario
$fichas_usuario = [];
if (isset($_SESSION['usuario_id'])) {
    $query = "SELECT * FROM fichas_pago WHERE usuario_id = :usuario_id ORDER BY fecha_subida DESC";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
    $stmt->execute();
    $fichas_usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<section class="page-header">
    <div class="container">
        <h1>PAGO DE FICHA</h1>
        <p>Costo: $400 - Proceso de admisión 2026</p>
    </div>
</section>

<section class="pago-section py-5">
    <div class="container">
        <!-- Información de pago -->
        <div class="row mb-5">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="info-pago-card">
                    <h3><i class="bi bi-bank"></i> INFORMACIÓN DE PAGO</h3>
                    
                    <div class="bancos-info">
                        <h5>PAGOS ÚNICAMENTE EN VENTANILLA</h5>
                        
                        <div class="banco-item">
                            <i class="bi bi-building"></i>
                            <strong>Banco Azteca</strong>
                            <p>Banca Institucional de Gobierno</p>
                        </div>
                        
                        <div class="banco-item">
                            <i class="bi bi-building"></i>
                            <strong>Santander</strong>
                        </div>
                        
                        <div class="banco-item">
                            <i class="bi bi-building"></i>
                            <strong>Bancomer</strong>
                        </div>
                    </div>
                    
                    <div class="referencia-box">
                        <h5>REFERENCIA DE PAGO:</h5>
                        <div class="ref-numero">10000000020151402277</div>
                        <button class="btn btn-sm btn-outline-light mt-2" onclick="copiarReferencia()">
                            <i class="bi bi-files"></i> Copiar referencia
                        </button>
                    </div>
                    
                    <div class="fecha-info mt-4">
                        <p><i class="bi bi-calendar"></i> Fichas a partir del 04 de Febrero al 20 de Mayo</p>
                        <p><i class="bi bi-clock"></i> Entrega de fichas: Lunes a Viernes 9:00 - 15:00 hrs</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4" data-aos="fade-left">
                <div class="imagen-clave-card">
                    <h3><i class="bi bi-upc-scan"></i> CLAVE PARA TRANSFERENCIA</h3>
                    <img src="img/ficha.jpg" alt="Clave de pago" class="img-fluid rounded">
                    <p class="mt-3 text-muted">Presenta este código en ventanilla para realizar tu pago</p>
                </div>
            </div>
        </div>
        
        <?php if(isset($_SESSION['usuario_id'])): ?>
            <!-- Sección para subir ficha -->
            <div id="subir" class="row mb-5">
                <div class="col-12">
                    <div class="subir-ficha-card" data-aos="zoom-in">
                        <h3><i class="bi bi-cloud-upload"></i> SUBIR COMPROBANTE DE PAGO</h3>
                        
                        <?php if($mensaje): ?>
                            <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show">
                                <?php echo $mensaje; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="comprobante" class="form-label">Selecciona tu comprobante de pago</label>
                                <input type="file" class="form-control" id="comprobante" name="comprobante" accept=".jpg,.jpeg,.png,.pdf" required>
                                <div class="form-text">Formatos permitidos: JPG, PNG, PDF (Máx. 5MB)</div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="confirmar_pago" required>
                                <label class="form-check-label" for="confirmar_pago">
                                    Confirmo que realicé el pago por $400 y adjunto el comprobante correspondiente
                                </label>
                            </div>
                            
                            <button type="submit" name="subir_ficha" class="btn btn-success">
                                <i class="bi bi-upload"></i> SUBIR COMPROBANTE
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
           <!-- Historial de fichas -->
<?php if(!empty($fichas_usuario)): ?>
<div id="estado" class="row">
    <div class="col-12">
        <div class="historial-card" data-aos="fade-up">
            <h3><i class="bi bi-clock-history"></i> MIS COMPROBANTES</h3>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Comprobante</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($fichas_usuario as $ficha): ?>
                        <tr>
                            <td><?php echo date('d/m/Y H:i', strtotime($ficha['fecha_subida'])); ?></td>
                            <td>
                                <a href="<?php echo $ficha['comprobante_path']; ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> Ver comprobante
                                </a>
                            </td>
                            <td>
                                <?php
                                $estado_class = [
                                    'pendiente' => 'warning',
                                    'aprobado' => 'success',
                                    'rechazado' => 'danger'
                                ];
                                $estado_texto = [
                                    'pendiente' => 'En revisión',
                                    'aprobado' => 'Aprobado',
                                    'rechazado' => 'Rechazado'
                                ];
                                ?>
                                <span class="badge bg-<?php echo $estado_class[$ficha['estado']]; ?>">
                                    <?php echo $estado_texto[$ficha['estado']]; ?>
                                </span>
                            </td>
                            <td>
                                <?php echo $ficha['observaciones'] ?? '-'; ?>
                            </td>
                            <td>
                                <a href="ver-comprobante.php?id=<?php echo $ficha['id']; ?>" 
                                   class="btn btn-sm btn-info">
                                    <i class="bi bi-file-text"></i> Detalle
                                </a>
                                
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Leyenda de estados -->
            <div class="mt-3 p-3 bg-light rounded">
                <small>
                    <span class="badge bg-warning">En revisión</span> - Tu comprobante está siendo verificado
                    <br>
                    <span class="badge bg-success">Aprobado</span> - Pago confirmado, ficha válida
                    <br>
                    <span class="badge bg-danger">Rechazado</span> - Problema con el pago, ver observaciones
                </small>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            Aún no has subido ningún comprobante de pago. Sube tu primer comprobante en el formulario de arriba.
        </div>
    </div>
</div>
<?php endif; ?>
            
        <?php else: ?>
            <!-- Mensaje para usuarios no logueados -->
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle"></i> 
                Para subir tu comprobante de pago, necesitas <a href="login.php?redirect=pago-ficha.php">iniciar sesión</a> 
                o <a href="registro.php">registrarte</a>.
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function copiarReferencia() {
    const referencia = '10000000020151402277';
    navigator.clipboard.writeText(referencia).then(() => {
        alert('Referencia copiada al portapapeles');
    });
}
</script>

<?php include 'includes/footer.php'; ?>