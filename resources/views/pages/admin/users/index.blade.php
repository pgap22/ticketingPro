@extends('layouts.app')
@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Usuarios</h1>
    <a href="/admin/users/crear" class="px-3 py-2 bg-gray-900 text-white rounded">Nuevo usuario</a>
  </div>
  <div class="bg-white border rounded shadow-sm overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left px-4 py-2">ID</th>
          <th class="text-left px-4 py-2">Nombre</th>
          <th class="text-left px-4 py-2">Email</th>
          <th class="text-left px-4 py-2">Rol</th>
          <th class="text-left px-4 py-2">Departamentos</th>
          <th class="text-left px-4 py-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $u)
          <tr class="border-t">
            <td class="px-4 py-2">{{ $u->id }}</td>
            <td class="px-4 py-2">{{ $u->name }}</td>
            <td class="px-4 py-2">{{ $u->email }}</td>
            <td class="px-4 py-2">{{ $u->role?->name }}</td>
            <td class="px-4 py-2">
              @foreach($u->departments as $d)
                <span class="px-2 py-1 bg-gray-100 rounded mr-1">{{ $d->name }}</span>
              @endforeach
            </td>
            <td class="px-4 py-2"><a class="text-blue-600 hover:underline" href="/admin/users/{{ $u->id }}/editar">Editar</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
