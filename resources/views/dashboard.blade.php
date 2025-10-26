<x-app-layout>
    <section class="px-3 py-6 space-y-4 w-full h-screen bg-white">
        <div class="space-y-4 flex flex-col items-center justify-center">
            <h2 class="font-semibold text-xl text-center text-gray-800 leading-tight uppercase">
                {{ date('F Y') }}
            </h2>
            <span class="flex items-center space-x-1">
                <p id="curTime"></p>
            </span>
        </div>
        <div>
            <form action="/punch" method="POST" class="mx-auto overflow-hidden max-w-5xl h-full">
                @csrf
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div
                                    class="border border-gray-200 rounded-lg shadow-xs overflow-hidden dark:border-neutral-700 dark:shadow-gray-900">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                        <thead>
                                            <tr class="divide-x divide-gray-200 text-center dark:divide-neutral-700">
                                                <th scope="col"
                                                    class="p-3 text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                                    Day</th>
                                                <th scope="col"
                                                    class="p-3 text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                                    In</th>
                                                <th scope="col"
                                                    class="p-3 text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                                    Out</th>
                                                <th scope="col"
                                                    class="p-3 text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                                    In</th>
                                                <th scope="col"
                                                    class="p-3 text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                                    Out</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 text-center dark:divide-neutral-700">
                                            @foreach ($punches as $punch)
                                                <tr class="divide-x divide-gray-200 dark:divide-neutral-700">
                                                    <td
                                                        class="p-3 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                                        {{ date('d', strtotime($punch->day_in)) }}
                                                    </td>
                                                    <td
                                                        class="p-3 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                                        {{ empty($punch->day_in) ? '--:-- --' : date('h:i A', strtotime($punch->day_in)) }}
                                                    </td>
                                                    <td
                                                        class="p-3 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                                        {{ empty($punch->lunch_out) ? '--:-- --' : date('h:i A', strtotime($punch->lunch_out)) }}
                                                    </td>
                                                    <td
                                                        class="p-3 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                                        {{ empty($punch->lunch_in) ? '--:-- --' : date('h:i A', strtotime($punch->lunch_in)) }}
                                                    </td>
                                                    <td
                                                        class="p-3 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                                        {{ empty($punch->day_out) ? '--:-- --' : date('h:i A', strtotime($punch->day_out)) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-2">
                        {{-- Day In --}}
                        @unless (empty($current->day_in ?? []))
                            <label for="day_in"
                                class="flex p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 {{ !empty($current->day_in) ? 'border-2 border-green-400' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6 text-green-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Day In</span>
                            </label>
                        @else
                            <label for="day_in"
                                class="flex p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                                <input type="radio" name="mode" value="1"
                                    class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                    id="day_in">
                                <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Day In</span>
                            </label>
                        @endunless

                        {{-- Lunch Out --}}
                        @unless (empty($current->lunch_out ?? []))
                            <label for="lunch_out"
                                class="flex p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 {{ !empty($current->lunch_out) ? 'border-2 border-green-400' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6 text-green-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Lunch Out</span>
                            </label>
                        @else
                            <label for="lunch_out"
                                class="flex p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                                <input type="radio" name="mode" value="2"
                                    class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                    id="lunch_out">
                                <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Lunch Out</span>
                            </label>
                        @endunless

                        {{-- Lunch In --}}
                        @unless (empty($current->lunch_in ?? []))
                            <label for="lunch_in"
                                class="flex p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 {{ !empty($current->lunch_in) ? 'border-2 border-green-400' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6 text-green-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Lunch In</span>
                            </label>
                        @else
                            <label for="lunch_in"
                                class="flex p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                                <input type="radio" name="mode" value="3"
                                    class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                    id="lunch_in">
                                <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Lunch In</span>
                            </label>
                        @endunless

                        {{-- Day Out --}}
                        @unless (empty($current->day_out ?? []))
                            <label for="day_out"
                                class="flex p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 {{ !empty($current->day_out) ? 'border-2 border-green-400' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6 text-green-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Day Out</span>
                            </label>
                        @else
                            <label for="day_out"
                                class="flex p-3 w-full bg-white border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                                <input type="radio" name="mode" value="4"
                                    class="shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none"
                                    id="day_out">
                                <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400">Day Out</span>
                            </label>
                        @endunless
                        @error('mode')
                            <p class="text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid gap-2 md:grid-cols-2">
                        <button type="submit"
                            class="px-5 py-2.5 w-full rounded-md bg-gray-900 text-white">Submit</button>
                        <a href="/export"
                            class="px-3 py-2.5 text-gray-900 bg-gray-300 rounded-md flex items-center justify-center space-x-2">
                            <span>Export Timesheet</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                            </svg>
                        </a>
                    </div>
                    <div class="space-y-2">
                        <p class="font-semibold">Total Hours: {{ $regularHours }}</p>
                        <p class="font-semibold">Overtime Hours: {{ $overTime }}</p>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-app-layout>
