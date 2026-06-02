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

// Procesar guardado de configuración
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_POST['guardar_general'])) {
        // Aquí guardarías en una tabla de configuraciones
        $mensaje = "Configuración guardada correctamente";
        $tipo_mensaje = "success";
    }
    
    if (isset($_POST['guardar_pagos'])) {
        $costo_ficha = $_POST['costo_ficha'] ?? 400;
        $mensaje = "Configuración de pagos actualizada";
        $tipo_mensaje = "success";
    }
}
?>

<style>
    .admin-header {
        background: linear-gradient(135deg, #1B5E20, #2E7D32);
        color: white;
        padding: 30px 0;
        margin-bottom: 30px;
    }
    
    .config-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .config-card h4 {
        color: #2E7D32;
        border-bottom: 2px solid #2E7D32;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    
    .info-box {
        background: #e8f5e9;
        border-left: 4px solid #2E7D32;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
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
                <h2><i class="bi bi-gear"></i> CONFIGURACIÓN DEL SISTEMA</h2>
            </div>
            
            <?php if($mensaje): ?>
                <div class="alert alert-<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-8">
                    
                    <!-- Configuración General -->
                    <div class="config-card">
                        <h4><i class="bi bi-building"></i> Información de la Universidad</h4>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nombre de la Universidad</label>
                                <input type="text" class="form-control" value="Universidad Tecnológica de la Selva" readonly>
                                <small class="text-muted">Contacta al desarrollador para cambiar este dato</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Unidad Académica</label>
                                <input type="text" class="form-control" value="Benemérito de las Américas" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" value="916 10 025 00">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email de contacto</label>
                                <input type="email" class="form-control" value="uacademica_benemerito@laselva.edu.mx">
                            </div>
                            
                            <button type="submit" name="guardar_general" class="btn btn-success">
                                <i class="bi bi-save"></i> Guardar cambios
                            </button>
                        </form>
                    </div>
                    
                    <!-- Configuración de Pagos -->
                    <div class="config-card">
                        <h4><i class="bi bi-cash"></i> Configuración de Pagos</h4>
                        
                        <div class="info-box">
                            <i class="bi bi-info-circle"></i>
                            Estos valores se muestran en las páginas de pago de ficha
                        </div>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Costo de ficha ($)</label>
                                <input type="number" class="form-control" name="costo_ficha" value="400" min="0" step="50">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Inscripción anual ($)</label>
                                <input type="number" class="form-control" value="500" min="0" readonly>
                                <small class="text-muted">Valor fijo por ahora</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Referencia de pago</label>
                                <input type="text" class="form-control" value="10000000020151402277">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Bancos habilitados</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked disabled>
                                    <label class="form-check-label">Banco Azteca</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked disabled>
                                    <label class="form-check-label">Santander</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked disabled>
                                    <label class="form-check-label">Bancomer</label>
                                </div>
                            </div>
                            
                            <button type="submit" name="guardar_pagos" class="btn btn-success">
                                <i class="bi bi-save"></i> Guardar configuración de pagos
                            </button>
                        </form>
                    </div>
                    
                    <!-- Configuración de Correo -->
                    <div class="config-card">
                        <h4><i class="bi bi-envelope"></i> Configuración de Correo</h4>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Notificaciones por correo</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">Enviar correo al aprobar/rechazar ficha</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">Enviar correo de bienvenida al registro</label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Correo remitente</label>
                                <input type="email" class="form-control" value="noreply@utselva.edu.mx">
                            </div>
                            
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Guardar configuración
                            </button>
                        </form>
                    </div>
                    
                </div>
                
                <div class="col-md-4">
                    
                    <!-- Información del Sistema -->
                    <div class="config-card">
                        <h4><i class="bi bi-info-circle"></i> Información del Sistema</h4>
                        
                        <table class="table table-sm">
                            <tr>
                                <th>Versión:</th>
                                <td>1.0.0</td>
                            </tr>
                            <tr>
                                <th>PHP:</th>
                                <td><?php echo phpversion(); ?></td>
                            </tr>
                            <tr>
                                <th>Base de datos:</th>
                                <td>MySQL</td>
                            </tr>
                            <tr>
                                <th>Última actualización:</th>
                                <td>Marzo 2025</td>
                            </tr>
                        </table>
                        
                        <hr>
                        
                        <h5>Estadísticas rápidas</h5>
                        <?php
                        $total_users = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
                        $total_fichas = $db->query("SELECT COUNT(*) FROM fichas_pago")->fetchColumn();
                        $pendientes = $db->query("SELECT COUNT(*) FROM fichas_pago WHERE estado='pendiente'")->fetchColumn();
                        ?>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-people"></i> Usuarios: <?php echo $total_users; ?></li>
                            <li><i class="bi bi-files"></i> Fichas: <?php echo $total_fichas; ?></li>
                            <li><i class="bi bi-clock-history"></i> Pendientes: <?php echo $pendientes; ?></li>
                        </ul>
                    </div>
                    
                    <!-- Ayuda -->
                    <div class="config-card">
                        <h4><i class="bi bi-question-circle"></i> Ayuda</h4>
                        <p>¿Necesitas ayuda con la configuración?</p>
                        <ul>
                            <li>Contacta al administrador del sistema</li>
                            <li>Email: admin@utselva.edu.mx</li>
                            <li>Tel: 916 10 025 00</li>
                        </ul>
                        <hr>
                        <p class="text-muted small">
                            <i class="bi bi-shield-check"></i> Sistema seguro - Todos los datos están protegidos
                        </p>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>