<?php
declare(strict_types=1);
namespace App\Http\Controllers\Admin;
use App\Core\Controller;
use App\Core\Session;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;

class UserController extends Controller
{
    public function index(): void
    {
        $users = User::with(['role','departments'])->orderBy('id')->get();
        $this->render('pages/admin/users/index', ['users' => $users, 'csrf' => Session::csrfToken()]);
    }
    public function create(): void
    {
        $roles = Role::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $this->render('pages/admin/users/create', ['roles' => $roles, 'departments' => $departments, 'csrf' => Session::csrfToken()]);
    }
    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $role_id = (int)($_POST['role_id'] ?? 0);
        $department_ids = array_map('intval', $_POST['department_ids'] ?? []);

        if ($name === '' || $email === '' || $password === '' || $role_id === 0) {
            Session::flash('error', 'Todos los campos son obligatorios.');
            $this->redirect('/admin/users/crear');
        }
        $exists = User::where('email', $email)->exists();
        if ($exists) { Session::flash('error', 'El email ya está registrado.'); $this->redirect('/admin/users/crear'); }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role_id' => $role_id,
        ]);
        if (!empty($department_ids)) { $user->departments()->sync($department_ids); }
        Session::flash('success', 'Usuario creado.');
        $this->redirect('/admin/users');
    }
    public function edit(int $id): void
    {
        $user = User::with('departments')->findOrFail($id);
        $roles = Role::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $this->render('pages/admin/users/edit', [
            'user' => $user, 'roles' => $roles, 'departments' => $departments, 'csrf' => Session::csrfToken()
        ]);
    }
    public function update(int $id): void
    {
        $user = User::findOrFail($id);
        $user->name = trim($_POST['name'] ?? $user->name);
        $user->email = trim($_POST['email'] ?? $user->email);
        $role_id = (int)($_POST['role_id'] ?? $user->role_id);
        $user->role_id = $role_id;
        if (!empty($_POST['password'])) {
            $user->password = password_hash((string)$_POST['password'], PASSWORD_BCRYPT);
        }
        $user->save();

        $department_ids = array_map('intval', $_POST['department_ids'] ?? []);
        $user->departments()->sync($department_ids);

        Session::flash('success', 'Usuario actualizado.');
        $this->redirect('/admin/users');
    }
}
