<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stunden Eintragen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <h2 class="pb-4">Monatsstunden für</h2>
                        <select name="filter-year" id="filter-year"
                                class="py-1 border border-gray-300 rounded ml-5 mb-3">
                            @php
                                $selectedYear = date('Y');
                            @endphp
                            @for($i = $selectedYear; $i >= 2021; $i--)
                                <option
                                    value="{{ $i }}" {{ $i == $selectedYear ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="pb-4">
                        <div class="w-full mx-auto">
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <div id="table-container">
                                    @component('components._table', ['users' => $users, 'workHours' => $workHours, 'year' => $selectedYear])
                                    @endcomponent
                                </div>
                            </div>
                        </div>
                        <h2 class="mt-4 underline">Ändern oder Eintragen:</h2>
                        <form action="{{ route('update.hours') }}" method="POST" class="my-4">
                            @csrf

                            <div class="flex items-center">
                                <div class="mr-2">
                                    <label for="user_id">User:</label>
                                    <select name="user_id" id="user_id"
                                            class="text-left py-1 border border-gray-300 rounded">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mr-2">
                                    <label for="year">Jahr:</label>
                                    <select name="year" id="year"
                                            class="py-1 border border-gray-300 rounded">
                                        @php
                                            $currentYear = date('Y');
                                        @endphp
                                        @for($i = $currentYear; $i >= 2021; $i--)
                                            <option
                                                value="{{ $i }}" {{ $i == $currentYear ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="mr-2">
                                    <label for="month">Monat:</label>
                                    <select name="month" id="month" class="px-2 py-1 border border-gray-300 rounded">
                                        <option value="1">Jänner</option>
                                        <option value="2">Februar</option>
                                        <option value="3">März</option>
                                        <option value="4">April</option>
                                        <option value="5">Mai</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Dezember</option>
                                    </select>
                                </div>

                                <div class="mr-2">
                                    <label for="hours">Stunden:</label>
                                    <input type="text" name="hours" id="hours" placeholder="HH:MM?"
                                           class="py-1 px-2 w-24 border border-gray-300 rounded" required>
                                </div>

                                <button type="submit"
                                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#filter-year').on('change', function () {
                var selectedYear = $(this).val();
                updateTable(selectedYear);
            });

            function updateTable(year) {
                $.ajax({
                    url: "{{ route('update.table') }}",
                    method: 'GET',
                    data: {
                        year: year
                    },
                    success: function (response) {
                        $('#table-container').html(response);
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    }
                });
            }
        });
    </script>
</x-app-layout>
