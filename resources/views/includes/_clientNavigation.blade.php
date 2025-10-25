<?php

use App\Helpers\HrHelper;
use App\Models\MenuPrivileges;
use App\Models\Menu;
use App\Helpers\CommonHelper;
use App\Models\Employee;
use Illuminate\Support\Facades\Cache;
$accType = Auth::user()->acc_type;
$employee_id = Auth::user()->employee_id;
if($accType == 'client')
{
    $m = Auth::user()->company_id;
}
$emp_img = '';
$m = Input::get("m");
$dashboardIcons = array('fas fa-home','fas fa-users','fas fa-clipboard-check','fab fa-sellsy','fas fa-money-bill-alt','fas fa-minus-circle','fas fa-dollar-sign','fas fa-paper-plane','fas fa-plus','fas fa-envelope-open','fas fa-hourglass-end','fas fa-location-arrow','fas fa-user-times','fas fa-id-card',' fas fa-level-up-alt','fas fa-edit','fas fa-briefcase','fa fa-file','fas fa-lock-open','fas fa-lock','fas fa-rupee-sign','fas fa-sort-amount-up','fas fa-arrows-alt-h','fas fa-bars','fas fa-bars fa-sm','fas fa-cogs');
$icons = array('HR'=>'fa fa-heart','Finance'=>'fa fa-usd','Purchase'=>'fa fa-money-bill',
        'Store'=>'fa fa-shopping-cart','Sales'=>'fa fa-money','Reports'=>'fa fa-print','Users'=>'fa fa-users', 'Dashboard' => 'fa fa-users');

$employee = Cache::get('employee');

if(array_key_exists($employee_id, $employee)): $emp_img = $employee[$employee_id]->img_path; endif;
$i = 0;
?>

<div id="main-wrapper" class="main-wrapper1" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
     data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">

    <header class="topbar" data-navbarbg="skin6">
        <nav class="navbar top-navbar navbar-expand-md">
            <div class="navbar-header" data-logobg="skin6">
                <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                    <i class="ti-menu ti-close"></i>
                </a>

                <div class="navbar-brand text-center">
                    <a href="{{url('/')}}" style=" display: block; margin-left: auto; margin-right: auto; width: 100%;">
                        <b class="logo-icon">
                            <img style="height: 60px; width: 186px;" src="{{ asset('assets/images/logo.png') }}"/>
                        </b>
                    </a>
                </div>

                <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                   data-toggle="collapse" data-target="#navbarSupportedContent"
                   aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i>
                </a>
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

                    <!--top nav -->
                    @if(Input::get('m') != '')
                        <?php
                        $txt =[];
                        Cache::forget('MainMenuTitles');
                        $MainMenuTitles = Cache::rememberForever('MainMenuTitles',function(){
                            return DB::table('main_menu_title')->select(['main_menu_id','id'])->where([['menu_type','=',2],['status','=',1]])->groupBy('main_menu_id')->get();
                        });
                        
                        ?>
                        @foreach($MainMenuTitles as $row)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle " aria-haspopup="true" aria-expanded="false" type="button" data-toggle="dropdown" role="button"><i data-feather="settings" class="svg-icon"></i></a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <?php
                                    $mainMenuId = $row->main_menu_id;
                                    Cache::forget('MainMenuTitlesSub');

                                    $MainMenuTitlesSub = Cache::rememberForever('MainMenuTitlesSub',function() use ($mainMenuId){
                                        return DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$mainMenuId],['status','=',1],['menu_type','=',2]])->get();
                                    });
                                    ?>
                                    @foreach($MainMenuTitlesSub as $row1)
                                        <li class="main-menu dropdown-submenu">
                                            <a class="test dropdown-item" tabindex="-1" href="#" >{{ $row1->title}}
                                                <i data-feather="chevron-right" class="svg-icon" style="float: right"></i>
                                            </a>
                                            <ul class="submenu dropdown-menu" aria-labelledby="navbarDropdown">
                                                <?php
                                                Cache::forget('data_'.$row1->id);
                                                $data = Cache::rememberForever('data_'.$row1->id,function() use ($row1){
                                                    return DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where([['m_parent_code','=',$row1->id],['page_type','=', 1],['status','=', 1]])->get();
                                                });
                                               
                                                ?>
                                                @foreach($data as $dataValue)
                                                    <?php
                                                    $MakeUrl = url(''.$dataValue->m_controller_name.'');
                                                    $txt[] = array('url'=>$MakeUrl,'name'=>$dataValue->name);
                                                    ?>

                                                    <li>
                                                        <a class="dropdown-item test1" tabindex="-1" href="{{ url(''.$dataValue->m_controller_name.'?m='.Input::get('m')) }}"> {{ $dataValue->name }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
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
                            <span class="text-dark">{{ Auth::user()->name }}</span>
                        </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                            <a class="dropdown-item" href="{{ url('/users/editMyProfile?m='.$m) }}"><i data-feather="user" class="svg-icon mr-2 ml-1"></i>
                                My Profile
                            </a>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/users/editUserProfile?m='.$m) }}"><i data-feather="settings" class="svg-icon mr-2 ml-1"></i>
                                Account Setting
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ url('/logout') }}"><i data-feather="power" class="svg-icon mr-2 ml-1"></i>
                                Logout
                            </a>
                            <div class="dropdown-divider"></div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <aside class="left-sidebar" data-sidebarbg="skin6" style="background: #29abe2;">
        <div class="scroll-sidebar" data-sidebarbg="skin6">
            <nav class="sidebar-nav">
                <?php
                $MainMenuTitles = DB::table('main_menu_title')->select(['main_menu_id','id'])->where([['menu_type','=',1],['status','=',1]])->groupBy('main_menu_id')->get();
                ?>
                @foreach($MainMenuTitles as $row)
                    <ul id="sidebarnav">
                        <?php
                        $m = 1;
                        $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['status','=',1],['menu_type','=',1]])->get();
                        ?>
                        @foreach($MainMenuTitlesSub as $row1)

                            <li class="sidebar-item">

                                <a id="sidelink" class="sidebar-link sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <span class="hide-menu" ><i class="{{ $dashboardIcons[$i] }}"></i>
                                        {{ $row1->title }}
                                    </span>
                                </a>

                                <ul aria-expanded="false" class="collapse first-level base-level-line row">
                                    <?php
                                    $data1 = DB::table('menu')->select(['m_type','js','name','m_controller_name','m_main_title','id','m_parent_code'])->where([['m_parent_code','=',$row1->id],['page_type', '=', 1],['status','=',1]])->get();
                                    ?>
                                    @foreach($data1 as $dataValues2)
                                        <?php
                                        $MakeUrl = url(''.$dataValues2->m_controller_name.'');
                                        $txt[] = array('url'=>$MakeUrl,'name'=>$dataValues2->name);

                                        ?>
                                        <li class="sidebar-item" >
                                            <a href="{{ url(''.$dataValues2->m_controller_name.'?m='.Input::get('m')) }}" id="sidelink" class="sidebar-link sidebar-link"> <span class="hide-menu">{{ $dataValues2->name }}</span>
                                            </a>
                                        </li>

                                    @endforeach
                                    <?php $i++; ?>
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
                <?php
                Storage::disk('local')->put('menu.json',response()->json($txt)->getContent() );
                ?>
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

    </script>