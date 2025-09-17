<?php
declare(strict_types=1);
namespace App\Http\Controllers;
use App\Core\Controller;
use App\Core\Session;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Department;
use App\Models\Category;

class TicketController extends Controller
{
    public function index(): void
    {
        $uid = Session::get('user_id');
        $user = User::with(['role','departments'])->find($uid);
        $role = $user?->role?->name ?? 'Cliente';
        $query = Ticket::with(['user','agent','department','category'])->latest();

        $departmentFilter = (int)($_GET['department_id'] ?? 0);
        if ($role === 'Cliente') {
            $query->where('user_id', $uid);
        } elseif ($role === 'Agente') {
            $deptIds = $user?->departments?->pluck('id')->all() ?? [];
            $query->whereIn('department_id', $deptIds);
            if ($departmentFilter) { $query->where('department_id', $departmentFilter); }
        } else { // Admin
            if ($departmentFilter) { $query->where('department_id', $departmentFilter); }
        }
        $tickets = $query->get();
        $departments = Department::orderBy('name')->get();

        $this->render('pages/tickets/index', [
            'tickets' => $tickets,
            'csrf' => Session::csrfToken(),
            'role' => $role,
            'departments' => $departments,
            'departmentFilter' => $departmentFilter,
        ]);
    }

    public function create(): void
    {
        $departments = Department::with('categories')->orderBy('name')->get();
        $this->render('pages/tickets/create', [
            'csrf' => Session::csrfToken(),
            'departments' => $departments,
        ]);
    }

    public function store(): void
    {
        $uid = Session::get('user_id');
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $priority = $_POST['priority'] ?? 'media';
        $department_id = (int)($_POST['department_id'] ?? 0);
        $category_id = (int)($_POST['category_id'] ?? 0);

        if ($title === '' || $description === '' || !$department_id || !$category_id) {
            Session::flash('error', 'Completa los campos obligatorios.');
            $this->redirect('/tickets/crear');
        }

        // Validar que la categoría pertenece al departamento
        $category = Category::where('id', $category_id)->where('department_id', $department_id)->first();
        if (!$category) {
            Session::flash('error', 'La categoría no corresponde al departamento seleccionado.');
            $this->redirect('/tickets/crear');
        }

        $ticket = Ticket::create([
            'title' => $title,
            'description' => $description,
            'priority' => in_array($priority, ['baja','media','alta'], true) ? $priority : 'media',
            'status' => 'abierto',
            'user_id' => $uid,
            'department_id' => $department_id,
            'category_id' => $category_id,
        ]);

        Session::flash('success', 'Ticket creado.');
        $this->redirect('/tickets/' . $ticket->id);
    }

    public function show(int $id): void
    {
        $uid = Session::get('user_id');
        $ticket = Ticket::with(['user','agent','department','category','comments.user','comments.attachments'])->findOrFail($id);

        // Autorización
        $user = User::with(['role','departments'])->find($uid);
        $role = $user?->role?->name ?? 'Cliente';
        $allowed = false;
        if ($role === 'Administrador') { $allowed = true; }
        elseif ($role === 'Agente') {
            $deptIds = $user?->departments?->pluck('id')->all() ?? [];
            $allowed = in_array($ticket->department_id, $deptIds, true);
        } else { // Cliente
            $allowed = ($ticket->user_id === $uid);
        }
        if (!$allowed) {
            Session::flash('error', 'No autorizado.');
            $this->redirect('/tickets');
        }

        $agents = User::whereHas('role', fn($q) => $q->where('name', 'Agente'))->get();
        $this->render('pages/tickets/show', [
            'ticket' => $ticket,
            'agents' => $agents,
            'csrf' => Session::csrfToken(),
            'role' => $role,
        ]);
    }

    public function edit(int $id): void
    {
        $ticket = Ticket::findOrFail($id);
        $departments = Department::with('categories')->orderBy('name')->get();
        $this->render('pages/tickets/edit', [
            'ticket' => $ticket,
            'csrf' => Session::csrfToken(),
            'departments' => $departments,
        ]);
    }

    public function update(int $id): void
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->title = trim($_POST['title'] ?? $ticket->title);
        $ticket->description = trim($_POST['description'] ?? $ticket->description);
        $ticket->priority = $_POST['priority'] ?? $ticket->priority;
        $ticket->status = $_POST['status'] ?? $ticket->status;
        $department_id = (int)($_POST['department_id'] ?? $ticket->department_id);
        $category_id = (int)($_POST['category_id'] ?? $ticket->category_id);

        // Validar relación depto-categoría
        $category = Category::where('id', $category_id)->where('department_id', $department_id)->first();
        if (!$category) {
            Session::flash('error', 'La categoría no corresponde al departamento.');
            $this->redirect('/tickets/' . $ticket->id . '/editar');
        }

        $ticket->department_id = $department_id;
        $ticket->category_id = $category_id;
        $ticket->save();

        Session::flash('success', 'Ticket actualizado.');
        $this->redirect('/tickets/' . $ticket->id);
    }

    public function assign(int $id): void
    {
        $ticket = Ticket::findOrFail($id);
        $agentId = (int)($_POST['agent_id'] ?? 0);
        if ($agentId > 0) {
            $ticket->agent_id = $agentId;
            $ticket->save();
            Session::flash('success', 'Ticket asignado.');
        }
        $this->redirect('/tickets/' . $ticket->id);
    }

    public function close(int $id): void
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'cerrado';
        $ticket->save();
        Session::flash('success', 'Ticket cerrado.');
        $this->redirect('/tickets/' . $ticket->id);
    }
}
