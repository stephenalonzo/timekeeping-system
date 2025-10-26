<x-app-layout>
    <section class="px-3 py-6 space-y-4 w-full h-screen bg-white">
        <div class="space-y-4 flex flex-col items-center justify-center">
            <h2 class="font-semibold text-xl text-center text-gray-800 leading-tight uppercase">
                {{ date('F Y') }}
            </h2>
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
                    <div class="grid gap-2 md:grid-cols-2">
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
        <hr class="block opacity-100 mx-auto max-w-5xl">
        <div class="mx-auto max-w-5xl space-y-4 hs-accordion-group">
            @foreach ($archives as $archive)
                <div class="hs-accordion hs-accordion-active:border-gray-200 bg-white border border-gray-200 rounded-xl dark:hs-accordion-active:border-neutral-700 dark:bg-neutral-800 dark:border-transparent"
                    id="hs-active-bordered-{{ $archive['uniqueId'] }}">
                    <button
                        class="hs-accordion-toggle hs-accordion-active:text-blue-600 inline-flex justify-between items-center gap-x-3 w-full font-semibold text-start text-gray-800 py-4 px-5 hover:text-gray-500 disabled:opacity-50 disabled:pointer-events-none dark:hs-accordion-active:text-blue-500 dark:text-neutral-200 dark:hover:text-neutral-400 dark:focus:outline-hidden dark:focus:text-neutral-400"
                        aria-expanded="false"
                        aria-controls="hs-basic-active-bordered-collapse-{{ $archive['uniqueId'] }}">
                        <span class="uppercase">{{ $archive['month'] }}</span>
                        <svg class="hs-accordion-active:hidden block size-3.5" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"></path>
                            <path d="M12 5v14"></path>
                        </svg>
                        <svg class="hs-accordion-active:block hidden size-3.5" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"></path>
                        </svg>
                    </button>
                    <div id="hs-basic-active-bordered-collapse-{{ $archive['uniqueId'] }}"
                        class="hs-accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
                        role="region" aria-labelledby="hs-active-bordered-{{ $archive['uniqueId'] }}">
                        <div class="space-y-4 p-5">
                            <div class="flex flex-col">
                                <div class="-m-1.5 overflow-x-auto">
                                    <div class="p-1.5 min-w-full inline-block align-middle">
                                        <div
                                            class="border border-gray-200 rounded-lg shadow-xs overflow-hidden dark:border-neutral-700 dark:shadow-gray-900">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                                <thead>
                                                    <tr
                                                        class="divide-x divide-gray-200 text-center dark:divide-neutral-700">
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
                                                <tbody
                                                    class="divide-y divide-gray-200 text-center dark:divide-neutral-700">
                                                    @foreach ($archive['punches'] as $punch)
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
                            <div class="grid gap-2 md:grid-cols-2">
                                <a href="{{ route('export.archive', ['employeeId' => $archive['punches']->first()->employeeId, 'month' => $archive['month']]) }}"
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
                                <p class="font-semibold">Total Hours: {{ $archive['archiveRegular'] }}</p>
                                <p class="font-semibold">Overtime Hours: {{ $archive['archiveOT'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-app-layout>
