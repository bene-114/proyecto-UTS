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
    'usuarios' => $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn(),
    'fichas' => $db->query("SELECT COUNT(*) FROM fichas_pago")->fetchColumn(),
    'pendientes' => $db->query("SELECT COUNT(*) FROM fichas_pago WHERE estado = 'pendiente'")->fetchColumn(),
    'aprobados' => $db->query("SELECT COUNT(*) FROM fichas_pago WHERE estado = 'aprobado'")->fetchColumn(),
    'rechazados' => $db->query("SELECT COUNT(*) FROM fichas_pago WHERE estado = 'rechazado'")->fetchColumn(),
    'nuevos_usuarios' => $db->query("SELECT COUNT(*) FROM usuarios WHERE fecha_registro > DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn()
];

// Obtener fichas pendientes recientes
$pendientes_query = "SELECT f.*, u.nombre_completo as usuario_nombre 
                     FROM fichas_pago f 
                     JOIN usuarios u ON f.usuario_id = u.id 
                     WHERE f.estado = 'pendiente' 
                     ORDER BY f.fecha_subida DESC 
                     LIMIT 5";
$pendientes = $db->query($pendientes_query)->fetchAll(PDO::FETCH_ASSOC);

// Actividad reciente
$actividad_query = "SELECT 'ficha' as tipo, f.id, f.fecha_subida as fecha, 
                           u.nombre_completo as descripcion,
                           f.estado
                    FROM fichas_pago f
                    JOIN usuarios u ON f.usuario_id = u.id
                    UNION ALL
                    SELECT 'usuario' as tipo, id, fecha_registro as fecha,
                           nombre_completo as descripcion,
                           'nuevo' as estado
                    FROM usuarios
                    ORDER BY fecha DESC
                    LIMIT 10";
$actividad = $db->query($actividad_query)->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .admin-header {
        background: linear-gradient(135deg, #1B5E20, #2E7D32);
        color: white;
        padding: 30px 0;
        margin-bottom: 30px;
    }
    
    .dashboard-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        height: 100%;
        border-bottom: 4px solid transparent;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
    }
    
    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 15px;
    }
    
    .card-value {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 10px 0;
    }
    
    .card-label {
        color: #666;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .recent-activity {
        background: white;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .activity-item {
        padding: 12px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
    }
    
    .activity-item:last-child {
        border-bottom: none;
    }
    
    .activity-icon {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }
    
    .badge-pendiente {
        background: #ffc107;
        color: #000;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar - aquí se incluye el menú compartido -->
        <div class="col-md-2 p-0">
            <?php include 'sidebar.php'; ?>
        </div>
        
        <!-- Contenido principal -->
        <div class="col-md-10 p-4">
            
            <div class="admin-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2><i class="bi bi-speedometer2"></i> DASHBOARD</h2>
                        <p class="mb-0">Bienvenido, <?php echo htmlspecialchars($_SESSION['admin_nombre']); ?></p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-light text-dark p-2">
                            <i class="bi bi-calendar"></i> <?php echo date('d/m/Y'); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Tarjetas de estadísticas -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="border-bottom-color: #17a2b8;">
                        <div class="card-icon" style="background: rgba(23,162,184,0.1); color: #17a2b8;">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="card-value"><?php echo $stats['usuarios']; ?></div>
                        <div class="card-label">Total Usuarios</div>
                        <small class="text-muted">+<?php echo $stats['nuevos_usuarios']; ?> nuevos (30 días)</small>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="border-bottom-color: #ffc107;">
                        <div class="card-icon" style="background: rgba(255,193,7,0.1); color: #ffc107;">
                            <i class="bi bi-files"></i>
                        </div>
                        <div class="card-value"><?php echo $stats['fichas']; ?></div>
                        <div class="card-label">Total Fichas</div>
                        <small class="text-muted"><?php echo $stats['pendientes']; ?> pendientes</small>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="border-bottom-color: #28a745;">
                        <div class="card-icon" style="background: rgba(40,167,69,0.1); color: #28a745;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="card-value"><?php echo $stats['aprobados']; ?></div>
                        <div class="card-label">Aprobadas</div>
                        <small class="text-muted">Fichas confirmadas</small>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="dashboard-card" style="border-bottom-color: #dc3545;">
                        <div class="card-icon" style="background: rgba(220,53,69,0.1); color: #dc3545;">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div class="card-value"><?php echo $stats['rechazados']; ?></div>
                        <div class="card-label">Rechazadas</div>
                        <small class="text-muted">Requieren atención</small>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Fichas pendientes recientes -->
                <div class="col-lg-6 mb-4">
                    <div class="recent-activity">
                        <h5 class="mb-3"><i class="bi bi-clock-history"></i> Fichas pendientes por revisar</h5>
                        
                        <?php if(empty($pendientes)): ?>
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-check2-circle fs-1"></i>
                                <p>No hay fichas pendientes</p>
                            </div>
                        <?php else: ?>
                            <?php foreach($pendientes as $ficha): ?>
                                <div class="activity-item">
                                    <div class="activity-icon" style="background: #fff3cd;">
                                        <i class="bi bi-clock text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong><?php echo htmlspecialchars($ficha['usuario_nombre']); ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?php echo date('d/m/Y H:i', strtotime($ficha['fecha_subida'])); ?>
                                        </small>
                                    </div>
                                    <a href="revisar-ficha.php?id=<?php echo $ficha['id']; ?>" 
                                       class="btn btn-sm btn-outline-warning">
                                        Revisar
                                    </a>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="text-center mt-3">
                                <a href="fichas.php?estado=pendiente" class="btn btn-link">
                                    Ver todas las pendientes →
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Actividad reciente -->
                <div class="col-lg-6 mb-4">
                    <div class="recent-activity">
                        <h5 class="mb-3"><i class="bi bi-activity"></i> Actividad reciente</h5>
                        
                        <?php foreach($actividad as $item): ?>
                            <div class="activity-item">
                                <?php if($item['tipo'] == 'ficha'): ?>
                                    <div class="activity-icon" style="background: 
                                        <?php echo $item['estado'] == 'pendiente' ? '#fff3cd' : 
                                            ($item['estado'] == 'aprobado' ? '#d4edda' : '#f8d7da'); ?>">
                                        <i class="bi bi-cash <?php 
                                            echo $item['estado'] == 'pendiente' ? 'text-warning' : 
                                                ($item['estado'] == 'aprobado' ? 'text-success' : 'text-danger'); ?>"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong>Nueva ficha</strong> de <?php echo htmlspecialchars($item['descripcion']); ?>
                                        <br>
                                        <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($item['fecha'])); ?></small>
                                    </div>
                                    <span class="badge bg-<?php 
                                        echo $item['estado'] == 'pendiente' ? 'warning' : 
                                            ($item['estado'] == 'aprobado' ? 'success' : 'danger'); ?>">
                                        <?php echo $item['estado']; ?>
                                    </span>
                                    
                                <?php else: ?>
                                    <div class="activity-icon" style="background: #d1e7dd;">
                                        <i class="bi bi-person-plus text-success"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong>Nuevo usuario:</strong> <?php echo htmlspecialchars($item['descripcion']); ?>
                                        <br>
                                        <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($item['fecha'])); ?></small>
                                    </div>
                                    <span class="badge bg-success">Nuevo</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Accesos rápidos -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="recent-activity">
                        <h5 class="mb-3"><i class="bi bi-lightning"></i> Accesos rápidos</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <a href="fichas.php" class="btn btn-outline-success w-100 mb-2">
                                    <i class="bi bi-files"></i> Ver todas las fichas
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="usuarios.php" class="btn btn-outline-info w-100 mb-2">
                                    <i class="bi bi-people"></i> Gestionar usuarios
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="configuracion.php" class="btn btn-outline-warning w-100 mb-2">
                                    <i class="bi bi-gear"></i> Configuración
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="../index.php" class="btn btn-outline-secondary w-100 mb-2" target="_blank">
                                    <i class="bi bi-house-door"></i> Ver sitio web
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>