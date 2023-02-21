<?
header('HTTP/1.1 503 Service Temporarily Unavailable');
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 300');//300 seconds
?>
<html>
<head>
    <title>Service is temporarily unavailable</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="robots" content="noindex">
</head>
<body>
<center>
    <h1>Service is temporarily unavailable</h1>
    The server is temporarily unable to service your request due to maintenance downtime or capacity problems. Please try again later.
</center>
</body>
</html>