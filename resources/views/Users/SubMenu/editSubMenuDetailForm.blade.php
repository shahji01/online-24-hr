<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row">

            </div>
            <hr>


                {{ Form::open(array('url' => 'uad/editSubMenuDetail','id'=>'editSubMenuForm')) }}

                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="subid" id="subid" value="{{ $subMenu->id }}">
            <input type="hidden" name="companyId" id="companyId" value="12">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>Main Navigation Name</label><br>
                        <select class="form-control" name="main_navigation_name" id="main_navigation_name">
                            <option value="">Select Main Navigation</option>
                            @foreach($MainMenuTitles as $key => $y)
                                @if($subMenu->m_main_title == $y->title_id)
                                <option selected value="<?php echo $y->id.'_'.$y->title_id?>">{{ $y->title}}</option>
                                @else
                                    <option value="<?php echo $y->id.'_'.$y->title_id?>">{{ $y->title}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>Sub Navigation Title Name</label>
                        <input type="text" name="sub_navigation_title_name" id="sub_navigation_title_name" value="{{ $subMenu->name }}" class="form-control" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>Sub Navigation Url</label>
                        <input type="text" name="sub_navigation_url" id="sub_navigation_url" value="{{ $subMenu->m_controller_name }}" class="form-control" />
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>Js</label>
                        <input type="text" name="js" id="js" value="{{ $subMenu->js }}" class="form-control" />
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label>Page Type</label>
                        <select class="form-control" name="page_type" id="page_type">
                            @if($subMenu->page_type==1)
                                <option selected value="1">Outer Page</option>
                                <option value="2">Inner Page</option>
                            @else
                                <option value="1">Outer Page</option>
                                <option selected value="2">Inner Page</option>
                            @endif


                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        {{ Form::submit('Update', ['class' => 'btn btn-sm btn-success']) }}
                        <button type="reset" id="reset" class="btn btn-sm btn-primary">Clear Form</button>
                    </div>
                </div>
                {{ Form::close() }}



            <div class="text-center ajax-loader"></div>
        </div>

    </div>
</div>

