<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Angestellte') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!--Table for Users-->
                    <div class="pb-4 pt-4">
                        <div class="w-4/5 mx-auto">
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

                                            <td class="pt-3 text-sm text-gray-700 flex items-center">
                                                @if($user->id === Auth::user()->id)
                                                    <p class="py-4 px-2 text-sm text-gray-400 italic">-----</p>
                                                @else
                                                    <form method="POST"
                                                          action="{{ route('delete.user', $user->id) }}">
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
</x-app-layout>
