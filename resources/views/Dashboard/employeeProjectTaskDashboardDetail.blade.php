@php
    $counter = 1;
    use Carbon\Carbon;
@endphp
<table class="table table-sm mb-0 table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th class="text-center">S.No</th>
            <th class="text-center">Project Name</th>
            <th class="text-center">Project Type</th>
            <th class="text-center">Employee Name</th>
            <th class="text-center">Task Name</th>
            <th class="text-center">Description</th>
            <th class="text-center">No of Working Days</th>
            <th class="text-center">Task Type</th>
            <th class="text-center">Task Status</th>
            <th class="text-center">Estimated Days</th>
            <th class="text-center">Total Days</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @if(count($projectTasks) != 0)
            @foreach($projectTasks as $ptRow)
                @php
                    $data = '0';
                    $start = Carbon::parse($ptRow->twho_start_date);

                    switch ($ptRow->task_status) {
                        case 2:
                            $completion = Carbon::parse($ptRow->twht_completion_date);
                            $numDays = $completion->diffInDays($start);
                            $data = $numDays + 1;
                            break;
                        case 6:
                            $reassign = Carbon::parse($ptRow->twht_reassign_date);
                            $numDays = $reassign->diffInDays($start);
                            $data = $numDays + 1;
                            break;
                        case 5:
                            $testing = Carbon::parse($ptRow->twht_testing_date);
                            $numDays = $testing->diffInDays($start);
                            $data = $numDays + 1;
                            break;
                        case 3:
                            $hold = Carbon::parse($ptRow->twht_end_date);
                            $numDays = $hold->diffInDays($start);
                            $data = $numDays + 1;
                            break;
                        case 4:
                            $inprogress = Carbon::parse(date('Y-m-d'));
                            $numDays = $inprogress->diffInDays($start);
                            $data = $numDays + 1;
                            break;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{$counter++}}</td>
                    <td>{{$ptRow->project_name}}</td>
                    <td class="text-center">
                        @if ($ptRow->project_type == 2)
                            International
                        @else
                            Local
                        @endif
                    </td>
                    <td>{{$ptRow->emp_name}}</td>
                    <td>{{$ptRow->task_name}}</td>
                    <td>{{$ptRow->description}}</td>
                    <td class="text-center">{{$ptRow->no_of_working_days}}</td>
                    <td class="text-center">{{ $ptRow->task_type == 2 ? 'Urgent' : ($ptRow->task_type == 3 ? 'Average' : 'Normal') }}</td>
                    <td class="text-center">{{ 
                        $ptRow->task_status == 2 ? 'Completed' :
                        ($ptRow->task_status == 3 ? 'Hold' :
                        ($ptRow->task_status == 4 ? 'Inprogress' :
                        ($ptRow->task_status == 5 ? 'Testing' :
                        ($ptRow->task_status == 6 ? 'Reassign' : 'Pending'))))
                    }}</td>
                    <td class="text-center">{{$ptRow->num_days}}</td>
                    <td class="text-center">{{$data}}</td>
                    <td class="text-center">{{ $ptRow->status == 2 ? 'Inactive' : 'Active' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <th colspan="12" class="text-center">No Record Found....</th>
            </tr>
        @endif
    </tbody>
</table>