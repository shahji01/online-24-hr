<?php
	$accType = Auth::user()->acc_type;
	if($accType == 'client'){
		$m = $_GET['m'];
	}else{
		$m = Auth::user()->company_id;
	}
	$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
?>
<div class="wsmenucontainer clearfix">
	<div class="overlapblackbg"></div>
	<div class="wsmobileheader clearfix">
		<a id="wsnavtoggle" class="animated-arrow"><span></span></a>
	</div>
		<div class="header">
		<div class="wrapper clearfix bigmegamenu">
			<!--Main Menu HTML Code-->
			<nav class="wsmenu clearfix">
				<ul class="mobile-sub wsmenu-list">
					<li aria-haspopup="true"><a href="#" class="{{ Request::is('dCompany')? 'active': '' }}"> Dashboard</a></li>
					<li aria-haspopup="true"><a href="#" class="{{ Request::is('hr/h','hr/*')? 'active': '' }}">HR <span class="arrow"></span></a>
						<div class="megamenu clearfix">
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Hiring Request</li>
                                <?php
                                $HRHiringRequests = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Hiring Request')->get();
                                ?>
								@foreach($HRHiringRequests as $HRHiringRequest)
									<li class="subtitle">{{ $HRHiringRequest->title }}</li>
                                    <?php
                                    $HRHRequests = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRHiringRequest->id)->get();
                                    foreach($HRHRequests as $HRHRequest){
                                    $HRHRequestMakeUrl = url(''.$HRHRequest->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$HRHRequest->m_controller_name.'?pageType='.$HRHRequest->m_type.'&&parentCode='.$HRHRequest->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $HRHRequest->name;?></a></li>
                                    <?php } ?>
								@endforeach

								<li class="title">Advance Salary</li>
                                <?php
                                $HRAdvanceSalarys = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Advance Salary')->get();
                                ?>
								@foreach($HRAdvanceSalarys as $HRAdvanceSalary)
									<li class="subtitle">{{ $HRAdvanceSalary->title }}</li>
                                    <?php
                                    $HRASalarys = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRAdvanceSalary->id)->get();
                                    foreach($HRASalarys as $HRASalary){
                                    $HRASalaryMakeUrl = url(''.$HRASalary->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$HRASalary->m_controller_name.'?pageType='.$HRASalary->m_type.'&&parentCode='.$HRASalary->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $HRASalary->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Employees</li>
                                <?php
                                $HREmployees = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Employee')->get();
                                ?>
								@foreach($HREmployees as $HREmployee)
									<li class="subtitle">{{ $HREmployee->title }}</li>
                                    <?php
                                    $HREmps = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HREmployee->id)->get();
                                    foreach($HREmps as $HREmp){
                                    $HREmpMakeUrl = url(''.$HREmp->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$HREmp->m_controller_name.'?pageType='.$HREmp->m_type.'&&parentCode='.$HREmp->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $HREmp->name;?></a></li>
                                    <?php } ?>
								@endforeach

								<li class="title">Leaves Policy</li>
                                <?php
                                $HRLeavesPolicys = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Leaves Policy')->get();
                                ?>
								@foreach($HRLeavesPolicys as $HRLeavesPolicy)
									<li class="subtitle">{{ $HRLeavesPolicy->title }}</li>
                                    <?php
                                    $HRLPolicys = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRLeavesPolicy->id)->get();
                                    foreach($HRLPolicys as $HRLPolicy){
                                    $HRLPolicyMakeUrl = url(''.$HRLPolicy->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$HRLPolicy->m_controller_name.'?pageType='.$HRLPolicy->m_type.'&&parentCode='.$HRLPolicy->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $HRLPolicy->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Attendance</li>
                                <?php
                                $HRAttendances = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Attendance')->get();
                                ?>
								@foreach($HRAttendances as $HRAttendance)
									<li class="subtitle">{{ $HRAttendance->title }}</li>
                                    <?php
                                    $HRAttens = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRAttendance->id)->get();
                                    foreach($HRAttens as $HRAtten){
                                    $HRAttenMakeUrl = url(''.$HRAtten->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$HRAtten->m_controller_name.'?pageType='.$HRAtten->m_type.'&&parentCode='.$HRAtten->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $HRAtten->name;?></a></li>
                                    <?php } ?>
								@endforeach

								<li class="title">Car Policy</li>
                                <?php
                                $HRCarPolicys = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Car Policy')->get();
                                ?>
								@foreach($HRCarPolicys as $HRCarPolicy)
									<li class="subtitle">{{ $HRCarPolicy->title }}</li>
                                    <?php
                                    $HRCPolicys = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRCarPolicy->id)->get();
                                    foreach($HRCPolicys as $HRCPolicy){
                                    $HRCPolicyMakeUrl = url(''.$HRCPolicy->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$HRCPolicy->m_controller_name.'?pageType='.$HRCPolicy->m_type.'&&parentCode='.$HRCPolicy->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $HRCPolicy->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Payroll</li>
                                <?php
                                $HRPayrolls = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Payroll')->get();
                                ?>
								@foreach($HRPayrolls as $HRPayroll)
									<li class="subtitle">{{ $HRPayroll->title }}</li>
                                    <?php
                                    $HRPays = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRPayroll->id)->get();
                                    foreach($HRPays as $HRPay){
                                    $HRPayMakeUrl = url(''.$HRPay->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$HRPay->m_controller_name.'?pageType='.$HRPay->m_type.'&&parentCode='.$HRPay->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $HRPay->name;?></a></li>
                                    <?php } ?>
								@endforeach

								<li class="title">Loan Request</li>
                                <?php
                                $HRLoanRequests = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Loan Request')->get();
                                ?>
								@foreach($HRLoanRequests as $HRLoanRequest)
									<li class="subtitle">{{ $HRLoanRequest->title }}</li>
                                    <?php
                                    $HRLRequests = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRLoanRequest->id)->get();
                                    foreach($HRLRequests as $HRLRequest){
                                    $HRLRequestMakeUrl = url(''.$HRLRequest->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$HRLRequest->m_controller_name.'?pageType='.$HRLRequest->m_type.'&&parentCode='.$HRLRequest->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $HRLRequest->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Allowance</li>
                                <?php
                                $HRAllowances = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Allowance')->get();
                                ?>
								@foreach($HRAllowances as $HRAllowance)
									<li class="subtitle">{{ $HRAllowance->title }}</li>
                                    <?php
                                    $HRAllos = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRAllowance->id)->get();
                                    foreach($HRAllos as $HRAllo){
                                    $HRAlloMakeUrl = url(''.$HRAllo->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$HRAllo->m_controller_name.'?pageType='.$HRAllo->m_type.'&&parentCode='.$HRAllo->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $HRAllo->name;?></a></li>
                                    <?php } ?>
								@endforeach

								<li class="title">Bonus</li>
                                <?php
                                $HRBonuss = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Bonus')->get();
                                ?>
								@foreach($HRBonuss as $HRBonus)
									<li class="subtitle">{{ $HRBonus->title }}</li>
                                    <?php
                                    $HRBonus = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRBonus->id)->get();
                                    foreach($HRBonus as $HRBonu){
                                    $HRBonuMakeUrl = url(''.$HRBonu->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$HRBonu->m_controller_name.'?pageType='.$HRBonu->m_type.'&&parentCode='.$HRBonu->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $HRBonu->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Deduction</li>
                                <?php
                                $HRDeductions = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Deduction')->get();
                                ?>
								@foreach($HRDeductions as $HRDeduction)
									<li class="subtitle">{{ $HRDeduction->title }}</li>
                                    <?php
                                    $HRDeducs = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRDeduction->id)->get();
                                    foreach($HRDeducs as $HRDeduc){
                                    $HRDeducMakeUrl = url(''.$HRDeduc->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$HRDeduc->m_controller_name.'?pageType='.$HRDeduc->m_type.'&&parentCode='.$HRDeduc->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $HRDeduc->name;?></a></li>
                                    <?php } ?>
								@endforeach

								<li class="title">Leave Application</li>
                                <?php
                                $HRLeaveApplications = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Leave Application')->get();
                                ?>
								@foreach($HRLeaveApplications as $HRLeaveApplication)
									<li class="subtitle">{{ $HRLeaveApplication->title }}</li>
                                    <?php
                                    $HRLApplications = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRLeaveApplication->id)->get();
                                    foreach($HRLApplications as $HRLApplication){
                                    $HRLApplicationMakeUrl = url(''.$HRLApplication->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$HRLApplication->m_controller_name.'?pageType='.$HRLApplication->m_type.'&&parentCode='.$HRLApplication->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $HRLApplication->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
						</div>
					</li>

					<li aria-haspopup="true"><a href="#" class="{{ Request::is('finance/f','finance/*')? 'active': '' }}"><i class="fa fa-align-justify"></i>&nbsp;&nbsp; Finance <span class="arrow"></span></a>
						<div class="megamenu clearfix">
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Payment Vouchers</li>
                                <?php
                                $FinancePaymentVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Finance')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', '% Payment %')->get();
                                ?>
								@foreach($FinancePaymentVouchers as $FinancePaymentVoucher)
									<li class="subtitle">{{ $FinancePaymentVoucher->title }}</li>
                                    <?php
                                    $FinancePayments = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$FinancePaymentVoucher->id)->get();
                                    foreach($FinancePayments as $FinancePayment){
                                    $FinancePaymentMakeUrl = url(''.$FinancePayment->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$FinancePayment->m_controller_name.'?pageType='.$FinancePayment->m_type.'&&parentCode='.$FinancePayment->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $FinancePayment->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Receipt Vouchers</li>
                                <?php
                                $FinanceReceiptVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Finance')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', '% Receipt %')->get();
                                ?>
								@foreach($FinanceReceiptVouchers as $FinanceReceiptVoucher)
									<li class="subtitle">{{ $FinanceReceiptVoucher->title }}</li>
                                    <?php
                                    $FinanceReceipts = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$FinanceReceiptVoucher->id)->get();
                                    foreach($FinanceReceipts as $FinanceReceipt){
                                    $FinanceReceiptMakeUrl = url(''.$FinanceReceipt->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$FinanceReceipt->m_controller_name.'?pageType='.$FinanceReceipt->m_type.'&&parentCode='.$FinanceReceipt->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $FinanceReceipt->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Journal Vouchers</li>
                                <?php
                                $FinanceJournalVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Finance')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', '%Journal%')->get();
                                ?>
								@foreach($FinanceJournalVouchers as $FinanceJournalVoucher)
									<li class="subtitle">{{ $FinanceJournalVoucher->title }}</li>
                                    <?php
                                    $FinanceJournals = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$FinanceJournalVoucher->id)->get();
                                    foreach($FinanceJournals as $FinanceJournal){
                                    $FinanceJournalMakeUrl = url(''.$FinanceJournal->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$FinanceJournal->m_controller_name.'?pageType='.$FinanceJournal->m_type.'&&parentCode='.$FinanceJournal->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $FinanceJournal->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Fiannce Reports</li>
                                <?php
                                $FinanceMasterMenuTitles = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Finance')->where('status','=','1')->where('menu_type','=','3')->get();
                                ?>
								@foreach($FinanceMasterMenuTitles as $FinanceMasterMenuTitle)
									<li class="subtitle">{{ $FinanceMasterMenuTitle->title }}</li>
                                    <?php
                                    $FinancesubMenu = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$FinanceMasterMenuTitle->id)->get();
                                    foreach($FinancesubMenu as $Financerow1){
                                    $FinancemakeUrl = url(''.$Financerow1->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$Financerow1->m_controller_name.'?pageType='.$Financerow1->m_type.'&&parentCode='.$Financerow1->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $Financerow1->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Master Tables</li>
                                <?php
                                $FinanceMasterMenuTitles = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Finance')->where('status','=','1')->where('menu_type','=','2')->get();
                                ?>
								@foreach($FinanceMasterMenuTitles as $FinanceMasterMenuTitle)
									<li class="subtitle">{{ $FinanceMasterMenuTitle->title }}</li>
                                    <?php
                                    $FinancesubMenu = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$FinanceMasterMenuTitle->id)->get();
                                    foreach($FinancesubMenu as $Financerow1){
                                    $FinancemakeUrl = url(''.$Financerow1->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$Financerow1->m_controller_name.'?pageType='.$Financerow1->m_type.'&&parentCode='.$Financerow1->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $Financerow1->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
						</div>
					</li>

					<li aria-haspopup="true"><a href="#" class="{{ Request::is('purchase/p','purchase/*')? 'active': '' }}"><i class="fa fa-align-justify"></i>&nbsp;&nbsp; Purchase <span class="arrow"></span></a>
						<div class="megamenu clearfix">
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Demand Vouchers</li>
                                <?php
                                $PurchaseDemandVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Purchase')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', '%Demand%')->get();
                                ?>
								@foreach($PurchaseDemandVouchers as $PurchaseDemandVoucher)
									<li class="subtitle">{{ $PurchaseDemandVoucher->title }}</li>
                                    <?php
                                    $PurchaseDemands = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$PurchaseDemandVoucher->id)->get();
                                    foreach($PurchaseDemands as $PurchaseDemand){
                                    $PurchaseDemandMakeUrl = url(''.$PurchaseDemand->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$PurchaseDemand->m_controller_name.'?pageType='.$PurchaseDemand->m_type.'&&parentCode='.$PurchaseDemand->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $PurchaseDemand->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-3 col-md-3 col-xs-12 link-list">
								<li class="title">Goods Receipt Note Vouchers</li>
                                <?php
                                $PurchaseGoodsReceiptNoteVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Purchase')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', '%Goods Receipt Note%')->get();
                                ?>
								@foreach($PurchaseGoodsReceiptNoteVouchers as $PurchaseGoodsReceiptNoteVoucher)
									<li class="subtitle">{{ $PurchaseGoodsReceiptNoteVoucher->title }}</li>
                                    <?php
                                    $PurchaseGoodsReceiptNotes = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$PurchaseGoodsReceiptNoteVoucher->id)->get();
                                    foreach($PurchaseGoodsReceiptNotes as $PurchaseGoodsReceiptNote){
                                    $PurchaseGoodsReceiptNoteMakeUrl = url(''.$PurchaseGoodsReceiptNote->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$PurchaseGoodsReceiptNote->m_controller_name.'?pageType='.$PurchaseGoodsReceiptNote->m_type.'&&parentCode='.$PurchaseGoodsReceiptNote->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $PurchaseGoodsReceiptNote->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-3 col-md-3 col-xs-12 link-list">

							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Master Tables</li>
                                <?php
                                $PurchaseMasterMenuTitles = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Purchase')->where('status','=','1')->where('menu_type','=','2')->get();
                                ?>
								@foreach($PurchaseMasterMenuTitles as $PurchaseMasterMenuTitle)
									<li class="subtitle">{{ $PurchaseMasterMenuTitle->title }}</li>
                                    <?php
                                    $PurchasesubMenu = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$PurchaseMasterMenuTitle->id)->get();
                                    foreach($PurchasesubMenu as $Purchaserow1){
                                    $PurchasemakeUrl = url(''.$Purchaserow1->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$Purchaserow1->m_controller_name.'?pageType='.$Purchaserow1->m_type.'&&parentCode='.$Purchaserow1->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $Purchaserow1->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
						</div>
					</li>

					<li aria-haspopup="true"><a href="#" class="{{ Request::is('store/st','store/*','stad/*')? 'active': '' }}"><i class="fa fa-align-justify"></i>&nbsp;&nbsp; Store <span class="arrow"></span></a>
						<div class="megamenu clearfix">
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Demand Vouchers</li>
                                <?php
                                $StoreDemandVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Store')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', '%Demand%')->get();
                                ?>
								@foreach($StoreDemandVouchers as $StoreDemandVoucher)
									<li class="subtitle">{{ $StoreDemandVoucher->title }}</li>
                                    <?php
                                    $StoreDemands = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$StoreDemandVoucher->id)->get();
                                    foreach($StoreDemands as $StoreDemand){
                                    $StoreDemandMakeUrl = url(''.$StoreDemand->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$StoreDemand->m_controller_name.'?pageType='.$StoreDemand->m_type.'&&parentCode='.$StoreDemand->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $StoreDemand->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Store Challan Vouchers</li>
                                <?php
                                $StoreStoreChallanVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Store')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Store Challan')->get();
                                ?>
								@foreach($StoreStoreChallanVouchers as $StoreStoreChallanVoucher)
									<li class="subtitle">{{ $StoreStoreChallanVoucher->title }}</li>
                                    <?php
                                    $StoreStoreChallans = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$StoreStoreChallanVoucher->id)->get();
                                    foreach($StoreStoreChallans as $StoreStoreChallan){
                                    $StoreStoreChallanMakeUrl = url(''.$StoreStoreChallan->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$StoreStoreChallan->m_controller_name.'?pageType='.$StoreStoreChallan->m_type.'&&parentCode='.$StoreStoreChallan->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $StoreStoreChallan->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Purchase Request Vouchers</li>
                                <?php
                                $StorePurchaseRequestVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Store')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Purchase Request')->get();
                                ?>
								@foreach($StorePurchaseRequestVouchers as $StorePurchaseRequestVoucher)
									<li class="subtitle">{{ $StorePurchaseRequestVoucher->title }}</li>
                                    <?php
                                    $StorePurchaseRequests = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$StorePurchaseRequestVoucher->id)->get();
                                    foreach($StorePurchaseRequests as $StorePurchaseRequest){
                                    $StorePurchaseRequestMakeUrl = url(''.$StorePurchaseRequest->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$StorePurchaseRequest->m_controller_name.'?pageType='.$StorePurchaseRequest->m_type.'&&parentCode='.$StorePurchaseRequest->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $StorePurchaseRequest->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-3 col-md-3 col-xs-12 link-list">
								<li class="title">Store Challan Return Vouchers</li>
                                <?php
                                $StoreStoreChallanReturnVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Store')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', '%Store Challan Return%')->get();
                                ?>
								@foreach($StoreStoreChallanReturnVouchers as $StoreStoreChallanReturnVoucher)
									<li class="subtitle">{{ $StoreStoreChallanReturnVoucher->title }}</li>
                                    <?php
                                    $StoreStoreChallanReturns = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$StoreStoreChallanReturnVoucher->id)->get();
                                    foreach($StoreStoreChallanReturns as $StoreStoreChallanReturn){
                                    $StoreStoreChallanReturnMakeUrl = url(''.$StoreStoreChallanReturn->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$StoreStoreChallanReturn->m_controller_name.'?pageType='.$StoreStoreChallanReturn->m_type.'&&parentCode='.$StoreStoreChallanReturn->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $StoreStoreChallanReturn->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Store Reports</li>
                                <?php
                                $StoreMasterMenuTitles = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Store')->where('status','=','1')->where('menu_type','=','3')->get();
                                ?>
								@foreach($StoreMasterMenuTitles as $StoreMasterMenuTitle)
									<li class="subtitle">{{ $StoreMasterMenuTitle->title }}</li>
                                    <?php
                                    $StoresubMenu = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$StoreMasterMenuTitle->id)->get();
                                    foreach($StoresubMenu as $Storerow1){
                                    $StoremakeUrl = url(''.$Storerow1->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$Storerow1->m_controller_name.'?pageType='.$Storerow1->m_type.'&&parentCode='.$Storerow1->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $Storerow1->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
						</div>
					</li>
					<li aria-haspopup="true"><a href="#" class="{{ Request::is('sales/s','sales/*')? 'active': '' }}"><i class="fa fa-align-justify"></i>&nbsp;&nbsp; Sale <span class="arrow"></span></a>
						<div class="megamenu clearfix">
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Cash Sale Vouchers</li>
                                <?php
                                $SaleCashVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Sales')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Cash Sale Vouchers')->get();
                                ?>
								@foreach($SaleCashVouchers as $SaleCashVoucher)
									<li class="subtitle">{{ $SaleCashVoucher->title }}</li>
                                    <?php
                                    $SaleCashs = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$SaleCashVoucher->id)->get();
                                    foreach($SaleCashs as $SaleCash){
                                    $SaleCashMakeUrl = url(''.$SaleCash->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$SaleCash->m_controller_name.'?pageType='.$SaleCash->m_type.'&&parentCode='.$SaleCash->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $SaleCash->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Credit Sale Vouchers</li>
                                <?php
                                $SaleCreditVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Sales')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Credit Sale Vouchers')->get();
                                ?>
								@foreach($SaleCreditVouchers as $SaleCreditVoucher)
									<li class="subtitle">{{ $SaleCreditVoucher->title }}</li>
                                    <?php
                                    $SaleCredits = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$SaleCreditVoucher->id)->get();
                                    foreach($SaleCredits as $SaleCredit){
                                    $SaleCreditMakeUrl = url(''.$SaleCredit->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$SaleCredit->m_controller_name.'?pageType='.$SaleCredit->m_type.'&&parentCode='.$SaleCredit->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $SaleCredit->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title"></li>
							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Sale Reports</li>

							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">
								<li class="title">Master Tables</li>
                                <?php
                                $SaleMasterMenuTitles = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Sales')->where('status','=','1')->where('menu_type','=','2')->get();
                                ?>
								@foreach($SaleMasterMenuTitles as $SaleMasterMenuTitle)
									<li class="subtitle">{{ $SaleMasterMenuTitle->title }}</li>
                                    <?php
                                    $SalesubMenu = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$SaleMasterMenuTitle->id)->get();
                                    foreach($SalesubMenu as $Salerow1){
                                    $SalemakeUrl = url(''.$Salerow1->m_controller_name.'');
                                    ?>
									<li><a href="<?php echo url(''.$Salerow1->m_controller_name.'?pageType='.$Salerow1->m_type.'&&parentCode='.$Salerow1->m_parent_code.'&&m='.$m.'#Online24')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $Salerow1->name;?></a></li>
                                    <?php } ?>
								@endforeach
							</ul>
						</div>
					</li>
					<li aria-haspopup="true"><a href="#"><i class="fa fa-align-justify"></i>&nbsp;&nbsp; Users <span class="arrow"></span></a>
						<div class="megamenu clearfix">
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">

							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">

							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">

							</ul>
							<ul class="col-lg-3 col-md-3 col-xs-12 link-list">

							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">

							</ul>
						</div>
					</li>
					<li aria-haspopup="true"><a href="#"><i class="fa fa-align-justify"></i>&nbsp;&nbsp; Reports <span class="arrow"></span></a>
						<div class="megamenu clearfix">
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">

							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">

							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">

							</ul>
							<ul class="col-lg-3 col-md-3 col-xs-12 link-list">

							</ul>
							<ul class="col-lg-2 col-md-2 col-xs-12 link-list">

							</ul>
						</div>
					</li>
					<li aria-haspopup="true"><a href="{{ url('/logout') }}"> {{ Auth::user()->name }}  =>  Logout <i class="fa fa-btn fa-sign-out"></i></a>
					</li>
				</ul>
			</nav>
			<!--Menu HTML Code-->



		</div>


	</div>
</div>
<br />
<br />
<br />
<!--For Demo Only (End Removable) -->
<input type="hidden" id="url" value="<?php echo url('/') ?>">


<!-- MENU SECTION END-->