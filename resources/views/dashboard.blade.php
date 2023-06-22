<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Главная страница') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-center gap-6">
                        <h1 class="text-center text-black text-xl text-bold">{{ __('Добро пожаловать на главную страницу!') }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
