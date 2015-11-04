<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body>
        <div>
            To reset your password, complete this form: {{ URL::to('password/reset', array($token)) }}.<br/>
            This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes.
        </div>
</body>
</html>