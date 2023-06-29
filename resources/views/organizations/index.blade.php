<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Организации') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-black">
                    <div class="flex justify-center gap-6">
                        <x-primary-button>
                            <a href="{{ route('organizations.create') }}">{{ __('Создать организацию') }}</a>
                        </x-primary-button>
                    </div>
                    <div class="w-full mt-4">
                        <form action="{{ route('organizations.search') }}" method="get">
                            <div class="flex justify-center">
                                <input name="search" type="search" id="search"
                                       class="block w-full p-4 pl-10 text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Найдите организацию по названию">
                                <select name="filter" id="filter" class="ml-2 py-6 border border-gray-300 rounded-lg">
                                    <option value="">Все</option>
                                    <option value="Министерство">Министерства</option>
                                    <option value="Комитет">Комитеты</option>
                                    <option value="Управление">Управления</option>
                                </select>
                                <x-primary-button type="submit" class="ml-2 py-4">
                                    <a href="{{ route('organizations.index') }}" class="p-2 bg-gray-300 text-white rounded-lg">Сбросить</a>
                                </x-primary-button>
                                <x-primary-button type="submit" class="ml-2 py-4">Найти</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="p-6 text-black">
                    <div class="relative py-6 overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-text-black dark:text-black">
                            <thead
                                class="text-xs text-text-black uppercase bg-gray-50 dark:bg-gray-700 dark:text-black">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Название организации
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Тип организации
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Курирующая организация
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Действия
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($organizations as $organization)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-black whitespace-nowrap dark:text-black">
                                        {{ $organization->name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $organization->type }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($organization->type === 'Управление')
                                            {{ $organization->followedByCommittee ? $organization->followedByCommittee->name : 'Отсутствует' }}
                                        @else
                                            {{ $organization->followedBy ? $organization->followedBy->name : 'Отсутствует' }}
                                        @endif
                                    </td>
                                    @if(\Illuminate\Support\Facades\Auth::id() === $organization->created_by)
                                        <td class="px-6 py-4">
                                            <x-primary-button>
                                                <a href="{{ route('organizations.show', $organization->id) }}"
                                                   class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Просмотреть</a>
                                            </x-primary-button>
                                            <x-primary-button class="mt-1">
                                                <a href="{{ route('organizations.edit', $organization->id) }}"
                                                   class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Изменить</a>
                                            </x-primary-button>
                                            <form class="mt-1"
                                                  action="{{ route('organizations.destroy', $organization->id) }}"
                                                  method="post">
                                                @csrf
                                                @method('delete')
                                                <x-primary-button type="submit">Удалить</x-primary-button>
                                            </form>
                                        </td>
                                    @else
                                        <td class="px-6 py-4">
                                            <h1>У вас нет доступа к данной организации.</h1>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
