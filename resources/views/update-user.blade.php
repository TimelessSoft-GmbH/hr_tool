<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Update User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class ="flex">
                    <h2 class="p-6 text-gray-900">
                        {{ __("Vacation Request: ") }}
                    </h2>
                </div>
                <!--Open Form-->
                <div class="p-6">
                    <div class="flex items-center justify-center pt-1">

                    <form method="POST"
                          action="{{ route('index.updated.user', ['id' => $user->id]) }}">
                        @csrf
                            <!-- IMAGE -->
                            @if($user->image !== '')
                                <img class="image rounded-circle"
                                     src="{{asset('/images/'.$user->image)}}" alt="profile_image"
                                     style="width: 80px;height: 80px;">
                            @else
                                <div class="relative inline-flex items-center justify-center w-20 h-20 overflow-hidden bg-gray-300 rounded-full mr-2">
                                    <span class="text-3xl text-gray-600">{{ $user->initials }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-10 space-y-6">
                            <!-- EMAIL -->
                            <div class="relative z-0 w-full mb-6 group">
                                @if($user->email !== '')
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                        value="{{ $user->email }}"
                                    />
                                @else
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                        placeholder=" "
                                    />
                                @endif
                                <label for="email" class="absolute text-sm text-gray-500 text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email address</label>
                            </div>
                            <!-- NAME -->
                            <div class="grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 w-full group">
                                    @if($user->name !== '')
                                        <input
                                            type="text"
                                            name="name"
                                            id="name"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                            value="{{ $user->name }}"
                                        />
                                    @else
                                        <input
                                            type="text"
                                            name="name"
                                            id="name"
                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                            placeholder=" "
                                        />
                                    @endif
                                    <label for="name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Name</label>
                                </div>

                                <!-- PHONE NUMBER -->
                                <div class="relative z-0 w-full group">
                                    @if($user->phoneNumber !== '')
                                        <input
                                            type="tel"
                                            name="phoneNumber"
                                            id="phoneNumber"
                                            class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                            value="{{ $user->phoneNumber }}"
                                        />
                                    @else
                                        <input
                                            type="tel"
                                            name="phoneNumber"
                                            id="phoneNumber"
                                            class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                            placeholder=" "
                                        />
                                    @endif
                                    <label for="phoneNumber" class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Phone number</label>
                                </div>
                            </div>

                            <!--ADRESSE -->
                            <div class="relative z-0 w-full mb-6 group">
                                @if($user->adress !== '')
                                    <input
                                        type="text"
                                        name="adress"
                                        id="adress"
                                        class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                        value="{{ $user->adress }}"
                                    />
                                @else
                                    <input
                                        type="text"
                                        name="adress"
                                        id="adress"
                                        class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                        placeholder=" "
                                    />
                                @endif
                                <label
                                    for="adress"
                                    class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                >
                                    Adresse
                                </label>
                            </div>

                            <!--Urlaubstage -->
                            <div class="pt-4 grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 w-full group">
                                    @if($user->vacationDays !== '')
                                        <input
                                            type="number"
                                            name="vacationDays"
                                            id="vacationDays"
                                            class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                            value="{{ $user->vacationDays }}"
                                        />
                                    @else
                                        <input
                                            type="number"
                                            name="vacationDays"
                                            id="vacationDays"
                                            class="block py-2.5 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                            placeholder=" "
                                        />
                                    @endif
                                    <label
                                        for="vacationDays"
                                        class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >
                                        Urlaubstage
                                    </label>
                                </div>

                                <!--Rolle -->
                                <div class="relative z-0 w-full group">
                                    <select name="hasrole" id="roles" size="1" class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                        @foreach ($roles as $role)
                                            @if($role->name === $user->hasrole)
                                                <option selected value="{{ $role->name }}">{{ $role->name }}</option>
                                            @else
                                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <label
                                        for="roles"
                                        class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >
                                        Rolle
                                    </label>
                                </div>
                            </div>

                            <!--Salary -->
                            <div class="grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 w-full mb-6 group">
                                    @if($user->salary !== '')
                                        <input
                                            type="number"
                                            name="salary"
                                            id="salary"
                                            class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                            value="{{ $user->salary }}"
                                        />
                                    @else
                                        <input
                                            type="number"
                                            name="salary"
                                            id="salary"
                                            class="block py-2.5 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                            placeholder=" "
                                        />
                                    @endif
                                    <label
                                        for="salary"
                                        class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >
                                        Gehalt
                                    </label>
                                </div>

                                <!--Start of Work -->
                                <div class="relative z-0 w-full mb-6 group">
                                    @if($user->start_of_work !== '')
                                        <input
                                            type="date"
                                            name="start_of_work"
                                            id="start_of_work"
                                            class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                            value="{{ $user->start_of_work }}"
                                        />
                                    @else
                                        <input
                                            type="date"
                                            name="start_of_work"
                                            id="start_of_work"
                                            class="block py-2.5 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                            placeholder=" "
                                        />
                                    @endif
                                    <label
                                        for="start_of_work"
                                        class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >
                                        Dienstbeginn
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
