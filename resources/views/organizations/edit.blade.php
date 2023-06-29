<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Изменить организацию') }}
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
                    <form action="{{ route('organizations.update', $organization->id) }}" method="post">
                        @csrf
                        @method('patch')

                        @if($organization->type === 'Министерство')
                            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                                <div class="sm:col-span-2">
                                    <label for="name" class="block mb-4 text-md font-medium text-gray-900 dark:text-black">Новое название организации</label>
                                    <input type="hidden" name="id" value="{{ $organization->id }}">
                                    <input type="hidden" name="type" value="Министерство">
                                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Введите новое название организации">
                                </div>
                            </div>
                        @endif
                        @if($organization->type === 'Комитет')
                            <div class="sm:col-span-2">
                                <div class="sm:col-span-2">
                                    <label for="name" class="block mb-4 text-md font-medium text-gray-900 dark:text-black">Новое название организации</label>
                                    <input type="hidden" name="id" value="{{ $organization->id }}">
                                    <input type="hidden" name="type" value="Комитет">
                                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Введите новое название организации">
                                </div>
                                @if($organization->followed_by === null)
                                    <label for="followed_by_add" class="mt-4 block mb-4 text-md font-medium text-gray-900 dark:text-black">Выберите министерство, к которому вы хотите прикрепиться</label>
                                    <select name="followed_by_add" id="followed_by_add" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Выберите министерство, к которому вы хотите прикрепиться</option>
                                        @foreach($organizations as $org)
                                            @if($org->type === 'Министерство')
                                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @else
                                    <label for="followed_by_remove" class="mt-4 block mb-4 text-md font-medium text-gray-900 dark:text-black">Выберите министерство, которое вас курирует, и открепитесь от него</label>
                                    <select name="followed_by_remove" id="followed_by_remove" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Выберите министерство, которое вас курирует, и открепитесь от него</option>
                                        @foreach($organizations as $org)
                                            @if($org->type === 'Министерство' && $org->id === $organization->followedBy->id)
                                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        @endif
                        @if($organization->type === 'Управление')
                            <div class="sm:col-span-2">
                                <div class="sm:col-span-2">
                                    <label for="name" class="block mb-4 text-md font-medium text-gray-900 dark:text-black">Новое название организации</label>
                                    <input type="hidden" name="id" value="{{ $organization->id }}">
                                    <input type="hidden" name="type" value="Управление">
                                    <input type="text" name="name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Введите новое название организации">
                                </div>
                                @if($organization->followed_by_committee === null)
                                    <label for="followed_by" class="mt-4 block mb-4 text-md font-medium text-gray-900 dark:text-black">Сначала выберите министерство, чтобы затем выбрать комитет для прикрепления</label>
                                    <select name="followed_by" id="followed_by" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Сначала выберите министерство, чтобы затем выбрать комитет для прикрепления</option>
                                        @foreach($organizations as $org)
                                            @if($org->type === 'Министерство')
                                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <label style="display: none;" id="label_followed_by_committee_add" for="followed_by_committee_add" class="mt-4 block mb-4 text-md font-medium text-gray-900 dark:text-black">Выберите комитет, к которому вы хотите прикрепиться</label>
                                    <select style="display: none;" name="followed_by_committee_add" id="followed_by_committee_add" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Выберите комитет, к которому вы хотите прикрепиться</option>
                                    </select>
                                @else
                                    <label for="followed_by_committee_remove" class="mt-4 block mb-4 text-md font-medium text-gray-900 dark:text-black">Выберите комитет, который вас курирует, и открепитесь от него</label>
                                    <select name="followed_by_committee_remove" id="followed_by_committee_remove" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="">Выберите комитет, который вас курирует, и открепитесь от него</option>
                                        @foreach($organizations as $org)
                                            @if($org->type === 'Комитет' && $org->id === $organization->followedByCommittee->id)
                                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        @endif
                        <div class="mt-6">
                            <x-primary-button type="submit">
                                Изменить организацию
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        const followedBySelect = document.getElementById('followed_by');
        const followedByCommitteeSelectAdd = document.getElementById('followed_by_committee_add');

        followedBySelect.addEventListener('change', function() {
            const selectedFollowedBy = this.value;

            followedByCommitteeSelectAdd.innerHTML = '<option value="">Выберите комитет, к которому вы хотите прикрепиться</option>';

            @foreach($organizations as $organization)
                @if($organization->type === 'Комитет' && $organization->followedBy)
                    if ('{{ $organization->followedBy->id }}' === selectedFollowedBy) {
                        const option = document.createElement('option');
                        option.value = '{{ $organization->id }}';
                        option.innerText = '{{ $organization->name }}';
                        followedByCommitteeSelectAdd.appendChild(option);
                    }
                @endif
            @endforeach

            if (followedByCommitteeSelectAdd.options.length > 1) {
                document.getElementById('followed_by_committee_add').style.display = 'block';
                document.getElementById('label_followed_by_committee_add').style.display = 'block';
            } else {
                document.getElementById('followed_by_committee_add').style.display = 'none';
                document.getElementById('label_followed_by_committee_add').style.display = 'none';
            }
        });
    </script>
</x-app-layout>
