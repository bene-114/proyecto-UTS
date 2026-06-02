<?php
session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);

include 'includes/header.php';
include 'includes/navbar.php';

// ===== DEBUG INICIAL =====
echo "<pre style='background:#000;color:#0f0;padding:10px'>";
echo "DEBUG INICIO\n";
echo "Metodo: ".$_SERVER['REQUEST_METHOD']."\n";
echo "Sesion:\n";
print_r($_SESSION);
echo "</pre>";

// Verificar si el usuario está logueado
require_once 'config/database.php';
$database = new Database();
$db = $database->getConnection();

$mensaje = '';
$tipo_mensaje = '';

// ===== PROCESO DE SUBIDA =====
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subir_ficha_robusto'])) {

    echo "<pre style='background:#111;color:#0ff;padding:10px'>POST DETECTADO\n";
    print_r($_POST);
    echo "</pre>";

    if (!isset($_SESSION['usuario_id'])) {
        $mensaje = "Error: usuario no logueado";
        $tipo_mensaje = "danger";
    } else {

        $usuario_id = $_SESSION['usuario_id'];
        $nombre = $_SESSION['usuario_nombre'];
        $email = $_SESSION['usuario_email'];

        if (!isset($_FILES['comprobante'])) {
            $mensaje = "No se recibió ningún archivo";
            $tipo_mensaje = "danger";
        } else {

            $archivo = $_FILES['comprobante'];
            echo "<pre>FILES:\n"; print_r($archivo); echo "</pre>";

            if ($archivo['error'] !== 0) {
                $mensaje = "Error al subir archivo. Código: ".$archivo['error'];
                $tipo_mensaje = "danger";
            } else {

                $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
                $permitidas = ['jpg','jpeg','png','pdf'];

                if (!in_array($ext,$permitidas)) {
                    $mensaje = "Solo se permiten archivos JPG, PNG o PDF";
                    $tipo_mensaje = "danger";
                } elseif ($archivo['size'] > 5*1024*1024) {
                    $mensaje = "Archivo excede 5MB";
                    $tipo_mensaje = "danger";
                } else {
                    $destino_dir = 'uploads/fichas/';
                    if (!file_exists($destino_dir)) mkdir($destino_dir,0777,true);
                    if (!is_writable($destino_dir)) {
                        $mensaje = "La carpeta $destino_dir no tiene permisos de escritura";
                        $tipo_mensaje = "danger";
                    } else {
                        $nombre_archivo = 'ficha_'.$usuario_id.'_'.time().'.'.$ext;
                        $ruta_destino = $destino_dir.$nombre_archivo;

                        if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
                            $query = "INSERT INTO fichas_pago 
                                      (usuario_id, nombre_completo, email, comprobante_path, fecha_subida, estado)
                                      VALUES (:uid,:nom,:em,:rut,NOW(),'pendiente')";
                            $stmt = $db->prepare($query);
                            $stmt->bindParam(':uid',$usuario_id);
                            $stmt->bindParam(':nom',$nombre);
                            $stmt->bindParam(':em',$email);
                            $stmt->bindParam(':rut',$ruta_destino);

                            if ($stmt->execute()) {
                                $mensaje = "✅ Comprobante subido correctamente";
                                $tipo_mensaje = "success";
                            } else {
                                $error = $stmt->errorInfo();
                                $mensaje = "❌ Error SQL: ".$error[2];
                                $tipo_mensaje = "danger";
                            }
                        } else {
                            $mensaje = "❌ Error moviendo archivo";
                            $tipo_mensaje = "danger";
                        }
                    }
                }
            }
        }
    }
}

// ===== CONSULTAR FICHAS DEL USUARIO =====
$fichas_usuario = [];
if (isset($_SESSION['usuario_id'])) {
    $query = "SELECT * FROM fichas_pago WHERE usuario_id=:uid ORDER BY fecha_subida DESC";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':uid', $_SESSION['usuario_id']);
    $stmt->execute();
    $fichas_usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>FICHAS ENCONTRADAS:\n";
    print_r($fichas_usuario);
    echo "</pre>";
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
        <div class="row mb-5">
            <div class="col-lg-6 mb-4">
                <div class="info-pago-card">
                    <h3><i class="bi bi-bank"></i> INFORMACIÓN DE PAGO</h3>
                    <div class="bancos-info">
                        <h5>PAGOS ÚNICAMENTE EN VENTANILLA</h5>
                        <div class="banco-item"><i class="bi bi-building"></i> Banco Azteca</div>
                        <div class="banco-item"><i class="bi bi-building"></i> Santander</div>
                        <div class="banco-item"><i class="bi bi-building"></i> Bancomer</div>
                    </div>
                    <div class="referencia-box">
                        <h5>REFERENCIA DE PAGO:</h5>
                        <div class="ref-numero">10000000020151402277</div>
                        <button class="btn btn-sm btn-outline-light mt-2" onclick="copiarReferencia()">Copiar referencia</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="imagen-clave-card">
                    <h3><i class="bi bi-upc-scan"></i> CLAVE PARA TRANSFERENCIA</h3>
                    <img src="img/ficha.jpg" class="img-fluid rounded">
                </div>
            </div>
        </div>

        <?php if(isset($_SESSION['usuario_id'])): ?>
        <div class="row mb-5">
            <div class="col-12">
                <div class="subir-ficha-card">
                    <h3><i class="bi bi-cloud-upload"></i> SUBIR COMPROBANTE DE PAGO</h3>

                    <?php if($mensaje): ?>
                        <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show">
                            <?php echo $mensaje; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form id="form-ficha" method="POST" enctype="multipart/form-data" onsubmit="return submitNativo();">
                        <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.pdf" required class="form-control mb-2">
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" required id="chk_pago">
                            <label class="form-check-label" for="chk_pago">Confirmo que realicé el pago y adjunto el comprobante</label>
                        </div>
                        <button type="submit" name="subir_ficha_robusto" class="btn btn-success">SUBIR COMPROBANTE</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(!empty($fichas_usuario)): ?>
        <div class="row">
            <div class="col-12">
                <div class="historial-card">
                    <h3>MIS COMPROBANTES</h3>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Comprobante</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($fichas_usuario as $ficha): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($ficha['fecha_subida'])); ?></td>
                                <td><a href="<?php echo $ficha['comprobante_path']; ?>" target="_blank">Ver</a></td>
                                <td><?php echo ucfirst($ficha['estado']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>

<script>
// Forzar submit nativo si JS de Bootstrap interfiere
function submitNativo() {
    console.log('Forzando submit nativo');
    return true; // permite el submit normal
}

function copiarReferencia() {
    navigator.clipboard.writeText('10000000020151402277').then(()=>alert('Referencia copiada'));
}
</script>

<?php include 'includes/footer.php'; ?>