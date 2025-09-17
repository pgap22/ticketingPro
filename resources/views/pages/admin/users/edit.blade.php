@extends('layouts.app')
@section('content')
  <h1 class="text-xl font-semibold mb-4">Editar usuario</h1>
  <form method="post" action="/admin/users/{{ $user->id }}" class="bg-white border rounded p-4 space-y-3">
    <input type="hidden" name="_token" value="{{ $csrf }}">
    <div>
      <label class="block text-sm">Nombre</label>
      <input name="name" class="w-full border rounded px-3 py-2" value="{{ $user->name }}" required>
    </div>
    <div>
      <label class="block text-sm">Email</label>
      <input type="email" name="email" class="w-full border rounded px-3 py-2" value="{{ $user->email }}" required>
    </div>
    <div>
      <label class="block text-sm">Rol</label>
      <select name="role_id" class="border rounded px-3 py-2">
        @foreach($roles as $r)
          <option value="{{ $r->id }}" @if($user->role_id===$r->id) selected @endif>{{ $r->name }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm">Nueva contraseña (opcional)</label>
      <input type="password" name="password" class="w-full border rounded px-3 py-2">
    </div>
    <div>
      <label class="block text-sm">Departamentos</label>
      <select name="department_ids[]" class="border rounded px-3 py-2" multiple size="5">
        @foreach($departments as $d)
          <option value="{{ $d->id }}" @if($user->departments->pluck('id')->contains($d->id)) selected @endif>{{ $d->name }}</option>
        @endforeach
      </select>
    </div>
    <button class="px-4 py-2 bg-gray-900 text-white rounded">Guardar</button>
  </form>
@endsection
