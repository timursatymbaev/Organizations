<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Просмотр организации') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-black">
                    <h1 class="flex justify-center">Название организации: {{ $organization->name }}</h1>
                    <h1 class="flex justify-center">Тип организации: {{ $organization->type }}</h1>
                    <h1 class="flex justify-center">Когда была создана организация: {{ $organization->created_at }}</h1>
                    <h1 class="flex justify-center">Когда была обновлена организация: {{ $organization->updated_at }}</h1>
                    @if($organization->type === 'Управление' && $organization->followedByCommittee)
                        <h1 class="flex justify-center">Курируются организацией: {{ $organization->followedByCommittee->name ?? 'отсутствуют' }}</h1>
                    @else
                        <h1 class="flex justify-center">Курируются организацией: {{ $organization->followedBy->name ?? 'отсутствуют' }}</h1>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
