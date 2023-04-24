<script>
function hideButton() {
    var element = document.getElementById("buttonSub");
    element.classList.toggle("hidden");
}

function hideButtonSick() {
    var element = document.getElementById("buttonSubSick");
    element.classList.toggle("hidden");
}
function addRowBtn() {
    var element = document.getElementById("addRowBtn");
    element.classList.toggle("hidden");
}
</script>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 id="TEST">Angestellte</h2>
                    <!--Table for Users-->
                    <div class="pb-4">
                        <div class="w-3/4 mx-auto">
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Image</th>
                                        <th scope="col" class="px-6 py-3">Username</th>
                                        <th scope="col" class="px-6 py-3">Email</th>
                                        <th scope="col" class="px-6 py-3">Role</th>
                                        <th scope="col" class="px-4 py-3">Edit</th>
                                        <th scope="col" class="pr-2 py-3">Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        <tr class="bg-gray-100">
                                            <td class="py-4 px-6 text-sm text-gray-700">
                                                @if($user->image !== '')
                                                    <img class="image rounded-circle"
                                                         src="{{asset('/images/'.$user->image)}}" alt="profile_image"
                                                         style="width: 40px;height: 40px; margin-left: 4px;"></td>
                                            @else
                                                <div
                                                    class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-300 rounded-full mr-2">
                                                <span
                                                    class="font-medium text-gray-600 dark:text-gray-500">{{ $user->initials }}</span>
                                                </div>
                                            @endif
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$user->name}}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$user->email}}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">
                                                @if($user->hasRole('admin'))
                                                    <b>{{Str::upper('admin')}}</b>
                                                @else
                                                    {{Str::upper('user')}}
                                                @endif
                                            </td>

                                            <td class="py-4 text-sm text-gray-700">
                                                <a href="{{ URL('/admin/user/update/'.$user->id)}}">
                                                    <button
                                                        class="block w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                                        type="button">
                                                            Edit
                                                    </button>
                                                </a>
                                            </td>

                                            <td class="pt-3 text-sm text-gray-700">
                                                @if($user->id === Auth::user()->id)
                                                    <p class="py-4 px-2 text-sm text-gray-400 italic">-----</p>
                                                @else
                                                    <form method="POST" action="{{ route('index.delete.user', $user->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            class="font-bold text-red-500 font-medium rounded-lg text-sm w-full sm:w-auto py-3 text-center"
                                                            type="submit"
                                                            onclick="return confirm('Are you sure that you want to delete User {{ $user->name }}?')"
                                                                >
                                                                    Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <a href="{{ URL('/users/create') }}">
                                <button
                                    class="mt-5 ml-4 block w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                    type="button">
                                    <span class="font-bold">+</span> Neuer User
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--<div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h1>hi</h1>
                </div>
            </div>
        </div>
    </div>-->

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2>Urlaub-Requests</h2>
                    <!--Table for Vacation-Requests-->
                    <div class="pb-4">
                        <div class="w-3/4 mx-auto">
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-center">Name</th>
                                        <th scope="col" class="px-6 py-3 text-center">Start Datum</th>
                                        <th scope="col" class="px-6 py-3 text-center">End Datum</th>
                                        <th scope="col" class="px-6 py-3 text-center">Tage Insg</th>
                                        <th scope="col" class="px-6 py-3 text-center">Status</th>
                                        <th scope="col" class="px-6 py-3 text-center">Antwort</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($vacationRequests as $vacationRequest)
                                        <tr class="bg-gray-100">
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">{{ \App\Models\User::find($vacationRequest->user_id)->name }}</td>
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
                                                          action="{{ route('vacation.answerUpdate', ['id' => $vacationRequest->id]) }}">
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
                                        </tr>
                                    @endforeach
                                        <tr class="bg-gray-100 hidden" id="addRowBtn">
                                            <form method="POST"
                                                  action="{{ route('dashboard-vacation') }}">
                                                @csrf
                                                <td class=" px-3 text-sm text-gray-700">
                                                    <select
                                                        name="user_id"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5 text-center"
                                                    >
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="py-4 px-3 text-sm text-gray-700 justify-center">
                                                    <input
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                                                        type="date"
                                                        name="start_date"
                                                    >
                                                </td>
                                                <td class="py-4 px-3 text-sm text-gray-700">
                                                    <input
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5"
                                                        type="date"
                                                        name="end_date"
                                                    >
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-700">
                                                    <button
                                                        class="block w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                                        type="submit"
                                                    >
                                                        Eintragen
                                                    </button>
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-700">

                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-700">

                                                </td>
                                            </form>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button
                                class="mt-5 ml-4 block w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                                type="button"
                                onclick="addRowBtn()"
                            >
                                <span class="font-bold">+</span> Urlaub nachtragen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2>Sickness-Requests</h2>
                    <!--Table for Users-->
                    <div class="pb-4">
                        <div class="w-3/4 mx-auto">
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Name</th>
                                        <th scope="col" class="px-6 py-3">Start Datum</th>
                                        <th scope="col" class="px-6 py-3">End Datum</th>
                                        <th scope="col" class="px-6 py-3">Tage Insg</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3">Antwort</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($sicknessRequests as $sicknessRequest)
                                        <tr class="bg-gray-100">
                                            <td class="py-4 px-6 text-sm text-gray-700">{{ \App\Models\User::find($sicknessRequest->user_id)->name }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$sicknessRequest->start_date->format('d M') }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$sicknessRequest->end_date->format('d M') }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$sicknessRequest->total_days }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">
                                                <div @class([
                                                                'text-green-500' =>  $sicknessRequest->accepted === 'accepted',
                                                                'text-yellow-600' =>  $sicknessRequest->accepted === 'pending',
                                                                'text-red-500' =>  $sicknessRequest->accepted === 'declined',
                                                    ])>
                                                    {{ $sicknessRequest->accepted }}
                                                </div>
                                            </td>
                                            <td class="py-4 px-6 text-sm text-gray-700">
                                                @if($sicknessRequest->accepted === 'pending')
                                                    <form method="POST"
                                                          action="{{ route('sickness.answerUpdate', ['id' => $sicknessRequest->id]) }}">
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
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
