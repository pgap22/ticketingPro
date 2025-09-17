<?php
declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

require __DIR__ . '/../app/Core/Router.php';

/* Auth */
route('GET', '/login', [AuthController::class, 'showLogin'], []);
route('POST', '/login', [AuthController::class, 'login'], ['csrf']);
route('POST', '/logout', [AuthController::class, 'logout'], ['auth','csrf']);

/* Dashboard */
route('GET', '/', [DashboardController::class, 'index'], ['auth']);

/* Tickets */
route('GET', '/tickets', [TicketController::class, 'index'], ['auth']);
route('GET', '/tickets/crear', [TicketController::class, 'create'], ['auth']);
route('POST', '/tickets', [TicketController::class, 'store'], ['auth','csrf']);
route('GET', '/tickets/{id:\d+}', [TicketController::class, 'show'], ['auth']);
route('GET', '/tickets/{id:\d+}/editar', [TicketController::class, 'edit'], ['auth','role:Administrador,Agente']);
route('POST', '/tickets/{id:\d+}', [TicketController::class, 'update'], ['auth','csrf','role:Administrador,Agente']);
route('POST', '/tickets/{id:\d+}/asignar', [TicketController::class, 'assign'], ['auth','csrf','role:Administrador,Agente']);
route('POST', '/tickets/{id:\d+}/cerrar', [TicketController::class, 'close'], ['auth','csrf','role:Administrador,Agente']);
route('POST', '/tickets/{id:\d+}/comentarios', [CommentController::class, 'store'], ['auth','csrf']);

/* Admin: Departamentos */
route('GET', '/admin/departamentos', [DepartmentController::class, 'index'], ['auth','role:Administrador']);
route('POST', '/admin/departamentos', [DepartmentController::class, 'store'], ['auth','csrf','role:Administrador']);
route('GET', '/admin/departamentos/{id:\d+}/editar', [DepartmentController::class, 'edit'], ['auth','role:Administrador']);
route('POST', '/admin/departamentos/{id:\d+}', [DepartmentController::class, 'update'], ['auth','csrf','role:Administrador']);
route('POST', '/admin/departamentos/{id:\d+}/eliminar', [DepartmentController::class, 'destroy'], ['auth','csrf','role:Administrador']);

/* Admin: Categorías */
route('GET', '/admin/categorias', [CategoryController::class, 'index'], ['auth','role:Administrador']);
route('POST', '/admin/categorias', [CategoryController::class, 'store'], ['auth','csrf','role:Administrador']);
route('GET', '/admin/categorias/{id:\d+}/editar', [CategoryController::class, 'edit'], ['auth','role:Administrador']);
route('POST', '/admin/categorias/{id:\d+}', [CategoryController::class, 'update'], ['auth','csrf','role:Administrador']);
route('POST', '/admin/categorias/{id:\d+}/eliminar', [CategoryController::class, 'destroy'], ['auth','csrf','role:Administrador']);

/* Admin: Usuarios */
route('GET', '/admin/users', [AdminUserController::class, 'index'], ['auth','role:Administrador']);
route('GET', '/admin/users/crear', [AdminUserController::class, 'create'], ['auth','role:Administrador']);
route('POST', '/admin/users', [AdminUserController::class, 'store'], ['auth','csrf','role:Administrador']);
route('GET', '/admin/users/{id:\d+}/editar', [AdminUserController::class, 'edit'], ['auth','role:Administrador']);
route('POST', '/admin/users/{id:\d+}', [AdminUserController::class, 'update'], ['auth','csrf','role:Administrador']);

dispatchRoutes();
