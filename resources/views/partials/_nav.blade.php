@php($u = current_user())
<nav class="bg-white border-b border-gray-200">
    <div class="flex items-center justify-between px-4 py-3 mx-auto max-w-7xl">
        <a href="/" class="font-semibold">TicketingPRO v2</a>
        <div class="flex items-center gap-4">
            <a href="/tickets" class="text-sm hover:underline">Tickets</a>
            @if ($u && $u->role?->name === 'Administrador')
                <div x-data="{ open: false }" class="relative">
                    <button class="text-sm" @click="open = !open">Administración ▾</button>

                    <div x-cloak x-show="open" @click.outside="open = false" x-transition
                        class="absolute left-0 z-50 p-2 bg-white border rounded shadow top-full">
                        <a class="block px-3 py-1 hover:bg-gray-50" href="/admin/departamentos">Departamentos</a>
                        <a class="block px-3 py-1 hover:bg-gray-50" href="/admin/categorias">Categorías</a>
                        <a class="block px-3 py-1 hover:bg-gray-50" href="/admin/users">Usuarios</a>
                    </div>
                </div>
            @endif
            @if ($u)
                <form action="/logout" method="post" class="inline">
                    <input type="hidden" name="_token" value="{{ csrf() }}">
                    <button class="px-3 py-1 text-sm text-white bg-gray-900 rounded">Salir</button>
                </form>
            @endif
        </div>
    </div>
</nav>
