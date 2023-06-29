<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Создать организацию') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div
                            class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                            role="alert">
                            <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor"
                                 viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                      d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <span class="sr-only">{{ __('Ошибка') }}</span>
                            <div>
                                <span
                                    class="font-medium ml-2">{{ __('Убедитесь, что вы придерживаетесь следующих условий:') }}</span>
                                <ul class="mt-1.5 ml-4 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ ($error) }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endempty
                    <form action="{{ route('organizations.store') }}" method="post">
                        @csrf

                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <div class="sm:col-span-2">
                                <label for="name" class="block mb-4 text-md font-medium text-gray-900 dark:text-black">Название
                                    организации</label>
                                <input type="text" name="name" id="name"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                       placeholder="Введите название организации">
                            </div>
                        </div>
                        <div class="mt-4 sm:col-span-2">
                            <label for="type" class="block mb-4 text-md font-medium text-gray-900 dark:text-black">Выберите
                                тип организации</label>
                            <select name="type" id="type"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">Выберите тип организации</option>
                                <option value="Министерство">Министерство</option>
                                <option value="Комитет">Комитет</option>
                                <option value="Управление">Управление</option>
                            </select>
                        </div>
                        <div class="mt-4 sm:col-span-2" id="committeeFields" style="display: none;">
                            <label for="followed_by" class="block mb-4 text-md font-medium text-gray-900 dark:text-black">Выберите министерство, которое вас курирует</label>
                            <select name="followed_by" id="followed_by" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">Выберите министерство, которое вас курирует</option>
                                @foreach($organizations as $organization)
                                    @if($organization->type === 'Министерство')
                                        <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4 sm:col-span-2" id="managementFields" style="display: none;">
                            <label for="followed_by_committee" class="mt-4 block mb-4 text-md font-medium text-gray-900 dark:text-black">Выберите комитет, который вас курирует</label>
                            <select name="followed_by_committee" id="followed_by_committee" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">Выберите комитет, который вас курирует</option>
                            </select>
                        </div>
                        <div class="mt-6">
                            <x-primary-button type="submit">
                                Добавить организацию
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const followedBySelect = document.getElementById('followed_by');
        const followedByCommitteeSelect = document.getElementById('followed_by_committee');

        followedBySelect.addEventListener('change', function() {
            const selectedFollowedBy = this.value;

            followedByCommitteeSelect.innerHTML = '<option value="">Выберите комитет, который вас курирует</option>';

            @foreach($organizations as $organization)
                @if($organization->type === 'Комитет' && $organization->followedBy)
                    if ('{{ $organization->followedBy->id }}' === selectedFollowedBy) {
                        const option = document.createElement('option');
                        option.value = '{{ $organization->id }}';
                        option.innerText = '{{ $organization->name }}';
                        followedByCommitteeSelect.appendChild(option);
                    }
                @endif
            @endforeach

            if (followedByCommitteeSelect.options.length > 1) {
                document.getElementById('managementFields').style.display = 'block';
            } else {
                document.getElementById('managementFields').style.display = 'none';
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let organizationTypeSelect = document.getElementById('type');

            organizationTypeSelect.addEventListener('change', function () {
                let selectedOption = organizationTypeSelect.value;

                let committeeFields = document.getElementById('committeeFields');
                let managementFields = document.getElementById('managementFields');

                if (selectedOption === 'Комитет') {
                    committeeFields.style.display = 'block';
                    managementFields.style.display = 'none';
                } else if (selectedOption === 'Управление') {
                    committeeFields.style.display = 'block';
                    managementFields.style.display = 'block';
                } else {
                    committeeFields.style.display = 'none';
                    managementFields.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>
