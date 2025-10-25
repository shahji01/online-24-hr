<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Sub Task Notification - {{ env('APP_NAME') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #dddddd;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <p>Hi, {{ $project_sub_task_detail->emp_name }}!</p>
        <p>{{$subject}}</p>
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center">Project Name</th>
                    <th class="text-center">Project Type</th>
                    <th class="text-center">Task Name</th>
                    <th class="text-center">Sub Task Name</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Task Type</th>
                    <th class="text-center">Task Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $project_sub_task_detail->project_name }}</td>
                    <td class="text-center">
                        @if ($project_sub_task_detail->project_type == 1) 
                            Local 
                        @else 
                            International 
                        @endif
                    </td>
                    <td>{{ $project_sub_task_detail->task_name }}</td>
                    <td>{{ $project_sub_task_detail->sub_task_name }}</td>
                    <td>{{ $project_sub_task_detail->description }}</td>
                    <td class="text-center">
                        @if ($project_sub_task_detail->sub_task_type == 1) 
                            Normal 
                        @elseif($project_sub_task_detail->sub_task_type == 2) 
                            Urgent 
                        @else 
                            Average 
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($project_sub_task_detail->sub_task_status == 1) 
                            Pending 
                        @elseif ($project_sub_task_detail->sub_task_status == 2) 
                            Completed 
                        @elseif ($project_sub_task_detail->sub_task_status == 3) 
                            Hold 
                        @elseif ($project_sub_task_detail->sub_task_status == 4) 
                            In Progress  
                        @else 
                            In Progress 
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <p>Thank You,</p>
        <p>{{ env('APP_NAME') }}</p>
    </div>
</body>

</html>
