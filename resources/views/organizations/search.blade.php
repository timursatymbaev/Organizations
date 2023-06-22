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
                    <div class="w-full mt-4">
                        <form method="get">
                            <div class="flex justify-center">
                                <input name="search" type="search" id="search"
                                       class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Найдите организацию по названию">
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
                                    Категория организации
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
                            @foreach($ministries as $ministry)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-black whitespace-nowrap dark:text-black">
                                        {{ $ministry->ministry_name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        Министерство
                                    </td>
                                    <td class="px-6 py-4">
                                        Отсутствует
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-primary-button>
                                            <a href="{{ route('ministries.show', $ministry->id) }}"
                                               class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Просмотреть</a>
                                        </x-primary-button>
                                        <x-primary-button class="mt-1">
                                            <a href="{{ route('ministries.edit', $ministry->id) }}"
                                               class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Изменить</a>
                                        </x-primary-button>
                                        <form class="mt-1" action="{{ route('ministries.destroy', $ministry->id) }}"
                                              method="post">
                                            @csrf
                                            @method('delete')
                                            <x-primary-button type="submit">Удалить</x-primary-button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @foreach($committees as $committee)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-black whitespace-nowrap dark:text-black">
                                        {{ $committee->committee_name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        Комитет
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $committee->ministry->ministry_name ?? 'Отсутствует' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-primary-button>
                                            <a href="{{ route('committees.show', $committee->id) }}"
                                               class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Просмотреть</a>
                                        </x-primary-button>
                                        <x-primary-button class="mt-1">
                                            <a href="{{ route('committees.edit', $committee->id) }}"
                                               class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Изменить</a>
                                        </x-primary-button>
                                        <form class="mt-1" action="{{ route('committees.destroy', $committee->id) }}"
                                              method="post">
                                            @csrf
                                            @method('delete')
                                            <x-primary-button type="submit">Удалить</x-primary-button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @foreach($managements as $management)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-black whitespace-nowrap dark:text-black">
                                        {{ $management->management_name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        Управление
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $management->committee->committee_name ?? 'Отсутствует' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-primary-button>
                                            <a href="{{ route('managements.show', $management->id) }}"
                                               class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Просмотреть</a>
                                        </x-primary-button>
                                        <x-primary-button class="mt-1">
                                            <a href="{{ route('managements.edit', $management->id) }}"
                                               class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Изменить</a>
                                        </x-primary-button>
                                        <form class="mt-1" action="{{ route('managements.destroy', $management->id) }}"
                                              method="post">
                                            @csrf
                                            @method('delete')
                                            <x-primary-button type="submit">Удалить</x-primary-button>
                                        </form>
                                    </td>
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
