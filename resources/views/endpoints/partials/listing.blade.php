<div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
  <table class="min-w-full text-sm text-gray-700">
    <thead class="bg-gray-100 text-xs uppercase text-gray-500">
      <tr>
        <th class="px-4 py-3 text-left">Name</th>
        <th class="px-4 py-3 text-center">Visitors</th>
        <th class="px-4 py-3 text-center">Views</th>
        <th class="px-4 py-3 text-center">Created At</th>
        <th class="px-4 py-3">
          <span class="sr-only">Actions</span>
        </th>
      </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-100">
      @foreach ($items as $item)
        @include('endpoints.partials.listing-item', ['item' => $item])
      @endforeach
    </tbody>
  </table>
</div>
