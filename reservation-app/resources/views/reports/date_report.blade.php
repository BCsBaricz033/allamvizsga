
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Date Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Date Report</h2>
    <table>
        <thead>
            <tr>
                <th>Institution</th>
                <th>Section</th>
                <th>Doctor</th>
                <th>Patient</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dates as $date)
                <tr>
                    <td>{{ $date['institution_name'] }}</td>
                    <td>{{ $date['section_name'] }}</td>
                    <td>{{ $date['doctor_name'] }}</td>
                    <td>{{ $date['patient_name'] }}</td>
                    <td>{{ $date['start_time'] }}</td>
                    <td>{{ $date['end_time'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
