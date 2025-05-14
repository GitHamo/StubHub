<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/favicons/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicons/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('/favicons/site.webmanifest') }}">

        <title>{{ config('app.name', 'StubHub') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-logo class="w-20 h-20 fill-current text-gray-500" backgroundColor="#1f2937" />
                </a>
            </div>

            <div class="w-full sm:max-w-5xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">

                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">

                                <div class="mb-5">
                                    <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
                                        {{ __('Subscriptions') }}
                                    </h2>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full table-auto border border-gray-200">
                                        <thead>
                                            <tr class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
                                                <th class="p-4"></th>
                                                <th class="p-4 text-center">{{ __('Basic') }}</th>
                                                <th class="p-4 text-center">{{ __('Advanced') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm text-gray-800">
                                            <tr class="border-t">
                                                <td class="p-4 font-medium">{{ __('Endpoints') }}</td>
                                                <td class="p-4 text-center">5</td>
                                                <td class="p-4 text-center">100</td>
                                            </tr>
                                            <tr class="border-t bg-gray-50">
                                                <td class="p-4 font-medium">{{ __('Stub size') }}</td>
                                                <td class="p-4 text-center">2 Kbs</td>
                                                <td class="p-4 text-center">40 Kbs</td>
                                            </tr>
                                            <tr class="border-t">
                                                <td class="p-4 font-medium">{{ __('Field repeat') }}</td>
                                                <td class="p-4 text-center">10</td>
                                                <td class="p-4 text-center">50</td>
                                            </tr>
                                            <tr class="border-t">
                                                <td class="p-4 font-medium">{{ __('Nesting depth') }}</td>
                                                <td class="p-4 text-center">3</td>
                                                <td class="p-4 text-center">10</td>
                                            </tr>
                                            <tr class="border-t">
                                                <td class="p-4 font-medium">{{ __('Data retention') }}</td>
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
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </body>
</html>
