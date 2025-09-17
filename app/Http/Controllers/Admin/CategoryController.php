<?php
declare(strict_types=1);
namespace App\Http\Controllers\Admin;
use App\Core\Controller;
use App\Core\Session;
use App\Models\Department;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(): void
    {
        $department_id = (int)($_GET['department_id'] ?? 0);
        $departments = Department::orderBy('name')->get();
        $categories = $department_id ? Category::where('department_id', $department_id)->orderBy('name')->get() : collect();
        $this->render('pages/admin/categories/index', [
            'departments' => $departments,
            'categories' => $categories,
            'department_id' => $department_id,
            'csrf' => Session::csrfToken()
        ]);
    }
    public function store(): void
    {
        $name = trim($_POST['name'] ?? '');
        $department_id = (int)($_POST['department_id'] ?? 0);
        if ($name === '' || !$department_id) { Session::flash('error', 'Datos incompletos.'); \redirect('/admin/categorias?department_id='.$department_id); }
        Category::create(['name' => $name, 'department_id' => $department_id]);
        Session::flash('success', 'Categoría creada.');
        \redirect('/admin/categorias?department_id='.$department_id);
    }
    public function edit(int $id): void
    {
        $cat = Category::findOrFail($id);
        $deps = Department::orderBy('name')->get();
        $this->render('pages/admin/categories/edit', ['cat' => $cat, 'departments' => $deps, 'csrf' => Session::csrfToken()]);
    }
    public function update(int $id): void
    {
        $cat = Category::findOrFail($id);
        $name = trim($_POST['name'] ?? '');
        $department_id = (int)($_POST['department_id'] ?? 0);
        if ($name === '' || !$department_id) { Session::flash('error', 'Datos incompletos.'); \redirect('/admin/categorias/'.$id.'/editar'); }
        $cat->name = $name; $cat->department_id = $department_id; $cat->save();
        Session::flash('success', 'Categoría actualizada.');
        \redirect('/admin/categorias?department_id='.$department_id);
    }
    public function destroy(int $id): void
    {
        $cat = Category::findOrFail($id);
        $depId = $cat->department_id;
        $cat->delete();
        Session::flash('success', 'Categoría eliminada.');
        \redirect('/admin/categorias?department_id='.$depId);
    }
}
