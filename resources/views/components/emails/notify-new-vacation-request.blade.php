<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $data['type_of_notification'] }} von {{ \App\Models\User::findorfail($data['user_id'])->name }}</title>
</head>
<body>
<h2>{{ \App\Models\User::findorfail($data['user_id'])->name }} hat einen {{ $data['type_of_notification'] }} für {{ $data['total_days'] }} Tage gestellt.</h2>
<p class="text-md-center">Urlaubsantrag vom {{ $data['start_date'] }} bis {{ $data['end_date'] }}</p>
</body>
</html>
