<?php
session_start();
include 'includes/header.php';
include 'includes/navbar.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php?redirect=mis-fichas.php');
    exit();
}

require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Obtener todas las fichas del usuario
$query = "SELECT * FROM fichas_pago 
          WHERE usuario_id = :usuario_id 
          ORDER BY fecha_subida DESC";
$stmt = $db->prepare($query);
$stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
$stmt->execute();
$fichas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estadísticas del usuario
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) as pendientes,
    SUM(CASE WHEN estado = 'aprobado' THEN 1 ELSE 0 END) as aprobados,
    SUM(CASE WHEN estado = 'rechazado' THEN 1 ELSE 0 END) as rechazados
    FROM fichas_pago 
    WHERE usuario_id = :usuario_id";
$stats_stmt = $db->prepare($stats_query);
$stats_stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
$stats_stmt->execute();
$stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
?>

<style>
    .mis-fichas-header {
        background: linear-gradient(135deg, var(--verde-utselva), var(--verde-oscuro));
        color: white;
        padding: 60px 0;
        margin-bottom: 40px;
    }
    
    .stat-card-fichas {
        background: white;
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s;
        border-bottom: 4px solid transparent;
    }
    
    .stat-card-fichas.total {
        border-bottom-color: #17a2b8;
    }
    .stat-card-fichas.pendientes {
        border-bottom-color: #ffc107;
    }
    .stat-card-fichas.aprobados {
        border-bottom-color: #28a745;
    }
    .stat-card-fichas.rechazados {
        border-bottom-color: #dc3545;
    }
    
    .stat-card-fichas:hover {
        transform: translateY(-5px);
    }
    
    .stat-number-fichas {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 10px 0;
    }
    
    .ficha-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        border-left: 5px solid transparent;
        transition: all 0.3s;
    }
    
    .ficha-card.pendiente {
        border-left-color: #ffc107;
    }
    .ficha-card.aprobado {
        border-left-color: #28a745;
    }
    .ficha-card.rechazado {
        border-left-color: #dc3545;
    }
    
    .ficha-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .fecha-ficha {
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .estado-badge-ficha {
        padding: 5px 15px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .acciones-ficha {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f8f9fa;
        border-radius: 15px;
    }
    
    .empty-state i {
        font-size: 5rem;
        color: #c0c0c0;
        margin-bottom: 20px;
    }
    
    @media (max-width: 768px) {
        .acciones-ficha {
            justify-content: flex-start;
            margin-top: 15px;
        }
    }
</style>

<section class="mis-fichas-header">
    <div class="container text-center">
        <h1><i class="bi bi-files"></i> MIS FICHAS DE PAGO</h1>
        <p class="lead">Historial de todos tus comprobantes subidos</p>
    </div>
</section>

<section class="py-4">
    <div class="container">
        
        <!-- Tarjetas de estadísticas -->
        <div class="row mb-5">
            <div class="col-md-3 mb-3">
                <div class="stat-card-fichas total">
                    <i class="bi bi-files fs-1 text-info"></i>
                    <div class="stat-number-fichas"><?php echo $stats['total'] ?? 0; ?></div>
                    <div class="stat-label">Total de fichas</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card-fichas pendientes">
                    <i class="bi bi-clock-history fs-1 text-warning"></i>
                    <div class="stat-number-fichas"><?php echo $stats['pendientes'] ?? 0; ?></div>
                    <div class="stat-label">En revisión</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card-fichas aprobados">
                    <i class="bi bi-check-circle fs-1 text-success"></i>
                    <div class="stat-number-fichas"><?php echo $stats['aprobados'] ?? 0; ?></div>
                    <div class="stat-label">Aprobadas</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card-fichas rechazados">
                    <i class="bi bi-x-circle fs-1 text-danger"></i>
                    <div class="stat-number-fichas"><?php echo $stats['rechazados'] ?? 0; ?></div>
                    <div class="stat-label">Rechazadas</div>
                </div>
            </div>
        </div>
        
        <!-- Listado de fichas -->
        <h3 class="mb-4"><i class="bi bi-list-ul"></i> Historial de comprobantes</h3>
        
        <?php if (empty($fichas)): ?>
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>No hay fichas registradas</h4>
                <p class="text-muted">Aún no has subido ningún comprobante de pago.</p>
                <a href="pago-ficha.php#subir" class="btn btn-success mt-3">
                    <i class="bi bi-upload"></i> Subir mi primer comprobante
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($fichas as $ficha): ?>
                <div class="ficha-card <?php echo $ficha['estado']; ?>">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <div class="fecha-ficha">
                                <i class="bi bi-calendar3"></i>
                                <?php echo date('d/m/Y', strtotime($ficha['fecha_subida'])); ?>
                            </div>
                            <div class="fecha-ficha">
                                <i class="bi bi-clock"></i>
                                <?php echo date('H:i', strtotime($ficha['fecha_subida'])); ?> hrs
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <span class="estado-badge-ficha bg-<?php 
                                echo $ficha['estado'] == 'aprobado' ? 'success' : 
                                    ($ficha['estado'] == 'rechazado' ? 'danger' : 'warning'); 
                            ?> text-white">
                                <i class="bi bi-<?php 
                                    echo $ficha['estado'] == 'aprobado' ? 'check-circle' : 
                                        ($ficha['estado'] == 'rechazado' ? 'exclamation-circle' : 'hourglass-split'); 
                                ?>"></i>
                                <?php 
                                    echo $ficha['estado'] == 'aprobado' ? 'APROBADO' : 
                                        ($ficha['estado'] == 'rechazado' ? 'RECHAZADO' : 'EN REVISIÓN'); 
                                ?>
                            </span>
                        </div>
                        
                        <div class="col-md-3">
                            <?php if ($ficha['observaciones']): ?>
                                <span class="text-muted" title="<?php echo htmlspecialchars($ficha['observaciones']); ?>">
                                    <i class="bi bi-chat-text"></i> 
                                    <?php echo substr($ficha['observaciones'], 0, 30) . '...'; ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">Sin observaciones</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="acciones-ficha">
                                <a href="<?php echo $ficha['comprobante_path']; ?>" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-primary"
                                   title="Ver comprobante">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="ver-comprobante.php?id=<?php echo $ficha['id']; ?>" 
                                   class="btn btn-sm btn-info text-white"
                                   title="Ver detalles">
                                    <i class="bi bi-info-circle"></i>
                                </a>
                                <?php if ($ficha['estado'] == 'rechazado'): ?>
                                    <a href="pago-ficha.php#subir" 
                                       class="btn btn-sm btn-success"
                                       title="Subir nuevo comprobante">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información adicional para móviles -->
                    <div class="row mt-2 d-md-none">
                        <div class="col-12">
                            <small class="text-muted">
                                <i class="bi bi-file-earmark"></i> 
                                <?php echo basename($ficha['comprobante_path']); ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <!-- Resumen adicional -->
            <div class="alert alert-info mt-4">
                <i class="bi bi-info-circle"></i>
                <strong>Información importante:</strong>
                <ul class="mb-0 mt-2">
                    <li>Los comprobantes en estado <span class="badge bg-warning">EN REVISIÓN</span> serán atendidos en máximo 24 horas hábiles.</li>
                    <li>Si tu ficha es <span class="badge bg-danger">RECHAZADA</span>, revisa las observaciones y sube un nuevo comprobante.</li>
                    <li>Las fichas <span class="badge bg-success">APROBADAS</span> ya están validadas para tu proceso de admisión.</li>
                </ul>
            </div>
        <?php endif; ?>
        
        <!-- Botón para subir nueva ficha -->
        <div class="text-center mt-4">
            <a href="pago-ficha.php#subir" class="btn btn-success btn-lg">
                <i class="bi bi-cloud-upload"></i> SUBIR NUEVO COMPROBANTE
            </a>
            <a href="pago-ficha.php" class="btn btn-outline-secondary btn-lg ms-2">
                <i class="bi bi-arrow-left"></i> VOLVER A PAGO FICHA
            </a>
        </div>
        
    </div>
</section>

<!-- Script para tooltips -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<?php include 'includes/footer.php'; ?>