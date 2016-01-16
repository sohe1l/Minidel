<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact From</title>
</head>
<body>
        <h4>Name</h4>
        <div>{{ $name }}</div>

        <h4>Mobile</h4>
        <div>{{ $mobile }}</div>

        <h4>Email</h4>
        <div>{{ $email }}</div>

        <h4>Message</h4>
        <div>{{ $info }}</div>

        <h4>Technical Info</h4>
        <div>Submitted on: {{ \Carbon\Carbon::now() }}</div>
        <div>{{ \Request::ip() }}</div>
        <div>{{ json_encode(\Session::all()) }}</div>

</body>
</html>