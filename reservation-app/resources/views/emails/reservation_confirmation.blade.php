
<!DOCTYPE html>
<html>
<head>
    <title>Reservation Confirmation</title>
</head>
<body>
    <h1>Dear {{ $name }},</h1>
    <p>Thank you for your reservation. Here are the details:</p>
    <ul>
        <li>Institution: {{ $date['institution'] }}</li>
        <li>Section: {{ $date['section'] }}</li>
        <li>Start Time: {{ $date['start_time'] }}</li>
        <li>End Time: {{ $date['end_time'] }}</li>
    </ul>
    <p>We look forward to seeing you.</p>
</body>
</html>
