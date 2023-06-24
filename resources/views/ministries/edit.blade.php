<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Изменить министерство') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                            <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">{{ __('Ошибка') }}</span>
                            <div>
                                <span class="font-medium ml-2">{{ __('Убедитесь, что вы придерживаетесь следующих условий:') }}</span>
                                <ul class="mt-1.5 ml-4 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ ($error) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endempty
                    <form action="{{ route('ministries.update', $ministry->id) }}" method="post">
                        @csrf
                        @method('put')

                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <div class="sm:col-span-2">
                                <label for="ministry_name" class="block mb-4 text-md font-medium text-gray-900 dark:text-black">Введите новое название министерства</label>
                                <input type="text" name="ministry_name" id="ministry_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Введите новое название министерства">
                            </div>
                        </div>
                        <div class="sm:col-span-2 mt-3">
                            <label for="committee_id_add" class="block mb-4 text-md font-medium text-gray-900 dark:text-black">Выберите комитет, который вы хотите курировать</label>
                            <select name="committee_id_add" id="committee_id_add" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option>Выберите комитет, который вы хотите курировать</option>
                                @foreach($committees as $committee)
                                    @if(\Illuminate\Support\Facades\Auth::id() === $committee->user_id)
                                        @if($committee->ministry_id === null)
                                            <option value="{{ $committee->id }}">{{ $committee->committee_name }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2 mt-3">
                            <label for="committee_id_remove" class="block mb-4 text-md font-medium text-gray-900 dark:text-black">Выберите комитет, который вы хотите открепить</label>
                            <select name="committee_id_remove" id="committee_id_remove" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option>Выберите комитет, который вы хотите открепить</option>
                                @foreach($committees as $committee)
                                    @if(\Illuminate\Support\Facades\Auth::id() === $committee->user_id)
                                        @if($committee->ministry_id !== null)
                                            <option value="{{ $committee->id }}">{{ $committee->committee_name }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-6">
                            <x-primary-button type="submit">
                                Обновить министерство
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
