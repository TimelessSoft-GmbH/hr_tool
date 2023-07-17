@php
    use Carbon\Carbon;
    $now = now();
    $year = $now->year;
    $month = $now->month;
    $daysInMonth = $now->daysInMonth;
    $firstDayOfWeek = $now->startOfMonth()->dayOfWeek;
    $date = 1;

    // Check if the user has provided a specific year and month
    if (isset($selectedYear) && isset($selectedMonth)) {
        $year = $selectedYear;
        $month = $selectedMonth;
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $firstDayOfWeek = Carbon::create($year, $month)->startOfMonth()->dayOfWeek;
    }

    $currentMonthHolidays = collect($holiday_dates)->map(fn($holiday) => Carbon::parse($holiday)->format('d'))->toArray();

    // Check for all VacationRequests
    $currentMonthVacationDays = collect($vacationRequests->where('accepted', 'accepted'))
        ->flatMap(function ($request) use ($year, $month) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $vacationDays = [];

            while ($startDate->lte($endDate)) {
                if ($startDate->year == $year && $startDate->month == $month) {
                    $vacationDays[] = [
                        'date' => $startDate->format('d'),
                        'initials' => $request->user->initials,
                    ];
                }

                $startDate->addDay();
            }

            return $vacationDays;
        })
        ->groupBy('date')
        ->toArray();

    // Calculate the previous and next month
    $previousMonth = Carbon::create($year, $month)->subMonth();
    $nextMonth = Carbon::create($year, $month)->addMonth();

    $currentMonthTitle = Carbon::create($year, $month)->format('F Y');
@endphp

<div class="border border-gray-300 rounded-lg overflow-hidden">
    <div class="flex justify-between bg-gray-200 px-3 py-2">
        <div>
            <a href="{{ url()->current() }}?selectedYear={{ $previousMonth->year }}&selectedMonth={{ $previousMonth->month }}"
               class="text-gray-600 hover:text-red-800 text-lg font-bold">&lt;</a>
        </div>
        <span class="text-gray-800 font-bold">{{ $currentMonthTitle }}</span>
        <div>
            <a href="{{ url()->current() }}?selectedYear={{ $nextMonth->year }}&selectedMonth={{ $nextMonth->month }}"
               class="text-gray-600 hover:text-red-800 text-lg font-bold">&gt;</a>
        </div>
    </div>
    <table class="w-full">
        <thead>
        <tr>
            <th class="py-2 px-1 text-xs text-gray-500">Mon</th>
            <th class="py-2 px-1 text-xs text-gray-500">Tue</th>
            <th class="py-2 px-1 text-xs text-gray-500">Wed</th>
            <th class="py-2 px-1 text-xs text-gray-500">Thu</th>
            <th class="py-2 px-1 text-xs text-gray-500">Fri</th>
            <th class="py-2 px-1 text-xs text-gray-500">Sat</th>
            <th class="py-2 px-1 text-xs text-gray-500">Sun</th>
        </tr>
        </thead>
        <tbody>
        @for ($i = 0; $i < 6; $i++)
            <tr>
                @for ($j = 0; $j < 7; $j++)
                    @php
                        $cellIndex = $i * 7 + $j + 1;
                        $isCurrentMonth = ($cellIndex >= $firstDayOfWeek && $cellIndex < $firstDayOfWeek + $daysInMonth);
                        $currentDate = Carbon::create($year, $month, $date)->format('d');
                        $isPublicHoliday = in_array($currentDate, $currentMonthHolidays);
                        $isWeekend = ($j === 5 || $j === 6); // Saturday or Sunday
                        $isVacation = isset($currentMonthVacationDays[$currentDate]);
                        $usersOnVacation = $isVacation ? array_column($currentMonthVacationDays[$currentDate], 'initials') : [];
                        $isSick = false; // Set to true if the user is sick
                    @endphp

                    <td class="w-20 h-20 border border-gray-200 {{ $isCurrentMonth ? 'relative' : 'bg-gray-100' }} {{ $isCurrentMonth && $date == Carbon::now()->day && $year == $now->year && $month == $now->month? 'bg-red-200' : '' }}">
                        @if ($isCurrentMonth)
                            <div
                                class="absolute top-0 left-0 w-full h-1 @if ($isPublicHoliday || $isWeekend) bg-red-600 @endif"></div>
                            <div class="p-2 flex flex-col items-start justify-start h-full">
                                <div class="text-sm font-bold">{{ $date }}</div>
                                @if ($isVacation)
                                    <div class="flex flex-wrap">
                                        @foreach ($usersOnVacation as $initials)
                                            <div
                                                class="relative inline-flex items-center justify-center w-8 h-8 overflow-hidden bg-gray-300 rounded-full mr-2 mt-2">
                                                <span
                                                    class="font-medium text-gray-600 dark:text-gray-500">{{ $initials }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif ($isSick)
                                    <div class="flex items-center justify-center w-6 h-6 rounded-full bg-red-500"></div>
                                @endif
                            </div>
                            @php $date++; @endphp
                        @endif
                    </td>

                @endfor
            </tr>
            @if ($date > $daysInMonth)
                @break
            @endif
        @endfor
        </tbody>
    </table>
</div>
<div class="flex justify-center mt-4">
    <form action="{{ url()->current() }}" method="GET">
        <button type="submit" class="bg-gray-400 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
            Jump to Current Month
        </button>
    </form>
</div>
