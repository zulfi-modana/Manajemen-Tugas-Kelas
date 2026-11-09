
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Task Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>

        <p>Email: {{ auth()->user()->email }}</p>
        <p>Role: <strong>{{ auth()->user()->role }}</strong></p>

        <form method="POST" action="/logout">
            @csrf
            <button class="btn btn-danger">Logout</button>
        </form>
    </div>
</div>
</body>
</html>
