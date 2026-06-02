<?php
// Verificar que el usuario sea admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener la página actual
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
    .admin-sidebar {
        background: linear-gradient(135deg, #1B5E20, #2E7D32);
        min-height: 100vh;
        color: white;
        padding: 20px;
        position: sticky;
        top: 0;
    }
    
    .admin-sidebar a {
        color: white;
        text-decoration: none;
        padding: 12px 15px;
        display: block;
        border-radius: 8px;
        margin: 5px 0;
        transition: all 0.3s;
    }
    
    .admin-sidebar a:hover {
        background: rgba(255,255,255,0.2);
        padding-left: 20px;
    }
    
    .admin-sidebar a.active {
        background: rgba(255,255,255,0.3);
        border-left: 4px solid #FFD700;
    }
    
    .admin-sidebar a i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }
    
    .admin-sidebar hr {
        border-color: rgba(255,255,255,0.2);
        margin: 20px 0;
    }
    
    .admin-user-info {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    
    .admin-avatar {
        width: 60px;
        height: 60px;
        background: #FFD700;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: #1B5E20;
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .admin-name {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .admin-role {
        font-size: 0.8rem;
        opacity: 0.8;
    }
</style>

<div class="admin-sidebar">
    <div class="admin-user-info">
        <div class="admin-avatar">
            <img src="../img/logo2.jpg" alt="UT Selva" height="60">
            <?php echo strtoupper(substr($_SESSION['admin_nombre'], 0, 1)); ?>
        </div>
        <div class="admin-name"><?php echo htmlspecialchars($_SESSION['admin_nombre']); ?></div>
        <div class="admin-role">Administrador</div>
    </div>
    
    <a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    
    <a href="fichas.php" class="<?php echo $current_page == 'fichas.php' ? 'active' : ''; ?>">
        <i class="bi bi-cash-stack"></i> Fichas de Pago
    </a>
    
    <a href="usuarios.php" class="<?php echo $current_page == 'usuarios.php' ? 'active' : ''; ?>">
        <i class="bi bi-people"></i> Usuarios
    </a>
    
    <a href="configuracion.php" class="<?php echo $current_page == 'configuracion.php' ? 'active' : ''; ?>">
        <i class="bi bi-gear"></i> Configuración
    </a>
    
    <hr>
    
    <a href="reportes.php" class="<?php echo $current_page == 'reportes.php' ? 'active' : ''; ?>">
        <i class="bi bi-graph-up"></i> Reportes
    </a>
    
    <a href="backup.php" class="<?php echo $current_page == 'backup.php' ? 'active' : ''; ?>">
        <i class="bi bi-database"></i> Respaldo
    </a>
    
    <hr>
    
    <a href="../index.php" target="_blank">
        <i class="bi bi-house-door"></i> Ver sitio
    </a>
    
    <a href="logout.php" class="text-danger">
        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
    </a>
</div>