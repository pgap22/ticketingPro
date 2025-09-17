@extends('layouts.app')
@section('content')
  <h1 class="text-xl font-semibold mb-4">Editar Categoría</h1>
  <form method="post" action="/admin/categorias/{{ $cat->id }}" class="bg-white border rounded p-4 space-y-3">
    <input type="hidden" name="_token" value="{{ $csrf }}">
    <div>
      <label class="block text-sm">Nombre</label>
      <input name="name" class="w-full border rounded px-3 py-2" value="{{ $cat->name }}" required>
    </div>
    <div>
      <label class="block text-sm">Departamento</label>
      <select name="department_id" class="border rounded px-3 py-2">
        @foreach($departments as $d)
          <option value="{{ $d->id }}" @if($cat->department_id===$d->id) selected @endif>{{ $d->name }}</option>
        @endforeach
      </select>
    </div>
    <button class="px-4 py-2 bg-gray-900 text-white rounded">Guardar</button>
  </form>
@endsection
