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

// Estadísticas
$stats = [
    'pendientes' => $db->query("SELECT COUNT(*) FROM fichas_pago WHERE estado = 'pendiente'")->fetchColumn(),
    'aprobados' => $db->query("SELECT COUNT(*) FROM fichas_pago WHERE estado = 'aprobado'")->fetchColumn(),
    'rechazados' => $db->query("SELECT COUNT(*) FROM fichas_pago WHERE estado = 'rechazado'")->fetchColumn(),
    'total' => $db->query("SELECT COUNT(*) FROM fichas_pago")->fetchColumn()
];

// Obtener fichas pendientes
$query = "SELECT f.*, u.nombre_completo as usuario_nombre 
          FROM fichas_pago f 
          JOIN usuarios u ON f.usuario_id = u.id 
          WHERE f.estado = 'pendiente' 
          ORDER BY f.fecha_subida DESC";
$pendientes = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .admin-sidebar {
        background: linear-gradient(135deg, #1B5E20, #2E7D32);
        min-height: 100vh;
        color: white;
        padding: 20px;
    }
    .admin-sidebar a {
        color: white;
        text-decoration: none;
        padding: 10px 15px;
        display: block;
        border-radius: 5px;
        margin: 5px 0;
        transition: all 0.3s;
    }
    .admin-sidebar a:hover, .admin-sidebar a.active {
        background: rgba(255,255,255,0.2);
        padding-left: 25px;
    }
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        border-left: 4px solid #2E7D32;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 p-0">
            <div class="admin-sidebar">
                <div class="text-center mb-4">
                    <img src="../img/logo2.jpg" alt="UT Selva" height="60">
                    <h5 class="mt-2">Admin Panel</h5>
                    <p><?php echo $_SESSION['admin_nombre']; ?></p>
                </div>
                
                <a href="dashboard.php" class="active">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="fichas.php">
                    <i class="bi bi-cash"></i> Fichas de Pago
                </a>
                <a href="usuarios.php">
                    <i class="bi bi-people"></i> Usuarios
                </a>
                <a href="configuracion.php">
                    <i class="bi bi-gear"></i> Configuración
                </a>
                <hr>
                <a href="logout.php">
                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                </a>
            </div>
        </div>
        
        <!-- Contenido principal -->
        <div class="col-md-10 p-4">
            <h2>Dashboard</h2>
            
            <!-- Tarjetas de estadísticas -->
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card">
                        <h5>Pendientes</h5>
                        <h2><?php echo $stats['pendientes']; ?></h2>
                        <small class="text-warning">Esperando revisión</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <h5>Aprobados</h5>
                        <h2><?php echo $stats['aprobados']; ?></h2>
                        <small class="text-success">Pagos confirmados</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <h5>Rechazados</h5>
                        <h2><?php echo $stats['rechazados']; ?></h2>
                        <small class="text-danger">Requieren corrección</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <h5>Total</h5>
                        <h2><?php echo $stats['total']; ?></h2>
                        <small>Fichas registradas</small>
                    </div>
                </div>
            </div>
            
            <!-- Fichas pendientes -->
            <h3 class="mt-4">Fichas Pendientes de Revisión</h3>
            <?php if(count($pendientes) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Comprobante</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($pendientes as $ficha): ?>
                            <tr>
                                <td>#<?php echo $ficha['id']; ?></td>
                                <td><?php echo $ficha['usuario_nombre']; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($ficha['fecha_subida'])); ?></td>
                                <td>
                                    <a href="../<?php echo $ficha['comprobante_path']; ?>" target="_blank">
                                        Ver comprobante
                                    </a>
                                </td>
                                <td>
                                    <a href="revisar-ficha.php?id=<?php echo $ficha['id']; ?>" 
                                       class="btn btn-sm btn-success">
                                        Revisar
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-success">
                    No hay fichas pendientes de revisión.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>