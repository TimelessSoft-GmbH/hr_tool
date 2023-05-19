<table class="w-full text-sm text-left text-gray-500">
    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
    <tr>
        <th scope="col" class="px-6 py-4 text-center">User</th>
        <th scope="col" class="px-6 py-4 text-center">Jan</th>
        <th scope="col" class="px-6 py-4 text-center">Feb</th>
        <th scope="col" class="px-6 py-4 text-center">Mär</th>
        <th scope="col" class="px-4 py-4 text-center">Apr</th>
        <th scope="col" class="pr-2 py-4 text-center">Mai</th>
        <th scope="col" class="pr-2 py-4 text-center">Jun</th>
        <th scope="col" class="pr-2 py-4 text-center">Jul</th>
        <th scope="col" class="pr-2 py-4 text-center">Aug</th>
        <th scope="col" class="pr-2 py-4 text-center">Sep</th>
        <th scope="col" class="pr-2 py-4 text-center">Okt</th>
        <th scope="col" class="pr-2 py-4 text-center">Nov</th>
        <th scope="col" class="pr-2 py-4 text-center">Dez</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr class="bg-gray-100">
            <td class="py-5 px-6 text-sm text-gray-700 text-center">{{ $user->name }}</td>
            @for($month = 1; $month <= 12; $month++)
                @php
                    $monthHours = $workHours
                        ->where('user_id', $user->id)
                        ->where('month', $month)
                        ->where('year', $year)
                        ->first();
                    $monthValue = $monthHours ? $monthHours->hours : null;
                @endphp

                <td class="py-5 px-6 text-sm text-center{{ $monthValue === null ? ' text-gray-300' : '' }}">
                    {{ $monthValue ?? 'N/A' }}
                </td>
            @endfor
        </tr>
    @endforeach
    </tbody>
</table>
