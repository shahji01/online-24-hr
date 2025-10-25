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
use App\Mail\ProjectSubTask;

class ProjectSubTaskController extends Controller
{
    public function __construct(Request $request)
    {
        $this->page = 'TaskManagement.project-sub-tasks.';
    }

    public function index(Request $request){
        $company_id = $request->input('m');
        $accType = Auth::user()->acc_type;
        $emp_id = Auth::user()->employee_id;
        $userId = Auth::user()->id;
        $customerId = Auth::user()->customer_id;
        CommonHelper::companyDatabaseConnection($company_id);
            $employeeList = DB::table('employee')->get();
            if($accType == 'client'){
                $projectList = DB::table('company_projects')->where('status',1)->where('project_status',1)->get();
            }else if($accType == 'customer'){
                $projectList = DB::table('company_projects')->where('status',1)->where('customer_id',$customerId)->where('project_status',1)->get();
            }else{
                $projectList = DB::table('project_sub_tasks as pst')
                    ->join('company_projects as cp', 'cp.id', '=', 'pst.project_id')
                    ->select('cp.*')
                    ->where(function($query) use ($emp_id, $userId) {
                        $query->where('pst.employee_id', $emp_id)
                              ->orWhere('pst.user_id', $userId);
                    })
                    ->get();
            }
            if($accType == 'client'){
                $projectTaskList = DB::table('project_tasks')->get();
            }else if($accType == 'customer'){
                $projectTaskList = DB::table('project_sub_tasks as pst')
                    ->join('company_projects as cp', 'cp.id', '=', 'pst.project_id')
                    ->join('project_tasks as pt', 'pt.id', '=', 'pst.task_id')
                    ->select('pt.*')
                    ->where(function($query) use ($customerId, $userId) {
                        $query->where('cp.customer_id', $customerId);
                    })
                    ->get();
            }else{
                $projectTaskList = DB::table('project_sub_tasks as pst')
                    ->join('company_projects as cp', 'cp.id', '=', 'pst.project_id')
                    ->join('project_tasks as pt', 'pt.id', '=', 'pst.task_id')
                    ->select('pt.*')
                    ->where(function($query) use ($emp_id, $userId) {
                        $query->where('pst.employee_id', $emp_id)
                              ->orWhere('pst.user_id', $userId);
                    })
                    ->get();
            }
        CommonHelper::reconnectMasterDatabase();
        if($request->ajax()){
            $filterEmployeeId = $request->input('filterEmployeeId');
            $filterProjectId = $request->input('filterProjectId');
            $filterTaskId = $request->input('filterTaskId');
            $filterSubTaskStatus = $request->input('filterSubTaskStatus');
            $filterStatus = $request->input('filterStatus');
            $customerId = $request->input('customer_id');
            CommonHelper::companyDatabaseConnection($company_id);
            $projectSubTasks = DB::table('project_sub_tasks as pst')
                ->select(
                    'pst.*',
                    'cp.project_type',
                    'cp.project_name',
                    'e.emp_name',
                    'pt.task_name',
                    DB::raw("
                        CASE 
                            WHEN pst.start_date IS NULL OR pst.end_date IS NULL THEN 0
                            WHEN pst.start_date = pst.end_date THEN 1
                            ELSE DATEDIFF(pst.end_date, pst.start_date) + 1
                        END AS num_days
                    "),
                    'stwho.id as stwho_id',
                    'stwho.start_date as stwho_start_date',
                    'stwho.start_time as stwho_start_time',
                    'stwho.end_date as stwho_end_date',
                    'stwho.end_time as stwho_end_time',
                    'stwho.testing_date as stwho_testing_date',
                    'stwho.testing_time as stwho_testing_time',
                    'stwho.reassign_date as stwho_reassign_date',
                    'stwho.reassign_time as stwho_reassign_time',
                    'stwho.completion_date as stwho_completion_date',
                    'stwho.completion_time as stwho_completion_time',
                    'stwht.id as stwht_id',
                    'stwht.start_date as stwht_start_date',
                    'stwht.start_time as stwht_start_time',
                    'stwht.end_date as stwht_end_date',
                    'stwht.end_time as stwht_end_time',
                    'stwht.testing_date as stwht_testing_date',
                    'stwht.testing_time as stwht_testing_time',
                    'stwht.reassign_date as stwht_reassign_date',
                    'stwht.reassign_time as stwht_reassign_time',
                    'stwht.completion_date as stwht_completion_date',
                    'stwht.completion_time as stwht_completion_time'
                )
                ->join('project_tasks as pt', 'pst.task_id', '=', 'pt.id')
                ->join('company_projects as cp', 'pt.project_id', '=', 'cp.id')
                ->join('employee as e', 'pt.employee_id', '=', 'e.id')
                ->leftJoin(DB::raw('(SELECT sub_task_id, id, start_date, start_time, end_date, end_time, testing_date, testing_time, reassign_date, reassign_time, completion_date, completion_time, ROW_NUMBER() OVER (PARTITION BY sub_task_id ORDER BY id ASC) AS rn FROM sub_task_work_history) AS stwho'), function($join) {
                    $join->on('pst.id', '=', 'stwho.sub_task_id')
                        ->where('stwho.rn', '=', 1);
                })
                ->leftJoin(DB::raw('(SELECT sub_task_id, id, start_date, start_time, end_date, end_time, testing_date, testing_time, reassign_date, reassign_time, completion_date, completion_time, ROW_NUMBER() OVER (PARTITION BY sub_task_id ORDER BY id DESC) AS rn FROM sub_task_work_history) AS stwht'), function($join) {
                    $join->on('pst.id', '=', 'stwht.sub_task_id')
                        ->where('stwht.rn', '=', 1);
                })
                ->when($filterEmployeeId != '', function ($q) use ($filterEmployeeId){
                    return $q->where('pst.employee_id','=',$filterEmployeeId);
                })
                ->when($filterProjectId != '', function ($q) use ($filterProjectId){
                    return $q->where('pst.project_id','=',$filterProjectId);
                })
                ->when($filterTaskId != '', function ($q) use ($filterTaskId){
                    return $q->where('pst.task_id','=',$filterTaskId);
                })
                ->when($filterSubTaskStatus != '', function ($q) use ($filterSubTaskStatus){
                    return $q->where('pst.sub_task_status','=',$filterSubTaskStatus);
                })
                ->when($filterStatus != '', function ($q) use ($filterStatus){
                    return $q->where('pst.status','=',$filterStatus);
                })
                ->when($accType == 'user', function ($query) use ($emp_id,$userId) {
                    return $query->where('pst.employee_id', $emp_id)
                              ->orWhere('pst.user_id', $userId);
                })
                ->when($accType == 'customer', function ($query) use ($customerId) {
                    return $query->where('cp.customer_id', $customerId);
                })
                ->orderBy('pst.id', 'ASC')
                ->get();
            CommonHelper::reconnectMasterDatabase();
            return DataTables::of($projectSubTasks)
            ->addIndexColumn()
            ->addColumn('project_type', function ($row) {
                if($row->project_type == 2){
                    return 'International';
                }else {
                    return 'Local';
                }
            })
            ->addColumn('sub_task_status', function ($row) {
                if($row->sub_task_status == 2){
                    return '<button class="btn btn-xs btn-success">Completed</button>';
                }else if($row->sub_task_status == 3){
                    return '<button class="btn btn-danger">Hold</button>';
                }else if($row->sub_task_status == 4){
                    return '<button class="btn btn-info">Inprogress</button>';
                }else if($row->sub_task_status == 5){
                    return '<button class="btn btn-primary">Testing</button>';
                }else if($row->sub_task_status == 6){
                    return '<button class="btn btn-warning">Reassign</button>';
                }else {
                    return '<button class="btn btn-warning">Pending</button>';
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
                $start = Carbon::parse($row->stwho_start_date);
                if($row->sub_task_status == 2){
                    $completion = Carbon::parse($row->stwht_completion_date);
                    $numDays = $completion->diffInDays($start);
                    $data = $numDays+1;
                }else if($row->sub_task_status == 6){
                    $reassign = Carbon::parse($row->stwht_reassign_date);
                    $numDays = $reassign->diffInDays($start);
                    $data = $numDays+1;
                }else if($row->sub_task_status == 5){
                    $testing = Carbon::parse($row->stwht_testing_date);
                    $numDays = $testing->diffInDays($start);
                    $data = $numDays+1;
                }else if($row->sub_task_status == 3){
                    $hold = Carbon::parse($row->stwht_end_date);
                    $numDays = $hold->diffInDays($start);
                    $data = $numDays+1;
                }else if($row->sub_task_status == 4){
                    $inprogress = Carbon::parse(date('Y-m-d'));
                    $numDays = $inprogress->diffInDays($start);
                    $data = $numDays+1;
                }
                return $data;
            })
            ->addColumn('action',function ($row){
                $data = '<td class="text-center hidden-print" id="hide-table-row"><i class="fas fa-solid fa-file" onclick="showDetailModelOneParamerter(\'tms/project-sub-task/viewProjectSubTaskDetail\', ' . $row->id . ', \'View Project Sub Task Detail\')"></i></td>';
                return $data;
            })
            ->rawColumns(['project_type','sub_task_status','status','action','total_days'])
            ->make(true);
        }
        return view($this->page.'index',compact('employeeList','projectList','projectTaskList'));
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
            $projectList = DB::table('company_projects')->where('status',1)->where('project_status',1)->where('customer_id',$currentCustomerId)->get();
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
            'project_id' => $request->input('project_id'),
            'task_id' => $request->input('task_id'),
            'employee_id' => $request->input('employee_id'),
            'sub_task_name' => $request->input('sub_task_name'),
            'description' => $request->input('description'),
            'sub_task_type' => $request->input('sub_task_type'),
            'status' => 1, // Ensure this matches your database constraints
            'sub_task_status' => 1, // Ensure this matches your database constraints
            'user_id' => Auth::user()->id,
            'created_date' => date('Y-m-d'),
            'created_by' => Auth::user()->name
        );
        CommonHelper::companyDatabaseConnection($request->input('company_id'));
        // Insert data into the 'company_projects' table
        $projectSubTaskId = DB::table('project_sub_tasks')->insertGetId($data);
        $getProjectSubTaskDetail = CommonHelper::getSingleProjectSubTaskDetail($projectSubTaskId,$request->input('company_id'));
        // Prepare email data
        $array = [
            'view' => $this->page.'email', // Ensure this view exists
            'subject' => 'Add New Project Sub Task',
            'from' => env('MAIL_FROM_ADDRESS'),
            'project_sub_task_detail' => $getProjectSubTaskDetail,
            'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
        ];
        if (filter_var($getProjectSubTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
            $array['cc'] = [$getProjectSubTaskDetail->email]; // Add CC email addresses here
        }
    
        // Send email

        CommonHelper::reconnectMasterDatabase();
        Mail::to($getProjectSubTaskDetail->official_email)->queue(new ProjectSubTask($array));
        Session::flash('dataInsert', 'Successfully Saved');
        return Redirect::to('tms/project-sub-task?m='.$request->input('company_id'));

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

    public function loadTaskAgainstProjectId(Request $request){
        $m = $request->input('m');
        $projectId = $request->input('projectId');
        $type = $request->input('type');
        $empId = Auth::user()->employee_id;
        $customerId = Auth::user()->customer_id;
        $accType = Auth::user()->acc_type;
        CommonHelper::companyDatabaseConnection($m);
        if(in_array($accType, ['client', 'customer'])){
            $projectTaskList = DB::table('project_tasks')->where('project_id',$projectId)->get();
        }else{
            $query = DB::table('project_tasks')
                ->where('project_id', $projectId)
                ->where('employee_id', $empId)
                ->where('status', 1);

            // Apply additional condition based on $type
            if ($type == 1) {
                $query->where('task_status', '!=', 2);
            }
            $projectTaskList = $query->get();
        }
        CommonHelper::reconnectMasterDatabase();
        $data = '<option value="">Select Task Name</option>';
        foreach($projectTaskList as $ptlRow){
            $data .= '<option value="'.$ptlRow->id.'">'.$ptlRow->task_name.'</option>';
        }
        return $data;
    }

    public function viewProjectSubTaskDetail(Request $request){
        $id = $request->input('id');
        $m = $request->input('m');
        CommonHelper::companyDatabaseConnection($m);
            $taskSubDetail = DB::table('project_sub_tasks as pst')
                ->join('project_tasks as pt','pst.task_id','=','pt.id')
                ->join('company_projects as cp','pst.project_id','=','cp.id')
                ->join('customers as c','cp.customer_id','=','c.id')
                ->join('employee as eo','pt.employee_id','=','eo.id')
                ->join('employee as et','pst.employee_id','=','et.id')
                ->select('pst.*','c.customer_name','cp.project_name','pt.task_name','cp.project_type','eo.emp_name as emp_name_one','et.emp_name as emp_name_two')
                ->where('pst.id',$id)
                ->first();
            $subTaskWorkHistoryLast = DB::table('sub_task_work_history')
                ->where('sub_task_id',$id)
                ->orderBy('id', 'desc')
                ->first();
        CommonHelper::reconnectMasterDatabase();
        return view($this->page.'viewProjectSubTaskDetail',compact('taskSubDetail','subTaskWorkHistoryLast'));
    }

    public function subTaskStart(Request $request){
        $m = $request->input('m');
        $taskId = $request->input('taskId');
        $subTaskId = $request->input('subTaskId');
        $empId = Auth::user()->employee_id;
        $remarksInput = $request->input('remarks');
        $data = array(
            'emp_id' => $empId,
            'task_id' => $taskId,
            'sub_task_id' => $subTaskId,
            'start_date' => date('Y-m-d'),
            'start_time' => date('H:i:s'),
            'status' => 1, // Ensure this matches your database constraints
            'user_id' => Auth::user()->id,
            'created_date' => date('Y-m-d'),
            'created_by' => Auth::user()->name
        );

        $data2 = array(
            'emp_id' => $empId,
            'task_id' => $taskId,
            'start_date' => date('Y-m-d'),
            'start_time' => date('H:i:s'),
            'status' => 1, // Ensure this matches your database constraints
            'user_id' => Auth::user()->id,
            'created_date' => date('Y-m-d'),
            'created_by' => Auth::user()->name
        );
        CommonHelper::companyDatabaseConnection($m);
        $projectSubTaskDetail = DB::table('project_sub_tasks')->where('id',$subTaskId)->first();
        $oldRemarks = $projectSubTaskDetail->remarks;
        // Decode to associative array
        $remarksData = json_decode($oldRemarks, true);
        // Add new key-value pair
        $remarksData['Start Sub Task'] = ($remarksInput ?? '-') . ' - ' . date('Y-m-d');

        // Encode back to JSON
        $updatedRemarksJson = json_encode($remarksData);
        DB::table('sub_task_work_history')
            ->where('emp_id', $empId)
            ->whereNull('end_date')
            ->update([
                'end_date' => date('Y-m-d'),
                'end_time' => date('H:i:s')
            ]);
            DB::table('sub_task_work_history')->insert($data);
            DB::table('project_sub_tasks')->where('id',$subTaskId)->update(['sub_task_status' => 4, 'remarks' => $updatedRemarksJson]);

            DB::table('task_work_history')->insert($data2);
            DB::table('project_tasks')->where('id',$taskId)->update(['task_status' => 4,'remarks' => $updatedRemarksJson]);

            $getProjectSubTaskDetail = CommonHelper::getSingleProjectSubTaskDetail($subTaskId,$m);
            // Prepare email data
            $array = [
                'view' => $this->page.'email', // Ensure this view exists
                'subject' => 'Start Project Sub Task',
                'from' => env('MAIL_FROM_ADDRESS'),
                'project_sub_task_detail' => $getProjectSubTaskDetail,
                'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
            ];
            if (filter_var($getProjectSubTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
                $array['cc'] = [$getProjectSubTaskDetail->email]; // Add CC email addresses here
            }
        
            // Send email
    
            CommonHelper::reconnectMasterDatabase();
            Mail::to($getProjectSubTaskDetail->official_email)->queue(new ProjectSubTask($array));
        echo 'Done';

    }

    public function subTaskStop(Request $request){
        $m = $request->input('m');
        $taskId = $request->input('taskId');
        $subTaskId = $request->input('subTaskId');
        $subTaskWorkHistoryLastId = $request->input('subTaskWorkHistoryLastId');
        $empId = Auth::user()->employee_id;
        $remarksInput = $request->input('remarks');
        $data = array(
            'end_date' => date('Y-m-d'),
            'end_time' => date('H:i:s')
        );
        CommonHelper::companyDatabaseConnection($m);
            $projectSubTaskDetail = DB::table('project_sub_tasks')->where('id',$subTaskId)->first();
            $oldRemarks = $projectSubTaskDetail->remarks;
            // Decode to associative array
            $remarksData = json_decode($oldRemarks, true);
            // Add new key-value pair
            $remarksData['Stop Task'] = ($remarksInput ?? '-') . ' - ' . date('Y-m-d');

            // Encode back to JSON
            $updatedRemarksJson = json_encode($remarksData);

            DB::table('sub_task_work_history')->where('id',$subTaskWorkHistoryLastId)->update($data);
            DB::table('project_sub_tasks')->where('id',$subTaskId)->update(['sub_task_status' => 3,'remarks' => $updatedRemarksJson]);
            $getProjectSubTaskDetail = CommonHelper::getSingleProjectSubTaskDetail($subTaskId,$m);
            // Prepare email data
            $array = [
                'view' => $this->page.'email', // Ensure this view exists
                'subject' => 'Stop Project Sub Task',
                'from' => env('MAIL_FROM_ADDRESS'),
                'project_sub_task_detail' => $getProjectSubTaskDetail,
                'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
            ];
            if (filter_var($getProjectSubTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
                $array['cc'] = [$getProjectSubTaskDetail->email]; // Add CC email addresses here
            }
        
            // Send email
    
        CommonHelper::reconnectMasterDatabase();
        Mail::to($getProjectSubTaskDetail->official_email)->queue(new ProjectSubTask($array));
        echo 'Done';
    }

    public function subTaskSendTesting(Request $request){
        $m = $request->input('m');
        $taskId = $request->input('taskId');
        $subTaskId = $request->input('subTaskId');
        $subTaskWorkHistoryLastId = $request->input('subTaskWorkHistoryLastId');
        $empId = Auth::user()->employee_id;
        $userId = Auth::user()->id;
        $remarksInput = $request->input('remarks');
        $data = array(
            'testing_date' => date('Y-m-d'),
            'testing_time' => date('H:i:s'),
            'testing_user_id' => $userId
        );
        CommonHelper::companyDatabaseConnection($m);
            $projectSubTaskDetail = DB::table('project_sub_tasks')->where('id',$subTaskId)->first();
            $oldRemarks = $projectSubTaskDetail->remarks;
            // Decode to associative array
            $remarksData = json_decode($oldRemarks, true);
            // Add new key-value pair
            $remarksData['Send Testing'] = ($remarksInput ?? '-') . ' - ' . date('Y-m-d');

            // Encode back to JSON
            $updatedRemarksJson = json_encode($remarksData);

            DB::table('sub_task_work_history')->where('id',$subTaskWorkHistoryLastId)->update($data);
            DB::table('project_sub_tasks')->where('id',$subTaskId)->update(['sub_task_status' => 5, 'remarks' => $updatedRemarksJson]);
            $getProjectSubTaskDetail = CommonHelper::getSingleProjectSubTaskDetail($subTaskId,$m);
            // Prepare email data
            $array = [
                'view' => $this->page.'email', // Ensure this view exists
                'subject' => 'Testing Project Sub Task',
                'from' => env('MAIL_FROM_ADDRESS'),
                'project_sub_task_detail' => $getProjectSubTaskDetail,
                'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
            ];
            if (filter_var($getProjectSubTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
                $array['cc'] = [$getProjectSubTaskDetail->email]; // Add CC email addresses here
            }
        
            // Send email
    
        CommonHelper::reconnectMasterDatabase();
        Mail::to($getProjectSubTaskDetail->official_email)->queue(new ProjectSubTask($array));
        echo 'Done';
    }

    public function subTaskCompleted(Request $request){
        $m = $request->input('m');
        $taskId = $request->input('taskId');
        $subTaskId = $request->input('subTaskId');
        $subTaskWorkHistoryLastId = $request->input('subTaskWorkHistoryLastId');
        $empId = Auth::user()->employee_id;
        $userId = Auth::user()->id;
        $remarksInput = $request->input('remarks');
        $data = array(
            'completion_date' => date('Y-m-d'),
            'completion_time' => date('H:i:s'),
            'completion_user_id' => $userId
        );
        CommonHelper::companyDatabaseConnection($m);
            $projectSubTaskDetail = DB::table('project_sub_tasks')->where('id',$subTaskId)->first();
            $oldRemarks = $projectSubTaskDetail->remarks;
            // Decode to associative array
            $remarksData = json_decode($oldRemarks, true);
            // Add new key-value pair
            $remarksData['Completed Task'] = ($remarksInput ?? '-') . ' - ' . date('Y-m-d');

            // Encode back to JSON
            $updatedRemarksJson = json_encode($remarksData);

            DB::table('sub_task_work_history')->where('id',$subTaskWorkHistoryLastId)->update($data);
            DB::table('project_sub_tasks')->where('id',$subTaskId)->update(['sub_task_status' => 2, 'remarks' => $updatedRemarksJson]);
            $getProjectSubTaskDetail = CommonHelper::getSingleProjectSubTaskDetail($subTaskId,$m);
            // Prepare email data
            $array = [
                'view' => $this->page.'email', // Ensure this view exists
                'subject' => 'Completed Project Sub Task',
                'from' => env('MAIL_FROM_ADDRESS'),
                'project_sub_task_detail' => $getProjectSubTaskDetail,
                'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
            ];
            if (filter_var($getProjectSubTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
                $array['cc'] = [$getProjectSubTaskDetail->email]; // Add CC email addresses here
            }
        
            // Send email
    
        CommonHelper::reconnectMasterDatabase();
        Mail::to($getProjectSubTaskDetail->official_email)->queue(new ProjectSubTask($array));
        echo 'Done';
    }

    public function subTaskReassign(Request $request){
        $m = $request->input('m');
        $taskId = $request->input('taskId');
        $subTaskId = $request->input('subTaskId');
        $subTaskWorkHistoryLastId = $request->input('subTaskWorkHistoryLastId');
        $empId = Auth::user()->employee_id;
        $userId = Auth::user()->id;
        $remarksInput = $request->input('remarks');
        $data = array(
            'reassign_date' => date('Y-m-d'),
            'reassign_time' => date('H:i:s'),
            'reassign_user_id' => $userId
        );
        CommonHelper::companyDatabaseConnection($m);
            $projectSubTaskDetail = DB::table('project_sub_tasks')->where('id',$subTaskId)->first();
            $oldRemarks = $projectSubTaskDetail->remarks;
            // Decode to associative array
            $remarksData = json_decode($oldRemarks, true);
            // Add new key-value pair
            $remarksData['Reassign Task'] = ($remarksInput ?? '-') . ' - ' . date('Y-m-d');

            // Encode back to JSON
            $updatedRemarksJson = json_encode($remarksData);

            DB::table('sub_task_work_history')->where('id',$subTaskWorkHistoryLastId)->update($data);
            DB::table('project_sub_tasks')->where('id',$subTaskId)->update(['sub_task_status' => 6,'remarks' => $updatedRemarksJson]);
            $getProjectSubTaskDetail = CommonHelper::getSingleProjectSubTaskDetail($subTaskId,$m);
            // Prepare email data
            $array = [
                'view' => $this->page.'email', // Ensure this view exists
                'subject' => 'Reassign Project Sub Task',
                'from' => env('MAIL_FROM_ADDRESS'),
                'project_sub_task_detail' => $getProjectSubTaskDetail,
                'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
            ];
            if (filter_var($getProjectSubTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
                $array['cc'] = [$getProjectSubTaskDetail->email]; // Add CC email addresses here
            }
        
            // Send email
    
        CommonHelper::reconnectMasterDatabase();
        Mail::to($getProjectSubTaskDetail->official_email)->queue(new ProjectSubTask($array));
        echo 'Done';
    }

    public function updateDetailAgainstSubTask(Request $request){
        $m = $request->input('m');
        $subTaskStartDate = $request->input('subTaskStartDate');
        $subTaskEndDate = $request->input('subTaskEndDate');
        $remarksInput = $request->input('remarks');
        $id = $request->input('id');
        $emp_id = Auth::user()->employee_id;
        $remarksInput = $request->input('remarks'); 
        CommonHelper::companyDatabaseConnection($m);
            DB::table('project_sub_tasks')->where('id',$id)->update([
                'start_date' => $subTaskStartDate,
                'end_date' => $subTaskEndDate,
                'update_created_date' => date('Y-m-d'),
                'update_emp_id' => $emp_id,
                'remarks' => json_encode(['Update Sub Task Detail' => ($remarksInput ?? '-') . ' - ' . date('Y-m-d')]),
            ]);
            $getProjectSubTaskDetail = CommonHelper::getSingleProjectSubTaskDetail($id,$m);
            // Prepare email data
            $array = [
                'view' => $this->page.'email', // Ensure this view exists
                'subject' => 'Attend Project Sub Task',
                'from' => env('MAIL_FROM_ADDRESS'),
                'project_sub_task_detail' => $getProjectSubTaskDetail,
                'bcc' => ['nawazali20@gmail.com'] // Add BCC email addresses here
            ];
            if (filter_var($getProjectSubTaskDetail->email, FILTER_VALIDATE_EMAIL)) {
                $array['cc'] = [$getProjectSubTaskDetail->email]; // Add CC email addresses here
            }
        
            // Send email
    
        CommonHelper::reconnectMasterDatabase();
        Mail::to($getProjectSubTaskDetail->official_email)->queue(new ProjectSubTask($array));
        echo 'Done';
    }

    public function projectSubTaskComment(Request $request){
        $m = $request->input('m');
        $taskId = $request->input('taskId');
        $subTaskId = $request->input('subTaskId');
        $commentBody = $request->input('comment_body');
        $empId = Auth::user()->employee_id;
        $customerId = Auth::user()->customer_id;
        $createdBy = Auth::user()->name;
        $createdDate = date('Y-m-d');
        $createdTime = date('H:i:s');
        $accType = Auth::user()->acc_type;
        CommonHelper::companyDatabaseConnection($m);
           $data = array(
                'task_id' => $taskId,
                'sub_task_id' => $subTaskId,
                'parent_id' => 0,
                'body' => $commentBody,
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
            $commentId = DB::table('sub_task_comments')->insertGetId($data);
            // Handle multiple attachments
            if ($request->hasFile('attachment')) {
                foreach ($request->file('attachment') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('sub_task_comments', 'public'); // Store in storage/app/public/task_comments
                        DB::table('sub_task_comment_attachments')->insert([
                            'sub_task_comment_id' => $commentId,
                            'file_path' => $path,
                            'original_name' => $file->getClientOriginalName(),
                        ]);
                    }
                }
            }
        CommonHelper::reconnectMasterDatabase();
        echo 'Done';
    }

    public function projectSubTaskReplyAgainstComment(Request $request){
        $m = $request->input('m');
        $taskId = $request->input('taskId');
        $subTaskId = $request->input('subTaskId');
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
                'sub_task_id' => $subTaskId,
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
            DB::table('sub_task_comments')->insert($data);
        CommonHelper::reconnectMasterDatabase();
        echo 'Done';
    }

    function loadCommentAgainstSubTask(Request $request){
        $m = $request->input('m');
        $taskId = $request->input('taskId');
        $subTaskId = $request->input('subTaskId');
        CommonHelper::companyDatabaseConnection($m);
            $getCommentDetail = DB::table('sub_task_comments as c1')
                ->leftJoin('sub_task_comments as c2', 'c1.id', '=', 'c2.parent_id')
                ->leftJoin('employee as e', 'c1.emp_id', '=', 'e.id')
                ->leftJoin('employee as e2', 'c2.emp_id', '=', 'e2.id')

                ->leftJoin('customers as cus', 'c1.customer_id', '=', 'cus.id')
                ->leftJoin('customers as cus2', 'c2.customer_id', '=', 'cus2.id')
                ->select(
                    'c1.id as comment_id',
                    'e.emp_name as comment_emp_name',
                    'cus.customer_name as comment_customer_name',
                    'c1.task_id as comment_task_id',
                    'c1.sub_task_id as comment_sub_task_id',
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
                ->where('c1.sub_task_id',$subTaskId)
                ->where('c1.parent_id','=',0)
                ->get();
        CommonHelper::reconnectMasterDatabase();
        return view($this->page.'loadCommentAgainstSubTask',compact('getCommentDetail'));
    }
}
