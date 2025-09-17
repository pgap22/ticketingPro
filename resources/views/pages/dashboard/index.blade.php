@extends('layouts.app')
@section('content')
  <h1 class="text-xl font-semibold mb-4">Dashboard</h1>
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 border rounded shadow-sm">
      <div class="text-sm text-gray-500">Total</div>
      <div class="text-2xl font-bold">{{ $metrics['total'] }}</div>
    </div>
    <div class="bg-white p-4 border rounded shadow-sm">
      <div class="text-sm text-gray-500">Abiertos</div>
      <div class="text-2xl font-bold">{{ $metrics['abiertos'] }}</div>
    </div>
    <div class="bg-white p-4 border rounded shadow-sm">
      <div class="text-sm text-gray-500">En proceso</div>
      <div class="text-2xl font-bold">{{ $metrics['en_proceso'] }}</div>
    </div>
    <div class="bg-white p-4 border rounded shadow-sm">
      <div class="text-sm text-gray-500">Cerrados</div>
      <div class="text-2xl font-bold">{{ $metrics['cerrados'] }}</div>
    </div>
  </div>
  <div class="bg-white border rounded shadow-sm">
    <div class="p-4 border-b font-medium">Últimos tickets</div>
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="text-left px-4 py-2">ID</th>
          <th class="text-left px-4 py-2">Título</th>
          <th class="text-left px-4 py-2">Depto</th>
          <th class="text-left px-4 py-2">Categoría</th>
          <th class="text-left px-4 py-2">Estado</th>
          <th class="text-left px-4 py-2">Prioridad</th>
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
            <td class="px-4 py-2"><a href="/tickets/{{ $t->id }}" class="text-blue-600 hover:underline">Ver</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
