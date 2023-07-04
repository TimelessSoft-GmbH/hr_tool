<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $data['type_of_notification'] }} von {{ \App\Models\User::findorfail($data['user_id'])->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold mb-4">{{ \App\Models\User::findorfail($data['user_id'])->name }} hat
            einen {{ $data['type_of_notification'] }} für {{ $data['total_days'] }} Tage gestellt.</h2>
        <p class="text-center">{{ $data['type_of_notification'] }} vom {{ $data['start_date'] }}
            bis {{ $data['end_date'] }}</p>
        <p class="text-center mt-4">
            <a href="https://hr.staging-server.at/login" class="text-blue-500">Handlung erforderlich</a>.
        </p>
    </div>
</div>
</body>
</html>
