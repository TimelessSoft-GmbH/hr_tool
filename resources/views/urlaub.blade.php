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
</x-app-layout>

