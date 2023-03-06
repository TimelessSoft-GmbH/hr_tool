<div id="editUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
    <div class="relative w-full h-full max-w-lg md:h-auto">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-5 border-b rounded-t">
                <h3 class="text-xl font-medium text-gray-900">
                    Edit User
                </h3>
                <button type="button" onclick="showUserUpdateForm()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="medium-modal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!--TODO: HOW TO GET THE CORRECT USER-->
            <!--TODO: HOW TO GET THE CORRECT USER-->
            <!--TODO: HOW TO GET THE CORRECT USER-->
            <!-- Modal body -->
            <!--{{ route('user.update', [$user->id]) }}-->
            <form method="POST" action="">
                @csrf
                <div class="flex items-center justify-center pt-2">
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
                                name="floating_email"
                                id="floating_email"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                value="{{ $user->email }}"
                            />
                        @else
                            <input
                                type="email"
                                name="floating_email"
                                id="floating_email"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                placeholder=" "
                            />
                        @endif
                        <label for="floating_email" class="absolute text-sm text-gray-500 text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Email address</label>
                    </div>
                    <!-- NAME -->
                    <div class="grid md:grid-cols-2 md:gap-6">
                        <div class="relative z-0 w-full group">
                            @if($user->name !== '')
                                <input
                                    type="text"
                                    name="floating_name"
                                    id="floating_name"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                    value="{{ $user->name }}"
                                />
                            @else
                                <input
                                    type="text"
                                    name="floating_name"
                                    id="floating_name"
                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                    placeholder=" "
                                />
                            @endif
                            <label for="floating_name" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Name</label>
                        </div>

                        <!-- PHONE NUMBER -->
                        <div class="relative z-0 w-full group">
                            @if($user->phoneNumber !== '')
                                <input
                                    type="tel"
                                    pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                    name="floating_phone"
                                    id="floating_phone"
                                    class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                    value="{{ $user->phoneNumber }}"
                                />
                            @else
                                <input
                                    type="tel"
                                    pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                    name="floating_phone"
                                    id="floating_phone"
                                    class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                    placeholder=" "
                                />
                            @endif
                            <label for="floating_phone" class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Phone number</label>
                        </div>
                    </div>
                    <!--ADRESSE -->
                    <div class="relative z-0 w-full mb-6 group">
                        @if($user->adress !== '')
                            <input
                                type="text"
                                name="floating_adress"
                                id="floating_adress"
                                class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                value="{{ $user->adress }}"
                            />
                        @else
                            <input
                                type="text"
                                name="floating_adress"
                                id="floating_adress"
                                class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                placeholder=" "
                            />
                        @endif
                        <label
                            for="floating_adress"
                            class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                        >
                            Adresse
                        </label>
                    </div>

                    <div class="pt-4 grid md:grid-cols-2 md:gap-6">
                        <!--Urlaubstage -->
                        <div class="relative z-0 w-full group">
                            @if($user->vacationDays !== '')
                                <input
                                    type="number"
                                    name="floating_vacationDays"
                                    id="floating_vacationDays"
                                    class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                    value="{{ $user->vacationDays }}"
                                />
                            @else
                                <input
                                    type="number"
                                    name="floating_vacationDays"
                                    id="floating_vacationDays"
                                    class="block py-2.5 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                    placeholder=" "
                                />
                            @endif
                            <label
                                for="floating_vacationDays"
                                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >
                                Urlaubstage
                            </label>
                        </div>

                        <!--Rolle -->
                        <div class="relative z-0 w-full group">
                            <select id="floating_roles" size="1" class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                @foreach ($roles as $role)
                                    @if($user->hasRole($role->name))
                                        <option selected value="{{ $role->name }}">{{ $role->name }}</option>
                                    @else
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <label
                                for="floating_roles"
                                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >
                                Rolle
                            </label>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 md:gap-6">
                        <!--Salary -->
                        <div class="relative z-0 w-full mb-6 group">
                            @if($user->salary !== '')
                                <input
                                    type="number"
                                    name="floating_salary"
                                    id="floating_salary"
                                    class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                    value="{{ $user->salary }}"
                                />
                            @else
                                <input
                                    type="number"
                                    name="floating_salary"
                                    id="floating_salary"
                                    class="block py-2.5 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                    placeholder=" "
                                />
                            @endif
                            <label
                                for="floating_salary"
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
                                    name="floating_start_of_work"
                                    id="floating_start_of_work"
                                    class="block py-2.5 px-2 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                    value="{{ $user->start_of_work }}"
                                />
                            @else
                                <input
                                    type="date"
                                    name="floating_start_of_work"
                                    id="floating_start_of_work"
                                    class="block py-2.5 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 appearance-none border-gray-600 focus:border-blue-500 focus:outline-none focus:ring-0 peer"
                                    placeholder=" "
                                />
                            @endif
                            <label
                                for="floating_start_of_work"
                                class="peer-focus:font-medium absolute text-sm text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6"
                            >
                                Dienstbeginn
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                </div>
            </form>
            <!-- Modal footer -->
            <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                <button onclick="showUserUpdateForm()" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Speichern</button>
                <button onclick="showUserUpdateForm()" type="button" class="text-gray-500 bg-red hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-red-900 focus:z-10">Löschen</button>
            </div>
        </div>
    </div>
</div>
