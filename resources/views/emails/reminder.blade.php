<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Reminder</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito|Roboto&display=swap" rel="stylesheet">
    <style>
        #projects {
          font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
          border-collapse: collapse;
          width: 100%;
        }

        #projects td, #projects th {
          border: 1px solid #ddd;
          padding: 8px;
        }

        #projects tr:nth-child(even){background-color: #f2f2f2;}

        #projects tr:hover {background-color: #ddd;}

        #projects th {
          padding-top: 12px;
          padding-bottom: 12px;
          text-align: left;
          background-color: #6777EF;
          color: white;
        }
    </style>
</head>
<body>
    <h1 align="center">Reminder Deadline</h1>
    <p>Pagi {{ $user->name }}, semoga sehat selalu, berdoa dulu sebelum mengerjakan projectmu dan jangan lupa bersyukur</p>

    <div id="projects">
        <h3>Ini daftar Pekerjaanmu yang sedang mendekati deadline</h3>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Client</th>
                    <th>Project Progress</th>
                    <th>Deadline</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->title }}</td>
                        <td>{{ $project->client->name }}</td>
                        <td>{{ $project->projectStatus->name }}</td>
                        <td>{{ $project->deadline }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h3>Ini daftar Pekerjaanmu yang sudah melewati deadline</h3>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Client</th>
                    <th>Project Progress</th>
                    <th>Deadline</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr>
                        <td>{{ $project->title }}</td>
                        <td>{{ $project->client->name }}</td>
                        <td>{{ $project->projectStatus->name }}</td>
                        <td>{{ $project->deadline }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>Semoga kamu bisa deliver pekerjaan ke client tepat waktu :D</p>
    </div>
</body>
</html>
