<?php
declare(strict_types=1);
namespace App\Http\Controllers\Admin;
use App\Core\Controller;
use App\Core\Session;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index(): void
    {
        $departments = Department::orderBy('name')->get();
        $this->render('pages/admin/departments/index', [
            'departments' => $departments,
            'csrf' => Session::csrfToken()
        ]);
    }
    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        if ($name === '') { Session::flash('error', 'El nombre es obligatorio.'); \redirect('/admin/departamentos'); }
        Department::create(['name' => $name]);
        Session::flash('success', 'Departamento creado.');
        \redirect('/admin/departamentos');
    }
    public function edit(int $id): void
    {
        $dep = Department::findOrFail($id);
        $this->render('pages/admin/departments/edit', ['dep' => $dep, 'csrf' => Session::csrfToken()]);
    }
    public function update(int $id): void
    {
        $dep = Department::findOrFail($id);
        $name = trim($_POST['name'] ?? '');
        if ($name === '') { Session::flash('error', 'El nombre es obligatorio.'); \redirect('/admin/departamentos/'.$id.'/editar'); }
        $dep->name = $name;
        $dep->save();
        Session::flash('success', 'Departamento actualizado.');
        \redirect('/admin/departamentos');
    }
    public function destroy(int $id): void
    {
        $dep = Department::findOrFail($id);
        $dep->delete();
        Session::flash('success', 'Departamento eliminado.');
        \redirect('/admin/departamentos');
    }
}
