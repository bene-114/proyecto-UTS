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

// Procesar acciones
if (isset($_GET['action'])) {
    $id = $_GET['id'] ?? 0;
    
    if ($_GET['action'] == 'delete' && $id > 0) {
        // No permitir eliminar al propio admin
        if ($id != $_SESSION['admin_id']) {
            $query = "DELETE FROM usuarios WHERE id = :id AND rol != 'admin'";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                $mensaje = "Usuario eliminado correctamente";
                $tipo_mensaje = "success";
            }
        } else {
            $mensaje = "No puedes eliminarte a ti mismo";
            $tipo_mensaje = "danger";
        }
    }
    
    if ($_GET['action'] == 'toggle_admin' && $id > 0) {
        if ($id != $_SESSION['admin_id']) {
            $query = "UPDATE usuarios SET rol = IF(rol = 'admin', 'usuario', 'admin') WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                $mensaje = "Rol de usuario actualizado";
                $tipo_mensaje = "success";
            }
        }
    }
}

// Procesar búsqueda
$search = $_GET['search'] ?? '';
$where = '';
if ($search) {
    $where = " WHERE nombre_completo LIKE :search OR email LIKE :search";
}

// Obtener usuarios
$query = "SELECT * FROM usuarios" . $where . " ORDER BY id DESC";
$stmt = $db->prepare($query);

if ($search) {
    $search_param = "%$search%";
    $stmt->bindParam(':search', $search_param);
}

$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estadísticas
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN rol = 'admin' THEN 1 ELSE 0 END) as admins,
    SUM(CASE WHEN fecha_registro > DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as nuevos
    FROM usuarios";
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
    
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: #2E7D32;
    }
    
    .table-users {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .table-users th {
        background: #2E7D32;
        color: white;
        font-weight: 600;
    }
    
    .badge-rol {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
    }
    
    .badge-rol.admin {
        background: #dc3545;
        color: white;
    }
    
    .badge-rol.usuario {
        background: #28a745;
        color: white;
    }
    
    .action-btns {
        display: flex;
        gap: 5px;
    }
    
    .search-box {
        background: white;
        border-radius: 30px;
        padding: 5px 5px 5px 20px;
        border: 1px solid #ddd;
    }
    
    .search-box input {
        border: none;
        outline: none;
        width: 250px;
    }
    
    .search-box button {
        border-radius: 30px;
        padding: 8px 20px;
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
                <div class="d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-people"></i> GESTIÓN DE USUARIOS</h2>
                    <div>
                        <form method="GET" class="search-box d-flex align-items-center">
                            <input type="text" name="search" placeholder="Buscar usuario..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <?php if($mensaje): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show">
                    <?php echo $mensaje; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <i class="bi bi-people fs-1 text-success"></i>
                        <div class="stats-number"><?php echo $stats['total']; ?></div>
                        <div>Total de usuarios</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <i class="bi bi-shield-lock fs-1 text-danger"></i>
                        <div class="stats-number"><?php echo $stats['admins']; ?></div>
                        <div>Administradores</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <i class="bi bi-person-plus fs-1 text-info"></i>
                        <div class="stats-number"><?php echo $stats['nuevos']; ?></div>
                        <div>Nuevos (30 días)</div>
                    </div>
                </div>
            </div>
            
            <!-- Tabla de usuarios -->
            <div class="table-responsive table-users">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Fecha registro</th>
                            <th>Fichas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($usuarios as $usuario): 
                            // Contar fichas del usuario
                            $fichas_query = "SELECT COUNT(*) as total FROM fichas_pago WHERE usuario_id = :uid";
                            $fichas_stmt = $db->prepare($fichas_query);
                            $fichas_stmt->bindParam(':uid', $usuario['id']);
                            $fichas_stmt->execute();
                            $total_fichas = $fichas_stmt->fetch(PDO::FETCH_ASSOC)['total'];
                        ?>
                        <tr>
                            <td>#<?php echo $usuario['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($usuario['nombre_completo']); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                            <td>
                                <span class="badge-rol <?php echo $usuario['rol']; ?>">
                                    <?php echo strtoupper($usuario['rol']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($usuario['fecha_registro'])); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo $total_fichas; ?></span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="ver-usuario.php?id=<?php echo $usuario['id']; ?>" 
                                       class="btn btn-sm btn-info" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <?php if($usuario['id'] != $_SESSION['admin_id']): ?>
                                        <a href="?action=toggle_admin&id=<?php echo $usuario['id']; ?>" 
                                           class="btn btn-sm btn-<?php echo $usuario['rol'] == 'admin' ? 'warning' : 'secondary'; ?>"
                                           title="<?php echo $usuario['rol'] == 'admin' ? 'Quitar admin' : 'Hacer admin'; ?>"
                                           onclick="return confirm('¿Estás seguro?')">
                                            <i class="bi bi-shield"></i>
                                        </a>
                                        
                                        <a href="?action=delete&id=<?php echo $usuario['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           title="Eliminar usuario"
                                           onclick="return confirm('¿Eliminar este usuario? Se borrarán también sus fichas.')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if(empty($usuarios)): ?>
                <div class="alert alert-info text-center mt-4">
                    <i class="bi bi-info-circle"></i> No se encontraron usuarios
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>