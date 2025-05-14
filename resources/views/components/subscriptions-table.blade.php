
<div class="overflow-x-auto">
    <table class="min-w-full table-auto border border-gray-200 dark:border-zinc-600">
        <thead>
            <tr class="bg-gray-100 dark:bg-zinc-700 text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                <th class="p-4"></th>
                <th class="p-4 text-center">{{ __('Basic') }}</th>
                <th class="p-4 text-center">{{ __('Advanced') }}</th>
            </tr>
        </thead>
        <tbody class="text-sm text-gray-800 dark:text-gray-500">
            <tr class="border-t">
                <td class="p-4 font-medium">{{ __('Maximum Endpoints') }}</td>
                <td class="p-4 text-center">5</td>
                <td class="p-4 text-center">100</td>
            </tr>
            <tr class="border-t">
                <td class="p-4 font-medium">{{ __('Stub size') }}</td>
                <td class="p-4 text-center">2 Kb</td>
                <td class="p-4 text-center">40 Kb</td>
            </tr>
            <tr class="border-t">
                <td class="p-4 font-medium">{{ __('Maximum Repeat') }}</td>
                <td class="p-4 text-center">10</td>
                <td class="p-4 text-center">50</td>
            </tr>
            <tr class="border-t">
                <td class="p-4 font-medium">{{ __('Data Retention') }}</td>
                <td class="p-4 text-center">365 {{ __('days') }}</td>
                <td class="p-4 text-center">1825 {{ __('days') }}</td>
            </tr>
            <tr class="border-t">
                <td class="p-4 font-medium"></td>
                <td class="p-4 text-center">
                    <a href="{{ route('register') }}" class="bg-gradient-to-t from-green-500 to-green-700 text-white text-xs py-1 px-3 uppercase font-semibold rounded border-2 border-transparent hover:border-green-800">Subscribe</a>
                </td>
                <td class="p-4 text-center">
                    <a href="https://github.com/GitHamo/StubHub" target="_blank" class="bg-gradient-to-t from-green-500 to-green-700 text-white text-xs py-1 px-3 uppercase font-semibold rounded border-2 border-transparent hover:border-green-800">Subscribe</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
