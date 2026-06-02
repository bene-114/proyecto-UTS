<?php include 'includes/headerO.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!-- Hero Section con Video/Imagen de fondo -->
<section class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-video">
        <!-- Aquí puedes poner un video de fondo o imagen -->
        <img src="img/campus.jpg" alt="Campus UT Selva">
    </div>
    <div class="hero-content">
        <div class="container text-center text-white">
            <h1 class="display-3 mb-4" data-aos="fade-down">
                UNIVERSIDAD TECNOLÓGICA DE LA SELVA
            </h1>
            <h2 class="h3 mb-4" data-aos="fade-up" data-aos-delay="100">
                Unidad Académica Benemérito de las Américas
            </h2>
            <div class="highlight-box" data-aos="zoom-in" data-aos-delay="200">
                <p class="lead mb-0">
                    🎓 PRIMERA UNIVERSIDAD PÚBLICA EN EL ESTADO<br>
                    EN IMPLEMENTAR UN EXAMEN DIAGNÓSTICO
                </p>
            </div>
            <div class="mt-5" data-aos="fade-up" data-aos-delay="300">
                <a href="registro.php" class="btn btn-success btn-lg mx-2">¡REGÍSTRATE AHORA!</a>
                <a href="pago-ficha.php" class="btn btn-outline-light btn-lg mx-2">COMPRA TU FICHA</a>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Estadísticas -->
<section class="stats-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6 mb-4" data-aos="flip-left">
                <div class="stat-card">
                    <div class="stat-number">3 años 4 meses</div>
                    <div class="stat-label">Obtienes DOS TÍTULOS</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4" data-aos="flip-left" data-aos-delay="100">
                <div class="stat-card">
                    <div class="stat-number">70% - 60%</div>
                    <div class="stat-label">Práctica Profesional</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4" data-aos="flip-left" data-aos-delay="200">
                <div class="stat-card">
                    <div class="stat-number">25</div>
                    <div class="stat-label">Alumnos por grupo</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4" data-aos="flip-left" data-aos-delay="300">
                <div class="stat-card">
                    <div class="stat-number">$400</div>
                    <div class="stat-label">Costo de Ficha</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Eventos -->
<section id="eventos" class="eventos-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5" data-aos="fade-down">
            <i class="bi bi-calendar-event"></i> EVENTOS Y CONVOCATORIAS
        </h2>
        
        <div class="row">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="event-card">
                    <div class="event-date">
                        <span class="day">22</span>
                        <span class="month">MAYO</span>
                    </div>
                    <div class="event-info">
                        <h3>EXAMEN DIAGNÓSTICO</h3>
                        <p>Fichas a partir del 04 de Febrero al 20 de Mayo</p>
                        <div class="event-details">
                            <span><i class="bi bi-cash"></i> Inscripción Anual: $500</span>
                            <span><i class="bi bi-ticket"></i> Ficha: $400</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4" data-aos="fade-left">
                <div class="event-card">
                    <div class="event-date">
                        <span class="day">23</span>
                        <span class="month">MAYO</span>
                    </div>
                    <div class="event-info">
                        <h3>EXAMEN DE ADMISIÓN</h3>
                        <p>Entrega de fichas en instalaciones de la universidad</p>
                        <div class="event-details">
                            <span><i class="bi bi-clock"></i> 9:00 - 15:00 hrs</span>
                            <span><i class="bi bi-geo-alt"></i> Instalaciones UT Selva</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Banner informativo -->
        <div class="info-banner mt-4" data-aos="zoom-in">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4>📢 FICHAS PARA ASPIRANTES 2026 BENEMÉRITO</h4>
                    <p>PAGOS ÚNICAMENTE EN VENTANILLA</p>
                    <p class="small">Banco Azteca | Santander | Bancomer</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="ref-pago">REF: 10000000020151402277</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Croquis -->
<section id="croquis" class="croquis-section py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-5" data-aos="fade-down">
            <i class="bi bi-map"></i> CROQUIS DE INSTALACIONES
        </h2>
        
        <div class="row">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="croquis-container">
                    <img src="img/croquis.png" alt="Croquis UT Selva" class="img-fluid rounded shadow">
                    <div class="croquis-overlay">
                        <h4>Unidad Académica Benemérito</h4>
                        <p>Haz clic para ver departamentos</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <h3>GUÍA DE DEPARTAMENTOS</h3>
                <div class="dept-grid">
                    <div class="dept-item" data-departamento="agro">
                        <i class="bi bi-tree"></i>
                        <span>División Agroalimentaria</span>
                    </div>
                    <div class="dept-item" data-departamento="ti">
                        <i class="bi bi-laptop"></i>
                        <span>División TI</span>
                    </div>
                    <div class="dept-item" data-departamento="admin">
                        <i class="bi bi-building"></i>
                        <span>División Administración</span>
                    </div>
                    <div class="dept-item" data-departamento="control">
                        <i class="bi bi-file-text"></i>
                        <span>Control Escolar</span>
                    </div>
                    <div class="dept-item" data-departamento="Deportes">
                        <i class="bi bi-trophy"></i>
                        <span>Deportes</span>
                    </div>
                    <div class="dept-item" data-departamento="biblioteca">
                        <i class="bi bi-book"></i>
                        <span>Biblioteca</span>
                    </div>
                </div>
                
                <div class="info-panel mt-4" id="deptInfo">
                    <p>Selecciona un departamento para ver más información</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Ubicación -->
<section id="ubicacion" class="ubicacion-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5" data-aos="fade-down">
            <i class="bi bi-geo-alt-fill"></i> UBICACIÓN
        </h2>
        
        <div class="row">
            <div class="col-lg-6 mb-4" data-aos="fade-right">
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3225.866796278291!2d-90.61372947661799!3d16.449309240818327!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x858b2b86b7ae930f%3A0x97001dafc472271b!2sUniversidad%20tecnol%C3%B3gica%20de%20la%20selva%20unidad%20academica%20crucero%20zamora%20pico%20de%20oro!5e0!3m2!1ses-419!2sus!4v1772729948086!5m2!1ses-419!2sus"
                        width="100%" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>
            
            <div class="col-lg-6" data-aos="fade-left">
                <div class="location-info">
                    <h3>¿CÓMO LLEGAR?</h3>
                    <div class="info-card">
                        <h4><i class="bi bi-bus-front"></i> Transporte Escolar</h4>
                        <p>Rutas desde Benemérito de las Américas  a partir de las 6:00 AM</p>
                    </div>
                    
                    <div class="info-card">
                        <h4><i class="bi bi-car-front"></i> Acceso Vehicular</h4>
                        <p>Carretera Palenque -La trinitaria Km. 307</p>
                        <p>Estacionamiento gratuito para visitantes</p>
                    </div>
                    
                    <div class="info-card">
                        <h4><i class="bi bi-building"></i> SEDES</h4>
                        <ul>
                            <li>Sede Ocosingo</li>
                            <li>Unidad Académica Rayón</li>
                            <li>Unidad Académica Trinitaria</li>
                            <li>Unidad Académica San Javier</li>
                            <li>Unidad Académica Benemérito</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer1.php'; ?>