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
                        <div class="w-2/3 mx-auto">
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Id</th>
                                        <th scope="col" class="px-6 py-3">Username</th>
                                        <th scope="col" class="px-6 py-3">Email</th>
                                        <th scope="col" class="px-6 py-3">Role</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                        <tr class="bg-gray-100">
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$user->id}}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$user->name}}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$user->email}}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$user->hasrole}}</td>
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
                                    @foreach($vacationRequests as $vacationRequest)
                                        <tr class="bg-gray-100">
                                            <td class="py-4 px-6 text-sm text-gray-700">{{ \App\Models\User::find($vacationRequest->user_id)->name }}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$vacationRequest->start_date}}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$vacationRequest->end_date}}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">inc days insg.</td>
                                            <td class="py-4 px-6 text-sm text-gray-700"><div @class([
                                                                    'text-green-500' =>  $vacationRequest->accepted === 'accepted',
                                                                    'text-yellow-600' =>  $vacationRequest->accepted === 'pending',
                                                                    'text-red-500' =>  $vacationRequest->accepted === 'declined',
                                                        ])>
                                                    {{ $vacationRequest->accepted }}
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
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$sicknessRequest->start_date}}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">{{$sicknessRequest->end_date}}</td>
                                            <td class="py-4 px-6 text-sm text-gray-700">inc days insg.</td>
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

