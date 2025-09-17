@extends('layouts.app')
@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Tickets</h1>
    <a href="/tickets/crear" class="px-3 py-2 bg-gray-900 text-white rounded">Nuevo ticket</a>
  </div>

  <form method="get" class="mb-3">
    <label class="text-sm">Filtrar por departamento:</label>
    <select name="department_id" class="border rounded px-2 py-1">
      <option value="">Todos</option>
      @foreach($departments as $d)
        <option value="{{ $d->id }}" @if($departmentFilter==$d->id) selected @endif>{{ $d->name }}</option>
      @endforeach
    </select>
    <button class="px-3 py-1 border rounded">Aplicar</button>
  </form>

  <div class="bg-white border rounded shadow-sm overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left px-4 py-2">ID</th>
          <th class="text-left px-4 py-2">Título</th>
          <th class="text-left px-4 py-2">Depto</th>
          <th class="text-left px-4 py-2">Categoría</th>
          <th class="text-left px-4 py-2">Estado</th>
          <th class="text-left px-4 py-2">Prioridad</th>
          <th class="text-left px-4 py-2">Cliente</th>
          <th class="text-left px-4 py-2">Agente</th>
          <th class="text-left px-4 py-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($tickets as $t)
          <tr class="border-t">
            <td class="px-4 py-2">{{ $t->id }}</td>
            <td class="px-4 py-2">{{ $t->title }}</td>
            <td class="px-4 py-2">{{ $t->department?->name }}</td>
            <td class="px-4 py-2">{{ $t->category?->name }}</td>
            <td class="px-4 py-2">{{ $t->status }}</td>
            <td class="px-4 py-2">{{ $t->priority }}</td>
            <td class="px-4 py-2">{{ $t->user?->name }}</td>
            <td class="px-4 py-2">{{ $t->agent?->name ?? '-' }}</td>
            <td class="px-4 py-2">
              <a href="/tickets/{{ $t->id }}" class="text-blue-600 hover:underline">Ver</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
