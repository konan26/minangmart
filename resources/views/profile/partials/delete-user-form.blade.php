<section class="space-y-6">
    <p class="mt-1 text-sm text-gray-500">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-500/10 text-red-500 border border-red-500/20 px-6 py-3 rounded-full text-xs font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition"
    >
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-[#0b1a33] border border-white/10 rounded-[2rem]">
            @csrf
            @method('delete')

            <h2 class="text-xl font-bold text-white mb-4">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mb-6 text-sm text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <label class="block text-gray-400 text-xs font-bold uppercase tracking-widest mb-2" for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full input-gold"
                    placeholder="{{ __('Password') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-500" />
            </div>

            <div class="mt-8 flex justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="bg-white/5 border border-white/10 text-white px-6 py-3 rounded-full text-xs font-black uppercase tracking-widest hover:bg-white/10 transition">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="bg-red-500/10 border border-red-500/20 text-red-500 px-6 py-3 rounded-full text-xs font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
