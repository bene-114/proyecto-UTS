<?php
session_start();
?>
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="img/logo.jpg" alt="UT Selva3" height="90">
            
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="homeDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-house-door"></i> HOME
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php#eventos">Eventos</a></li>
                        <li><a class="dropdown-item" href="index.php#croquis">Croquis</a></li>
                        <li><a class="dropdown-item" href="index.php#ubicacion">Ubicación</a></li>
                    </ul>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="carrerasDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-book"></i> CARRERAS
                    </a>
                    <ul class="dropdown-menu">
                        <li><h6 class="dropdown-header">TÉCNICO SUPERIOR UNIVERSITARIO</h6></li>
                        <li><a class="dropdown-item" href="carreras.php?tipo=tsu&carrera=asp">🌱 ASP - Agricultura Sustentable</a></li>
                        <li><a class="dropdown-item" href="carreras.php?tipo=tsu&carrera=dsm">💻 DSM - Desarrollo de Software</a></li>
                        <li><a class="dropdown-item" href="carreras.php?tipo=tsu&carrera=eap">📊 EAP - Emprendimiento y Proyectos</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><h6 class="dropdown-header">INGENIERÍAS / LICENCIATURAS</h6></li>
                        <li><a class="dropdown-item" href="carreras.php?tipo=lic&carrera=asp-ing">🌱 Ing. Agricultura Sustentable</a></li>
                        <li><a class="dropdown-item" href="carreras.php?tipo=lic&carrera=tid">💻 Ing. Tecnologías de la Información</a></li>
                        <li><a class="dropdown-item" href="carreras.php?tipo=lic&carrera=adm">📈 Lic. Administración</a></li>
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="registro.php"><i class="bi bi-person-plus"></i> REGISTRO</a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="pagoDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-cash-coin"></i> PAGO FICHA
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="pago-ficha.php">💰 Información de Pago</a></li>
                        <li><a class="dropdown-item" href="pago-ficha.php#subir">📤 Subir Comprobante</a></li>
                        <li><a class="dropdown-item" href="pago-ficha.php#estado">🔍 Verificar Estado</a></li>
                    </ul>
                </li>
                
                <?php if(isset($_SESSION['usuario_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo $_SESSION['usuario_nombre']; ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="mi-cuenta.php">Mi Cuenta</a></li>
                            <li><a class="dropdown-item" href="mis-fichas.php">Mis Fichas</a></li>
                            <?php if($_SESSION['usuario_rol'] == 'admin'): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="admin/dashboard.php">Panel Admin</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login .php"><i class="bi bi-box-arrow-in-right"></i> INICIAR SESIÓN</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="nav-spacer"></div>