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
            <th class="text-center">Sub Task Name</th>
            <th class="text-center">Description</th>
            <th class="text-center">Task Status</th>
            <th class="text-center">Estimated Days</th>
            <th class="text-center">Total Days</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
        @if(count($projectSubTasks) != 0)
            @foreach($projectSubTasks as $pstRow)
                @php
                    $data = '0';
                    $start = Carbon::parse($pstRow->stwho_start_date);

                    switch ($pstRow->sub_task_status) {
                        case 2:
                            $completion = Carbon::parse($pstRow->stwht_completion_date);
                            $numDays = $completion->diffInDays($start);
                            $data = $numDays + 1;
                            break;
                        case 6:
                            $reassign = Carbon::parse($pstRow->stwht_reassign_date);
                            $numDays = $reassign->diffInDays($start);
                            $data = $numDays + 1;
                            break;
                        case 5:
                            $testing = Carbon::parse($pstRow->stwht_testing_date);
                            $numDays = $testing->diffInDays($start);
                            $data = $numDays + 1;
                            break;
                        case 3:
                            $hold = Carbon::parse($pstRow->stwht_end_date);
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
                    <td>{{$pstRow->project_name}}</td>
                    <td class="text-center">{{ $pstRow->project_type == 2 ? 'International' : 'Local' }}</td>
                    <td>{{$pstRow->emp_name}}</td>
                    <td>{{$pstRow->task_name}}</td>
                    <td>{{$pstRow->sub_task_name}}</td>
                    <td>{{$pstRow->description}}</td>
                    <td class="text-center">{{ 
                        $pstRow->sub_task_status == 2 ? 'Completed' :
                        ($pstRow->sub_task_status == 3 ? 'Hold' :
                        ($pstRow->sub_task_status == 4 ? 'Inprogress' :
                        ($pstRow->sub_task_status == 5 ? 'Testing' :
                        ($pstRow->sub_task_status == 6 ? 'Reassign' : 'Pending'))))
                    }}</td>
                    <td class="text-center">{{$pstRow->num_days}}</td>
                    <td class="text-center">{{$data}}</td>
                    <td class="text-center">{{ $pstRow->status == 2 ? 'Inactive' : 'Active' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <th colspan="12" class="text-center">No Record Found....</th>
            </tr>
        @endif
    </tbody>
</table>