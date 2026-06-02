<?php
session_start();
include '../includes/header.php';
?>

<style>
    .admin-login {
        min-height: 100vh;
        background: linear-gradient(135deg, #1B5E20, #2E7D32);
    }
    .admin-login-card {
        background: white;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
</style>

<div class="admin-login d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="admin-login-card">
                    <div class="text-center mb-4">
                        <img src="../img/logo-utselva.png" alt="UT Selva" height="80">
                        <h3 class="mt-3">Panel de Administración</h3>
                    </div>
                    
                    <form method="POST" action="auth.php">
                        <div class="mb-3">
                            <label>Usuario</label>
                            <input type="text" name="usuario" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            ACCEDER AL PANEL
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>