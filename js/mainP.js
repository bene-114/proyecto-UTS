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
                desc: 'Edificio principal, planta baja. Laboratorios de suelos, invernaderos y áreas de cultivo.'
            },
            ti: {
                titulo: 'División de Tecnologías de la Información',
                desc: 'Edificio de cómputo, 2do piso. Laboratorios de software, redes y multimedia.'
            },
            admin: {
                titulo: 'División de Administración',
                desc: 'Edificio académico, 1er piso. Aulas interactivas y sala de juntas.'
            },
            control: {
                titulo: 'Control Escolar',
                desc: 'Edificio administrativo, planta baja. Trámites, certificaciones y servicios escolares.'
            },
            caja: {
                titulo: 'Caja',
                desc: 'Edificio administrativo, planta baja. Pagos de fichas, colegiaturas y servicios.'
            },
            biblioteca: {
                titulo: 'Biblioteca',
                desc: 'Edificio de servicios académicos. Sala de lectura, hemeroteca y centro de cómputo.'
            }
        };
        
        const info = infoDepartamentos[depto];
        if (info) {
            infoPanel.innerHTML = `
                <div class="alert alert-success">
                    <h5>${info.titulo}</h5>
                    <p>${info.desc}</p>
                </div>
            `;
        }
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

// Validación en tiempo real del formulario de pago
document.getElementById('comprobante')?.addEventListener('change', function(e) {
    const file = this.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // en MB
        
        if (fileSize > 5) {
            alert('El archivo no debe exceder los 5MB');
            this.value = '';
        }
    }
});

// 🔴 CORREGIDO: Todo el código de Bootstrap debe ejecutarse DESPUÉS de que cargue
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ DOM cargado, inicializando Bootstrap...');
    
    // Verificar que bootstrap está disponible
    if (typeof bootstrap === 'undefined') {
        console.error('❌ Bootstrap no está cargado');
        return;
    }
    
    // Tooltips - Verificar que existe el elemento
    try {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltipTriggerList.length > 0) {
            [...tooltipTriggerList].map(tooltipTriggerEl => {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            console.log('✅ Tooltips inicializados');
        }
    } catch (error) {
        console.error('Error en tooltips:', error);
    }
    
    // Carrusel - Verificar que existe el elemento
    try {
        const carruselElement = document.getElementById('carruselUniversidad');
        if (carruselElement) {
            const carousel = new bootstrap.Carousel(carruselElement, {
                interval: 5000,
                wrap: true
            });
            console.log('✅ Carrusel inicializado');
        }
    } catch (error) {
        console.error('Error en carrusel:', error);
    }
});

// Efecto de escritura para el hero (opcional, puede causar parpadeo)
const heroTitle = document.querySelector('.hero-content h1');
if (heroTitle) {
    // Solo aplicar si no es muy largo
    if (heroTitle.textContent.length < 50) {
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
        
        // Empezar después de un pequeño retraso
        setTimeout(typeWriter, 500);
    }
}

// Prevenir envío múltiple de formularios
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton && !submitButton.disabled) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';
        }
    });
});

// Función para copiar referencia (ya estaba en pago-ficha.php)
window.copiarReferencia = function() {
    const referencia = '10000000020151402277';
    navigator.clipboard.writeText(referencia).then(() => {
        alert('✅ Referencia copiada al portapapeles');
    }).catch(() => {
        alert('❌ No se pudo copiar, selecciona manualmente');
    });
};