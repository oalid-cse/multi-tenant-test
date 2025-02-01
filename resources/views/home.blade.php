<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $tenant->name }}'s Tenant</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container pt-5">

    <h1>{{ $tenant->name }} Home Page</h1>
    <p>Welcome to the tenant home page</p>

    <div class="card">
        <div class="card-header">
            <h3>Projects</h3>
        </div>
        <div class="card-body">
            <ul>
                @foreach($projects as $project)
                    <li>{{ $project->name }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
</body>
</html>
