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

$mensaje = '';
$tipo_mensaje = '';

// Filtros
$estado_filtro = $_GET['estado'] ?? 'todos';
$search = $_GET['search'] ?? '';

// Construir query con filtros
$query = "SELECT f.*, u.nombre_completo as usuario_nombre, u.email as usuario_email 
          FROM fichas_pago f 
          JOIN usuarios u ON f.usuario_id = u.id 
          WHERE 1=1";

$params = [];

if ($estado_filtro != 'todos') {
    $query .= " AND f.estado = :estado";
    $params[':estado'] = $estado_filtro;
}

if ($search) {
    $query .= " AND (u.nombre_completo LIKE :search OR u.email LIKE :search OR f.id LIKE :search)";
    $params[':search'] = "%$search%";
}

$query .= " ORDER BY f.fecha_subida DESC";

$stmt = $db->prepare($query);
foreach($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$fichas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estadísticas
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
    SUM(CASE WHEN estado = 'aprobado' THEN 1 ELSE 0 END) as aprobados,
    SUM(CASE WHEN estado = 'rechazado' THEN 1 ELSE 0 END) as rechazados
    FROM fichas_pago";
$stats_stmt = $db->query($stats_query);
$stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
?>

<style>
    .admin-header {
        background: linear-gradient(135deg, #1B5E20, #2E7D32);
        color: white;
        padding: 30px 0;
        margin-bottom: 30px;
    }
    
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
        border-left: 4px solid #2E7D32;
    }
    
    .filtros-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .table-fichas {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .table-fichas th {
        background: #2E7D32;
        color: white;
    }
    
    .estado-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .estado-pendiente { background: #ffc107; color: #000; }
    .estado-aprobado { background: #28a745; color: #fff; }
    .estado-rechazado { background: #dc3545; color: #fff; }
    
    .ficha-row:hover {
        background: #f8f9fa;
    }
    
    .preview-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 5px;
        cursor: pointer;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 p-0">
            <?php include 'sidebar.php'; ?>
        </div>
        
        <!-- Contenido principal -->
        <div class="col-md-10 p-4">
            
            <div class="admin-header">
                <h2><i class="bi bi-cash-stack"></i> GESTIÓN DE FICHAS DE PAGO</h2>
            </div>
            
            <?php if($mensaje): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <i class="bi bi-files fs-1 text-info"></i>
                        <div class="stats-number"><?php echo $stats['total']; ?></div>
                        <div>Total fichas</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" style="border-left-color: #ffc107;">
                        <i class="bi bi-clock-history fs-1 text-warning"></i>
                        <div class="stats-number"><?php echo $stats['pendientes']; ?></div>
                        <div>Pendientes</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" style="border-left-color: #28a745;">
                        <i class="bi bi-check-circle fs-1 text-success"></i>
                        <div class="stats-number"><?php echo $stats['aprobados']; ?></div>
                        <div>Aprobadas</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" style="border-left-color: #dc3545;">
                        <i class="bi bi-x-circle fs-1 text-danger"></i>
                        <div class="stats-number"><?php echo $stats['rechazados']; ?></div>
                        <div>Rechazadas</div>
                    </div>
                </div>
            </div>
            
            <!-- Filtros -->
            <div class="filtros-card">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="todos" <?php echo $estado_filtro == 'todos' ? 'selected' : ''; ?>>Todos</option>
                            <option value="pendiente" <?php echo $estado_filtro == 'pendiente' ? 'selected' : ''; ?>>Pendientes</option>
                            <option value="aprobado" <?php echo $estado_filtro == 'aprobado' ? 'selected' : ''; ?>>Aprobados</option>
                            <option value="rechazado" <?php echo $estado_filtro == 'rechazado' ? 'selected' : ''; ?>>Rechazados</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Buscar</label>
                        <input type="text" name="search" class="form-control" placeholder="Nombre, email o ID..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Tabla de fichas -->
            <div class="table-responsive table-fichas">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Comprobante</th>
                            <th>Estado</th>
                            <th>Observaciones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($fichas as $ficha): ?>
                        <tr class="ficha-row">
                            <td>#<?php echo $ficha['id']; ?></td>
                            <td>
                                <?php echo date('d/m/Y', strtotime($ficha['fecha_subida'])); ?>
                                <br>
                                <small><?php echo date('H:i', strtotime($ficha['fecha_subida'])); ?></small>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($ficha['usuario_nombre']); ?></strong>
                                <br>
                                <small><?php echo htmlspecialchars($ficha['usuario_email']); ?></small>
                            </td>
                            <td>
                                <?php
                                $ext = pathinfo($ficha['comprobante_path'], PATHINFO_EXTENSION);
                                if(in_array($ext, ['jpg', 'jpeg', 'png'])):
                                ?>
                                    <img src="../<?php echo $ficha['comprobante_path']; ?>" 
                                         class="preview-img" 
                                         onclick="window.open('../<?php echo $ficha['comprobante_path']; ?>', '_blank')">
                                <?php else: ?>
                                    <a href="../<?php echo $ficha['comprobante_path']; ?>" target="_blank">
                                        <i class="bi bi-file-pdf fs-1 text-danger"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="estado-badge estado-<?php echo $ficha['estado']; ?>">
                                    <?php echo strtoupper($ficha['estado']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo $ficha['observaciones'] ? substr($ficha['observaciones'], 0, 30) . '...' : '-'; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="revisar-ficha.php?id=<?php echo $ficha['id']; ?>" 
                                       class="btn btn-sm btn-success">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="../<?php echo $ficha['comprobante_path']; ?>" 
                                       target="_blank"
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if(empty($fichas)): ?>
                <div class="alert alert-info text-center mt-4">
                    <i class="bi bi-inbox"></i> No hay fichas para mostrar
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>