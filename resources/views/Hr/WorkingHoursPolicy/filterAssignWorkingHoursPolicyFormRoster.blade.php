<?php
use Carbon\Carbon;
use App\Models\TaxSlabs;
use App\Models\Tax;
use App\Models\Rebate;
 $m = Input::get('company_id');
// exit();
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Bonus;
use App\Models\BonusIssue;
use App\Models\EmployeePromotion;
$counter = 1;
$data1 ='';
$colors=['#f34c92','#993fdd'];

CommonHelper::companyDatabaseConnection('12');
$role_id=DB::table('employee')->select('role_id')->where('status','=',1)->where('id','=',Auth::user()->employee_id)->value('role_id');
CommonHelper::reconnectMasterDatabase();

?>


<style>
    
.swal-button--roll{
    background-color:#77bb77 !important;
}


#external-events {
  /* position: absolute;
  z-index: 2;
  top: 30px;
  left: 20px;
  width: 150px; */
  /* padding: 0 10px;
  padding-left:8px;
  padding-right:7px; */
  border: 1px solid #ccc;
  /* background: #eee; */
  /*margin-top:70px;*/
  
  
}
.fc-popover{
    width: auto !important;
    max-height: 600px !important;
    overflow-y: scroll !important;
}
.fc-day-number{
    font-size:14px !important;
}

#external-events .fc-event {
  cursor: move;
}

/* #calendar-container {
  position: relative;
  z-index: 1;
  margin-left: 200px;
} */

#calendar {
  max-width: 95%;
  margin: 15px auto;
}


</style>    

<div class="lineHeight">&nbsp;</div>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            
            
                
                
        <div class="row">
            <!--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">-->
            <!--<div id='external-events'>-->
          
            <!--    <h4>&nbsp;             -->
            <!--        &nbsp;&nbsp;&nbsp;&nbsp;W-H Policies-->
            <!--    </h4>-->
            <!--    @php $c=0; @endphp-->
            <!--    @foreach($workingHoursPolicy as $key => $y)-->
            <!--        @php $c==1 ? $c=0 : $c++; @endphp -->
            <!--        <div style="background:@php echo $colors[$c] @endphp" id="{{ $y->id }}" class='fc-event'>{{ $y->working_hours_policy}}</div>-->
            <!--    @endforeach-->
              
                
                
            <!--</div>-->
            <!--</div>-->
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div id='calendar-container'>
                    <div id='calendar'></div>
                </div>
            </div>
        </div>                
        

                
            
            <div id="event_entry_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">

                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header modal-colored-header bg-primary" style="height: 80px;">
                            <div class="text-center mt-2 mb-3"></div>
                            <div class="text-center mt-3 mb-4">
                                <h3 style="font-weight: bolder;" class="modalTitle" aria-hidden="true">Assign W-H Policies</h3>
                            </div>
                            <button class="btn btn-sm btn-danger" type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">X</button>
                        </div>
                        <div class="modal-body">
                            <div class="">
                            <div class="row">
                            
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label for="event_start_date" class="text-left">From Date</label>
                                    <input type="date" name="from_date" onchange="dateChange(event)" id="from_date" class="form-control onlydatepicker" placeholder="From date">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <div class="form-group">
                                    <label for="event_end_date" class="text-left">To Date</label>
                                    <input type="date" name="to_date" onchange="dateChange(event)" id="to_date" class="form-control" placeholder="To date">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" >
                                <label class="sf-label text-left" >Working Hours Policy:</label>
                                <span class="rflabelsteric text-left"><strong>*</strong></span>
                                <select class="form-control" name="working_hours_policy_id" id="working_hours_policy_id">
                                    <option value="">Select Policy</option>
                                    @foreach($workingHoursPolicy as $key => $y)
                                        @if(in_array($role_id,explode('=>',$y->role_id)))
                                        <option value="{{ $y->id }}">{{ $y->working_hours_policy}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <div id="emp_loader"></div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" >
                                <div class="form-group">
                                    <label for="event_start_date" class="text-left">Select Color</label>
                                    <!--<input type="color" name="color" id="color" class="form-control" value="#1f3fdb">-->
                                    <br/> 
                                   
                                    <span class="label" style="background-color:#2383b3;">&nbsp;&nbsp;&nbsp;<input checked type="radio" name="color" id="color" value="#1f3fdb">&nbsp;&nbsp;&nbsp;</span>
                                    &nbsp;
                                    <span class="label" style="background-color:#eb255c;">&nbsp;&nbsp;&nbsp;<input type="radio" name="color" id="color" value="#eb255c">&nbsp;&nbsp;&nbsp;</span>
                                    
                                    &nbsp;
                                    <span class="label" style="background-color:#6ecd06;">&nbsp;&nbsp;&nbsp;<input  type="radio" name="color" id="color" value="#6ecd06;">&nbsp;&nbsp;&nbsp;</span>
                                    
                                    &nbsp;
                                    <span class="label" style="background-color:#fd852f;">&nbsp;&nbsp;&nbsp;<input type="radio" name="color" id="color" value="#fd852f">&nbsp;&nbsp;&nbsp;</span>
                                    
                                    &nbsp;
                                    <span class="label" style="background-color:#cb06bc;"> &nbsp;&nbsp;&nbsp;<input type="radio" name="color" id="color" value="#cb06bc">&nbsp;&nbsp;&nbsp;</span>
                                   <!--#993fdd-->
                                    
                                   
                                </div>
                            </div>      
                        </div>
                            
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print">
                                    <button class="btn btn-sm btn-success" type="button" onclick="save_policies()">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>


            <div id="event_edit_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">

                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header modal-colored-header bg-primary" style="height: 80px;">
                            <div class="text-center mt-2 mb-3"></div>
                            <div class="text-center mt-3 mb-4">
                                <h3 style="font-weight: bolder;" class="modalTitle" aria-hidden="true">Update Assign W-H Policies</h3>
                            </div>
                            <button class="btn btn-sm btn-danger" type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">X</button>
                        </div>
                        <div class="modal-body">
                            <div class="">
                            <div class="row">
                            <!--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="display:none;">-->
                            <!--    <label class="sf-label text-left" >Working Hours Policy:</label>-->
                            <!--    <span class="rflabelsteric text-left"><strong>*</strong></span>-->
                            <!--    <select class="form-control" name="working_hours_policy_id" id="working_hours_policy_id">-->
                            <!--        <option value="">Select Policy</option>-->
                            <!--        @foreach($workingHoursPolicy as $key => $y)-->
                            <!--            <option value="{{ $y->id }}">{{ $y->working_hours_policy}}</option>-->
                            <!--        @endforeach-->
                            <!--    </select>-->
                            <!--    <div id="emp_loader"></div>-->
                            <!--</div>-->
                            <!--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">-->
                            <!--    <div class="form-group">-->
                            <!--        <label for="event_start_date" class="text-left">From Date</label>-->
                            <!--        <input type="date" name="from_date" id="from_date" class="form-control onlydatepicker" placeholder="From date">-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">-->
                            <!--    <div class="form-group">-->
                            <!--        <label for="event_end_date" class="text-left">To Date</label>-->
                            <!--        <input type="date" name="to_date" id="to_date" class="form-control" placeholder="To date">-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" style="display:none;">-->
                            <!--    <div class="form-group">-->
                            <!--        <label for="event_start_date" class="text-left">Select Color</label>-->
                            <!--        <input type="color" name="color" id="color" class="form-control" value="#1f3fdb">-->
                            <!--    </div>-->
                            <!--</div>      -->
                        </div>
                            
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print">
                                    <button class="btn btn-sm btn-success" type="button" onclick="save_policies()">Update</button>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>

                
            </div>
        </div>
    </div>
</div>
<br />


<script>
var m=$('#m').val();
var baseUrl=$('#baseUrl').val();
var color='';
$(document).ready(function() {
    // $('#working_hours_policy_id').multiselect({
    //     enableFiltering: true,
    //     enableCaseInsensitiveFiltering: true,
    //     maxHeight: 250,
    //     maxWidth:250
    // });
    
    $('#external-events .fc-event').each(function() {

        // store data so the calendar knows to render an event upon drop
        // $(this).data('event', {
        //     title: $.trim($(this).text()), // use the element's text as the event title
        //     stick: true, // maintain when user navigates (see docs on the renderEvent method)
        //     color:$(this).css('background-color')
        // });

        // make the event draggable using jQuery UI
        $(this).draggable({
        zIndex: 999,
        revert: true,      // will cause the event to go back to its
        revertDuration: 0  //  original position after the drag
        });

    });

	display_events();
}); //end document.ready block


function getPreviousDay(date = new Date()) {
  const previous = new Date(date.getTime());
  previous.setDate(date.getDate() - 1);

  return moment(previous).format('YYYY-MM-DD');
}

// function display_events()
// {
//     var department_id = $('#department_id').val();
//     var sub_department_id = $('#sub_department_id').val();
//     var project_id = $('#project_id').val();
//     var category_id = $('#category_id').val();
//     var employee_id = $('#employee_ids').val();
//     var working_hours_policy_id = $('#working_hours_policy_id').val();
//     var company_id = '{{ $m }}';
//     var events = new Array();
//     var data = {company_id:company_id,employee_id:employee_id,department_id:department_id,sub_department_id:sub_department_id,
//         project_id:project_id,category_id:category_id,working_hours_policy_id:working_hours_policy_id
//     }

//     console.log(data);
// }

function display_events() {

var department_id = $('#department_id').val();
var sub_department_id = $('#sub_department_id').val();
var project_id = $('#project_id').val();
var category_id = $('#category_id').val();
var location_id = $('#location_id').val();

var employee_id = $('#employee_ids').val();
var working_hours_policy_id = $('#working_hours_policy_id').val();
var company_id = '{{ $m }}';
var events = new Array();

$.ajax({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
    url: baseUrl+'/hdc/displayPolicies',  
    type: 'GET',
    // dataType: 'json',
    data: {company_id:company_id,employee_id:employee_id,department_id:department_id,sub_department_id:sub_department_id,
        project_id:project_id,category_id:category_id,location_id:location_id,working_hours_policy_id:working_hours_policy_id
    },
    success: function (response) {
       
    var result=response.data;
    
    $.each(result, function (i, item) {
        var d = new Date(result[i].to_date);
        var endDate = d.setDate(d.getDate() + 1);
        endDate=moment(endDate).format('YYYY-MM-DD');
        
    	events.push({
            event_id: result[i].id,
            title: result[i].working_hour_policy_name+' - '+result[i].emp_id+' - '+result[i].emp_name,
            name: result[i].emp_name,
            start: result[i].from_date,
            end: endDate,
            color: result[i].color
        }); 	
    })
  
    var calendar = $('#calendar').fullCalendar({
        
	    defaultView: 'month',
		timeZone: 'local',
	    editable: true,
        droppable: true, // this allows things to be dropped onto the calendar
        drop: function(info) {
        // is the "remove after drop" checkbox checked?
        // if (checkbox.checked) {
        //     // if so, remove the element from the "Draggable Events" list
        //     info.draggedEl.parentNode.removeChild(info.draggedEl);
        // }
            
            var d = new Date(info._d);
            var date = d.setDate(d.getDate());
            var from_date=moment(date).format('YYYY-MM-DD');
            var to_date=moment(date).format('YYYY-MM-DD');
            $('#from_date').val(from_date);
            $('#to_date').val(to_date);
            var working_hours_policy_id=$(this).attr('id');
            $("#working_hours_policy_id").val(working_hours_policy_id).change();
            color=$(this).css("background-color");
            //$('#color').val(color);
            
            $('#event_entry_modal').modal('show');
            //drag_policies(working_hours_policy_id,from_date,to_date,color);
        },
        selectable: true,
		selectHelper: true,
        eventLimit: true, // allow "more" link when too many events
        eventLimitText: "More", //sets the text for more events
        select: function(start, end) {
                //alert(start);
				//alert(end);
                var d = new Date(end);
                var yesterday = d.setDate(d.getDate() - 1);
				$('#from_date').val(moment(start).format('YYYY-MM-DD'));
				$('#to_date').val(moment(yesterday).format('YYYY-MM-DD'));
				$('#event_entry_modal').modal('show');
				dateChange();
			},
            dayClick: function(date, jsEvent, view) {
                    //Change background color of day when it is clicked
                    // $(this).css('background-color', '#bed7f3');
                    //Get the date that was clicked
                    var date_clicked =  date.format();
                   
                    //Redirect to the new event entry form
                    //window.location.href = "{{URL::to('events')}}" + "/" + date_clicked;
                },
                eventClick: function(event, jsEvent, view) {
                   
                    //delete code
                       
                    let start_date=event.start.toISOString();
                    let end_date=event.end.toISOString();
                    let yesterday=getPreviousDay(new Date(end_date));
                    let min_date=moment(start_date).format('YYYY-MM-DD');
                    let max_date=moment(yesterday).format('YYYY-MM-DD');
                    let name=event.name;
                    swal({
                        title: "Are you sure you want to delete this?",
                        text: "Select a date or otherwise, it will be deleted completely",
                        icon: "warning",
                        content: {
                        element: "input",
                        attributes: {
                          type: "date",
                          id: "swal_date",
                          min: min_date,
                          max: max_date
                        },
                        },
                        buttons: {
                            cancel: true,
                            confirm:{ 
                                text: "For "+name,
                                value: true,
                                visible: true,
                                className: "btnconfirm"
                            },
                            roll: {
                                text: "For All Emp",
                                value: "all_employees"
                            },
                            
                        },
                        dangerMode: true,
                        })
                        .then(function(willDelete){
                        let btnconfirm=$('.btnconfirm').html();     
                        let swal_date=$('#swal_date').val();    
                         
                        if(willDelete == 'all_employees') {
                            let ids= new Array();
                            events.forEach((element)=>{
                                ids.push(element.event_id);     
                            });
                            $.ajax({
                                url: baseUrl+'/had/deletePoliciesForAll',  
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                  },
                                // dataType: 'json',
                                data: {company_id:m,ids:ids,id:event.event_id,start_date:start_date,end_date:yesterday,deleted_date:swal_date
                                },
                                success: function (response) {
                                    if(response.status == true){  
                                        filterAssignWorkingHoursPolicyForm();   
                                        swal("Deleted. Your Policy has been deleted.", {
                                        icon: "success",
                                        })
                                    }else{
                                        swal("Your Policy has not been deleted.");
                                        revertFunc();
                                    }
                                } 
                            });  
                        }     
                       else if(willDelete!=null) {
                            $.ajax({
                                url: baseUrl+'/had/deletePolicies',  
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                  },
                                data: {company_id:m,id:event.event_id,start_date:start_date,end_date:yesterday,deleted_date:swal_date
                                },
                                success: function (response) {
                                    if(response.status == true){   
                                        filterAssignWorkingHoursPolicyForm(); 
                                        swal("Deleted. Your Policy has been deleted.", {
                                        icon: "success",
                                        })
                                    }else{
                                        swal("Your Policy has not been deleted.");
                                        revertFunc();
                                    }
                                } 
                            });  
                        } else {
                            swal("Your Policy has not been deleted.");
                            revertFunc();
                        }
                    });

                    //end delete code
                },
                eventDragStart: function(event, jsEvent, view) {
                   
                },
                // drop on a new date and submit to database
                eventDrop: function(event, delta, revertFunc, jsEvent, view) {
                   
                    let start_date=event.start.toISOString();
                    let end_date=event.end.toISOString();
                    let yesterday=getPreviousDay(new Date(end_date));
                   
                    swal({
                        title: "You moved the Policy. Save it?",
                        text: "You can move it as mush as you want.",
                        icon: "warning",
                        buttons: {
                            cancel: true,
                            confirm: "For "+event.name,
                            roll: {
                                text: "For All",
                                value: "all_employees"
                            },
                        },
                        //dangerMode: true,
                        })
                        .then(function(willDelete){
                           
                        if(willDelete=='all_employees'){
                            let ids= new Array();
                            events.forEach((element)=>{
                                ids.push(element.event_id);     
                            });
                            
                            $.ajax({
                                url: baseUrl+'/had/updatePoliciesForAll',  
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                  },
                                // dataType: 'json',
                                data: {company_id:m,ids:ids,id:event.event_id,start_date:start_date,end_date:yesterday
                                },
                                success: function (response) {
                                    if(response.status == true){   
                                        filterAssignWorkingHoursPolicyForm(); 
                                        swal("Moved. Your Policy has been rescheduled.", {
                                        icon: "success",
                                        })
                                    }else{
                                        swal("Your Policy has not been rescheduled.");
                                        revertFunc();
                                    }
                                } 
                            }); 
                        } 
                        else if(willDelete) {
                            $.ajax({
                                url: baseUrl+'/had/updatePolicies',  
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                  },
                                // dataType: 'json',
                                data: {company_id:m,id:event.event_id,start_date:start_date,end_date:yesterday
                                },
                                success: function (response) {
                                    if(response.status == true){    
                                        swal("Moved. Your Policy has been rescheduled.", {
                                        icon: "success",
                                        })
                                    }else{
                                        swal("Your Policy has not been rescheduled.");
                                        revertFunc();
                                    }
                                } 
                            });   
                        } else {
                            swal("Your Policy has not been rescheduled.");
                            revertFunc();
                        }
                    });   
                },
                eventResize: function(event, delta, revertFunc){
                    let start_date=event.start.toISOString();
                    let end_date=event.end.toISOString();
                    let yesterday=getPreviousDay(new Date(end_date));
                    
                    swal({
                        title: "Changed Timeline. Save it?",
                        text: "You can expand it as far as you need to.",
                        icon: "warning",
                        buttons: {
                            cancel: true,
                            confirm: "For "+event.name,
                            roll: {
                                text: "For All Emp",
                                value: "all_employees"
                            },
                        },
                        dangerMode: true,
                        })
                        .then(function(willDelete){
                        if(willDelete == 'all_employees') {
                            let ids= new Array();
                            events.forEach((element)=>{
                                ids.push(element.event_id);     
                            });
                            $.ajax({
                                url: baseUrl+'/had/updatePoliciesForAll',  
                               type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                  },
                                data: {company_id:m,ids:ids,id:event.event_id,start_date:start_date,end_date:yesterday
                                },
                                success: function (response) {
                                    if(response.status == true){  
                                        filterAssignWorkingHoursPolicyForm();   
                                        swal("Moved. Your Policy has been rescheduled.", {
                                        icon: "success",
                                        })
                                    }else{
                                        swal("Your Policy has not been rescheduled.");
                                        revertFunc();
                                    }
                                } 
                            });  
                        }     
                       else if(willDelete) {
                            $.ajax({
                                url: baseUrl+'/had/updatePolicies',  
                               type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                  },
                                data: {company_id:m,id:event.event_id,start_date:start_date,end_date:yesterday
                                },
                                success: function (response) {
                                    if(response.status == true){    
                                        swal("Moved. Your Policy has been rescheduled.", {
                                        icon: "success",
                                        })
                                    }else{
                                        swal("Your Policy has not been rescheduled.");
                                        revertFunc();
                                    }
                                } 
                            });  
                        } else {
                            swal("Your Policy has not been rescheduled.");
                            revertFunc();
                        }
                    });   
                },
                    
        events: events,
	    eventRender: function(event, element, view) { 
            element.bind('click', function() {
					//alert(event.event_id);
				});
    	}
		}); //end fullCalendar block	
	  },//end success block
	  error: function (xhr, status) {
	  alert(response.msg);
	  }
	});//end ajax block	
}

function save_policies()
{
var m=$('#m').val();
var baseUrl=$('#baseUrl').val();
var department_id = $('#department_id').val();
var sub_department_id = $('#sub_department_id').val();
var project_id = $('#project_id').val();
var category_id = $('#category_id').val();
var employee_id = $('#employee_ids').val();
var working_hours_policy_id = $('#working_hours_policy_id').val();
var from_date=$("#from_date").val();
var to_date=$("#to_date").val();
var color=$('input[name="color"]:checked').val();
if(working_hours_policy_id=="" || from_date=="" || to_date=="")
{
alert("Please enter all required details.");
return false;
}
// else{

    $('#event_entry_modal').modal('hide'); 
// }
$.ajax({
    url: baseUrl+'/had/savepolicies',  
    type: 'GET',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
    data: {company_id:m,employee_id:employee_id,department_id:department_id,sub_department_id:sub_department_id,
        color:color,project_id:project_id,category_id:category_id,working_hours_policy_id:working_hours_policy_id,from_date:from_date,to_date:to_date
    },
 success:function(response){
     if(response.status == true)
     {	
        filterAssignWorkingHoursPolicyForm();
	// location.reload();
   }
   else
   {
	 alert(response.msg);
   }
  },
  error: function (xhr, status) {
  console.log('ajax error = ' + xhr.statusText);
  alert(response.msg);
  }
});    
return false;
}


function drag_policies(working_hours_policy_id,from_date,to_date,color)
{
var m=$('#m').val();
var baseUrl=$('#baseUrl').val();
var department_id = $('#department_id').val();
var sub_department_id = $('#sub_department_id').val();
var project_id = $('#project_id').val();
var category_id = $('#category_id').val();
var employee_id = $('#employee_ids').val();
if(employee_id=="")
{
alert("Please select an employee.");
return false;
}
$.ajax({
    url: baseUrl+'/had/savepolicies',  
    type: 'POST',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
    data: {company_id:m,employee_id:employee_id,department_id:department_id,sub_department_id:sub_department_id,
        color:color,project_id:project_id,category_id:category_id,working_hours_policy_id:working_hours_policy_id,from_date:from_date,to_date:to_date
    },
 success:function(response){
   $('#event_entry_modal').modal('hide');  
   if(response.status == true)
   {
    $.notify({
        icon: "fa fa-check-circle",
        message: "<b>"+response.msg+"</b>.",
    }, {
        type: 'success',
        timer: 3000
    });
	
    filterAssignWorkingHoursPolicyForm();
	// location.reload();
   }
   else
   {
	 alert(response.msg);
   }
  },
  error: function (xhr, status) {
  console.log('ajax error = ' + xhr.statusText);
  alert(response.msg);
  }
});    
return false;
}


function dateChange(e)
    {
        let from = document.getElementById("from_date");
        let to = document.getElementById("to_date");

        (from.value > to.value) ? to.value = from.value : null ;
         
        from.setAttribute("max", to.value) ;
        to.setAttribute("min", from.value);
     
      
         

    }


</script>





