<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('ticket.store') }}" enctype = "multipart/form-data">
        @csrf
         <div>
            <x-input-label for="title" :value="__('Title')" />
            <x-text-input id="title" class="block mt-1 w-full" type="title" name="title" :value="old('title')" autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="description" :value="__('Description')" />
            <textarea name = "description" id = "description" class="block mt-1 w-full"></textarea>
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="image" :value="__('Image')" />
            <input name = "attachment" id = "attachment" type = "file" class="block mt-1 w-full"/>
            <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
        </div>
        <div class="flex items-center justify-end mt-4">
             <x-primary-button class="ml-3">
                {{ __('Submit') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
