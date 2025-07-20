@props(['item'])

<tr>
  <td class="px-4 py-3 text-left font-medium">{{ $item->name()}}</td>
  <td class="px-4 py-3 text-center">{{ $item->uniqueHits() }}</td>
  <td class="px-4 py-3 text-center">{{ $item->totalHits() }}</td>
  <td class="px-4 py-3 text-center text-gray-500">
    <div class="flex flex-col leading-tight">
      <span>{{ $item->createdAt()->format('M d, Y') }}</span>
      <span class="text-xs text-gray-400">{{ $item->createdAt()->format('H:i') }}</span>
    </div>
  </td>
  <td class="px-4 py-3 text-right">
    <div class="inline-flex gap-2 justify-end">
      <!-- Download Button -->
      <a
        href="{{ route('endpoints.download', $item->id()) }}"
        target="_blank"
        class="inline-flex items-center px-3 py-1 text-xs font-semibold uppercase text-white bg-gradient-to-t from-yellow-700 to-yellow-500 border border-yellow-600 hover:border-yellow-800 rounded"
      >
        Download
        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
          <path d="M14 3h7v7h-2V6.414l-9.293 9.293-1.414-1.414L17.586 5H14V3z"/>
          <path d="M5 5h7V3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7h-2v7H5V5z"/>
        </svg>
      </a>
      <!-- Regenerate Button -->
      <button
        onclick="document.getElementById('regenerate-form-{{ $item->id() }}').submit();"
        class="inline-flex items-center px-3 py-1 text-xs font-semibold uppercase text-white bg-gradient-to-t from-blue-600 to-blue-400 border border-blue-500 hover:border-blue-700 rounded"
      >
        Regenerate
      </button>
      <form id="regenerate-form-{{ $item->id() }}" action="{{ route('endpoints.regenerate', $item->id()) }}" method="POST" class="hidden">
        @csrf
      </form>
      <!-- View Button -->
      <a
        href="{{ route('traffic.serve', $item->id()) }}"
        target="_blank"
        class="inline-flex items-center px-3 py-1 text-xs font-semibold uppercase text-white bg-gradient-to-t from-blue-600 to-blue-400 border border-blue-500 hover:border-blue-700 rounded"
      >
        View
        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
          <path d="M14 3h7v7h-2V6.414l-9.293 9.293-1.414-1.414L17.586 5H14V3z"/>
          <path d="M5 5h7V3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7h-2v7H5V5z"/>
        </svg>
      </a>

      <!-- Delete Button -->
      <button
        onclick="if(confirm('Are you sure you want to delete this item?')) { document.getElementById('delete-form-{{ $item->id() }}').submit(); }"
        class="px-3 py-1 text-xs font-semibold uppercase text-white bg-gradient-to-t from-red-600 to-red-400 border border-red-500 hover:border-red-700 rounded"
      >
        Delete
      </button>

      <form id="delete-form-{{ $item->id() }}" action="{{ route('endpoints.destroy', $item->id()) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
      </form>
    </div>
  </td>
</tr>
