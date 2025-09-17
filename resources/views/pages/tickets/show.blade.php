@extends('layouts.app')
@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-semibold">Ticket #{{ $ticket->id }}</h1>
    <div class="flex gap-2">
      @if(in_array($role, ['Administrador','Agente']))
        <a href="/tickets/{{ $ticket->id }}/editar" class="px-3 py-2 bg-gray-700 text-white rounded">Editar</a>
      @endif
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="md:col-span-2 space-y-4">
      <div class="bg-white border rounded p-4">
        <div class="text-sm text-gray-500">Título</div>
        <div class="font-medium">{{ $ticket->title }}</div>
        <div class="mt-2 text-sm text-gray-500">Descripción</div>
        <div>{{ $ticket->description }}</div>
        <div class="mt-4 text-sm">
          <span class="px-2 py-1 bg-gray-100 rounded">Depto: {{ $ticket->department?->name }}</span>
          <span class="px-2 py-1 bg-gray-100 rounded">Categoría: {{ $ticket->category?->name }}</span>
          <span class="px-2 py-1 bg-gray-100 rounded">Estado: {{ $ticket->status }}</span>
          <span class="px-2 py-1 bg-gray-100 rounded">Prioridad: {{ $ticket->priority }}</span>
        </div>
      </div>

      <div class="bg-white border rounded p-4">
        <h2 class="font-medium mb-3">Comentarios</h2>
        <div class="space-y-3">
          @forelse($ticket->comments as $c)
            <div class="border rounded p-3">
              <div class="text-sm text-gray-500">{{ $c->user?->name }} — {{ $c->created_at }}</div>
              <div class="mb-3">{{ $c->body }}</div>
              @if($c->attachments && $c->attachments->count())
                <div class="flex flex-wrap gap-3">
                  @foreach($c->attachments as $a)
                    @php $url = '/storage/' . $a->file_path; @endphp
                    @if(str_starts_with($a->mime_type, 'image/'))
                      <a href="{{ $url }}" target="_blank" class="block">
                        <img src="{{ $url }}" alt="{{ $a->original_name }}" class="h-24 w-24 object-cover rounded border">
                      </a>
                    @else
                      <a href="{{ $url }}" target="_blank" class="text-blue-600 hover:underline">{{ $a->original_name }}</a>
                    @endif
                  @endforeach
                </div>
              @endif
            </div>
          @empty
            <div class="text-sm text-gray-500">Sin comentarios</div>
          @endforelse
        </div>
        <form method="post" action="/tickets/{{ $ticket->id }}/comentarios" class="mt-4" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="{{ $csrf }}">
          <textarea name="body" class="w-full border rounded px-3 py-2" rows="3" placeholder="Escribe un comentario..."></textarea>
          <input type="file" name="attachments[]" multiple class="filepond mt-2" />
          <button class="mt-2 px-3 py-2 bg-gray-900 text-white rounded">Agregar comentario</button>
        </form>
      </div>
    </div>

    <div class="space-y-4">
      <div class="bg-white border rounded p-4">
        <div class="text-sm text-gray-500">Cliente</div>
        <div>{{ $ticket->user?->name }}</div>
        <div class="text-sm text-gray-500 mt-2">Agente</div>
        <div>{{ $ticket->agent?->name ?? '-' }}</div>
      </div>

      @if(in_array($role, ['Administrador','Agente']))
      <div class="bg-white border rounded p-4">
        <h3 class="font-medium mb-2">Acciones</h3>
        <form method="post" action="/tickets/{{ $ticket->id }}/asignar" class="space-y-2">
          <input type="hidden" name="_token" value="{{ $csrf }}">
          <label class="block text-sm">Asignar a agente</label>
          <select name="agent_id" class="border rounded px-2 py-1">
            <option value="">-- Seleccionar --</option>
            @foreach($agents as $a)
              <option value="{{ $a->id }}" @if($ticket->agent_id===$a->id) selected @endif>{{ $a->name }}</option>
            @endforeach
          </select>
          <button class="px-3 py-1 bg-gray-900 text-white rounded">Asignar</button>
        </form>
        <form method="post" action="/tickets/{{ $ticket->id }}/cerrar" class="mt-2">
          <input type="hidden" name="_token" value="{{ $csrf }}">
          <button class="px-3 py-1 bg-green-700 text-white rounded">Cerrar ticket</button>
        </form>
      </div>
      @endif
    </div>
  </div>
@endsection
