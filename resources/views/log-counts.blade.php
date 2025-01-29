<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Counts</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        form {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        label {
            font-size: 16px;
            color: #555;
        }

        input[type="date"] {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 180px;
        }

        button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f1f1f1;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <h1>Log Counts</h1>
    <div class="container">
        <form method="GET" action="{{ route('log-counts') }}">
            <div>
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div>
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div>
                <button type="submit">Filter</button>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($counts as $type => $count)
                    <tr>
                        <td>{{ $type }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
