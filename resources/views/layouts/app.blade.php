<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'TicketingPRO v2' }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ vite('resources/css/app.css') }}">
  <link rel="stylesheet" href="https://unpkg.com/filepond@4/dist/filepond.min.css">
  <link rel="stylesheet" href="https://unpkg.com/filepond-plugin-image-preview@4/dist/filepond-plugin-image-preview.min.css">
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">
  @include('partials._nav')

  <main class="max-w-7xl mx-auto px-4 py-6">
    @if(session('_flash.success'))
      <div class="mb-4 p-3 border border-green-200 bg-green-50 rounded text-green-800">
        {{ session('_flash.success') }}
      </div>
    @endif
    @if(session('_flash.error'))
      <div class="mb-4 p-3 border border-red-200 bg-red-50 rounded text-red-800">
        {{ session('_flash.error') }}
      </div>
    @endif
    @yield('content')
  </main>

  <script type="module" src="{{ vite('resources/js/app.js') }}"></script>
  <script src="https://unpkg.com/filepond@4/dist/filepond.min.js"></script>
  <script src="https://unpkg.com/filepond-plugin-image-preview@4/dist/filepond-plugin-image-preview.min.js"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script type="module" src="{{ vite('resources/js/app.js') }}"></script>
</body>
</html>
