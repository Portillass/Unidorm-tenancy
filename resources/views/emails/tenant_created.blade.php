<!DOCTYPE html>
<html>
<head>
    <title>Tenant Created</title>
</head>
<body>
    <h1>Welcome, {{ $userName }}!</h1>
    <p>Your tenant account has been created successfully.</p>
    <p><strong>Email:</strong> {{ $userEmail }}</p>
    <p><strong>Password:</strong> {{ $randomPassword }}</p>
    <p><strong>Domain:</strong>
    <a href="{{ $domainUrl }}">
        {{ $domain }}
    </a>
    </p>
    <p><strong>Subscription Plan:</strong> {{ $subscriptionPlan }}</p>
    <p>Please use these credentials to log in to your account.</p>
</body>
</html>
