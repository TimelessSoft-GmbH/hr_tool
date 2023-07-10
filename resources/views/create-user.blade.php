<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!--Open Form-->
                    <form method="POST"
                          action="{{ route('users.create') }}"
                          enctype="multipart/form-data"
                    >
                        @csrf
                        <div class="flex items-center justify-center pt-1">
                            <!-- IMAGE -->
                            <div
                                class="relative inline-flex items-center justify-center w-20 h-20 overflow-hidden bg-gray-300 rounded-full mr-2">
                            </div>
                        </div>
                        <div class="p-10 space-y-6">

                            <!-- EMAIL -->
                            <div class="relative z-0 w-full group">
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                    placeholder=" "
                                    required
                                />
                                <label for="email"
                                       class="absolute text-sm text-gray-500 text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email
                                    address
                                </label>
                            </div>

                            <!-- NAME -->
                            <div class="grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 w-full group">
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                        placeholder=" "
                                        required
                                    />
                                    <label for="name"
                                           class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Name
                                    </label>
                                </div>

                                <!-- PASSWORD -->
                                <div class="relative z-0 w-full group">
                                    <input
                                        type="text"
                                        name="password"
                                        id="password"
                                        class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                        placeholder=" "
                                        required
                                    />
                                    <label
                                        for="password"
                                        class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >
                                        Passwort
                                    </label>
                                </div>
                            </div>

                            <!--ADRESSE -->
                            <div class="pt-4 grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 w-full group">
                                    <input
                                        type="text"
                                        name="adress"
                                        id="adress"
                                        class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                        placeholder=" "
                                    />
                                    <label
                                        for="adress"
                                        class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >
                                        Adresse
                                    </label>
                                </div>

                                <div class="relative z-0 w-full group">
                                    <input
                                        type="number"
                                        step="0.01"
                                        name="hours_per_week"
                                        id="hours_per_week"
                                        class="block py-2.5 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                        placeholder=" "
                                        required
                                    />
                                    <label
                                        for="hours_per_week"
                                        class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >
                                        Wochenstunden
                                    </label>
                                </div>
                            </div>

                            <!--URLAUBSTAGE -->
                            <div class="pt-4 grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 w-full group">
                                    <input
                                        type="number"
                                        name="vacationDays"
                                        id="vacationDays"
                                        class="block py-2.5 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                        placeholder=" "
                                    />
                                    <label
                                        for="vacationDays"
                                        class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >
                                        Urlaubstage
                                    </label>
                                </div>

                                <!--Rolle -->
                                <div class="relative z-0 w-full group">
                                    <select name="hasrole" id="roles" size="1"
                                            class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
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

                            <!--SALARY -->
                            <div class="grid md:grid-cols-2 md:gap-6">
                                <div class="relative z-0 w-full group">
                                    <input
                                        type="number"
                                        step="0.01"
                                        name="salary"
                                        id="salary"
                                        class="block py-2.5 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                        placeholder=" "
                                        required
                                    />
                                    <label
                                        for="salary"
                                        class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >
                                        Aktuelles Gehalt
                                    </label>
                                </div>

                                <!--Start of Work -->
                                <div class="relative z-0 w-full mb-0 group">
                                    <input
                                        type="date"
                                        name="start_of_work"
                                        id="start_of_work"
                                        class="block py-2.5 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                        placeholder=" "
                                        required
                                    />
                                    <label
                                        for="start_of_work"
                                        class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >
                                        Dienstbeginn
                                    </label>
                                </div>
                            </div>

                            <!--PHONE NUMBER-->
                            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6">
                                <!-- PHONE NUMBER -->
                                <div class="relative z-0 w-full group">
                                    <input
                                        type="tel"
                                        name="phoneNumber"
                                        id="phoneNumber"
                                        class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                        placeholder=" "
                                    />
                                    <label for="phoneNumber"
                                           class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                                    >
                                        Telefonnummer
                                    </label>
                                </div>

                                <!--File Input-->
                                <div class="relative z-0 w-full mb-0 group">
                                    <input type="file"
                                           class="mb-2 mt-2 block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none placeholder-gray-400 py-2 px-3 leading-5"
                                           id="contract"
                                           name="contract"
                                    >
                                    <label for="contract"
                                           class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                        PDF's
                                    </label>
                                    <div class="flex flex-wrap mt-2">
                                        @foreach ($fileHistories as $fileHistory)
                                            <a href="{{ asset('/storage/' . $fileHistory->file_path) }}" target="_blank"
                                               class="mr-2 mb-1 py-2 px-3 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 text-white font-medium rounded-lg text-sm focus:outline-none">
                                                {{ $fileHistory->file_name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!--Workdays-->
                            <div class="grid">
                                <p class="text-sm text-gray-400">Workdays:</p>
                                <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex">
                                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                        <div class="flex items-center pl-3">
                                            <label class="w-full py-3 ml-2 text-sm font-medium text-gray-900">
                                                <input type="checkbox" name="workdays[]" value="Monday"
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                                >
                                                <span class="ml-2">Monday</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                        <div class="flex items-center pl-3">
                                            <label class="w-full py-3 ml-2 text-sm font-medium text-gray-900">
                                                <input type="checkbox" name="workdays[]" value="Tuesday"
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                                >
                                                <span class="ml-2">Tuesday</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                        <div class="flex items-center pl-3">
                                            <label class="w-full py-3 ml-2 text-sm font-medium text-gray-900">
                                                <input type="checkbox" name="workdays[]" value="Wednesday"
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                                >
                                                <span class="ml-3">Wednesday</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                        <div class="flex items-center pl-3">
                                            <label class="w-full py-3 ml-2 text-sm font-medium text-gray-900">
                                                <input type="checkbox" name="workdays[]" value="Thursday"
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                                >
                                                <span class="ml-2">Thursday</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                        <div class="flex items-center pl-3">
                                            <label class="w-full py-3 ml-2 text-sm font-medium text-gray-900">
                                                <input type="checkbox" name="workdays[]" value="Friday"
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                                >
                                                <span class="ml-2">Friday</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r">
                                        <div class="flex items-center pl-3">
                                            <label class="w-full py-3 ml-2 text-sm font-medium text-gray-900">
                                                <input type="checkbox" name="workdays[]" value="Saturday"
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                                >
                                                <span class="ml-2">Saturday</span>
                                            </label>
                                        </div>
                                    </li>
                                    <li class="w-full">
                                        <div class="flex items-center pl-3">
                                            <label class="w-full py-3 ml-2 text-sm font-medium text-gray-900">
                                                <input type="checkbox" name="workdays[]" value="Sunday"
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                                >
                                                <span class="ml-2">Sunday</span>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <!--Submit Button-->
                            <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-md w-full sm:w-auto px-7 py-3 text-center">
                                Speichern
                            </button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
