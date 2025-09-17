@extends('layouts.app')
@section('content')
  <h1 class="mb-4 text-xl font-semibold">Editar Ticket #{{ $ticket->id }}</h1>
  <form method="post" action="/tickets/{{ $ticket->id }}" class="p-4 space-y-3 bg-white border rounded" x-data="ticketForm()">
    <input type="hidden" name="_token" value="{{ $csrf }}">
    <div>
      <label class="block text-sm">Título</label>
      <input name="title" class="w-full px-3 py-2 border rounded" value="{{ $ticket->title }}" required>
    </div>
    <div>
      <label class="block text-sm">Descripción</label>
      <textarea name="description" class="w-full px-3 py-2 border rounded" rows="5" required>{{ $ticket->description }}</textarea>
    </div>
    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
      <div>
        <label class="block text-sm">Departamento</label>
        <select name="department_id" x-model.number="department" class="px-3 py-2 border rounded" required>
          @foreach($departments as $d)
            <option value="{{ $d->id }}" @if($ticket->department_id===$d->id) selected @endif>{{ $d->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm">Categoría</label>
        <select name="category_id" class="px-3 py-2 border rounded" required>
          <template x-for="c in categories" :key="c.id">
            <option :value="c.id" :selected="c.id==={{ $ticket->category_id }}}" x-text="c.name"></option>
          </template>
        </select>
      </div>
    </div>
    <div>
      <label class="block text-sm">Prioridad</label>
      <select name="priority" class="px-3 py-2 border rounded">
        <option value="baja" @if($ticket->priority==='baja') selected @endif>Baja</option>
        <option value="media" @if($ticket->priority==='media') selected @endif>Media</option>
        <option value="alta" @if($ticket->priority==='alta') selected @endif>Alta</option>
      </select>
    </div>
    <div>
      <label class="block text-sm">Estado</label>
      <select name="status" class="px-3 py-2 border rounded">
        <option value="abierto" @if($ticket->status==='abierto') selected @endif>Abierto</option>
        <option value="en_proceso" @if($ticket->status==='en_proceso') selected @endif>En proceso</option>
        <option value="cerrado" @if($ticket->status==='cerrado') selected @endif>Cerrado</option>
      </select>
    </div>
    <button class="px-4 py-2 text-white bg-gray-900 rounded">Guardar</button>
  </form>
  
  <script>
    const db = {
      departments: {!! json_encode($departments->map(fn($d)=>['id'=>$d->id,'name'=>$d->name,'categories'=>$d->categories->map(fn($c)=>['id'=>$c->id,'name'=>$c->name])])->toArray()) !!},
    };
    function ticketForm(){
      return {
        department: {{ $ticket->department_id }},
        get categories(){
          const d = db.departments.find(x => x.id === this.department);
          return d ? d.categories : [];
        }
      }
    }
  </script>
@endsection
