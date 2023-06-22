<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Просмотр министерства') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-center gap-6">
                        <h1>Название организации: {{ $ministry->ministry_name }}</h1>
                    </div>
                    <div class="flex justify-center gap-6">
                        <h1>Дата создания организации: {{ $ministry->created_at }}</h1>
                    </div>
                    <div class="flex justify-center gap-6">
                        @php
                            $committeeNames = $ministry->committee->pluck('committee_name')->implode(', ');
                        @endphp
                        <h1>Курирующиеся организации: {{ $committeeNames === '' ? 'отсутствуют' : $committeeNames }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
