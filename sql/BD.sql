CREATE DATABASE IF NOT EXISTS utselva_benemerito;
USE utselva_benemerito;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    rol ENUM('usuario', 'admin') DEFAULT 'usuario'
);

-- Tabla de fichas de pago
CREATE TABLE fichas_pago (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    comprobante_path VARCHAR(255),
    fecha_subida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',
    observaciones TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla de eventos
CREATE TABLE eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    fecha_evento DATE,
    imagen_path VARCHAR(255),
    activo BOOLEAN DEFAULT TRUE
);

-- Insertar usuario admin por defecto (password: Admin123!)
INSERT INTO usuarios (nombre_completo, email, password, rol) VALUES 
('Administrador', 'admin@utselva.edu.mx', '$2y$10$YourHashedPasswordHere', 'admin');