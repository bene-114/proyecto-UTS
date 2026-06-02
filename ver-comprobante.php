<?php
session_start();
include 'includes/header.php';
include 'includes/navbar.php';

require_once 'config/database.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?redirect=pago-ficha.php');
    exit();
}

// Obtener el ID del comprobante
$id = $_GET['id'] ?? 0;

$database = new Database();
$db = $database->getConnection();

// Consultar el comprobante específico
$query = "SELECT * FROM fichas_pago WHERE id = :id AND usuario_id = :usuario_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
$stmt->execute();

$ficha = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ficha) {
    header('Location: pago-ficha.php');
    exit();
}

// Determinar color según estado
$estado_color = [
    'pendiente' => 'warning',
    'aprobado' => 'success',
    'rechazado' => 'danger'
];

$estado_texto = [
    'pendiente' => 'EN REVISIÓN',
    'aprobado' => 'APROBADO',
    'rechazado' => 'RECHAZADO'
];

$estado_icono = [
    'pendiente' => 'clock-history',
    'aprobado' => 'check-circle',
    'rechazado' => 'exclamation-triangle'
];
?>

<style>
    .estado-badge {
        font-size: 1.2rem;
        padding: 10px 20px;
        border-radius: 50px;
    }
    .comprobante-container {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
    }
    .info-label {
        font-weight: 600;
        color: #2E7D32;
        min-width: 150px;
    }
</style>

<section class="page-header">
    <div class="container">
        <h1>DETALLE DEL COMPROBANTE</h1>
        <p>Folio: #<?php echo str_pad($ficha['id'], 6, '0', STR_PAD_LEFT); ?></p>
    </div>
</section>

<section class="detalle-comprobante py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Estado del comprobante -->
                <div class="text-center mb-4">
                    <div class="estado-badge bg-<?php echo $estado_color[$ficha['estado']]; ?> text-white d-inline-block">
                        <i class="bi bi-<?php echo $estado_icono[$ficha['estado']]; ?>"></i>
                        <?php echo $estado_texto[$ficha['estado']]; ?>
                    </div>
                </div>
                
                <!-- Tarjeta principal -->
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-receipt"></i> 
                            Información del Pago
                        </h4>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <!-- Información del pago -->
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="info-label">Folio:</td>
                                        <td><strong>#<?php echo str_pad($ficha['id'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Fecha de subida:</td>
                                        <td><?php echo date('d/m/Y', strtotime($ficha['fecha_subida'])); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Hora:</td>
                                        <td><?php echo date('H:i:s', strtotime($ficha['fecha_subida'])); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Nombre:</td>
                                        <td><?php echo htmlspecialchars($ficha['nombre_completo']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Email:</td>
                                        <td><?php echo htmlspecialchars($ficha['email']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="info-label">Monto:</td>
                                        <td><strong class="text-success">$400.00 MXN</strong></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <!-- Observaciones -->
                            <div class="col-md-6">
                                <?php if($ficha['observaciones']): ?>
                                <div class="alert alert-<?php echo $ficha['estado'] == 'rechazado' ? 'danger' : 'info'; ?>">
                                    <h5>
                                        <i class="bi bi-chat-text"></i> 
                                        Observaciones:
                                    </h5>
                                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($ficha['observaciones'])); ?></p>
                                </div>
                                <?php endif; ?>
                                
                                <?php if($ficha['estado'] == 'rechazado'): ?>
                                <div class="alert alert-warning mt-3">
                                    <i class="bi bi-exclamation-triangle"></i>
                                    <strong>¿Tu pago fue rechazado?</strong>
                                    <p class="mb-0 mt-2">
                                        Puedes subir un nuevo comprobante con la información correcta.
                                        <a href="pago-ficha.php#subir" class="alert-link">Haz clic aquí</a>
                                    </p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Comprobante -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>
                                    <i class="bi bi-file-image"></i> 
                                    Comprobante de Pago
                                </h5>
                                <div class="comprobante-container">
                                    <?php
                                    $extension = strtolower(pathinfo($ficha['comprobante_path'], PATHINFO_EXTENSION));
                                    $file_url = htmlspecialchars($ficha['comprobante_path']);
                                    
                                    if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])):
                                    ?>
                                        <img src="<?php echo $file_url; ?>" 
                                             class="img-fluid rounded" 
                                             alt="Comprobante de pago"
                                             style="max-height: 400px; cursor: pointer;"
                                             onclick="window.open(this.src, '_blank')">
                                    <?php elseif($extension == 'pdf'): ?>
                                        <div class="pdf-preview">
                                            <i class="bi bi-file-pdf" style="font-size: 5rem; color: #dc3545;"></i>
                                            <h5>Archivo PDF</h5>
                                            <a href="<?php echo $file_url; ?>" target="_blank" class="btn btn-danger mt-3">
                                                <i class="bi bi-file-pdf"></i> Ver PDF
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-secondary">
                                            <i class="bi bi-file"></i>
                                            <a href="<?php echo $file_url; ?>" target="_blank">
                                                Ver archivo adjunto
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div class="row mt-4">
                            <div class="col-12 text-center">
                                <a href="pago-ficha.php" class="btn btn-secondary btn-lg me-2">
                                    <i class="bi bi-arrow-left"></i> Volver al historial
                                </a>
                                <?php if($ficha['estado'] == 'rechazado'): ?>
                                <a href="pago-ficha.php#subir" class="btn btn-success btn-lg">
                                    <i class="bi bi-upload"></i> Subir nuevo comprobante
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pie con información adicional -->
                    <div class="card-footer text-muted">
                        <small>
                            <i class="bi bi-info-circle"></i>
                            Los comprobantes son revisados en un plazo máximo de 24 horas hábiles.
                            Si tienes dudas, contacta a soporte.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>