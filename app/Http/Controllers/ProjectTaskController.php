<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Auth;
use DB;
use Session;
use Redirect;
use Input;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProjectTask;

class ProjectTaskController extends Controller
{
    public function __construct(Request $request)
    {
        $this->page = 'TaskManagement.project-tasks.';
    }

    public function index(Request $request){
        $company_id = $request->input('m');
        $accType = Auth::user()->acc_type;
        $emp_id = Auth::user()->employee_id;
        $customer_id = Auth::user()->customer_id;
        CommonHelper::companyDatabaseConnection($company_id);
            $employeeList = DB::table('employee')->where('status',1)->get();
            if($accType == 'client'){
                $projectList = DB::table('company_projects')->where('status',1)->where('project_status',1)->get();
            }else if($accType == 'customer'){
                $projectList = DB::table('company_projects')->where('customer_id',$customer_id)->where('status',1)->where('project_status',1)->get();
            }else{
                $projectList = DB::table('assign_employee_project as aep')
                    ->join('company_projects as cp', 'cp.id', '=', 'aep.project_id')
                    ->select('cp.*')
                    ->where('aep.employee_id',$emp_id)
                    ->get();
            }
        CommonHelper::reconnectMasterDatabase();
        if($request->ajax()){
            $filterEmployeeId = $request->input('filterEmployeeId');
            $filterProjectId = $request->input('filterProjectId');
            $filterProjectTaskStatus = $request->input('filterProjectTaskStatus');
            $filterStatus = $request->input('filterStatus');
            $filterTaskType = $request->input('filterTaskType');
            $customerId = $request->input('customer_id');
            CommonHelper::companyDatabaseConnection($company_id);
            $projectTasks = DB::table('project_tasks as pt')
                ->select('pt.*','cp.project_name','cp.project_type','e.emp_name',
                DB::raw("
                        CASE 
                            WHEN pt.start_date IS NULL OR pt.end_date IS NULL THEN 0
                            WHEN pt.start_date = pt.end_date THEN 1
                            ELSE DATEDIFF(pt.end_date, pt.start_date) + 1
                        END AS num_days
                    "),
                    'twho.id as twho_id',
                    'twho.start_date as twho_start_date',
                    'twho.start_time as twho_start_time',
                    'twho.end_date as twho_end_date',
                    'twho.end_time as twho_end_time',
                    'twho.testing_date as twho_testing_date',
                    'twho.testing_time as twho_testing_time',
                    'twho.reassign_date as twho_reassign_date',
                    'twho.reassign_time as twho_reassign_time',
                    'twho.completion_date as twho_completion_date',
                    'twho.completion_time as twho_completion_time',
                    'twht.id as twht_id',
                    'twht.start_date as twht_start_date',
                    'twht.start_time as twht_start_time',
                    'twht.end_date as twht_end_date',
                    'twht.end_time as twht_end_time',
                    'twht.testing_date as twht_testing_date',
                    'twht.testing_time as twht_testing_time',
                    'twht.reassign_date as twht_reassign_date',
                    'twht.reassign_time as twht_reassign_time',
                    'twht.completion_date as twht_completion_date',
                    'twht.completion_time as twht_completion_time'
                )
                ->join('company_projects AS cp', 'pt.project_id','=', 'cp.id')
                ->join('employee AS e', 'pt.employee_id','=', 'e.id')
                ->leftJoin(DB::raw('(SELECT task_id, id, start_date, start_time, end_date, end_time, testing_date, testing_time, reassign_date, reassign_time, completion_date, completion_time, ROW_NUMBER() OVER (PARTITION BY task_id ORDER BY id ASC) AS rn FROM task_work_history) AS twho'), function($join) {
                    $join->on('pt.id', '=', 'twho.task_id')
                        ->where('twho.rn', '=', 1);
                })
                ->leftJoin(DB::raw('(SELECT task_id, id, start_date, start_time, end_date, end_time, testing_date, testing_time, reassign_date, reassign_time, completion_date, completion_time, ROW_NUMBER() OVER (PARTITION BY task_id ORDER BY id DESC) AS rn FROM task_work_history) AS twht'), function($join) {
                    $join->on('pt.id', '=', 'twht.task_id')
                        ->where('twht.rn', '=', 1);
                })
                ->when($filterEmployeeId != '', function ($q) use ($filterEmployeeId){
                    return $q->whereIn('pt.employee_id',$filterEmployeeId);
                })
                ->when($filterProjectId != '', function ($q) use ($filterProjectId){
                    return $q->whereIn('pt.project_id',$filterProjectId);
                })
                ->when($filterTaskType != '', function ($q) use ($filterTaskType){
                    return $q->whereIn('pt.task_type',$filterTaskType);
                })
                ->when($filterProjectTaskStatus != '', function ($q) use ($filterProjectTaskStatus){
                    return $q->whereIn('pt.task_status',$filterProjectTaskStatus);
                })
                ->when($filterStatus != '', function ($q) use ($filterStatus){
                    return $q->whereIn('pt.status',$filterStatus);
                })
                ->when($accType == 'user', function ($query) use ($emp_id) {
                    return $query->where('pt.employee_id', $emp_id);
                })
                ->when($accType == 'customer', function ($query) use ($customerId) {
                    return $query->whereIn('cp.customer_id', $customerId);
                })
                ->orderBy('pt.id', 'ASC')
                ->get();
            CommonHelper::reconnectMasterDatabase();
            return DataTables::of($projectTasks)
            ->addIndexColumn()
            ->addColumn('project_type', function ($row) {
                if($row->project_type == 2){
                    return 'International';
                }else {
                    return 'Local';
                }
            })
            ->addColumn('task_status', function ($row) {
                if($row->task_status == 2){
                    return '<button class="btn btn-xs btn-success">Completed</button>';
                }else if($row->task_status == 3){
                    return '<button class="btn btn-danger">Hold</button>';
                }else if($row->task_status == 4){
                    return '<button class="btn btn-info">Inprogress</button>';
                }else if($row->task_status == 5){
                    return '<button class="btn btn-primary">Testing</button>';
                }else if($row->task_status == 6){
                    return '<button class="btn btn-warning">Reassign</button>';
                }else {
                    return '<button class="btn btn-warning">Pending</button>';
                }
            })

            // ->addColumn('task_status', function ($row) {
            //     $statusOptions = [
            //         1 => ['label' => 'Pending', 'class' => 'text-dark'],
            //         2 => ['label' => 'Completed', 'class' => 'bg-success text-white'],
            //         3 => ['label' => 'Hold', 'class' => 'bg-danger text-white'],
            //         4 => ['label' => 'Inprogress', 'class' => 'bg-info text-white'],
            //         5 => ['label' => 'Testing', 'class' => 'bg-primary text-white'],
            //         6 => ['label' => 'Reassign', 'class' => 'bg-warning text-dark'],
            //     ];
            
            //     $statusClass = $statusOptions[$row->task_status]['class'];
            //     $dropdown = '<select class="form-control form-control-sm project-task-change-status '.$statusClass.'" 
            //                     style="min-width: 120px; width: 100%;" 
            //                     data-id="'.$row->id.'">';
            
            //     foreach ($statusOptions as $key => $option) {
            //         $selected = $row->task_status == $key ? 'selected' : '';
            //         $dropdown .= "<option value='$key' $selected>".$option['label']."</option>";
            //     }
            
            //     $dropdown .= '</select>';
            //     return $dropdown;
            // })

            ->addColumn('task_type', function ($row) {
                if($row->task_type == 2){
                    return '<button class="btn btn-danger">Urgent</button>';
                }else if($row->task_type == 3){
                    return '<button class="btn btn-warning">Average</button>';
                }else {
                    return '<button class="btn btn-primary">Normal</button>';
                }
            })
            ->addColumn('status', function ($row) {
                if($row->status == 2){
                    return 'Inactive';
                }else {
                    return 'Active';
                }
            })
            ->addColumn('total_days',function ($row){
                $data = '0';
                $start = Carbon::parse($row->twho_start_date);
                if($row->task_status == 2){
                    $completion = Carbon::parse($row->twht_completion_date);
                    $numDays = $completion->diffInDays($start);
                    $data = $numDays+1;
                }else if($row->task_status == 6){
                    $reassign = Carbon::parse($row->twht_reassign_date);
                    $numDays = $reassign->diffInDays($start);
                    $data = $numDays+1;
                }else if($row->task_status == 5){
                    $testing = Carbon::parse($row->twht_testing_date);
                    $numDays = $testing->diffInDays($start);
                    $data = $numDays+1;
                }else if($row->task_status == 3){
                    $hold = Carbon::parse($row->twht_end_date);
                    $numDays = $hold->diffInDays($start);
                    $data = $numDays+1;
                }else if($row->task_status == 4){
                    $inprogress = Carbon::parse(date('Y-m-d'));
                    $numDays = $inprogress->diffInDays($start);
                    $data = $numDays+1;
                }
                return $data;
            })
            ->addColumn('action',function ($row){
                $data = '<td class="text-center hidden-print" id="hide-table-row">
                <i class="fas fa-solid fa-file" onclick="showDetailModelOneParamerter(\'tms/project-tasks/viewProjectTaskDetail\', ' . $row->id . ', \'View Project Task Detail\')"></i></td>';
                return $data;
            })
            ->rawColumns(['project_type','task_status','task_type','status','action','total_days'])
            ->make(true);
        }
        return view($this->page.'index',compact('employeeList','projectList'));
    }

    public function create(Request $request)
    {
        $accType = Auth::user()->acc_type;
        $currentEmpId = Auth::user()->employee_id;
        $currentCustomerId = Auth::user()->customer_id;
        $m = $request->get('m');
        CommonHelper::companyDatabaseConnection($m);
        $employeeList = DB::table('employee')->where('status',1)->get();
        if($accType == 'client'){
            $projectList = DB::table('company_projects')->where('status',1)->where('project_status',1)->get();
        }else if($accType == 'customer'){
            $projectList = DB::table('company_projects')->where('customer_id',$currentCustomerId)->where('status',1)->where('project_status',1)->get();
        }else{
            $projectList = DB::table('company_projects as cp')
                ->join('assign_employee_project as aep', 'cp.id', '=', 'aep.project_id')
                ->where('cp.status', 1)
                ->where('cp.project_status', 1)
                ->where('aep.employee_id',$currentEmpId)
                ->select('cp.*')
                ->get();
        }
        CommonHelper::reconnectMasterDatabase();
        return view($this->page.'create',compact('projectList','employeeList'));
    }

    public function store(Request $request)
    {
        
        // Create an associative array with data for insertion
        $data = array(
            'company_id' => $request->input('company_id'), // Correct the key name here
            'project_id' => $request->input('project_id'),
            'employee_id' => $request->input('employee_id'),
            'task_name' => $request->input('task_name'),
            'description' => $request->input('description'),
            'no_of_working_days' => $request->input('no_of_working_days'),
            'task_type' => $request->input('task_type'),
            'status' => 1, // Ensure this matches your database constraints
            'task_status' => 1, // Ensure this matches your database constraints
            'user_id' => Auth::user()->id,
            'created_date' => date('Y-m-d'),
            'created_by' => Auth::user()->name
        );
        CommonHelper::companyDatabaseConnection($request->input('company_id'));
        // Insert data into the 'company_projects' table
        $projectTaskId = DB::table('project_tasks')->insertGetId($data);
        $getProjectTaskDetail = CommonHelper::getSingleProjectTaskDetail($projectTaskId,$request->input('company_id'));
        // Prepare email data
        
        $array = [
            'view' => $this->page.'email', // Ensure this view exists
            'subject' => 'Add New Project Task',
            'from' => env('MAIL_FROM_ADDRESS'),
            'project_task_detail' => $getProjectTaskDetail,
            'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
        ];
        if (filter_var($getProjectTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
            $array['cc'] = [$getProjectTaskDetail->email]; // Add CC email addresses here
        }
        
    
        // Send email
        CommonHelper::reconnectMasterDatabase();
        Mail::to($getProjectTaskDetail->official_email)->queue(new ProjectTask($array));
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('tms/project-tasks?m='.$request->input('company_id'));

    }

    public function show(string $id)
    {
        
    }

    public function edit(string $id)
    {
        return view($this->page.'edit');
    }

    public function update(Request $request)
     {
         
 
     }

    public function status(Request $request)
    {
        
    }

    public function viewProjectTaskDetail(Request $request){
        $id = $request->input('id');
        $m = $request->input('m');
        CommonHelper::companyDatabaseConnection($m);
            $taskDetail = DB::table('project_tasks as pt')
                ->select('pt.*', 'cp.project_name','cp.project_type', 'e.emp_name','c.customer_name')
                ->join('company_projects as cp', 'pt.project_id', '=', 'cp.id')
                ->join('employee as e', 'pt.employee_id', '=', 'e.id')
                ->join('customers as c','cp.customer_id','=','c.id')
                ->where('pt.id',$id)
                ->first();
            $taskWorkHistoryLast = DB::table('task_work_history')
                ->where('task_id',$id)
                ->orderBy('id', 'desc')
                ->first();
            $countPendingSubTasks = DB::table('project_sub_tasks')
                ->whereIn('sub_task_status', [1, 3, 4, 5, 6])
                ->where('task_id', $id)
                ->count();
        CommonHelper::reconnectMasterDatabase();
        return view($this->page.'viewProjectTaskDetail',compact('taskDetail','taskWorkHistoryLast','countPendingSubTasks'));
    }

    public function updateDetailAgainstTask(Request $request){
        $m = $request->input('m');
        $taskStartDate = $request->input('taskStartDate');
        $taskEndDate = $request->input('taskEndDate');
        $id = $request->input('id');
        $remarksInput = $request->input('remarks');
        $emp_id = Auth::user()->employee_id; 
        CommonHelper::companyDatabaseConnection($m);
            DB::table('project_tasks')->where('id',$id)->update([
                'start_date' => $taskStartDate,
                'end_date' => $taskEndDate,
                'update_created_date' => date('Y-m-d'),
                'update_emp_id' => $emp_id,
                'remarks' => json_encode(['Update Task Detail' => ($remarksInput ?? '-') . ' - ' . date('Y-m-d')]),
            ]);
            $getProjectTaskDetail = CommonHelper::getSingleProjectTaskDetail($id,$m);
            // Prepare email data
            
            $array = [
                'view' => $this->page.'email', // Ensure this view exists
                'subject' => 'Attend Project Task',
                'from' => env('MAIL_FROM_ADDRESS'),
                'project_task_detail' => $getProjectTaskDetail,
                'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
            ];
            if (filter_var($getProjectTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
                $array['cc'] = [$getProjectTaskDetail->email]; // Add CC email addresses here
            }
            
        
            // Send email
        CommonHelper::reconnectMasterDatabase();
        Mail::to($getProjectTaskDetail->official_email)->queue(new ProjectTask($array));
        echo 'Done';
    }

    public function taskStart(Request $request){
        $m = $request->input('m');
        $id = $request->input('id');
        $empId = Auth::user()->employee_id;
        $remarksInput = $request->input('remarks');
        $data = array(
            'emp_id' => $empId,
            'task_id' => $id,
            'start_date' => date('Y-m-d'),
            'start_time' => date('H:i:s'),
            'status' => 1, // Ensure this matches your database constraints
            'user_id' => Auth::user()->id,
            'created_date' => date('Y-m-d'),
            'created_by' => Auth::user()->name
        );
        CommonHelper::companyDatabaseConnection($m);
            $projectTaskDetail = DB::table('project_tasks')->where('id',$id)->first();
            $oldRemarks = $projectTaskDetail->remarks;
            // Decode to associative array
            $remarksData = json_decode($oldRemarks, true);
            // Add new key-value pair
            $remarksData['Start Task'] = ($remarksInput ?? '-') . ' - ' . date('Y-m-d');
            // Encode back to JSON
            $updatedRemarksJson = json_encode($remarksData);

            DB::table('task_work_history')->insert($data);
            DB::table('project_tasks')->where('id',$id)->update(['task_status' => 4,'remarks' => $updatedRemarksJson]);
            $getProjectTaskDetail = CommonHelper::getSingleProjectTaskDetail($id,$m);
            // Prepare email data
            
            $array = [
                'view' => $this->page.'email', // Ensure this view exists
                'subject' => 'Start Project Task',
                'from' => env('MAIL_FROM_ADDRESS'),
                'project_task_detail' => $getProjectTaskDetail,
                'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
            ];
            if (filter_var($getProjectTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
                $array['cc'] = [$getProjectTaskDetail->email]; // Add CC email addresses here
            }
            
        
            // Send email
        CommonHelper::reconnectMasterDatabase();
        Mail::to($getProjectTaskDetail->official_email)->queue(new ProjectTask($array));
        echo 'Done';
    }

    public function taskStop(Request $request){
        $m = $request->input('m');
        $id = $request->input('id');
        $taskWorkHistoryLastId = $request->input('taskWorkHistoryLastId');
        $empId = Auth::user()->employee_id;
        $remarksInput = $request->input('remarks');
        $data = array(
            'end_date' => date('Y-m-d'),
            'end_time' => date('H:i:s')
        );
        CommonHelper::companyDatabaseConnection($m);
            $projectTaskDetail = DB::table('project_tasks')->where('id',$id)->first();
            $oldRemarks = $projectTaskDetail->remarks;
            // Decode to associative array
            $remarksData = json_decode($oldRemarks, true);
            // Add new key-value pair
            $remarksData['Stop Task'] = ($remarksInput ?? '-') . ' - ' . date('Y-m-d');
            // Encode back to JSON
            $updatedRemarksJson = json_encode($remarksData);
            DB::table('task_work_history')->where('id',$taskWorkHistoryLastId)->update($data);
            DB::table('project_tasks')->where('id',$id)->update(['task_status' => 3,'remarks' => $updatedRemarksJson]);
            $getProjectTaskDetail = CommonHelper::getSingleProjectTaskDetail($id,$m);
            // Prepare email data
            
            $array = [
                'view' => $this->page.'email', // Ensure this view exists
                'subject' => 'Stop Project Task',
                'from' => env('MAIL_FROM_ADDRESS'),
                'project_task_detail' => $getProjectTaskDetail,
                'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
            ];
            if (filter_var($getProjectTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
                $array['cc'] = [$getProjectTaskDetail->email]; // Add CC email addresses here
            }
            
        
            // Send email
        CommonHelper::reconnectMasterDatabase();
        Mail::to($getProjectTaskDetail->official_email)->queue(new ProjectTask($array));
        echo 'Done';
    }

    public function taskSendTesting(Request $request){
        $m = $request->input('m');
        $id = $request->input('id');
        $taskWorkHistoryLastId = $request->input('taskWorkHistoryLastId');
        $empId = Auth::user()->employee_id;
        $userId = Auth::user()->id;
        $remarksInput = $request->input('remarks');
        $data = array(
            'testing_date' => date('Y-m-d'),
            'testing_time' => date('H:i:s'),
            'testing_user_id' => $userId
        );
        CommonHelper::companyDatabaseConnection($m);
            $projectTaskDetail = DB::table('project_tasks')->where('id',$id)->first();
            $oldRemarks = $projectTaskDetail->remarks;
            // Decode to associative array
            $remarksData = json_decode($oldRemarks, true);
            // Add new key-value pair
            $remarksData['Send Testing'] = ($remarksInput ?? '-') . ' - ' . date('Y-m-d');
            // Encode back to JSON
            $updatedRemarksJson = json_encode($remarksData);

            DB::table('task_work_history')->where('id',$taskWorkHistoryLastId)->update($data);
            DB::table('project_tasks')->where('id',$id)->update(['task_status' => 5,'remarks' => $updatedRemarksJson]);
            $getProjectTaskDetail = CommonHelper::getSingleProjectTaskDetail($id,$m);
            // Prepare email data
            
            $array = [
                'view' => $this->page.'email', // Ensure this view exists
                'subject' => 'Testing Project Task',
                'from' => env('MAIL_FROM_ADDRESS'),
                'project_task_detail' => $getProjectTaskDetail,
                'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
            ];
            if (filter_var($getProjectTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
                $array['cc'] = [$getProjectTaskDetail->email]; // Add CC email addresses here
            }
            
        
            // Send email
        CommonHelper::reconnectMasterDatabase();
        Mail::to($getProjectTaskDetail->official_email)->queue(new ProjectTask($array));
        echo 'Done';
    }

    public function taskCompleted(Request $request){
        $m = $request->input('m');
        $id = $request->input('id');
        $taskWorkHistoryLastId = $request->input('taskWorkHistoryLastId');
        $empId = Auth::user()->employee_id;
        $userId = Auth::user()->id;
        $remarksInput = $request->input('remarks');
        $data = array(
            'completion_date' => date('Y-m-d'),
            'completion_time' => date('H:i:s'),
            'completion_user_id' => $userId
        );
        CommonHelper::companyDatabaseConnection($m);

            $projectTaskDetail = DB::table('project_tasks')->where('id',$id)->first();
            $oldRemarks = $projectTaskDetail->remarks;
            // Decode to associative array
            $remarksData = json_decode($oldRemarks, true);
            // Add new key-value pair
            $remarksData['Complete Task'] = ($remarksInput ?? '-') . ' - ' . date('Y-m-d');
            // Encode back to JSON
            $updatedRemarksJson = json_encode($remarksData);

            DB::table('task_work_history')->where('id',$taskWorkHistoryLastId)->update($data);
            DB::table('project_tasks')->where('id',$id)->update(['task_status' => 2,'remarks' => $updatedRemarksJson]);
            $getProjectTaskDetail = CommonHelper::getSingleProjectTaskDetail($id,$m);
            // Prepare email data
            
            $array = [
                'view' => $this->page.'email', // Ensure this view exists
                'subject' => 'Completed Project Task',
                'from' => env('MAIL_FROM_ADDRESS'),
                'project_task_detail' => $getProjectTaskDetail,
                'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
            ];
            if (filter_var($getProjectTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
                $array['cc'] = [$getProjectTaskDetail->email]; // Add CC email addresses here
            }
            
        
            // Send email
        CommonHelper::reconnectMasterDatabase();
        Mail::to($getProjectTaskDetail->official_email)->queue(new ProjectTask($array));
        echo 'Done';
    }

    public function taskReassign(Request $request){
        $m = $request->input('m');
        $id = $request->input('id');
        $taskWorkHistoryLastId = $request->input('taskWorkHistoryLastId');
        $empId = Auth::user()->employee_id;
        $userId = Auth::user()->id;
        $remarksInput = $request->input('remarks');
        $data = array(
            'reassign_date' => date('Y-m-d'),
            'reassign_time' => date('H:i:s'),
            'reassign_user_id' => $userId
        );
        CommonHelper::companyDatabaseConnection($m);

            $projectTaskDetail = DB::table('project_tasks')->where('id',$id)->first();
            $oldRemarks = $projectTaskDetail->remarks;
            // Decode to associative array
            $remarksData = json_decode($oldRemarks, true);
            // Add new key-value pair
            $remarksData['Reassign Task'] = ($remarksInput ?? '-') . ' - ' . date('Y-m-d');
            // Encode back to JSON
            $updatedRemarksJson = json_encode($remarksData);

            DB::table('task_work_history')->where('id',$taskWorkHistoryLastId)->update($data);
            DB::table('project_tasks')->where('id',$id)->update(['task_status' => 6,'remarks' => $updatedRemarksJson]);
            $getProjectTaskDetail = CommonHelper::getSingleProjectTaskDetail($id,$m);
            // Prepare email data
            
            $array = [
                'view' => $this->page.'email', // Ensure this view exists
                'subject' => 'Reassign Project Task',
                'from' => env('MAIL_FROM_ADDRESS'),
                'project_task_detail' => $getProjectTaskDetail,
                'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
            ];
            if (filter_var($getProjectTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
                $array['cc'] = [$getProjectTaskDetail->email]; // Add CC email addresses here
            }
            
        
            // Send email
        CommonHelper::reconnectMasterDatabase();
        Mail::to($getProjectTaskDetail->official_email)->queue(new ProjectTask($array));
        echo 'Done';
    }

    function loadCommentAgainstTask(Request $request){
        $m = $request->input('m');
        $taskId = $request->input('taskId');
        CommonHelper::companyDatabaseConnection($m);
            $getCommentDetail = DB::table('task_comments as c1')
                ->leftJoin('task_comments as c2', 'c1.id', '=', 'c2.parent_id')
                ->leftJoin('employee as e', 'c1.emp_id', '=', 'e.id')
                ->leftJoin('employee as e2', 'c2.emp_id', '=', 'e2.id')

                ->leftJoin('customers as cus', 'c1.customer_id', '=', 'cus.id')
                ->leftJoin('customers as cus2', 'c2.customer_id', '=', 'cus2.id')
                ->select(
                    'c1.id as comment_id',
                    'e.emp_name as comment_emp_name',
                    'cus.customer_name as comment_customer_name',
                    'c1.task_id as comment_task_id',
                    'c1.parent_id as comment_parent_id',
                    'c1.body as comment_body',
                    'c1.created_date as comment_created_date',
                    'c1.created_time as comment_created_time',
                    'c1.created_by as comment_created_by',
                    'c2.id as reply_id',
                    'e2.emp_name as reply_emp_name',
                    'cus2.customer_name as reply_customer_name',
                    'c2.body as reply_body',
                    'c2.created_date as reply_created_date',
                    'c2.created_time as reply_created_time',
                    'c2.created_by as reply_created_by'
                )
                ->where('c1.task_id',$taskId)
                ->where('c1.parent_id','=',0)
                ->get();
        CommonHelper::reconnectMasterDatabase();
        return view($this->page.'loadCommentAgainstTask',compact('getCommentDetail'));
    }


    public function projectTaskComment(Request $request)
    {
        $m = $request->input('m');
        $taskId = $request->input('taskId');
        $commentBody = $request->input('commentBody');
        $empId = Auth::user()->employee_id;
        $customerId = Auth::user()->customer_id;
        $createdBy = Auth::user()->name;
        $createdDate = date('Y-m-d');
        $createdTime = date('H:i:s');
        $accType = Auth::user()->acc_type;

        CommonHelper::companyDatabaseConnection($m);

        // Insert comment
        $commentId = DB::table('task_comments')->insertGetId([
            'task_id' => $taskId,
            'parent_id' => 0,
            'body' => $commentBody,
            'status' => 1,
            'created_date' => $createdDate,
            'created_time' => $createdTime,
            'created_by' => $createdBy,
            $accType === 'customer' ? 'customer_id' : 'emp_id' => $accType === 'customer' ? $customerId : $empId
        ]);

        // Handle multiple attachments
        if ($request->hasFile('attachment')) {
            foreach ($request->file('attachment') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('task_comments', 'public'); // Store in storage/app/public/task_comments
                    DB::table('task_comment_attachments')->insert([
                        'task_comment_id' => $commentId,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        }

        CommonHelper::reconnectMasterDatabase();
        echo 'Done';
    }

    public function projectTaskReplyAgainstComment(Request $request){
        $m = $request->input('m');
        $taskId = $request->input('taskId');
        $replyBody = $request->input('replyBody');
        $id = $request->input('id');
        $empId = Auth::user()->employee_id;
        $customerId = Auth::user()->customer_id;
        $createdBy = Auth::user()->name;
        $createdDate = date('Y-m-d');
        $createdTime = date('H:i:s');
        $accType = Auth::user()->acc_type;
        CommonHelper::companyDatabaseConnection($m);
           $data = array(
                'task_id' => $taskId,
                'parent_id' => $id,
                'body' => $replyBody,
                'status' => 1,
                'created_date' => $createdDate,
                'created_time' => $createdTime,
                'created_by' => $createdBy
            );
            if($accType == 'customer'){
                $data['customer_id'] = $customerId;
            }else{
                $data['emp_id'] = $empId;
            }
            DB::table('task_comments')->insert($data);
        CommonHelper::reconnectMasterDatabase();
        echo 'Done';
    }
}
