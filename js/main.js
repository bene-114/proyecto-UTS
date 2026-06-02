// Inicializar AOS
AOS.init({
    duration: 1000,
    once: true,
    offset: 100
});

// Validación de formulario de registro
document.getElementById('registroForm')?.addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Las contraseñas no coinciden');
    }
});

// Funcionalidad para el croquis interactivo
document.querySelectorAll('.dept-item').forEach(item => {
    item.addEventListener('click', function() {
        const depto = this.dataset.departamento;
        const infoPanel = document.getElementById('deptInfo');
        
        const infoDepartamentos = {
            agro: {
                titulo: 'División Agroalimentaria',
                desc: 'Edificio D, planta baja. Laboratorios de suelos, invernaderos y áreas de cultivo.'
            },
            ti: {
                titulo: 'División de Tecnologías de la Información',
                desc: 'Edificio B. Laboratorio de software, redes y multimedia.'
            },
            admin: {
                titulo: 'División de Administración',
                desc: 'Edificio E. Aulas interactivas y sala de juntas.'
            },
            control: {
                titulo: 'Control Escolar',
                desc: 'Edificio A, planta baja. Trámites, certificaciones y servicios escolares.'
            },
            Deportes: {
                titulo: 'Deportes',
                desc: 'Domo lugar donde se encuentra la cancha deportiva para distintos deportes.'
            },
            biblioteca: {
                titulo: 'Biblioteca',
                desc: 'Edificio C. Sala de lectura y laboratorio de idiomas.'
            }
        };
        
        const info = infoDepartamentos[depto];
        infoPanel.innerHTML = `
            <div class="alert alert-success">
                <h5>${info.titulo}</h5>
                <p>${info.desc}</p>
            </div>
        `;
    });
});

// Galería de imágenes con lightbox
document.querySelectorAll('.carrera-card').forEach(card => {
    card.addEventListener('click', function() {
        // Aquí puedes implementar un modal con más información
        console.log('Mostrar más información de la carrera');
    });
});

// Contador regresivo para el examen
function actualizarContador() {
    const examenFecha = new Date('2025-05-23T09:00:00');
    const ahora = new Date();
    const diferencia = examenFecha - ahora;
    
    if (diferencia > 0) {
        const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
        const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
        
        const contadorElement = document.getElementById('contador-examen');
        if (contadorElement) {
            contadorElement.innerHTML = `${dias}d ${horas}h ${minutos}m`;
        }
    }
}

// Actualizar cada minuto
setInterval(actualizarContador, 60000);
actualizarContador();

// Smooth scroll para enlaces internos
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Tooltips
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Validación en tiempo real del formulario de pago
document.getElementById('comprobante')?.addEventListener('change', function(e) {
    const file = this.files[0];
    const fileSize = file.size / 1024 / 1024; // en MB
    
    if (fileSize > 5) {
        alert('El archivo no debe exceder los 5MB');
        this.value = '';
    }
});

// Efecto de escritura para el hero
const heroTitle = document.querySelector('.hero-content h1');
if (heroTitle) {
    const text = heroTitle.textContent;
    heroTitle.textContent = '';
    let i = 0;
    
    function typeWriter() {
        if (i < text.length) {
            heroTitle.textContent += text.charAt(i);
            i++;
            setTimeout(typeWriter, 50);
        }
    }
    
    typeWriter();
}

// Carrusel automático
document.addEventListener('DOMContentLoaded', function() {
    const carousel = new bootstrap.Carousel('#carruselUniversidad', {
        interval: 5000,
        wrap: true
    });
});

// Prevenir envío múltiple de formularios
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';
        }
    });
});