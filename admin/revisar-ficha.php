<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

include '../includes/header.php';
require_once '../config/database.php';

$database = new Database();
$db = $database->getConnection();

$id = $_GET['id'] ?? 0;

// Procesar el formulario de revisión
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $estado = $_POST['estado'];
    $observaciones = $_POST['observaciones'];
    
    $query = "UPDATE fichas_pago SET estado = :estado, observaciones = :observaciones WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':observaciones', $observaciones);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        $mensaje = "Ficha actualizada correctamente";
        $tipo_mensaje = "success";
    }
}

// Obtener datos de la ficha
$query = "SELECT f.*, u.nombre_completo, u.email 
          FROM fichas_pago f 
          JOIN usuarios u ON f.usuario_id = u.id 
          WHERE f.id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$ficha = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ficha) {
    header('Location: dashboard.php');
    exit();
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 p-0">
            <div class="admin-sidebar">
                <!-- ... mismo sidebar que en dashboard.php ... -->
            </div>
        </div>
        
        <!-- Contenido -->
        <div class="col-md-10 p-4">
            <h2>Revisar Ficha #<?php echo $ficha['id']; ?></h2>
            
            <?php if(isset($mensaje)): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Información del Usuario</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Nombre:</strong> <?php echo $ficha['nombre_completo']; ?></p>
                            <p><strong>Email:</strong> <?php echo $ficha['email']; ?></p>
                            <p><strong>Fecha de subida:</strong> <?php echo date('d/m/Y H:i', strtotime($ficha['fecha_subida'])); ?></p>
                        </div>
                    </div>
                    
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5>Revisión</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label>Estado:</label>
                                    <select name="estado" class="form-control">
                                        <option value="pendiente" <?php echo $ficha['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                        <option value="aprobado" <?php echo $ficha['estado'] == 'aprobado' ? 'selected' : ''; ?>>Aprobar</option>
                                        <option value="rechazado" <?php echo $ficha['estado'] == 'rechazado' ? 'selected' : ''; ?>>Rechazar</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label>Observaciones:</label>
                                    <textarea name="observaciones" class="form-control" rows="3"><?php echo $ficha['observaciones']; ?></textarea>
                                    <small class="text-muted">Motivo del rechazo o comentarios adicionales</small>
                                </div>
                                
                                <button type="submit" class="btn btn-success">
                                    Guardar Cambios
                                </button>
                                <a href="dashboard.php" class="btn btn-secondary">Volver</a>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Comprobante</h5>
                        </div>
                        <div class="card-body text-center">
                            <?php
                            $extension = strtolower(pathinfo($ficha['comprobante_path'], PATHINFO_EXTENSION));
                            if(in_array($extension, ['jpg', 'jpeg', 'png'])):
                            ?>
                                <img src="../<?php echo $ficha['comprobante_path']; ?>" 
                                     class="img-fluid rounded" 
                                     alt="Comprobante"
                                     style="max-height: 400px;">
                            <?php elseif($extension == 'pdf'): ?>
                                <iframe src="../<?php echo $ficha['comprobante_path']; ?>" 
                                        width="100%" 
                                        height="500px"></iframe>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>