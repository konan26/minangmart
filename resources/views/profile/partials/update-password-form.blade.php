<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <label class="block text-gray-400 text-xs font-bold uppercase tracking-widest mb-2" for="update_password_current_password">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full input-gold" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label class="block text-gray-400 text-xs font-bold uppercase tracking-widest mb-2" for="update_password_password">New Password</label>
            <input id="update_password_password" name="password" type="password" class="w-full input-gold" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label class="block text-gray-400 text-xs font-bold uppercase tracking-widest mb-2" for="update_password_password_confirmation">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full input-gold" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="btn-gold px-8 py-3 rounded-full text-sm font-bold uppercase tracking-widest text-[#000b1a] bg-gold hover:bg-white transition shadow-lg shadow-gold/20">
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gold italic"
                >{{ __('Changes saved.') }}</p>
            @endif
        </div>
    </form>
</section>
