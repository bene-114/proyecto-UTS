<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<style>
    /* Estilos adicionales para los carruseles y videos */
    .carrera-media {
        height: 200px;
        overflow: hidden;
        border-radius: 10px;
        margin-bottom: 15px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .carrera-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .carrera-media:hover img {
        transform: scale(1.05);
    }
    
    .video-container {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
        border-radius: 10px;
    }
    
    .video-container video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .video-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
    
    .carousel-control-prev,
    .carousel-control-next {
        width: 10%;
        background: rgba(0,0,0,0.3);
        border-radius: 5px;
    }
    
    .carrera-card {
        position: relative;
        padding-top: 20px;
    }
    
    .badge-media {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(46, 125, 50, 0.9);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        z-index: 10;
    }
    
    .badge-media i {
        margin-right: 5px;
    }
</style>

<section class="page-header">
    <div class="container">
        <h1>OFERTA EDUCATIVA</h1>
        <p>Formamos profesionales con excelencia académica</p>
    </div>
</section>

<section class="carreras-main py-5">
    <div class="container">
        
        <!-- ======================================== -->
        <!-- TÉCNICO SUPERIOR UNIVERSITARIO - CARRUSEL -->
        <!-- ======================================== -->
        <h2 class="section-subtitle mb-4" data-aos="fade-right">
            <span class="badge-tsu">TÉCNICO SUPERIOR UNIVERSITARIO</span>
        </h2>
        <p class="text-muted mb-4">Colegiatura Cuatrimestral: $600 - $750</p>
        
        <div class="row mb-5">
            <!-- ASP TSU - Carrusel -->
            <div class="col-md-4 mb-4" data-aos="flip-left">
                <div class="carrera-card tsu-card">
                    <div class="badge-media">
                        <i class="bi bi-images"></i> Galería
                    </div>
                    <div class="carrera-icon">
                        <i class="bi bi-tree"></i>
                    </div>
                    <h3>TSU en Agricultura Sustentable y Protegida</h3>
                    <p class="division">DIVISIÓN AGROALIMENTARIA</p>
                    
                    <!-- CARRUSEL PARA AGRICULTURA TSU -->
                    <div class="carrera-media">
                        <div id="carouselAgroTSU" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="img/carreras/agro.jpg" class="d-block w-100" alt="Invernaderos">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carreras/agro1.jpg" class="d-block w-100" alt="Cultivos hidropónicos">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carreras/agro2.jpg" class="d-block w-100" alt="Laboratorio de suelos">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carreras/agro3.jpg" class="d-block w-100" alt="Campo experimental">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselAgroTSU" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselAgroTSU" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="carrera-desc">
                        <p>Serás capaz de dominar tecnologías sustentables para la producción de alimentos agrícolas, plantas forestales y ornamentales de interés económico, mediante sistemas innovadores de producción en invernaderos, viveros y a campo abierto.</p>
                    </div>
                    <div class="carrera-features">
                        <span><i class="bi bi-check-circle"></i> 70% Práctica</span>
                        <span><i class="bi bi-check-circle"></i> Invernaderos</span>
                        <span><i class="bi bi-check-circle"></i> Tecnología Sustentable</span>
                    </div>
                </div>
            </div>
            
            <!-- DSM TSU - Carrusel -->
            <div class="col-md-4 mb-4" data-aos="flip-left" data-aos-delay="100">
                <div class="carrera-card tsu-card">
                    <div class="badge-media">
                        <i class="bi bi-images"></i> Galería
                    </div>
                    <div class="carrera-icon">
                        <i class="bi bi-laptop"></i>
                    </div>
                    <h3>TSU en Desarrollo de Software Multiplataforma</h3>
                    <p class="division">DIVISIÓN DE TECNOLOGÍAS DE LA INFORMACIÓN</p>
                    
                    <!-- CARRUSEL PARA TI TSU -->
                    <div class="carrera-media">
                        <div id="carouselTITSU" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="img/carreras/ti.jpg" class="d-block w-100" alt="Laboratorio de cómputo">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carreras/ti1.jpg" class="d-block w-100" alt="Clase de programación">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carreras/ti2.jpg" class="d-block w-100" alt="Estudiantes en hackathon">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carreras/ti3.jpg" class="d-block w-100" alt="Proyectos de software">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselTITSU" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselTITSU" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="carrera-desc">
                        <p>Podrás desempeñarte como Programador Full Stack, desarrollador de Apps, líder de proyectos de desarrollo de software, arquitecto de software, gestor para el diseño, ejecución y mantenimiento de sistemas de información.</p>
                    </div>
                    <div class="carrera-features">
                        <span><i class="bi bi-check-circle"></i> Full Stack</span>
                        <span><i class="bi bi-check-circle"></i> Apps Móviles</span>
                        <span><i class="bi bi-check-circle"></i> Arquitectura Software</span>
                    </div>
                </div>
            </div>
            
            <!-- EAP TSU - Carrusel -->
            <div class="col-md-4 mb-4" data-aos="flip-left" data-aos-delay="200">
                <div class="carrera-card tsu-card">
                    <div class="badge-media">
                        <i class="bi bi-images"></i> Galería
                    </div>
                    <div class="carrera-icon">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <h3>TSU en Emprendimiento y Evaluación de Proyectos</h3>
                    <p class="division">DIVISIÓN DE ADMINISTRACIÓN</p>
                    
                    <!-- CARRUSEL PARA ADMIN TSU -->
                    <div class="carrera-media">
                        <div id="carouselAdminTSU" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="img/carreras/adm.jpg" class="d-block w-100" alt="Sala de juntas">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carreras/adm1.jpg" class="d-block w-100" alt="Presentación de proyectos">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carreras/adm2.jpg" class="d-block w-100" alt="Asesoría empresarial">
                                </div>
                                <div class="carousel-item">
                                    <img src="img/carreras/adm3.jpg" class="d-block w-100" alt="Evento de emprendedores">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselAdminTSU" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Anterior</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselAdminTSU" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Siguiente</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="carrera-desc">
                        <p>Formar profesionales en Gestión de Negocios y Proyectos que cuenten con habilidades y conocimientos para proponer, gestionar negocios y proyectos de inversión que generen desarrollo en su entorno.</p>
                    </div>
                    <div class="carrera-features">
                        <span><i class="bi bi-check-circle"></i> Gestión de Negocios</span>
                        <span><i class="bi bi-check-circle"></i> Evaluación de Proyectos</span>
                        <span><i class="bi bi-check-circle"></i> Desarrollo Local</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ======================================== -->
        <!-- INGENIERÍAS / LICENCIATURAS - VIDEOS -->
        <!-- ======================================== -->
        <h2 class="section-subtitle mb-4" data-aos="fade-right">
            <span class="badge-ingenieria">INGENIERÍAS / LICENCIATURAS</span>
        </h2>
        
        <div class="row">
            <!-- ASP Ingeniería - Video -->
            <div class="col-md-4 mb-4" data-aos="flip-right">
                <div class="carrera-card ing-card">
                    <div class="badge-media">
                        <i class="bi bi-play-circle"></i> Video
                    </div>
                    <div class="carrera-icon">
                        <i class="bi bi-tree"></i>
                    </div>
                    <h3>Ingeniería en Agricultura Sustentable y Protegida</h3>
                    <p class="division">DIVISIÓN AGROALIMENTARIA</p>
                    
                    <!-- VIDEO PARA ING AGRÍCOLA -->
                    <div class="video-container">
                        <video controls poster="img/carreras/ing-agro-poster.jpg">
                            <source src="videos/agro.mp4" type="video/mp4">

                        </video>
                    </div>
                    
                    <div class="carrera-desc mt-3">
                        <p>Continuidad de estudios para TSU, especialización en sistemas agrícolas sustentables y gestión de recursos naturales.</p>
                    </div>
                    <div class="carrera-features">
                        <span><i class="bi bi-check-circle"></i> 60% Práctica</span>
                        <span><i class="bi bi-check-circle"></i> Investigación</span>
                        <span><i class="bi bi-check-circle"></i> Gestión Ambiental</span>
                    </div>
                </div>
            </div>
            
            <!-- TID Ingeniería - Video -->
            <div class="col-md-4 mb-4" data-aos="flip-right" data-aos-delay="100">
                <div class="carrera-card ing-card">
                    <div class="badge-media">
                        <i class="bi bi-play-circle"></i> Video
                    </div>
                    <div class="carrera-icon">
                        <i class="bi bi-cpu"></i>
                    </div>
                    <h3>Ingeniería en Tecnologías de la Información e Innovación Digital</h3>
                    <p class="division">DIVISIÓN DE TECNOLOGÍAS DE LA INFORMACIÓN</p>
                    
                    <!-- VIDEO PARA ING TI -->
                    <div class="video-container">
                        <video controls poster="img/carreras/ing-agro-poster.jpg">
                            <source src="videos/ti.mp4" type="video/mp4">
    
                        </video>
                    </div>
                    
                    <div class="carrera-desc mt-3">
                        <p>Especialización en innovación digital, gestión de proyectos tecnológicos y arquitectura de software empresarial.</p>
                    </div>
                    <div class="carrera-features">
                        <span><i class="bi bi-check-circle"></i> Innovación Digital</span>
                        <span><i class="bi bi-check-circle"></i> Ciberseguridad</span>
                        <span><i class="bi bi-check-circle"></i> IA y Datos</span>
                    </div>
                </div>
            </div>
            
            <!-- Administración Licenciatura - Video -->
            <div class="col-md-4 mb-4" data-aos="flip-right" data-aos-delay="200">
                <div class="carrera-card ing-card">
                    <div class="badge-media">
                        <i class="bi bi-play-circle"></i> Video
                    </div>
                    <div class="carrera-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3>Licenciatura en Administración</h3>
                    <p class="division">DIVISIÓN DE ADMINISTRACIÓN</p>
                    
                    <!-- VIDEO PARA LIC ADMINISTRACIÓN -->
                    <div class="video-container">
                        <video controls poster="img/carreras/admin-poster.jpg">
                            <source src="videos/admin.mp4" type="video/mp4">
                
                        </video>
                    </div>
                    
                    <div class="carrera-desc mt-3">
                        <p>Formación integral en gestión empresarial, finanzas, marketing y liderazgo organizacional para el desarrollo regional.</p>
                    </div>
                    <div class="carrera-features">
                        <span><i class="bi bi-check-circle"></i> Gestión Empresarial</span>
                        <span><i class="bi bi-check-circle"></i> Finanzas</span>
                        <span><i class="bi bi-check-circle"></i> Marketing Digital</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Información adicional (se mantiene igual) -->
        <div class="info-box mt-5" data-aos="zoom-in">
            <div class="row">
                <div class="col-md-6">
                    <h4><i class="bi bi-award"></i> MODELO EDUCATIVO</h4>
                    <ul class="modelo-list">
                        <li>TSU: <strong>70% PRÁCTICA</strong> y 30% TEÓRICO</li>
                        <li>INGENIERÍAS/LICENCIATURAS: <strong>60% PRÁCTICA</strong> y 40% TEÓRICO</li>
                        <li>En tan solo <strong>3 años y 8 meses</strong> obtienes DOS TÍTULOS</li>
                        <li><strong>8 meses de experiencia laboral</strong> incluidos</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h4><i class="bi bi-globe"></i> MOVILIDAD ESTUDIANTIL</h4>
                    <p>Convenios nacionales e internacionales para que formes parte de nuestra movilidad estudiantil.</p>
                    <div class="becas-highlight">
                        🎓 SOMOS GESTORES DE BECAS<br>
                        "Jóvenes Escribiendo el Futuro"
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Requisitos -->
        <div class="requisitos-box mt-5" data-aos="fade-up">
            <h3>REQUISITOS DE INGRESO</h3>
            <div class="row">
                <div class="col-md-6">
                    <h5>Copias:</h5>
                    <ul>
                        <li>Acta de nacimiento</li>
                        <li>Certificado de bachillerato o constancia de estudios con promedio mínimo de 7.0</li>
                        <li>CURP</li>
                        <li>Credencial oficial con fotografía</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Adicional:</h5>
                    <ul>
                        <li>2 fotografías tamaño infantil</li>
                        <li>Original del pago de ficha, costo $400</li>
                        <li class="text-warning">*El cupo mínimo para aperturar un grupo es de 25 alumnos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script adicional para control de videos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pausar videos cuando no están visibles
    const videos = document.querySelectorAll('video');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                entry.target.pause();
            }
        });
    }, { threshold: 0.5 });
    
    videos.forEach(video => observer.observe(video));
});
</script>

<?php include 'includes/footer.php'; ?>