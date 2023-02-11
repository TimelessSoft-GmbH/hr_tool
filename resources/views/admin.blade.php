<script>
    function hideButton() {
        var element = document.getElementById("buttonSub");
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
                    <h2>Angestellte</h2>
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
                                        <th scope="col" class="px-6 py-3">Edit Role</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        <tr class="bg-gray-100">
                                            <td class="py-4 px-6 text-sm text-gray-700"><img class="image rounded-circle" src="{{asset('/images/'.Auth::user()->image)}}" alt="profile_image" style="width: 40px;height: 40px; margin-left: 4px;"></td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$user->name}}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$user->email}}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">
                                                <div @class([
                                                                    'text-green-500 font-medium' =>  $user->hasrole === 'user',
                                                                    'text-red-600 font-bold' =>  $user->hasrole === 'admin',
                                                        ])>
                                                {{Str::upper($user->hasrole)}}
                                            </td>
                                            <td class="py-4 px-6 text-sm text-gray-700">
                                                <form method="POST" action="{{ route('role.update', [$user->id]) }}">
                                                    @csrf
                                                        <button
                                                            class="text-blue-400 focus:ring-4 font-medium text-xs py-1 "
                                                            type="submit"
                                                            onclick="return confirm('Bist du sicher dass du die Rolle tauschen willst?')"
                                                        >
                                                        Swop Roles
                                                    </button>
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
        </div>
    </div>

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2>Urlaub-Requests</h2>
                    <!--Table for Users-->
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
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">{{$vacationRequest->start_date->format('d M') }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center">{{$vacationRequest->end_date->format('d M') }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center font-bold">{{$vacationRequest->end_date->diffInDays($vacationRequest->start_date) }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700 text-center"><div @class([
                                                                    'text-green-500' =>  $vacationRequest->accepted === 'accepted',
                                                                    'text-yellow-600' =>  $vacationRequest->accepted === 'pending',
                                                                    'text-red-500' =>  $vacationRequest->accepted === 'declined',
                                                        ])>
                                                    {{ $vacationRequest->accepted }}
                                                </div>
                                            </td>
                                            <td class="py-4 px-6 text-sm text-gray-700">

                                                @if($vacationRequest->accepted === 'pending')
                                                <form method="POST" action="{{ route('updateAnswerDB') }}">
                                                    @csrf
                                                    <select
                                                        onchange="hideButton()"
                                                        name="antwort"
                                                        required
                                                        class="border-red-500 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-center"
                                                    >
                                                        <option disabled selected value> -- select an option -- </option>
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
                                    </tbody>
                                </table>
                            </div>
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
                        <div class="w-2/3 mx-auto">
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Name</th>
                                        <th scope="col" class="px-6 py-3">Start Datum</th>
                                        <th scope="col" class="px-6 py-3">End Datum</th>
                                        <th scope="col" class="px-6 py-3">Tage Insg</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($sicknessRequests as $sicknessRequest)
                                        <tr class="bg-gray-100">
                                            <td class="py-4 px-6 text-sm text-gray-700">{{ \App\Models\User::find($sicknessRequest->user_id)->name }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$sicknessRequest->start_date->format('d M') }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$sicknessRequest->end_date->format('d M') }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$sicknessRequest->end_date->diffInDays($sicknessRequest->start_date) }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700"><div @class([
                                                                    'text-green-500' =>  $sicknessRequest->accepted === 'accepted',
                                                                    'text-yellow-600' =>  $sicknessRequest->accepted === 'pending',
                                                                    'text-red-500' =>  $sicknessRequest->accepted === 'declined',
                                                        ])>
                                                    {{ $sicknessRequest->accepted }}
                                                </div>
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

    <!--
    FOR TABS:
    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
        <li class="mr-2" role="presentation">
            <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-tab" data-tabs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
        </li>
        <li class="mr-2" role="presentation">
            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">Dashboard</button>
        </li>
        <li class="mr-2" role="presentation">
            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="settings-tab" data-tabs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Settings</button>
        </li>
        <li role="presentation">
            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="contacts-tab" data-tabs-target="#contacts" type="button" role="tab" aria-controls="contacts" aria-selected="false">Contacts</button>
        </li>
    </ul>
</div>
<div id="myTabContent">
    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Profile tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
    </div>
    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
        <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Dashboard tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
    </div>
    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="settings" role="tabpanel" aria-labelledby="settings-tab">
        <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Settings tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
    </div>
    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
        <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Contacts tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
    </div>
</div>
    -->
</x-app-layout>

