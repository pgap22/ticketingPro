@extends('layouts.app')
@section('content')
  <h1 class="mb-4 text-xl font-semibold">Crear Ticket</h1>
  @if(session('_flash.error'))
    <div class="p-2 mb-3 text-red-700 border border-red-200 rounded bg-red-50">{{ session('_flash.error') }}</div>
  @endif
  <form method="post" action="/tickets" class="p-4 space-y-3 bg-white border rounded" x-data="ticketForm()">
    <input type="hidden" name="_token" value="{{ $csrf }}">
    <div>
      <label class="block text-sm">Título</label>
      <input name="title" class="w-full px-3 py-2 border rounded" required>
    </div>
    <div>
      <label class="block text-sm">Descripción</label>
      <textarea name="description" class="w-full px-3 py-2 border rounded" rows="5" required></textarea>
    </div>
    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
      <div>
        <label class="block text-sm">Departamento</label>
        <select name="department_id" x-model.number="department" class="px-3 py-2 border rounded" required>
          <option value="">-- Seleccionar --</option>
          @foreach($departments as $d)
            <option value="{{ $d->id }}">{{ $d->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm">Categoría</label>
        <select name="category_id" class="px-3 py-2 border rounded" required>
          <template x-for="c in categories" :key="c.id">
            <option :value="c.id" x-text="c.name"></option>
          </template>
        </select>
      </div>
    </div>
    <div>
      <label class="block text-sm">Prioridad</label>
      <select name="priority" class="px-3 py-2 border rounded">
        <option value="baja">Baja</option>
        <option value="media" selected>Media</option>
        <option value="alta">Alta</option>
      </select>
    </div>
    <button class="px-4 py-2 text-white bg-gray-900 rounded">Crear</button>
  </form>

  <script>
    const db = {
      departments: {!! json_encode($departments->map(fn($d)=>['id'=>$d->id,'name'=>$d->name,'categories'=>$d->categories->map(fn($c)=>['id'=>$c->id,'name'=>$c->name])])) !!},
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
