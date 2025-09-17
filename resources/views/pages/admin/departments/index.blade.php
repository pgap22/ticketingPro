@extends('layouts.app')
@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Departamentos</h1>
    <form method="post" action="/admin/departamentos">
      <input type="hidden" name="_token" value="{{ $csrf }}">
      <input name="name" placeholder="Nuevo departamento" class="border rounded px-3 py-1">
      <button class="px-3 py-1 bg-gray-900 text-white rounded">Crear</button>
    </form>
  </div>
  <div class="bg-white border rounded shadow-sm overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50"><tr><th class="px-4 py-2 text-left">Nombre</th><th class="px-4 py-2 text-left">Acciones</th></tr></thead>
      <tbody>
      @foreach($departments as $d)
        <tr class="border-t">
          <td class="px-4 py-2">{{ $d->name }}</td>
          <td class="px-4 py-2 space-x-2">
            <a class="text-blue-600 hover:underline" href="/admin/departamentos/{{ $d->id }}/editar">Editar</a>
            <form method="post" action="/admin/departamentos/{{ $d->id }}/eliminar" class="inline">
              <input type="hidden" name="_token" value="{{ $csrf }}">
              <button class="text-red-700">Eliminar</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
@endsection
