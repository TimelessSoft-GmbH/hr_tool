<script>
    function hideButton() {
        var element = document.getElementById("buttonSub");
        element.classList.toggle("hidden");
    }

    function hideButtonSick() {
        var element = document.getElementById("buttonSubSick");
        element.classList.toggle("hidden");
    }
</script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Anfrageverwaltung') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="pb-4">Übersicht</h2>
                    <div>
                        @include('components.Calendar', [
                                                'users' => $users,
                                                'vacationRequests' => $vacationRequests,
                                                'sicknessRequests' => $sicknessRequests,
                                                'holiday_dates' => $holiday_dates,
                                                'selectedYear' => request()->query('selectedYear'),
                                                'selectedMonth' => request()->query('selectedMonth'),
                                            ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pb-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="mb-4">Urlaub nachtragen</h2>
                    <div id="newVacReq" class="flex flex-col sm:flex-row justify-between items-center pt-5 pl-8">
                        <form method="POST" action="{{ route('dashboard-vacation-admin') }}">
                            @csrf
                            <div class="flex flex-col sm:flex-row items-center mb-2">
                                <label for="user_id"
                                       class="mr-2 text-sm font-medium text-gray-900">Angestellter:</label>
                                <select name="user_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block py-2.5 px-4">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <label for="start_date" class="ml-4 mr-2 text-sm font-medium text-gray-900">Start
                                    Datum:</label>
                                <input
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 start_date"
                                    type="date" name="start_date" id="start_date" required>
                                <label for="end_date" class="ml-4 mr-2 text-sm font-medium text-gray-900">End
                                    Datum:</label>
                                <input
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 end_date"
                                    type="date" name="end_date" id="end_date" required>

                                <button type="submit"
                                        class="ml-10 mt-2 px-4 py-2.5 mb-1.5 bg-green-600 text-white rounded-lg text-sm font-medium">
                                    Eintragen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2>Urlaub-Anträge</h2>
                    <!--Table for Vacation-Requests-->
                    <div class="pb-4 pt-4">
                        <div class="mr-4 ml-4">
                            <div class="relative mb-4 w-3/5 flex">
                                <div class="w-1/2 mr-2">
                                    <div class="relative">
                                        <select id="userFilterVacation"
                                                class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Users</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-1/2 mr-2">
                                    <div class="relative">
                                        <select id="yearFilterVacation"
                                                class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                            @php
                                                $currentYear = date('Y');
                                                $startYear = $currentYear - 9;
                                            @endphp
                                            <option value="">Years</option>
                                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="none"
                                                 stroke="currentColor"
                                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <!--<button type="submit"
                                        onclick="applyFilter('vacationTable', 'userFilterVacation', 'yearFilterVacation')"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 ml-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Search
                                </button>-->
                            </div>

                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table id="vacationTable" class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-center">Name</th>
                                        <th scope="col" class="px-6 py-3 text-center">Jahr</th>
                                        <th scope="col" class="px-6 py-3 text-center">Start</th>
                                        <th scope="col" class="px-6 py-3 text-center">Ende</th>
                                        <th scope="col" class="px-6 py-3 text-center">Dauer</th>
                                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                                        <th scope="col" class="px-6 py-3 text-center">Antwort</th>
                                        <th scope="col" class="px-6 py-3 text-center">Löschen</th>
                                        <th scope="col" class="py-3 text-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($vacationRequests->sortByDesc('start_date') as $vacationRequest)
                                        <tr class="bg-gray-100">
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">{{ \App\Models\User::find($vacationRequest->user_id)->name }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">{{ $vacationRequest->start_date->format('Y') }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">
                                                <div id="start_date_{{ $vacationRequest->id }}"
                                                     data-start="{{ $vacationRequest->start_date->format('Y-m-d') }}"
                                                     data-end="{{ $vacationRequest->end_date->format('Y-m-d') }}">
                                                    {{ $vacationRequest->start_date->format('d M') }}
                                                </div>
                                            </td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">
                                                <div id="end_date_{{ $vacationRequest->id }}"
                                                     data-start="{{ $vacationRequest->start_date->format('Y-m-d') }}"
                                                     data-end="{{ $vacationRequest->end_date->format('Y-m-d') }}">
                                                    {{ $vacationRequest->end_date->format('d M') }}
                                                </div>
                                            </td>

                                            <td class="py-4 px-6 text-sm text-gray-700 text-center font-bold">{{ $vacationRequest->total_days }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">
                                                <div @class([
                                                                'text-green-500' =>  $vacationRequest->accepted === 'accepted',
                                                                'text-yellow-600' =>  $vacationRequest->accepted === 'pending',
                                                                'text-red-500' =>  $vacationRequest->accepted === 'declined',
                                                    ])>
                                                    {{ $vacationRequest->accepted }}
                                                </div>
                                            </td>
                                            <td class="py-4 px-6 text-sm text-gray-700">

                                                @if($vacationRequest->accepted === 'pending')
                                                    <form method="POST"
                                                          action="{{ route('update.vacationAnswer', ['id' => $vacationRequest->id]) }}">
                                                        @csrf
                                                        @method('POST')
                                                        <select
                                                            onchange="hideButton()"
                                                            name="antwort"
                                                            required
                                                            class="border-red-500 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-center"
                                                        >
                                                            <option disabled selected value> -- select an option --
                                                            </option>
                                                            <option value="accepted">accepted</option>
                                                            <option value="declined">declined</option>
                                                        </select>

                                                        <button
                                                            type="submit"
                                                            id="buttonSub"
                                                            class="hidden ml-10 text-white rounded-lg text-sm mt-2 px-8 py-3 bg-green-600 font-medium rounded-lg text-sm px-4 py-2"
                                                        >
                                                            Submit
                                                        </button>
                                                    </form>

                                                @else
                                                    <p class="text-gray-400 italic text-center">Bereits bearbeitet</p>
                                                @endif
                                            </td>
                                            <td class="px-6 text-sm text-gray-700  text-center">
                                                <form method="POST"
                                                      action="{{ route('delete.vacationRequest', $vacationRequest->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="font-bold text-red-500 font-medium rounded-lg text-sm w-full sm:w-auto pt-3 text-center"
                                                        type="submit"
                                                        onclick="return confirm('Bist du sicher, dass du das löschen wirst?')"
                                                    >
                                                        Löschen
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="pr-4 text-sm text-gray-700 text-center">
                                                <div
                                                    class="flex items-center justify-center h-full fill-blue-500 cursor-pointer">
                                                    <svg id="edit_icon_{{ $vacationRequest->id }}"
                                                         xmlns="http://www.w3.org/2000/svg" height="1em"
                                                         viewBox="0 0 512 512"
                                                         onclick="toggleEdit({{ $vacationRequest->id }})">
                                                        <path
                                                            d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"/>
                                                    </svg>
                                                    <!-- Cancel button to revert changes -->
                                                    <button id="cancel_button_{{ $vacationRequest->id }}"
                                                            class="hidden ml-2 text-white rounded-lg text-sm bg-red-600 font-medium px-4 py-2"
                                                            onclick="toggleEdit({{ $vacationRequest->id }})">X
                                                    </button>

                                                    <!-- Save button to save changes -->
                                                    <button id="save_button_{{ $vacationRequest->id }}"
                                                            class="hidden ml-2 text-white rounded-lg text-sm bg-green-600 font-medium px-4 py-2"
                                                            onclick="console.log('ID:', {{ $vacationRequest->id }}); saveChanges({{ $vacationRequest->id }})">
                                                        &#x2713;
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-center mt-4">
                                <button id="displayMoreButtonVacation"
                                        class="text-blue-500 hover:text-blue-700 underline focus:outline-none">
                                    Mehr anzeigen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pb-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="mb-4">Krankenstand nachtragen</h2>
                    <div id="newVacReq" class="flex flex-col sm:flex-row justify-between items-center pt-5 pl-8">
                        <form method="POST" action="{{ route('dashboard-sickness-admin') }}">
                            @csrf
                            <div class="flex flex-col sm:flex-row items-center mb-2">
                                <label for="user_id"
                                       class="mr-2 text-sm font-medium text-gray-900">Angestellter:</label>
                                <select name="user_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block py-2.5 px-4">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <label for="start_date" class="ml-4 mr-2 text-sm font-medium text-gray-900">Start
                                    Datum:</label>
                                <input
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 start_date"
                                    type="date" name="start_date" id="start_date" required>
                                <label for="end_date" class="ml-4 mr-2 text-sm font-medium text-gray-900">End
                                    Datum:</label>
                                <input
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 end_date"
                                    type="date" name="end_date" id="end_date" required>

                                <button type="submit"
                                        class="ml-10 mt-2 px-4 py-2.5 mb-1.5 bg-green-600 text-white rounded-lg text-sm font-medium">
                                    Eintragen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2>Krankenstand-Anfragen</h2>
                    <!--Table for Sickness-->
                    <div class="pb-4 pt-4">
                        <div class="mr-4 ml-4">
                            <div class="relative mb-4 w-3/5 flex">
                                <div class="w-1/2 mr-2">
                                    <div class="relative">
                                        <select id="userFilterSickness"
                                                class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">Users</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-1/2 mr-2">
                                    <div class="relative">
                                        <select id="yearFilterSickness"
                                                class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                                            @php
                                                $currentYear = date('Y');
                                                $startYear = $currentYear - 9;
                                            @endphp
                                            <option value="">Years</option>
                                            @for ($year = $currentYear; $year >= $startYear; $year--)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg aria-hidden="true" class="w-5 h-5 text-gray-500" fill="none"
                                                 stroke="currentColor" viewBox="0 0 24 24"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <!--<button type="submit"
                                        onclick="applyFilter('sicknessTable', 'userFilterSickness', 'yearFilterSickness')"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 ml-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Search
                                </button>-->
                            </div>

                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table id="sicknessTable" class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-center">Name</th>
                                        <th scope="col" class="px-6 py-3 text-center">Jahr</th>
                                        <th scope="col" class="px-6 py-3 text-center">Start</th>
                                        <th scope="col" class="px-6 py-3 text-center">Ende</th>
                                        <th scope="col" class="px-6 py-3 text-center">Dauer</th>
                                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                                        <th scope="col" class="px-6 py-3 text-center">Antwort</th>
                                        <th scope="col" class="px-6 py-3 text-center">Löschen</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($sicknessRequests->sortByDesc('start_date') as $sicknessRequest)
                                        <tr class="bg-gray-100">
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">{{ \App\Models\User::find($sicknessRequest->user_id)->name }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">{{$sicknessRequest->start_date->format('Y') }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">{{$sicknessRequest->start_date->format('d M') }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">{{$sicknessRequest->end_date->format('d M') }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center font-bold">{{$sicknessRequest->total_days }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">
                                                <div @class([
                                                                'text-green-500' =>  $sicknessRequest->accepted === 'accepted',
                                                                'text-yellow-600' =>  $sicknessRequest->accepted === 'pending',
                                                                'text-red-500' =>  $sicknessRequest->accepted === 'declined',
                                                    ])>
                                                    {{ $sicknessRequest->accepted }}
                                                </div>
                                            </td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">
                                                @if($sicknessRequest->accepted === 'pending')
                                                    <form method="POST"
                                                          action="{{ route('update.sicknessAnswer', ['id' => $sicknessRequest->id]) }}">
                                                        @csrf
                                                        @method('POST')
                                                        <select
                                                            onchange="hideButtonSick()"
                                                            name="antwortSicknessRequest"
                                                            required
                                                            class="border-red-500 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-center"
                                                        >
                                                            <option disabled selected value> -- select an option --
                                                            </option>
                                                            <option value="accepted">accepted</option>
                                                            <option value="declined">declined</option>
                                                        </select>

                                                        <button
                                                            type="submit"
                                                            id="buttonSubSick"
                                                            class="hidden ml-10 text-white rounded-lg text-sm mt-2 px-8 py-3 bg-green-600 font-medium rounded-lg text-sm px-4 py-2"
                                                        >
                                                            Submit
                                                        </button>
                                                    </form>

                                                @else
                                                    <p class="text-gray-400 italic text-center">Bereits bearbeitet</p>
                                                @endif
                                            </td>
                                            <td class="px-6 text-sm text-gray-700  text-center">
                                                <form method="POST"
                                                      action="{{ route('delete.sicknessRequest', $sicknessRequest->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="font-bold text-red-500 font-medium rounded-lg text-sm w-full sm:w-auto pt-3 text-center"
                                                        type="submit"
                                                        onclick="return confirm('Bist du sicher, dass du das löschen willst?')"
                                                    >
                                                        Löschen
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-center mt-4">
                                <button id="displayMoreButtonSickness"
                                        class="text-blue-500 hover:text-blue-700 underline focus:outline-none">
                                    Mehr anzeigen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    // Function to toggle between displaying text and input fields
    function toggleEdit(vacationRequestId) {
        const startDateSpan = document.getElementById('start_date_' + vacationRequestId);
        const endDateSpan = document.getElementById('end_date_' + vacationRequestId);
        const editIcon = document.getElementById('edit_icon_' + vacationRequestId);
        const cancelButton = document.getElementById('cancel_button_' + vacationRequestId);
        const saveButton = document.getElementById('save_button_' + vacationRequestId);

        const startDateValue = startDateSpan.getAttribute('data-start');
        const endDateValue = endDateSpan.getAttribute('data-end');

        if (startDateSpan.querySelector('input') === null) {
            startDateSpan.innerHTML = `
            <input type="date" name="start_date" value="${startDateValue}" class="border rounded px-1 text-center" oninput="enableSaveButton(${vacationRequestId})">
        `;

            endDateSpan.innerHTML = `
            <input type="date" name="end_date" value="${endDateValue}" class="border rounded px-1 text-center" oninput="enableSaveButton(${vacationRequestId})">
        `;

            // Hide the pencil icon
            editIcon.style.display = 'none';

            // Show the cancel button and save button
            cancelButton.style.display = 'inline';
            saveButton.style.display = 'inline';
        } else {
            // If the input fields are already visible, switch back to displaying text
            const formattedStartDate = formatDate(startDateValue);
            const formattedEndDate = formatDate(endDateValue);

            startDateSpan.innerHTML = formattedStartDate;
            endDateSpan.innerHTML = formattedEndDate;

            // Show the pencil icon
            editIcon.style.display = 'inline';

            // Hide the cancel button and save button
            cancelButton.style.display = 'none';
            saveButton.style.display = 'none';
        }
    }

    // Enable the Save button when the input fields are modified
    function enableSaveButton(vacationRequestId) {
        const cancelButton = document.getElementById('cancel_button_' + vacationRequestId);
        const saveButton = document.getElementById('save_button_' + vacationRequestId);
        saveButton.style.display = 'inline';
        cancelButton.style.display = 'none';
    }

    // Function to format the date as day/month (e.g., "10 Jul")
    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = date.getDate().toString().padStart(2, '0');
        const month = date.toLocaleString('default', {month: 'short'});
        return day + ' ' + month;
    }

    function saveChanges(id) {
        const startInputDiv = document.querySelector(`#start_date_${id}`);
        const endInputDiv = document.querySelector(`#end_date_${id}`);

        // Get the input elements within the divs
        const startInput = startInputDiv.querySelector('input');
        const endInput = endInputDiv.querySelector('input');

        const formData = new FormData();
        formData.append('start_date', startInput.value);
        formData.append('end_date', endInput.value);

        // Get the CSRF token from the meta tag
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        fetch(`/update-vacation-request/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
        }).then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
            .then(data => {
                // Check if the response contains the "message" field
                if (data.message) {
                    console.log(data.message); // Log the success message
                    location.reload(); // Reload the page
                } else {
                    console.log('Unexpected response:', data); // Handle other cases, if necessary
                }
            })
            .catch(error => {
                console.log('Fetch error:', error);
            });
    }

    function applyFilter(tableId, userFilterId, yearFilterId) {
        var userFilter = document.getElementById(userFilterId);
        var yearFilter = document.getElementById(yearFilterId);
        var userValue = userFilter.value;
        var yearValue = yearFilter.value;

        console.log("Year filter: ", yearValue);
        console.log("User filter: ", userValue);

        var table = document.getElementById(tableId);
        var rows = table.getElementsByTagName("tr");

        for (var i = 1; i < rows.length; i++) { // Start from 1 to skip table header
            var cells = rows[i].getElementsByTagName("td");
            var userMatch = true;
            var yearMatch = true;

            if (userValue !== "" && userValue !== "users") {
                var userCell = cells[0].innerText;
                userMatch = (userCell === userValue);
            }

            if (yearValue !== "" && yearValue !== "years") {
                var yearCell = cells[1].innerText;
                yearMatch = (yearCell === yearValue);
            }

            rows[i].style.display = (userMatch && yearMatch) ? "" : "none";
        }
    }

    // Add event listeners to the filters
    var userFilterVacation = document.getElementById("userFilterVacation");
    var yearFilterVacation = document.getElementById("yearFilterVacation");
    userFilterVacation.addEventListener("change", function () {
        applyFilter("vacationTable", "userFilterVacation", "yearFilterVacation");
    });
    yearFilterVacation.addEventListener("change", function () {
        applyFilter("vacationTable", "userFilterVacation", "yearFilterVacation");
    });

    var userFilterSickness = document.getElementById("userFilterSickness");
    var yearFilterSickness = document.getElementById("yearFilterSickness");
    userFilterSickness.addEventListener("change", function () {
        applyFilter("sicknessTable", "userFilterSickness", "yearFilterSickness");
    });
    yearFilterSickness.addEventListener("change", function () {
        applyFilter("sicknessTable", "userFilterSickness", "yearFilterSickness");
    });

    var numRowsToShow = 15; // Number of rows to show initially
    var totalRows = document.getElementById("vacationTable").getElementsByTagName("tr").length - 1; // Subtract 1 for table header

    function showRows(startIndex, endIndex) {
        var tableRows = document.getElementById("vacationTable").getElementsByTagName("tr");

        for (var i = 1; i <= totalRows; i++) {
            if (i >= startIndex && i <= endIndex) {
                tableRows[i].style.display = ""; // Show the row
            } else {
                tableRows[i].style.display = "none"; // Hide the row
            }
        }
    }

    // Function to display more rows for a given table
    function displayMoreRows(tableId, displayButtonId) {
        var table = document.getElementById(tableId);
        var rows = table.getElementsByTagName("tr");
        for (var i = 15; i < rows.length; i++) {
            rows[i].style.display = ""; // Display the rows
        }
        var displayButton = document.getElementById(displayButtonId);
        displayButton.innerText = "Weniger anzeigen"; // Change the button text
        displayButton.removeEventListener("click", displayMoreRows); // Remove the existing event listener
        displayButton.addEventListener("click", function () {
            showLessRows(tableId, displayButtonId); // Add a new event listener for showing fewer rows
        });

        // Scroll to the table
        table.scrollIntoView({behavior: 'smooth', block: 'start'});
    }

    // Function to show fewer rows for a given table
    function showLessRows(tableId, displayButtonId) {
        var table = document.getElementById(tableId);
        var rows = table.getElementsByTagName("tr");
        for (var i = 15; i < rows.length; i++) {
            rows[i].style.display = "none"; // Hide the rows
        }
        var displayButton = document.getElementById(displayButtonId);
        displayButton.innerText = "Mehr anzeigen"; // Change the button text
        displayButton.removeEventListener("click", showLessRows); // Remove the existing event listener
        displayButton.addEventListener("click", function () {
            displayMoreRows(tableId, displayButtonId); // Add a new event listener for displaying more rows
        });

        // Scroll to the table
        table.scrollIntoView({behavior: 'smooth', block: 'start'});
    }

    // Function to initialize the table display
    function initializeTableDisplay(tableId, displayButtonId) {
        var table = document.getElementById(tableId);
        var rows = table.getElementsByTagName("tr");
        for (var i = 10; i < rows.length; i++) {
            rows[i].style.display = "none"; // Hide the rows initially
        }
        var displayButton = document.getElementById(displayButtonId);
        displayButton.style.display = "block"; // Show the display more button
        displayButton.addEventListener("click", function () {
            displayMoreRows(tableId, displayButtonId); // Add an event listener for displaying more rows
        });
    }

    // Initialize the table display on page load
    initializeTableDisplay("vacationTable", "displayMoreButtonVacation");
    initializeTableDisplay("sicknessTable", "displayMoreButtonSickness");

    function validateEndDate(event) {
        const form = event.target.form;
        const startDateInput = form.querySelector('.start_date');
        const endDateInput = form.querySelector('.end_date');

        const startDate = startDateInput.value;

        // Set the minimum allowed value for the end date
        endDateInput.min = startDate;

        // Clear the end date value if it's before the start date or if it's the same day
        if (endDateInput.value < startDate || endDateInput.value === startDate) {
            endDateInput.value = '';
        }
    }

    // Attach the event listeners to all start_date inputs
    const startDateInputs = document.querySelectorAll('.start_date');
    startDateInputs.forEach((input) => {
        input.addEventListener('input', validateEndDate);
    });
</script>
