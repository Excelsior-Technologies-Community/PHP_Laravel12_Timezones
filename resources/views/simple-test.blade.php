<!DOCTYPE html>
<html>
<head>
    <title>Simple Test</title>
</head>
<body>
    <h1>Laravel is Working!</h1>
    <p>If you can see this, Laravel is functioning correctly.</p>
    <p>Current Time: {{ now() }}</p>
    <a href="{{ url('/events') }}">Go to Events</a>
</body>
</html>