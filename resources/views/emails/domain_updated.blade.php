<!DOCTYPE html>
<html>
<head>
    <title>Updated Domain</title>
</head>

<style>
    .domain-link {
        color: #3490dc;
        text-decoration: none;
        font-weight: bold;
    }
    .domain-link:hover {
        text-decoration: underline;
    }
</style>

<body>
    <p>A new domain has been added to your tenant account:</p>

    <ul>
        <li>Tenant: {{ $tenant->id }}</li>
        <li>Domain: <a href="{{ $domainUrl }}">{{ $domain }} </a> </li>
        <li>Date: {{ now()->toDateTimeString() }}</li>
    </ul>

<p>You can access the domain by clicking the link above.</p>

<p>Thank you for using our service!</p>
</body>
</html>