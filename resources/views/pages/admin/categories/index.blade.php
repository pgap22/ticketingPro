@extends('layouts.app')
@section('content')
  <h1 class="text-xl font-semibold mb-4">Categorías por Departamento</h1>
  <form method="get" class="mb-3">
    <label class="text-sm">Departamento:</label>
    <select name="department_id" class="border rounded px-2 py-1">
      <option value="">-- Seleccionar --</option>
      @foreach($departments as $d)
        <option value="{{ $d->id }}" @if($department_id==$d->id) selected @endif>{{ $d->name }}</option>
      @endforeach
    </select>
    <button class="px-3 py-1 border rounded">Ver</button>
  </form>

  @if($department_id)
    <div class="bg-white border rounded shadow-sm mb-4 overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50"><tr><th class="px-4 py-2 text-left">Nombre</th><th class="px-4 py-2 text-left">Acciones</th></tr></thead>
        <tbody>
        @foreach($categories as $c)
          <tr class="border-t">
            <td class="px-4 py-2">{{ $c->name }}</td>
            <td class="px-4 py-2 space-x-2">
              <a class="text-blue-600 hover:underline" href="/admin/categorias/{{ $c->id }}/editar">Editar</a>
              <form method="post" action="/admin/categorias/{{ $c->id }}/eliminar" class="inline">
                <input type="hidden" name="_token" value="{{ $csrf }}">
                <button class="text-red-700">Eliminar</button>
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>

    <form method="post" action="/admin/categorias" class="bg-white border rounded p-4 space-y-2">
      <input type="hidden" name="_token" value="{{ $csrf }}">
      <input type="hidden" name="department_id" value="{{ $department_id }}">
      <label class="block text-sm">Nueva categoría</label>
      <input name="name" class="border rounded px-3 py-2" placeholder="Nombre" required>
      <button class="px-3 py-1 bg-gray-900 text-white rounded">Crear</button>
    </form>
  @endif
@endsection
