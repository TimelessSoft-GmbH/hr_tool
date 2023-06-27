<aside class="fixed top-0 left-0 h-screen flex-shrink-0 w-64 bg-gray-700 border-r border-gray-200">
    <div class="h-full flex flex-col justify-between">
        <div class="py-4 px-6 bg-gray-700">
            <div class="py-4 px-6 flex flex-col items-center">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="w-40 block h-9 w-auto fill-current text-white"></x-application-logo>
                </a>
                <hr class="border-t-1 border-white-700 w-40 mt-8">
            </div>
        </div>
        <nav class="flex-grow px-6 py-4 bg-gray-700">
            <a href="{{ route('dashboard') }}"
               class="flex items-center text-white py-2 px-4 hover:bg-gray-200 hover:text-gray-800 {{ Request::is('dashboard') ? 'bg-gray-200 text-black' : '' }}">
                <svg class="w-8 h-8 mr-3 text-red-500" fill="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
                <span class="text-md">Dashboard</span>
            </a>
            @if(Auth::user()->hasRole('admin'))
                <a href="{{ route('enterHours') }}"
                   class="flex items-center py-3 px-4 mt-2 text-white hover:bg-gray-200 hover:text-gray-800 {{ Request::is('enterHours') ? 'bg-gray-200 text-black' : '' }}">
                    <svg class="w-8 h-8 mr-3 text-red-500" fill="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd"
                              d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-md">Stunden</span>
                </a>
                <a href="{{ route('users.load') }}"
                   class="flex items-center py-3 px-4 mt-2 text-white hover:bg-gray-200 hover:text-gray-800 {{ Request::is('users') ? 'bg-gray-200 text-black' : '' }}">
                    <svg class="w-8 h-8 mr-3 text-red-500" fill="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                    </svg>
                    <span class="text-md">Angestellte</span>
                </a>
                <a href="{{ route('admin') }}"
                   class="flex items-center py-3 px-4 mt-2 text-white hover:bg-gray-200 hover:text-gray-800 {{ Request::is('admin') ? 'bg-gray-200 text-black' : '' }}">
                    <svg class="w-8 h-8 mr-3 text-red-500" fill="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    <span class="text-md">Anfragen</span>
                </a>
            @endif
            <a href="{{ route('profile.edit') }}"
               class="flex items-center py-3 px-4 mt-2 text-white hover:bg-gray-200 hover:text-gray-800 {{ Request::is('profile') ? 'bg-gray-200 text-black' : '' }}">
                <svg class="w-8 h-8 mr-3 text-red-500" fill="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                          clip-rule="evenodd"></path>
                </svg>
                <span class="text-md">Profile</span>
            </a>
        </nav>
        <div class="px-6 py-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="bg-red-700 flex items-center py-3 px-7 text-white hover:bg-red-800 hover:text-gray-800 focus:ring-4 focus:ring-red-300 rounded-lg ">
                    <svg class="w-8 h-8 mr-3 text-white" fill="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-md">Log Out</span>
                </button>
            </form>
        </div>
    </div>
</aside>
