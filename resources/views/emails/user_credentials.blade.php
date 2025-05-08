<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Credentials</title>
</head>
<body>
    <h2>Welcome, {{ $username }}!</h2>
    <p>Your account has been created with the following credentials:</p>
    <ul>
        <li><strong>Username:</strong> {{ $username }}</li>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Role:</strong> {{ $role }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>
    <p>Please keep this information secure. You can now log in using the provided credentials.</p>
</body>
</html>