@if (empty($endpoints))
  <div class="flex flex-col items-center justify-center h-64 text-center">
    <p class="text-xl font-semibold text-gray-700">You don't have any endpoints yet.</p>
    <a href="{{ route('endpoints.create') }}" class="bg-gradient-to-t from-green-500 to-green-700 text-white text-lg py-2 px-6 uppercase font-semibold rounded mt-4 border-2 border-transparent hover:border-green-800">
      Create New
    </a>
  </div>
@else
  <div class="mb-5 flex items-center justify-between">
    <!-- Left side: Headline -->
    <h1 class="text-xl font-semibold">Endpoints</h1>

    <!-- Right side: Button and Stats -->
    <div class="flex items-center space-x-4">
      <!-- Stats -->
      <span class="text-gray-500 text-sm">({{ count($endpoints) }} of 20)</span>
      <!-- Button -->
      <a href="{{ route('endpoints.create') }}" class="bg-gradient-to-t from-green-500 to-green-700 text-white text-xs py-1 px-3 uppercase font-semibold rounded border-2 border-transparent hover:border-green-800">
          Create
      </a>
    </div>
  </div>
  <div>
    @include('endpoints.partials.listing', ['items' => $endpoints])
  </div>
@endif