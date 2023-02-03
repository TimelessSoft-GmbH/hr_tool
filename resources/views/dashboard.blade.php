<script>
    function myFunction() {
        var element = document.getElementById("newVacReq");
        element.classList.toggle("hidden");
    }
</script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class ="flex pb-5">
                    <h2 class="p-6 text-gray-900">
                        {{ __("Vacation Request: ") }}
                    </h2>
                    <button
                        type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-large rounded-lg text-md px-4 py-0.5 mr-2 mb-4 mt-3 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                        onclick=" myFunction()"
                    >
                        {{ __("+ Stelle einen Urlaubsantrag") }}

                    </button>
                </div>

                <div id="newVacReq" class="flex flex-row justify-center pb-5 hidden">
                    <form method="POST" action="/dashboard">
                        @csrf
                        <label
                            for="start_date"
                            class="mb-2 text-sm font-medium text-gray-900"
                        >
                            Start Datum:
                        </label>
                        <input
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 mr-10"
                            type="date"
                            name="start_date"
                            id="start_date"
                            required
                        >
                        <label
                            for="end_date"
                            class="mb-2 text-sm font-medium text-gray-900"
                        >
                            End Datum:
                        </label>
                        <input
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                            type="date"
                            name="end_date"
                            id="end_date"
                            required
                        >
                        <input type="hidden" name="user_id" value="{{Auth::id()}}">

                        <button
                            type="submit"
                            class="ml-10 text-white rounded-lg text-sm mt-2 px-8 py-3 bg-green-600 font-medium rounded-lg text-sm px-4 py-2"
                        >
                            Anfragen
                        </button>
                    </form>
                </div>

                <div class="pb-4">
                    <div class="w-2/3 mx-auto">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            User
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Start Datum
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            End Datum
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Angefragt
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Stand
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>

                                @if(count($vacationRequests) < 1)

                                    <tr class="bg-gray-100">
                                        <th class="py-4 px-6 text-sm text-gray-400 italic">
                                            {{ __("Noch kein Antrag ") }}
                                        </th>
                                        <td class="py-4 px-6 text-sm text-gray-400 italic">
                                            {{ __("---------") }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-400 italic">
                                            {{ __("---------") }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-400 italic">
                                            {{ __("---------") }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-400 italic">
                                            {{ __("---------") }}
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($vacationRequests as $vacationRequest)
                                            <tr class="bg-gray-100">
                                                <th class="py-4 px-6 text-sm text-gray-700">
                                                    {{ Auth::user()->name }}
                                                </th>

                                                <th class="py-4 px-6 text-sm text-gray-700">
                                                    {{ $vacationRequest->start_date }}
                                                </th>

                                                <td class="py-4 px-6 text-sm text-gray-700">
                                                    {{ $vacationRequest->end_date }}
                                                </td>

                                                <td class="py-4 px-6 text-sm text-gray-700">
                                                    {{ $vacationRequest->created_at->toDateString() }}
                                                </td>

                                                <td class="py-4 px-6 text-sm text-gray-700">
                                                    {{ $vacationRequest->accepted }}
                                                </td>
                                            </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="Test" class="pb-12 hidden">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="p-6 text-gray-900">
                    {{ __("Vacation Request: ") }}
                </h2>
            </div>
        </div>
    </div>
</x-app-layout>
