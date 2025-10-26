<x-timesheet.layout>
    <section class="p-6">
        <div class="mx-auto w-full h-screen flex flex-col items-center justify-center">
            <form action="/register" method="post" class="w-full p-6 rounded-md bg-white border border-gray-400">
                @csrf
                <div class="space-y-6">
                    <div class="space-y-2 flex flex-col">
                        <label for="fullName">Full Name</label>
                        <input type="text" name="name" id="fullName"
                            class="px-3 py-2.5 bg-white rounded-md border border-gray-400 placeholder:text-gray-400"
                            placeholder="Enter your full name" value="{{ old('fullName') }}" required>
                        @error('fullName')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2 flex flex-col">
                        <label for="email">Email Address</label>
                        <input type="text" name="email" id="email"
                            class="px-3 py-2.5 bg-white rounded-md border border-gray-400 placeholder:text-gray-400"
                            placeholder="Enter your email address" value="{{ old('email') }}" required>
                        @error('email')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2 flex flex-col">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password"
                            class="px-3 py-2.5 bg-white rounded-md border border-gray-400">
                    </div>
                    <div class="space-y-2 flex flex-col">
                        <label for="confirmPassword">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="confirmPassword"
                            class="px-3 py-2.5 bg-white rounded-md border border-gray-400" required>
                        @error('password_confirmation')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-md bg-gray-900 text-white w-full">Register</button>
                </div>
            </form>
        </div>
    </section>
</x-timesheet.layout>
