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
                        @include('components.Calendar', ['users' => $users, '$vacationRequests' => $vacationRequests, '$sicknessRequests' => $sicknessRequests, '$holiday_dates' => $holiday_dates ])
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
                                <button type="submit"
                                        onclick="applyFilter('vacationTable', 'userFilterVacation', 'yearFilterVacation')"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 ml-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Search
                                </button>
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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($vacationRequests->sortByDesc('start_date') as $vacationRequest)
                                        <tr class="bg-gray-100">
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">{{ \App\Models\User::find($vacationRequest->user_id)->name }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">{{ $vacationRequest->start_date->format('Y') }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">{{ $vacationRequest->start_date->format('d M') }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">{{ $vacationRequest->end_date->format('d M') }}</td>
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
                                <button type="submit"
                                        onclick="applyFilter('sicknessTable', 'userFilterSickness', 'yearFilterSickness')"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 ml-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Search
                                </button>

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

        // Clear the end date value if it's before the start date
        if (endDateInput.value < startDate) {
            endDateInput.value = '';
        }
    }

    // Attach the event listeners to all start_date inputs
    const startDateInputs = document.querySelectorAll('.start_date');
    startDateInputs.forEach((input) => {
        input.addEventListener('input', validateEndDate);
    });
</script>
