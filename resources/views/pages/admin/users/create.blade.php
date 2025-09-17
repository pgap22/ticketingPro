@extends('layouts.app')
@section('content')
  <h1 class="text-xl font-semibold mb-4">Crear usuario</h1>
  @if(session('_flash.error'))
    <div class="mb-3 p-2 bg-red-50 text-red-700 border border-red-200 rounded">{{ session('_flash.error') }}</div>
  @endif
  <form method="post" action="/admin/users" class="bg-white border rounded p-4 space-y-3">
    <input type="hidden" name="_token" value="{{ $csrf }}">
    <div>
      <label class="block text-sm">Nombre</label>
      <input name="name" class="w-full border rounded px-3 py-2" required>
    </div>
    <div>
      <label class="block text-sm">Email</label>
      <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
    </div>
    <div>
      <label class="block text-sm">Contraseña</label>
      <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
    </div>
    <div>
      <label class="block text-sm">Rol</label>
      <select name="role_id" class="border rounded px-3 py-2">
        @foreach($roles as $r)
          <option value="{{ $r->id }}">{{ $r->name }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm">Departamentos (solo agentes)</label>
      <select name="department_ids[]" class="border rounded px-3 py-2" multiple size="5">
        @foreach($departments as $d)
          <option value="{{ $d->id }}">{{ $d->name }}</option>
        @endforeach
      </select>
    </div>
    <button class="px-4 py-2 bg-gray-900 text-white rounded">Crear</button>
  </form>
@endsection
