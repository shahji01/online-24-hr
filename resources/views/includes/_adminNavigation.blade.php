<?php
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\MenuPrivileges;
use App\Models\Employee;
use App\Models\Menu;

$accType = Auth::user()->acc_type;
if($accType == 'client'){$m = $_GET['m'];}else{$m = Auth::user()->company_id;}

$user_rights = MenuPrivileges::where([['emp_id','=',Auth::user()->emp_id]]);

$crud_permission='';
if($user_rights->count() > 0):
    $main_modules = explode(",",$user_rights->value('main_modules'));
    $submenu_ids  = explode(",",$user_rights->value('submenu_id'));
    $crud_rights  = explode(",",$user_rights->value('crud_rights'));
    $companyList= $user_rights->value('company_list');

    foreach($submenu_ids as $val):
        $parent_code[] = Menu::where([['id', '=', $val],['status','=', 1]])->value('m_parent_code');
    endforeach;
else: ?>
<script>
    $('.preloader').css({"display": "none"});
</script>
<div class="row">
    <div class="col-sm-12 text-center">
        <div style="margin-top: 20px;">
            <?php
            echo "<h4>Account Type:".$accType."</h4>";
            echo '<h4>Insufficient Menu Privileges'."</h4>";
            echo "<a class='btn btn-sm btn-info' href='".url('/logout')."'>Logout</a>";
            ?>
        </div>
    </div>
</div>

<?php

endif;
use Illuminate\Support\Facades\Cache;
$dashboardIcons = array('fas fa-home','fas fa-users','fas fa-clipboard-check','fab fa-sellsy','fas fa-money-bill-alt','fas fa-minus-circle','fas fa-dollar-sign','fas fa-paper-plane','fas fa-plus','fas fa-envelope-open','fas fa-hourglass-end','fas fa-location-arrow','fas fa-user-times','fas fa-id-card',' fas fa-level-up-alt','fas fa-edit','fas fa-briefcase','fa fa-file','fas fa-lock-open','fas fa-lock','fas fa-rupee-sign','fas fa-sort-amount-up','fas fa-arrows-alt-h','fas fa-bars','fas fa-bars fa-sm','fas fa-cogs');
$icons = array('HR'=>'fa fa-heart','Finance'=>'fa fa-usd','Purchase'=>'fa fa-money-bill',
        'Store'=>'fa fa-shopping-cart','Sales'=>'fa fa-money','Reports'=>'fa fa-print','Users'=>'fa fa-users', 'Dashboard' => 'fa fa-users');
$m = Input::get("m");
$i = 0;
$employee_id = Auth::user()->employee_id;
$emp_img = '';
$employee = Cache::get('employee');
if(array_key_exists($employee_id, $employee)): $emp_img = $employee[$employee_id]->img_path; endif;
if(Auth::user()->password_status == 0):
?>
<style>
    .topbar
    {visibility: hidden !important;}
    .left-sidebar
    {visibility: hidden !important;}
</style>

<?php endif; ?>
<div id="main-wrapper" class="main-wrapper1" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
     data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

    <header class="topbar" data-navbarbg="skin6">
        <nav class="navbar top-navbar navbar-expand-md" >
            <div class="navbar-header" data-logobg="skin6">
                <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                <div class="col-sm-12 navbar-brand text-center">
                    <div id="logo" class="color-text">
                        <a href="{{url('/')}}" style=" display: block; margin-left: auto; margin-right: auto; width: 100%;">
                            <b class="logo-icon">
                                <img style="height: 60px; width: 186px;" src="{{ asset('assets/images/logo.png') }}"/>
                            </b>
                        </a>
                    </div>
                </div>
                <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                   data-toggle="collapse" data-target="#navbarSupportedContent"
                   aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="ti-more"></i></a>
            </div>

            <div class="navbar-collapse collapse" id="navbarSupportedContent">
                <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                    <!-- Notification -->
                    <li id="hidemenu" class="nav-item dropdown">
                        <a onclick="hidemenu()" class="nav-link dropdown-toggle pl-md-3 position-relative" href="javascript:void(0)"
                           id="bell" role="button" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            <span><i data-feather="align-justify" class="svg-icon"></i></span>
                        </a>
                    </li>
                    <li id="showmenu" class="nav-item dropdown" style="display:none;">
                        <a onclick="showmenu()" class="nav-link dropdown-toggle pl-md-3 position-relative" href="javascript:void(0)"
                           id="bell" role="button" data-toggle="dropdown" aria-haspopup="true"
                           aria-expanded="false">
                            <span><i data-feather="align-justify" class="svg-icon"></i></span>
                        </a>
                    </li>

                    @if(Input::get('m') != '' && $accType=='client')
                        <?php
                        $txt =[];
                        $MainMenuTitles = DB::table('main_menu_title')->select(['main_menu_id','id'])->where([['menu_type','=',2],['status','=',1]])->groupBy('main_menu_id')->get();
                        $counter = 1;
                        foreach($MainMenuTitles as $row){  ?>
                        <li class="nav-item dropdown">


                            <a class="nav-link dropdown-toggle " aria-haspopup="true" aria-expanded="false" type="button" data-toggle="dropdown" role="button"><i data-feather="settings" class="svg-icon"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php
                                $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['status','=',1],['menu_type','=',2]])->get();
                                foreach($MainMenuTitlesSub as $row1){
                                ?>
                                <li class="main-menu dropdown-submenu">
                                    <a class="test dropdown-item" tabindex="-1" href="#">{{ $row1->title}}
                                        <i data-feather="chevron-right" class="svg-icon"></i>
                                    </a>
                                    <ul class="submenu dropdown-menu" aria-labelledby="navbarDropdown">
                                        <?php
                                        $data = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where([['m_parent_code','=',$row1->id],['page_type','=', 1],['status','=', 1]])->get();
                                        foreach($data as $dataValue){
                                        $MakeUrl = url(''.$dataValue->m_controller_name.'');
                                        $txt[] = array('url'=>$MakeUrl,'name'=>$dataValue->name);

                                        ?>

                                        <li>
                                            <a class="dropdown-item test1" tabindex="-1" href="<?php echo url(''.$dataValue->m_controller_name.'?m='.Input::get('m'))?>"> <?php echo $dataValue->name;?>
                                            </a>
                                        </li>
                                        <?php
                                        } ?>
                                    </ul>
                                    <?php $counter++; } ?>
                                </li>
                                <?php }


                                ?>
                            </ul>
                        </li>
                    @endif
                </ul>

                <ul class="navbar-nav float-right">
                    @include('includes.dashboardNotifications')

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">

                            <img id="emp_image" src="{{ asset($emp_img!=''?'storage/'.$emp_img:'storage/app/uploads/employee_images/user-dummy.png') }}" alt="user" class="rounded-circle"
                                 width="40" style="height: 40px;">
							<span class="ml-2 d-none d-lg-inline-block">
                            <span class="text-dark">{{ Auth::user()->name }}</span> <i data-feather="chevron-down"
                                                                                       class="svg-icon"></i>
                        </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                            <a class="dropdown-item" href="{{ url('/users/editMyProfile?m='.$m) }}"><i data-feather="user"
                                                                                                       class="svg-icon mr-2 ml-1"></i>
                                My Profile</a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/users/editUserProfile?m='.$m) }}"><i data-feather="settings"
                                                                                                         class="svg-icon mr-2 ml-1"></i>
                                Account Setting</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/logout') }}"><i data-feather="power"
                                                                                    class="svg-icon mr-2 ml-1"></i>
                                Logout</a>
                            <div class="dropdown-divider"></div>

                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="left-sidebar" data-sidebarbg="skin6">
        <div class="scroll-sidebar" data-sidebarbg="skin6">
            <nav class="sidebar-nav">
                <?php if(Input::get('m') != ''):

                $MainMenuTitles = DB::table('main_menu_title')->select(['main_menu_id','id'])->where([['menu_type','=',1],['status','=',1]])->groupBy('main_menu_id')->get();

                $counter = 1;
                foreach($main_modules as $row){
                if(in_array($row,$main_modules)):
                $main_menu_id = DB::table('main_menu_title')->select('main_menu_id')->where([['id','=',$row]])->value('main_menu_id');
                ?>

                <ul id="sidebarnav" >

                    <?php
                    $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$main_menu_id],['status','=',1]])->whereIn('id', $parent_code)->get();

                    foreach($MainMenuTitlesSub as $row1){
                    ?>
                    <li class="sidebar-item">
                        <a id="sidelink" class="sidebar-link sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
							<span class="hide-menu" ><i class="{{ $dashboardIcons[$i] }}"></i>
                                {{ $row1->title }}
							</span>
                        </a>
                        <ul aria-expanded="false" class="collapse first-level base-level-line row">
                            <?php
                            $data = DB::table('menu')->select(['name','m_controller_name','m_main_title','id'])->where([['m_parent_code','=',$row1->id],['page_type', '=', 1],['status', '=', 1]])->get();

                            foreach($data as $dataValue){

                            if(in_array($dataValue->id,$submenu_ids)):
                            $MakeUrl = url(''.$dataValue->m_controller_name.'');
                            $txt[] = array('url'=>$MakeUrl,'name'=>$dataValue->name);
                            ?>

                            <li class="sidebar-item">

                                <a href="<?php echo url(''.$dataValue->m_controller_name.'?m='.Input::get('m'))?>" class="sidebar-link sidebar-link"><span class="hide-menu"><?php echo $dataValue->name;?></span></a>

                            </li>
                            <?php

                            endif;
                            } ?>

                        </ul>
                    </li>
                    <?php $i++;  }   ?>
                </ul>

                <?php

                endif;
                }
                ?>
                <?php endif; Storage::disk('local')->put('menu.json',response()->json($txt)->getContent() ); ?>


            </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
    </aside>

    <script>
        $(document).ready(function(){
            $('.dropdown-submenu a.test').on("click", function(e){
                $(".main-menu .submenu").hide('fast');
                $(this).next('ul').toggle('fast');
                e.stopPropagation();
                e.preventDefault();
            });

        });
        function viewSalarySheet(){
            $.notify({
                icon: "fa fa-exclamation-triangle",
                message: "<b> Sorry! You cannot view your salary sheet of this month. </b>."
            }, {
                type: 'warning',
                timer: 5000

            });
        }

    </script>
