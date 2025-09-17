@extends('layouts.app')
@section('content')
  <div class="max-w-md mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Iniciar sesión</h1>
    @if(!empty($error))
      <div class="mb-4 p-3 border border-red-200 bg-red-50 rounded text-red-800">
        {{ $error }}
      </div>
    @endif
    <form method="post" action="/login" class="space-y-3 bg-white p-4 rounded shadow-sm border">
      <input type="hidden" name="_token" value="{{ $csrf }}">
      <div>
        <label class="block text-sm">Email</label>
        <input name="email" type="email" required class="w-full border rounded px-3 py-2">
      </div>
      <div>
        <label class="block text-sm">Contraseña</label>
        <input name="password" type="password" required class="w-full border rounded px-3 py-2">
      </div>
      <button class="px-4 py-2 rounded bg-gray-900 text-white">Entrar</button>
    </form>
  </div>
@endsection
