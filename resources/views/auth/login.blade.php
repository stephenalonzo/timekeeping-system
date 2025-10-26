<x-timesheet.layout>
    <section class="p-6">
        <div class="mx-auto w-full h-screen flex flex-col items-center justify-center space-y-4 sm:max-w-lg">
            <span class="text-2xl font-medium">Timekeeping System</span>
            <form action="/authenticate" method="post" class="w-full p-6 rounded-md bg-white border border-gray-400">
                @csrf
                <div class="space-y-6">
                    <div class="space-y-2 flex flex-col">
                        <label for="employeeId">Employee ID</label>
                        <input type="text" name="employeeId" id="employeeId"
                            class="px-3 py-2.5 bg-white rounded-md border border-gray-400 placeholder:text-gray-400"
                            placeholder="Enter Employee ID">
                        @error('employeeId')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2 flex flex-col">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password"
                            class="px-3 py-2.5 bg-white rounded-md border border-gray-400">
                    </div>
                    <button type="submit" class="px-5 py-2.5 rounded-md bg-gray-900 text-white w-full">Login</button>
                </div>
            </form>
        </div>
    </section>
</x-timesheet.layout>
