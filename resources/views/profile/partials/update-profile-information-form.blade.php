<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            @if(Auth::user()->role === 'mahasiswa')
                <x-input-label for="nim" :value="__('NIM')" />
                <x-text-input id="nim" name="nim" type="text" class="mt-1 block w-full bg-gray-100 cursor-not-allowed" :value="old('nim', $user->nim)" readonly />
            @else
                <x-input-label for="nik" :value="__('NIK')" />
                <x-text-input id="nik" name="nik" type="text" class="mt-1 block w-full bg-gray-100 cursor-not-allowed" :value="old('nik', $user->nik)" readonly />
            @endif
            
            <p class="text-sm text-gray-500 mt-1">
                {{ __('Identitas NIM/NIK tidak dapat diubah. Hubungi Admin jika terjadi kesalahan.') }}
            </p>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>