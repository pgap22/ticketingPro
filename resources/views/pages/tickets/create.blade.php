@extends('layouts.app')
@section('content')
  <h1 class="text-xl font-semibold mb-4">Crear Ticket</h1>
  @if(session('_flash.error'))
    <div class="mb-3 p-2 bg-red-50 text-red-700 border border-red-200 rounded">{{ session('_flash.error') }}</div>
  @endif
  <form method="post" action="/tickets" class="bg-white border rounded p-4 space-y-3" x-data="ticketForm()">
    <input type="hidden" name="_token" value="{{ $csrf }}">
    <div>
      <label class="block text-sm">Título</label>
      <input name="title" class="w-full border rounded px-3 py-2" required>
    </div>
    <div>
      <label class="block text-sm">Descripción</label>
      <textarea name="description" class="w-full border rounded px-3 py-2" rows="5" required></textarea>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
        <label class="block text-sm">Departamento</label>
        <select name="department_id" x-model.number="department" class="border rounded px-3 py-2" required>
          <option value="">-- Seleccionar --</option>
          @foreach($departments as $d)
            <option value="{{ $d->id }}">{{ $d->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm">Categoría</label>
        <select name="category_id" class="border rounded px-3 py-2" required>
          <template x-for="c in categories" :key="c.id">
            <option :value="c.id" x-text="c.name"></option>
          </template>
        </select>
      </div>
    </div>
    <div>
      <label class="block text-sm">Prioridad</label>
      <select name="priority" class="border rounded px-3 py-2">
        <option value="baja">Baja</option>
        <option value="media" selected>Media</option>
        <option value="alta">Alta</option>
      </select>
    </div>
    <button class="px-4 py-2 bg-gray-900 text-white rounded">Crear</button>
  </form>

  <script>
    const db = {
      departments: @json($departments->map(fn($d)=>['id'=>$d->id,'name'=>$d->name,'categories'=>$d->categories->map(fn($c)=>['id'=>$c->id,'name'=>$c->name])])),
    };
    function ticketForm(){
      return {
        department: '',
        get categories(){
          const d = db.departments.find(x => x.id === this.department);
          return d ? d.categories : [];
        }
      }
    }
  </script>
@endsection
