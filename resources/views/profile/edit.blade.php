<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                <h2 class="text-lg font-medium text-gray-900 pb-1">
                    {{ __('Update Profile Image') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 pb-7">
                    {{ __('Upload an Image of your choosing.') }}
                </p>

                <!-- Store Profile Image -->
                <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data">
                        @csrf

                        <input type="file" name="image" id="image">
                        @if(Auth::user()->image !== "basicUser.png")
                            <img src="{{ route('profile.image', ['filename' => Auth::user()->image]) }}" style="width:80px;margin-top: 10px;" alt="profile-img">
                        @endif

                        @error('image')
                            <span role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <button
                            type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        >
                            {{ __('Upload Profile') }}
                        </button>

                </form>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            </div>
        </div>
    </div>
</x-app-layout>
