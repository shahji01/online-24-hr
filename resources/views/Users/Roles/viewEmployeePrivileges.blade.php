<?php
use App\Models\Menu;

$main_modules = explode(",",$roles[0]['main_modules']);
$submenu_ids  = explode(",",$roles[0]['submenu_id']);
$location_ids  = explode(",",$roles[0]['location_permission']);
?>

@extends('layouts.default')

@section('css')
    <style>
        input[type="radio"],
        input[type="checkbox"] {
            box-sizing: border-box;
            padding: 0;
            /* width: 20%; */
            /* height: 3vh; */
            width: 20px;
            height: 20px;
        }

        .panel-heading.headerOfmenu {
            display: flex;
            justify-content: space-between;
            background-color: #7d9ce3c9;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;
            color: white;
            padding: 1%;
        }

        .pagesList {
            /* width: 150px; */
        }
    </style>
@endsection

@section('content')

    <div class="page-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4 class="card-title">Edit Role and Permission Form</h4>
                            </div>
                        </div>
                        <hr>
                        {{ Form::open(array('url' => 'uad/editUserRoleDetail')) }}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" value="{{ Input::get('m') }}">
                        <input type="hidden" name="id" value="{{ $roles[0]['id'] }}">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label class="sf-label pointer">Role Name</label>
                                        <input type="text" name="role_name" id="role_name" class="form-control" value="{{ $roles[0]['role_name'] }}">
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 panel panel-primary">
                                        <?php
                                        $MainMenuTitles = DB::table('main_menu_title')->select(['id','main_menu_id'])->where([['menu_type','=',1],['title_id', '=', 'dashboard'],['status', '=', 1]])->groupBy('main_menu_id')->get();

                                        $counter = 1;
                                        foreach($MainMenuTitles as $row){ ?>

                                        <div class="panel-heading headerOfmenu">
                                            <h3 class="panel-title">Dashboard</h3>
                                            <input @if(in_array($row->id,$main_modules)) checked @endif style="float: right" type="checkbox" id="{{ $row->main_menu_id }}" onclick="showPrilvigesMenu('<?php echo $row->main_menu_id;?>')" name="main_modules[]" value="<?php echo $row->id;?>">
                                        </div>
                                        <div class="panel-body" id="Prilviges_<?php echo $row->main_menu_id;?>">
                                            <?php
                                            $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['menu_type','=',1],['status', '=', 1]])->orderBy('id', 'desc')->get();
                                            foreach($MainMenuTitlesSub as $row1){ ?>
                                            <input type="hidden" name="menu_title_{{ $row->id }}[]" value="{{ $row1->title_id }}" />

                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" id="project">
                                                    <p><label>Location Access :</label></p>
                                                    @foreach($locations as $key => $loc)
                                                        <ul class="nav">
                                                            <li class="pagesList">
                                                                <input @if(in_array($loc->id,$location_ids)) checked @endif onclick="checkLocations()" class="locations" name="locations[]" type="checkbox" value="{{ $loc->id }}">
                                                                &nbsp;<strong>{{ $loc->location_name }}</strong>
                                                            </li>
                                                            <br>
                                                        </ul>
                                                    @endforeach
                                                    <span style="font-size:18px;" class="locationError"></span>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <p><label>Pages / Screens :</label></p>
                                                    <?php
                                                    $data = DB::table('menu')->select(['name','id'])->where([['m_parent_code','=',$row1->id],['status','=', 1]])->get();
                                                    foreach($data as $dataValue){
                                                    $data = explode(' ',$dataValue->name);
                                                    ?>
                                                    <ul class="nav">
                                                        <li class="pagesList">
                                                            <input @if(in_array($dataValue->id,$submenu_ids)) checked @endif class="{{ $row->main_menu_id.'_child' }}" name="sub_menu_<?php echo $row1->title_id; ?>[]" type="checkbox" value="<?=$dataValue->id?>">
                                                            <strong> <?php echo $dataValue->name;?></strong>&nbsp;&nbsp;
                                                        </li>
                                                        <br>
                                                    </ul>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <p><label>Default Dashboard :</label></p>
                                                    <?php

                                                    $crud_permission[]='';
                                                    $crud_rights  = explode(",",$roles[0]['crud_rights']);

                                                    if(in_array('HR_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "HR";
                                                    endif;
                                                    if(in_array('User_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "User";
                                                    endif;

                                                    $data = DB::table('menu')->select(['name','id'])->where([['m_parent_code','=',$row1->id],['status','=', 1]])->get();
                                                    foreach($data as $dataValue){
                                                    $data = explode(' ',$dataValue->name);
                                                    ?>
                                                    <input type="radio" @if(in_array($data[0],$crud_permission))   checked @endif class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="<?=$data[0]?>" /> <strong><?php echo $dataValue->name;?></strong>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="lineHeight">&nbsp;</div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                            <div class="row">
                                <?php
                                $MainMenuTitles = DB::table('main_menu_title')->select(['id','main_menu_id'])->where([['menu_type','=',1],['status', '=', 1],['title_id', '!=', 'dashboard']])->groupBy('main_menu_id')->get();

                                $counter = 1;
                                foreach($MainMenuTitles as $row){
                                ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 1%">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading headerOfmenu">
                                            <h3 class="panel-title"><?php echo $row->main_menu_id;?></h3>
                                            <input @if(in_array($row->id,$main_modules)) checked @endif style="float: right"  type="checkbox"
                                                   onclick="showPrilvigesMenu('<?php echo $row->main_menu_id;?>')" id="<?php echo $row->main_menu_id;?>"
                                                   name="main_modules[]" value="<?php echo $row->id;?>">
                                        </div>
                                        <div class="panel-body" id="Prilviges_<?php echo $row->main_menu_id;?>">
                                            <?php
                                            $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['menu_type','=',1],['status', '=', 1]])->orderBy('id', 'desc')->get();
                                            foreach($MainMenuTitlesSub as $row1){
                                            ?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label style="text-decoration: underline;color:royalblue;"><?php echo $row1->title; ?> :</label>
                                                    <input type="hidden" name="menu_title_<?php echo $row->id;?>[]" value="<?php echo $row1->title_id;?>" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <p><label>Pages / Screens :</label></p>
                                                    <ul class="nav">
                                                        <?php
                                                        $data = DB::table('menu')->select(['name','id'])->where([['m_parent_code','=',$row1->id],['status','=', 1]])->get();
                                                        foreach($data as $dataValue){?>
                                                        <li class="pagesList">
                                                            <input @if(in_array($dataValue->id,$submenu_ids)) checked @endif class="{{$row->main_menu_id.'_child'}}" name="sub_menu_<?php echo $row1->title_id; ?>[]"  type="checkbox" value="<?=$dataValue->id?>">
                                                            &nbsp;<strong> <?php echo $dataValue->name;?></strong>&nbsp;&nbsp;
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <?php
                                                    $crud_permission[]='';
                                                    $crud_rights  = explode(",",$roles[0]['crud_rights']);


                                                    if(in_array('view_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "view";
                                                    endif;
                                                    if(in_array('edit_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "edit";
                                                    endif;
                                                    if(in_array('repost_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "repost";
                                                    endif;
                                                    if(in_array('delete_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "delete";
                                                    endif;
                                                    if(in_array('print_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "print";
                                                    endif;
                                                    if(in_array('export_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "export";
                                                    endif;
                                                    if(in_array('approve_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "approve";
                                                    endif;
                                                    if(in_array('reject_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "reject";
                                                    endif;
                                                    ?>
                                                    <p><label>Actions :</label></p>
                                                    <ul class="privilegesList nav">
                                                        <li class="pagesList">
                                                            <input @if(in_array('view',$crud_permission))   checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="view" /> &nbsp;<strong>View</strong>&nbsp;&nbsp;</li>
                                                        <li class="pagesList">
                                                            <input @if(in_array('edit',$crud_permission))   checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="edit" /> &nbsp;<strong>Edit</strong>&nbsp;&nbsp;</li>
                                                        <li class="pagesList">
                                                            <input @if(in_array('approve',$crud_permission))checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="approve" /> &nbsp;<strong>Approve</strong>&nbsp;&nbsp;</li>
                                                        <li class="pagesList">
                                                            <input @if(in_array('reject',$crud_permission)) checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="reject" /> &nbsp;<strong>Reject</strong>&nbsp;&nbsp;</li>
                                                        <li class="pagesList">
                                                            <input @if(in_array('repost',$crud_permission)) checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="repost" /> &nbsp;<strong>Repost</strong>&nbsp;&nbsp;</li>
                                                        <li class="pagesList">
                                                            <input @if(in_array('delete',$crud_permission)) checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="delete" /> &nbsp;<strong>Delete</strong>&nbsp;&nbsp;</li>
                                                        <li class="pagesList">
                                                            <input @if(in_array('print',$crud_permission))  checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="print" /> &nbsp;<strong>Print</strong>&nbsp;&nbsp;</li>
                                                        <li class="pagesList">
                                                            <input @if(in_array('export',$crud_permission)) checked @endif type="checkbox" class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="export" /> &nbsp;<strong>Export</strong>&nbsp;&nbsp;</li>

                                                    </ul>
                                                    <?php $crud_permission[]=''; ?>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                            <div class="row">
                                <?php
                                $MainMenuTitles = DB::table('main_menu_title')->select(['id','main_menu_id'])->where([['menu_type','=',2],['status', '=', 1]])->groupBy('main_menu_id')->get();
                                $counter = 1;
                                foreach($MainMenuTitles as $row){
                                ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 1%">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading headerOfmenu">
                                            <h3 class="panel-title"><?php echo $row->main_menu_id ;?></h3>
                                            <input @if(in_array($row->id,$main_modules)) checked @endif style="float: right" type="checkbox"
                                                   onclick="showPrilvigesMenu('<?php echo str_replace(' ', '_', $row->main_menu_id); ?>','master')"
                                                   id="<?php echo $row->main_menu_id;?>_master" name="main_modules[]" value="<?php echo $row->id;?>">
                                        </div>
                                        <div class="panel-body" id="Prilviges_master_<?php echo str_replace(' ', '_', $row->main_menu_id); ?>">
                                            <?php
                                            $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['status', '=', 1],['menu_type','=',2]])->orderBy('id', 'desc')->get();
                                            foreach($MainMenuTitlesSub as $row1){
                                            ?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label style="text-decoration: underline;color:royalblue;"><?php echo $row1->title; ?> :</label>
                                                    <input type="hidden" name="menu_title_<?php echo $row->id;?>[]" value="<?php echo $row1->title_id; ?>" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <p><label>Pages / Screens :</label></p>
                                                    <ul class="nav">
                                                        <?php
                                                        $data = DB::table('menu')->select(['name','id'])->where([['m_parent_code','=',$row1->id],['status','=', 1]])->get();
                                                        foreach($data as $dataValue){?>
                                                        <li>
                                                            <input @if(in_array($dataValue->id,$submenu_ids)) checked @endif class="{{$row->main_menu_id.'_child'}}" name="sub_menu_<?php echo $row1->title_id; ?>[]" type="checkbox" value="<?=$dataValue->id?>">
                                                            &nbsp;<strong> <?php echo $dataValue->name;?></strong>&nbsp;&nbsp;
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <?php
                                                    $crud_permission[]='';
                                                    $crud_rights  = explode(",",$roles[0]['crud_rights']);

                                                    if(in_array('edit_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "edit";
                                                    endif;
                                                    if(in_array('repost_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "repost";
                                                    endif;
                                                    if(in_array('delete_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "delete";
                                                    endif;
                                                    if(in_array('print_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "print";
                                                    endif;
                                                    if(in_array('export_'.$row1->title_id,$crud_rights)):
                                                        $crud_permission[] = "export";
                                                    endif;

                                                    ?>
                                                    <p><label>Actions :</label></p>
                                                    <ul class="privilegesList nav">
                                                        <li class="pagesList"><input type="checkbox" @if(in_array('edit',$crud_permission)) checked @endif class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="edit" /> &nbsp;<strong>Edit</strong>&nbsp;&nbsp;</li>
                                                        <li class="pagesList"><input type="checkbox" @if(in_array('delete',$crud_permission)) checked @endif class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="delete" /> &nbsp;<strong>Delete</strong>&nbsp;&nbsp;</li>
                                                        <li class="pagesList"><input type="checkbox" @if(in_array('print',$crud_permission)) checked @endif class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="print" /> &nbsp;<strong>Print</strong>&nbsp;&nbsp;</li>
                                                        <li class="pagesList"><input type="checkbox" @if(in_array('export',$crud_permission)) checked @endif class="{{$row->main_menu_id.'_child'}}" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="export" /> &nbsp;<strong>Export</strong>&nbsp;&nbsp;</li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                            <div class="row">&nbsp;</div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <button type="submit" class="btn btn-sm btn-success">Update</button>
                            </div>
                        </div>
                    </div>

                    <div class="text-right"><span class="regionError" style="font-size:18px;"></span></div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        function showPrilvigesMenu(name,type) {

            if(type == 'master') {
                if($('#'+name+'_master').is(':checked')) {
                    $('#Prilviges_master_'+name).css("display","block");
                } else {
                    $('#Prilviges_master_'+name).css("display","none");
                }
            }

            if($('#'+name).is(':checked')) {
                $('#Prilviges_'+name).css("display","block");
            } else {
                $('#Prilviges_'+name).css("display","none");
            }
        }

//        function checkLocations() {
//            var counter = 0;
//            $.each($("input[class='locations']:checked"), function(){
//                counter++;
//            });
//            if(counter < 1) {
//                $('.locationError').html('<span class="label label-danger">Please Select at least one Location.</span>');
//                $('.btn').attr('disabled','disabled');
//            } else {
//                $('.locationError').html('');
//                $('.btn').removeAttr('disabled')
//            }
//        }
    </script>
@endsection