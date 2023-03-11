<!DOCTYPE html>
<html lang="en">
<head>
    <title>
        {{ $data['type_of_notification'] }} wurde
        @if($data['answer'] === 'accepted')
            akzeptiert.
        @elseif($data['answer'] === 'declined')
            abgelehnt.
        @endif
    </title>
</head>
<body>
<h2>Dein {{ $data['type_of_notification'] }} wurde
    @if($data['answer'] === 'accepted')
        akzeptiert.
    @elseif($data['answer'] === 'declined')
        leider abgelehnt. Bei Fragen, bitte melde dich bei uns!
    @endif
</h2>
<p class="text-md-center">Ganz liebe Grüße,<br>Nicole & Georg</p>
</body>
</html>
