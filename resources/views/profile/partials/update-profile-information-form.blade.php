<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <label class="block text-gray-400 text-xs font-bold uppercase tracking-widest mb-2" for="name">Full Name</label>
            <input id="name" name="name" type="text" class="w-full input-gold" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label class="block text-gray-400 text-xs font-bold uppercase tracking-widest mb-2" for="email">Email Address</label>
            <input id="email" name="email" type="email" class="w-full input-gold" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-400">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="underline text-gold hover:text-white transition">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                </div>
            @endif
        </div>

        <div>
            <label class="block text-gray-400 text-xs font-bold uppercase tracking-widest mb-2" for="phone_number">Phone Number</label>
            <input id="phone_number" name="phone_number" type="text" class="w-full input-gold" value="{{ old('phone_number', $user->phone_number) }}" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
        </div>

        <div>
            <label class="block text-gray-400 text-xs font-bold uppercase tracking-widest mb-2" for="address">Delivery Address</label>
            <textarea id="address" name="address" class="w-full input-gold min-h-[100px]" placeholder="Enter your full delivery address...">{{ old('address', $user->address) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="btn-gold px-8 py-3 rounded-full text-sm font-bold uppercase tracking-widest">
                {{ __('Update Profile') }}
            </button>

            @if (session('status') === 'profile-updated')
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
