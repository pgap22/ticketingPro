-- TicketingPRO v2 - Seeds

-- Roles
INSERT INTO roles (name) VALUES ('Administrador'), ('Agente'), ('Cliente');

-- Departamentos
INSERT INTO departments (name) VALUES ('Soporte TI'), ('Finanzas');

-- Categorías
INSERT INTO categories (name, department_id) VALUES
('Problema de Hardware', 1),
('Software - Instalación', 1),
('Acceso a Sistemas', 1),
('Consulta de Factura', 2),
('Reembolso', 2);

-- Usuarios (admin, agentes, clientes)
-- NOTA: reemplaza $2a$12$txiJzzSYkh600uJlt623t.hmjyDsrucR18ptsXsk3gDmfuztsoVQG 12345 por un hash bcrypt real.
INSERT INTO users (name, email, password, role_id) VALUES
('Administrador', 'admin@ticketingpro.local', '$2a$12$txiJzzSYkh600uJlt623t.hmjyDsrucR18ptsXsk3gDmfuztsoVQG', 1),
('Agente TI', 'agente.ti@ticketingpro.local', '$2a$12$txiJzzSYkh600uJlt623t.hmjyDsrucR18ptsXsk3gDmfuztsoVQG', 2),
('Agente Finanzas', 'agente.fin@ticketingpro.local', '$2a$12$txiJzzSYkh600uJlt623t.hmjyDsrucR18ptsXsk3gDmfuztsoVQG', 2),
('Cliente 1', 'cliente1@ticketingpro.local', '$2a$12$txiJzzSYkh600uJlt623t.hmjyDsrucR18ptsXsk3gDmfuztsoVQG', 3),
('Cliente 2', 'cliente2@ticketingpro.local', '$2a$12$txiJzzSYkh600uJlt623t.hmjyDsrucR18ptsXsk3gDmfuztsoVQG', 3);

-- Asignación de agentes a departamentos
INSERT INTO department_user (user_id, department_id) VALUES
(2, 1), -- Agente TI -> Soporte TI
(3, 2); -- Agente Finanzas -> Finanzas

-- Tickets de ejemplo
INSERT INTO tickets (title, description, status, priority, user_id, agent_id, department_id, category_id)
VALUES
('No enciende la laptop', 'El equipo no enciende desde ayer.', 'abierto', 'alta', 4, 2, 1, 1),
('Instalar Office', 'Solicito instalación de Office 365.', 'en_proceso', 'media', 5, 2, 1, 2),
('Consulta factura #123', 'No encuentro el detalle de la factura.', 'abierto', 'baja', 4, 3, 2, 4);

-- Comentarios de ejemplo
INSERT INTO comments (ticket_id, user_id, body) VALUES
(1, 2, 'Revisaré el equipo en sitio hoy.'),
(2, 2, 'Se programa instalación para mañana.'),
(3, 3, 'Por favor adjunta la factura en PDF.');