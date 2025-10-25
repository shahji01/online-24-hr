<?php

//Start HR
Route::group(['prefix' => 'hr','before' => 'csrf', 'middleware' => ['sanitize','noCache'] ], function () {
    // Refresh cache
    Route::get('/RefreshCache','HrController@RefreshCache');
    Route::get('/test','HrController@test');
    Route::get('/test1','HrController@test1');
    Route::get('/attendanceErrorReport','HrController@attendanceErrorReport');

    // fetching attendance remotely
    Route::get('/hoRefreshAttendance','HrController@hoRefreshAttendance');
    Route::get('/pqRefreshAttendance','HrController@pqRefreshAttendance');
    Route::get('/grRefreshAttendance','HrController@grRefreshAttendance');
    Route::get('/glRefreshAttendance','HrController@glRefreshAttendance');
    Route::get('/mdfRefreshAttendance','HrController@mdfRefreshAttendance');
    Route::get('/showLocationOnMap', 'HrController@showLocationOnMap');
    // Refresh cache
    Route::get('/viewLog','HrController@viewLogs');
    Route::get('/viewLogDetail','HrController@viewLogDetail');
    // final settlement
    Route::get('/createFinalSettlementForm','HrController@createFinalSettlementForm');
    Route::get('/viewFinalSettlementList','HrController@viewFinalSettlementList');
    Route::get('/editFinalSettlementDetailForm','HrController@editFinalSettlementDetailForm');
    Route::get('/viewFinalSettlementReport','HrController@viewFinalSettlementReport');
    

    Route::get('/downloadFnf/{m}/{id}','HrController@downloadFnf');
    //end final settlement

    // Probation
    Route::get('/viewEmployeeProbationExpireDetail','HrController@viewEmployeeProbationExpireDetail');
    // Probation

    Route::get('/downloadIncrementLetterPdf/{id}/{emp_id}', 'HrController@downloadIncrementLetterPdf');
    Route::get('/viewEmployeePromotionLetter/{id}/{emp_id}', 'HrController@viewEmployeePromotionLetter');

    Route::get('/sendEmail', 'HrController@sendEmail');
    Route::get('/forgotYourPassword', 'HrController@forgotYourPassword');
    Route::get('/createPfForEmployee', 'HrController@createPfForEmployee');
    Route::get('/viewPfForEmployee', 'HrController@viewPfForEmployee');
    Route::get('/editIncomeTaxDetailForm', 'HrController@editIncomeTaxDetailForm');
    Route::get('/createPfWithdrawalForm', 'HrController@createPfWithdrawalForm');
    Route::get('/viewPfWithdrawalList', 'HrController@viewPfWithdrawalList');
    Route::get('/editPfWithdrawalDetailForm', 'HrController@editPfWithdrawalDetailForm');

    Route::get('/createPfProfitForm', 'HrController@createPfProfitForm');
    Route::get('/viewPfProfitList', 'HrController@viewPfProfitList');
    Route::get('/editPfProfitDetailForm', 'HrController@editPfProfitDetailForm');

    Route::get('/addEmpTrainingForm', 'HrController@addEmpTrainingForm');

    Route::get('/viewEmpTrainingList', 'HrController@viewEmpTrainingList');

    Route::get('/h', 'HrController@toDayActivity');
    Route::get('/departmentAddNView', 'HrController@departmentAddNView');

    Route::get('/createDepartmentForm', 'HrController@createDepartmentForm');
    Route::get('/viewDepartmentList','HrController@viewDepartmentList');
    Route::get('/editDepartmentForm','HrController@editDepartmentForm');


    Route::get('/createDesignationWiseLocation','HrController@createDesignationWiseLocation');
    Route::get('/viewDesignationWiseLocation','HrController@viewDesignationWiseLocation');
    Route::get('/editDesignationWiseLocation','HrController@editDesignationWiseLocation');

    Route::get('/viewEmployeeJoiningReport','HrController@viewEmployeeJoiningReport');
    Route::get('/employeeTransferLeaves', 'HrController@employeeTransferLeaves');

    Route::get('/createSubDepartmentForm', 'HrController@createSubDepartmentForm');
    Route::get('/viewSubDepartmentList','HrController@viewSubDepartmentList');
    Route::get('/editSubDepartmentForm','HrController@editSubDepartmentForm');


    Route::get('/createDesignationForm', 'HrController@createDesignationForm');
    Route::get('/viewDesignationList','HrController@viewDesignationList');
    Route::get('/editDesignationForm','HrController@editDesignationForm');

    Route::get('/createCategoryForm', 'HrController@createCategoryForm');
    Route::get('/viewCategoryList', 'HrController@viewCategoryList');
    Route::get('/editCategoryDetailForm', 'HrController@editCategoryDetailForm');

    Route::get('/createHealthInsuranceForm', 'HrController@createHealthInsuranceForm');
    Route::get('/viewHealthInsuranceList','HrController@viewHealthInsuranceList');
    Route::get('/editHealthInsuranceForm', 'HrController@editHealthInsuranceForm');

    Route::get('/createJobTypeForm', 'HrController@createJobTypeForm');
    Route::get('/viewJobTypeList','HrController@viewJobTypeList');
    Route::get('/editJobTypeForm', 'HrController@editJobTypeForm');

    Route::get('/createQualificationForm', 'HrController@createQualificationForm');
    Route::get('/viewQualificationList','HrController@viewQualificationList');
    Route::get('/editQualificationForm', 'HrController@editQualificationForm');

    Route::get('/createLeaveTypeForm', 'HrController@createLeaveTypeForm');
    Route::get('/viewLeaveTypeList','HrController@viewLeaveTypeList');
    Route::get('/editLeaveTypeForm', 'HrController@editLeaveTypeForm');

    Route::get('/createLoanTypeForm', 'HrController@createLoanTypeForm');
    Route::get('/viewLoanTypeList','HrController@viewLoanTypeList');
    Route::get('/editLoanTypeForm', 'HrController@editLoanTypeForm');

    Route::get('/createAdvanceTypeForm', 'HrController@createAdvanceTypeForm');
    Route::get('/viewAdvanceTypeList','HrController@viewAdvanceTypeList');
    Route::get('/editAdvanceTypeForm', 'HrController@editAdvanceTypeForm');

    Route::get('/createShiftTypeForm', 'HrController@createShiftTypeForm');
    Route::get('/viewShiftTypeList','HrController@viewShiftTypeList');
    Route::get('/editShiftTypeForm', 'HrController@editShiftTypeForm');

    Route::get('/createHiringRequestAddForm','HrController@createHiringRequestAddForm');
    Route::get('/viewHiringRequestList','HrController@viewHiringRequestList');
    Route::get('/editHiringRequestForm','HrController@editHiringRequestForm');


    Route::get('/createEmployeeForm', 'HrController@createEmployeeForm');
    Route::get('/viewEmployeeList','HrController@viewEmployeeList')->middleware('isAdmin');
    Route::get('/viewEmployeeReportList','HrController@viewEmployeeReportList')->middleware('isAdmin');
    Route::get('/viewUseAccountsList','HrController@viewUseAccountsList');
    Route::get('/viewEmployeeLogDetail/{id}/{m}','HrController@viewEmployeeLogDetail');

    Route::get('/viewEmployeeBonusReportForm', 'HrController@viewEmployeeBonusReportForm');

    Route::get('/editEmployeeDetailForm/{id}/{m}', 'HrController@editEmployeeDetailForm')->middleware('isAdmin');
    Route::get('/editUserAccountDetailForm', 'HrController@editUserAccountDetailForm')->middleware('isAdmin');
    Route::get('/uploadEmployeeFileForm', 'HrController@uploadEmployeeFileForm');

    Route::get('/createManageAttendanceForm', 'HrController@createManageAttendanceForm');
    Route::get('/viewEmployeeAttendanceList','HrController@viewEmployeeAttendanceList');
    Route::get('/viewEmployeeAttendanceReport','HrController@viewEmployeeAttendanceReport');
    
    Route::get('/viewEmployeesAttendanceReport','HrController@viewEmployeesAttendanceReport');
    Route::get('/editEmployeeAttendanceDetailForm','HrController@editEmployeeAttendanceDetailForm');
    Route::get('/ViewAttendanceProgress','HrController@ViewAttendanceProgress');
    Route::get('/createAttendanceRequestQueriesForm','HrController@createAttendanceRequestQueriesForm');

    Route::get('/createPayrollForm','HrController@createPayrollForm');
    Route::get('/viewPayrollList','HrController@viewPayrollList');
    Route::get('/viewPayrollReport','HrController@viewPayrollReport');
    Route::get('/companyWisePayrollReport','HrController@companyWisePayrollReport');
    Route::get('/viewReconciliationReport','HrController@viewReconciliationReport');
    Route::get('/viewSalaryReconciliationReport','HrController@viewSalaryReconciliationReport');
    Route::get('/viewBankReportForm','HrController@viewBankReportForm');
    Route::get('/emailPayslips','HrController@emailPayslips');
    Route::get('/bonusPayslipsEmail','HrController@bonusPayslipsEmail');
    Route::get('/viewPayslipPdf/{m}/{month}/{year}/{emp_code}','HrController@viewPayslipPdf');
    Route::get('/downloadPayslipPdf/{m}/{month}/{year}/{emp_code}','HrController@downloadPayslipPdf');
    Route::get('/generatePayslip/{m}/{month}/{year}/{emp_code}','HrController@generatePayslip');
    Route::get('/viewBonusPayslipPdf/{m}/{month}/{year}/{emp_code}','HrController@viewBonusPayslipPdf');
    Route::get('/downloadBonusPayslipPdf/{m}/{month}/{year}/{emp_code}','HrController@downloadBonusPayslipPdf');
    Route::get('/generateBonusPayslip/{m}/{month}/{year}/{emp_code}','HrController@generateBonusPayslip');
    Route::get('/viewTaxCertificateList','HrController@viewTaxCertificateList');
    Route::get('/viewTaxCertificate/{employee_id}/{year}/{m}','HrController@viewTaxCertificate');
    Route::get('/downloadTaxCertificatePdf/{employee_id}/{year}/{m}','HrController@downloadTaxCertificatePdf');
    Route::get('/createTFAllowanceForm','HrController@createTFAllowanceForm');

    Route::get('/createMaritalStatusForm', 'HrController@createMaritalStatusForm');
    Route::get('/viewMaritalStatusList','HrController@viewMaritalStatusList');
    Route::get('/editMaritalStatusForm', 'HrController@editMaritalStatusForm');

    Route::get('/createAllowanceTypeForm', 'HrController@createAllowanceTypeForm');
    Route::get('/viewAllowanceTypeList','HrController@viewAllowanceTypeList');
    Route::get('/editAllowanceTypeForm', 'HrController@editAllowanceTypeForm');

    Route::get('/createAllowanceForm', 'HrController@createAllowanceForm');
    Route::get('/viewAllowanceList','HrController@viewAllowanceList');
    Route::get('/editAllowanceDetailForm', 'HrController@editAllowanceDetailForm');

    Route::get('/createDeductionForm', 'HrController@createDeductionForm');
    Route::get('/viewDeductionList','HrController@viewDeductionList');
    Route::get('/editDeductionDetailForm', 'HrController@editDeductionDetailForm');

    Route::get('/createAdvanceSalaryForm', 'HrController@createAdvanceSalaryForm');
    Route::get('/viewAdvanceSalaryList','HrController@viewAdvanceSalaryList');
    Route::get('/editAdvanceSalaryDetailForm', 'HrController@editAdvanceSalaryDetailForm');

    Route::get('/createLeavesPolicyForm', 'HrController@createLeavesPolicyForm');
    Route::get('/createManualLeaves', 'HrController@createManualLeaves');
    Route::get('/viewLeavesPolicyList','HrController@viewLeavesPolicyList');
    Route::get('/editLeavesPolicyDetailForm', 'HrController@editLeavesPolicyDetailForm');

    Route::get('/createCarPolicyForm', 'HrController@createCarPolicyForm');
    Route::get('/viewCarPolicyList','HrController@viewCarPolicyList');
    Route::get('/viewCarPolicyCriteria','HrController@viewCarPolicyCriteria');
    Route::get('/editCarPolicyDetailForm', 'HrController@editCarPolicyDetailForm');

    Route::get('/createVehicleTypeForm', 'HrController@createVehicleTypeForm');
    Route::get('/viewVehicleTypeList','HrController@viewVehicleTypeList');
    Route::get('/editVehicleTypeDetailForm', 'HrController@editVehicleTypeDetailForm');

    Route::get('/createLoanRequestForm', 'HrController@createLoanRequestForm');
    Route::get('/viewLoanRequestList','HrController@viewLoanRequestList');
    Route::get('/editLoanRequestDetailForm', 'HrController@editLoanRequestDetailForm');
    Route::get('/viewLoanReportForm', 'HrController@viewLoanReportForm');
    Route::get('/createStopPaymentForm', 'HrController@createStopPaymentForm');
    Route::get('/createLoanTopUpForm', 'HrController@createLoanTopUpForm');
    Route::get('/createLoanAdjustmentForm', 'HrController@createLoanAdjustmentForm');

    Route::get('/createEOBIForm', 'HrController@createEOBIForm');
    Route::get('/viewEOBIList','HrController@viewEOBIList');
    Route::get('/editEOBIDetailForm', 'HrController@editEOBIDetailForm');

    Route::get('/createTaxesForm', 'HrController@createTaxesForm');
    Route::get('/viewTaxesList','HrController@viewTaxesList');
    Route::get('/editTaxesDetailForm', 'HrController@editTaxesDetailForm');
    Route::get('/viewTaxCriteria','HrController@viewTaxCriteria');

    Route::get('/createBonusForm', 'HrController@createBonusForm');
    Route::get('/viewBonusList','HrController@viewBonusList');
    Route::get('/editBonusDetailForm', 'HrController@editBonusDetailForm');
    Route::get('/IssueBonusDetailForm', 'HrController@IssueBonusDetailForm');
    Route::get('/viewEmployeeBonusReportForm', 'HrController@viewEmployeeBonusReportForm');
    Route::get('/viewBonusBankReportForm', 'HrController@viewBonusBankReportForm');

    Route::get('/createLeaveApplicationForm','HrController@createLeaveApplicationForm');
    Route::get('/addLeaveApplicationFrom','HrController@addLeaveApplicationFrom');
    Route::get('/viewLeaveApplicationList','HrController@viewLeaveApplicationList');
    Route::get('/viewLeaveApplicationRequestList','HrController@viewLeaveApplicationRequestList');
    Route::get('/editLeaveApplicationDetailForm','HrController@editLeaveApplicationDetailForm');
    Route::get('/ApplyLeaveApplicationDetailForm','HrController@ApplyLeaveApplicationDetailForm');
    Route::get('/viewLeaveBalances','HrController@viewLeaveBalances');

    Route::get('/assignWorkingHoursPolicyForm','HrController@assignWorkingHoursPolicyForm');
    Route::get('/assignRosterPolicy','HrController@assignRosterPolicy');
    Route::get('/viewAssignedWorkingHoursPolicyList','HrController@viewAssignedWorkingHoursPolicyList');
    Route::get('/createWorkingHoursPolicyDetailForm', 'HrController@createWorkingHoursPolicyDetailForm');
    Route::get('/viewWorkingHoursPolicyList','HrController@viewWorkingHoursPolicyList');
    Route::get('/editWorkingHoursPolicyDetailForm','HrController@editWorkingHoursPolicyDetailForm');

    Route::get('/createHolidaysForm','HrController@createHolidaysForm');
    Route::get('/editHolidaysDetailForm','HrController@editHolidaysDetailForm');
    Route::get('/viewHolidaysList','HrController@viewHolidaysList');

    Route::get('/createEmployeeDepositForm','HrController@createEmployeeDepositForm');
    Route::get('/editEmployeeDepositDetail','HrController@editEmployeeDepositDetail');
    Route::get('/viewEmployeeDepositList','HrController@viewEmployeeDepositList');

    Route::get('/createEmployeeGradesForm','HrController@createEmployeeGradesForm');
    Route::get('/viewEmployeeGradesList','HrController@viewEmployeeGradesList');
    Route::get('/editEmployeeGradesDetailForm','HrController@editEmployeeGradesDetailForm');

    Route::get('/createLocationsForm','HrController@createLocationsForm');
    Route::get('/viewLocationsList','HrController@viewLocationsList');
    Route::get('/editLocationsDetailForm','HrController@editLocationsDetailForm');

    Route::get('/editEmployeeRegionsDetailForm','HrController@editEmployeeRegionsDetailForm');

    Route::get('/createEmployeeDegreeTypeForm','HrController@createEmployeeDegreeTypeForm');
    Route::get('/viewEmployeeDegreeTypeList','HrController@viewEmployeeDegreeTypeList');
    Route::get('/editEmployeeDegreeTypeDetailForm','HrController@editEmployeeDegreeTypeDetailForm');

    Route::get('/createEmployeeExitClearanceForm','HrController@createEmployeeExitClearanceForm');
    Route::get('/viewEmployeeExitClearanceList','HrController@viewEmployeeExitClearanceList');
    Route::get('/editEmployeeExitClearanceDetailForm','HrController@editEmployeeExitClearanceDetailForm');

    Route::get('/viewEmployeeDegreeTypeList','HrController@viewEmployeeDegreeTypeList');
    Route::get('/editEmployeeDegreeTypeDetailForm','HrController@editEmployeeDegreeTypeDetailForm');

    Route::get('/createEmployeeIdCardRequest','HrController@createEmployeeIdCardRequest');
    Route::get('/viewEmployeeIdCardRequestList','HrController@viewEmployeeIdCardRequestList');
    Route::get('/editEmployeeIdCardRequestDetailForm','HrController@editEmployeeIdCardRequestDetailForm');

    Route::get('/editEmployeePromotionDetailForm','HrController@editEmployeePromotionDetailForm');

    Route::get('/editEmployeeTransferDetailForm','HrController@editEmployeeTransferDetailForm');

    Route::get('/createEmployeeFuelDetailForm','HrController@createEmployeeFuelDetailForm');
    Route::get('/viewEmployeeFuel','HrController@viewEmployeeFuel');
    Route::get('/editEmployeeFuelDetailForm','HrController@editEmployeeFuelDetailForm');

    Route::get('/createHrLetters','HrController@createHrLetters');
    Route::get('/viewHrLetters','HrController@viewHrLetters');
    Route::get('/uploadLettersFile','HrController@uploadLettersFile');

    Route::get('/createEquipmentsForm','HrController@createEquipmentsForm');
    Route::get('/viewEquipmentsList','HrController@viewEquipmentsList');
    Route::get('/editEquipmentDetailForm','HrController@editEquipmentDetailForm');

    Route::get('/createEmployeeEquipmentsForm','HrController@createEmployeeEquipmentsForm');
    Route::get('/viewEmployeeEquipmentsList','HrController@viewEmployeeEquipmentsList');
    Route::get('/editEmployeeEquipmentsDetailForm','HrController@editEmployeeEquipmentsDetailForm');

    Route::get('/createDiseasesForm','HrController@createDiseasesForm');
    Route::get('/viewDiseasesList','HrController@viewDiseasesList');
    Route::get('/editDiseasesDetailForm','HrController@editDiseasesDetailForm');

    Route::get('/createEmployeeMedicalForm','HrController@createEmployeeMedicalForm');
    Route::get('/viewEmployeeMedicalList','HrController@viewEmployeeMedicalList');
    Route::get('/editEmployeeMedicalDetailForm','HrController@editEmployeeMedicalDetailForm');

    Route::get('/viewHrReports','HrController@viewHrReports');

    Route::get('/createTrainingForm','HrController@createTrainingForm');
    Route::get('/viewTrainingList','HrController@viewTrainingList');
    Route::get('/editTrainingDetailForm','HrController@editTrainingDetailForm');

    Route::get('/editFinalSettlementDetailForm','HrController@editFinalSettlementDetailForm');

    Route::get('/ViewAttendanceProgressList','HrController@ViewAttendanceProgressList');

    Route::get('/viewEmployeeQueries','HrController@viewEmployeeQueries');

    Route::get('/createProjectsForm','HrController@createProjectsForm');
    Route::get('/viewProjectsList','HrController@viewProjectsList');
    Route::get('/editProjectsForm','HrController@editProjectsForm');

    Route::get('/createEmployeePromotionForm','HrController@createEmployeePromotionForm');
    Route::get('/viewEmployeePromotionsList','HrController@viewEmployeePromotionsList');
    Route::get('/bulkIncrementForm','HrController@bulkIncrementForm');

    Route::get('/viewEmployeeOfTheMonth','HrController@viewEmployeeOfTheMonth');

    Route::get('/uploadPolicyFile','HrController@uploadPolicyFile');
    Route::get('/viewPolicyList','HrController@viewPolicyList');

    Route::get('/createProvidentFundForm','HrController@createProvidentFundForm');
    Route::get('/editProvidentFundDetail','HrController@editProvidentFundDetail');
    Route::get('/viewProvidentFundList','HrController@viewProvidentFundList');
    Route::get('/providentFundReport','HrController@providentFundReport');
    Route::get('/createPfOpeningBalance','HrController@createPfOpeningBalance');
    Route::get('/viewEmployeeProvidentFundReport','HrController@viewEmployeeProvidentFundReport');

    Route::get('/editWorkingOurPolicyList','HrController@editWorkingOurPolicyList');

    Route::get('/viewMySalarySheet', 'HrController@viewMySalarySheet');

    Route::get('/createRebateForm', 'HrController@createRebateForm');
    Route::get('/viewRebateList', 'HrController@viewRebateList');

    Route::get('/createCityForm','HrController@createCityForm');
    Route::get('/viewCityList','HrController@viewCityList');
    Route::get('/editCityForm','HrController@editCityForm');

    Route::get('/createEmployeeTransferForm','HrController@createEmployeeTransferForm');
    Route::get('/viewEmployeeTransferList','HrController@viewEmployeeTransferList');
    Route::get('/editEmployeeTransferForm','HrController@editEmployeeTransferForm');

    Route::get('/createChangeReportingManagerForm','HrController@createChangeReportingManagerForm');

    Route::get('/viewEmployeeGratuityForm','HrController@viewEmployeeGratuityForm');
    
     Route::get('/leavesEncashmentForm','HrController@leavesEncashmentForm');


});

Route::group(['prefix' => 'had','before' => 'csrf', 'middleware' => ['sanitize','noCache'] ], function () {

    Route::get('/savepolicies','HrAddDetailControler@savepolicies');
    // final settlement
    Route::post('/addFinalSettlementDetail','HrAddDetailControler@addFinalSettlementDetail');
    Route::post('/editFinalSettlementDetail','HrEditDetailControler@editFinalSettlementDetail');

    // final settlement end

    Route::post('/addStopPaymentDetail','HrAddDetailControler@addStopPaymentDetail');
    Route::post('/addLoanTopUpDetail','HrAddDetailControler@addLoanTopUpDetail');
    Route::post('/addLoanAdjustmentDetail','HrAddDetailControler@addLoanAdjustmentDetail');

    //  increment multi insertion

    Route::post('/addIncrementsDetail', 'HrAddDetailControler@addIncrementsDetail');
    Route::post('/bulkInsertionByCsv', 'HrAddDetailControler@bulkInsertionByCsv');

    //  increment multi insertion end

    // csv upload

    Route::post('/addLeaveApplicationDetailByCsv', 'HrAddDetailControler@addLeaveApplicationDetailByCsv');
    Route::post('/addEmployeeAdvanceDetailCsv', 'HrAddDetailControler@addEmployeeAdvanceDetailCsv');
    Route::post('/addEmployeeAllowanceDetailCsv', 'HrAddDetailControler@addEmployeeAllowanceDetailCsv');
    Route::post('/addEmployeeDeductionDetailCsv', 'HrAddDetailControler@addEmployeeDeductionDetailCsv');
    Route::post('/addLoanRequestDetailCsv', 'HrAddDetailControler@addLoanRequestDetailCsv');

    // end csv upload

    Route::post('/addStopPaymentDetail','HrAddDetailControler@addStopPaymentDetail');
    Route::post('/addLoanTopUpDetail','HrAddDetailControler@addLoanTopUpDetail');
    Route::post('/addLoanAdjustmentDetail','HrAddDetailControler@addLoanAdjustmentDetail');

    Route::post('/addCitiesDetail','HrAddDetailControler@addCitiesDetail');
    Route::post('/editCitiesDetail','HrEditDetailControler@editCitiesDetail');

    Route::post('/addAllowanceTypeDetail','HrAddDetailControler@addAllowanceTypeDetail');
    Route::post('/editAllowanceTypeDetail','HrEditDetailControler@editAllowanceTypeDetail');

    Route::post('/addAttendanceRequestQueriesDetail', 'HrAddDetailControler@addAttendanceRequestQueriesDetail');
    Route::get('/updateAttendanceRequestQueriesDetail', 'HrAddDetailControler@updateAttendanceRequestQueriesDetail');
    Route::post('/addIncomeTaxDetail', 'HrAddDetailControler@addIncomeTaxDetail');
    Route::post('/editIncomeTaxDetail', 'HrEditDetailControler@editIncomeTaxDetail');
    Route::post('/addEmployeeTransferProject', 'HrAddDetailControler@addEmployeeTransferProject');
    Route::post('/ediTransferProject', 'HrEditDetailControler@ediTransferProject');

    Route::post('/addRebateDetail','HrAddDetailControler@addRebateDetail');

    Route::post('/addPfDetail','HrAddDetailControler@addPfDetail');
    Route::post('/addEmployeeTrainingDetail','HrAddDetailControler@addEmployeeTrainingDetail');

    Route::get('/editAttendanceDetail','HrEditDetailControler@editAttendanceDetail');
    Route::get('/updateAttendanceError','HrEditDetailControler@updateAttendanceError');



    Route::post('/addPfWithrawalDetail','HrAddDetailControler@addPfWithrawalDetail');
    Route::post('/addPfProfitDetail','HrAddDetailControler@addPfProfitDetail');


    Route::post('/addEmployeeTransferLeave', 'HrAddDetailControler@addEmployeeTransferLeave');
    Route::post('/addDepartmentDetail', 'HrAddDetailControler@addDepartmentDetail');
    Route::post('/editDepartmentDetail', 'HrEditDetailControler@editDepartmentDetail');

    
    Route::post('/addDesignationWiseLocationDetail', 'HrAddDetailControler@addDesignationWiseLocationDetail');
    Route::post('/editDesignationWiseLocationDetail', 'HrEditDetailControler@editDesignationWiseLocationDetail');

    Route::post('/addSubDepartmentDetail', 'HrAddDetailControler@addSubDepartmentDetail');
    Route::post('/editSubDepartmentDetail', 'HrEditDetailControler@editSubDepartmentDetail');

    Route::post('/addDesignationDetail', 'HrAddDetailControler@addDesignationDetail');
    Route::post('/editDesignationDetail', 'HrEditDetailControler@editDesignationDetail');

    Route::post('/addHealthInsuranceDetail', 'HrAddDetailControler@addHealthInsuranceDetail');
    Route::post('/editHealthInsuranceDetail', 'HrEditDetailControler@editHealthInsuranceDetail');

    Route::post('/addLifeInsuranceDetail', 'HrAddDetailControler@addLifeInsuranceDetail');
    Route::post('/editLifeInsuranceDetail', 'HrEditDetailControler@editLifeInsuranceDetail');

    Route::post('/addJobTypeDetail', 'HrAddDetailControler@addJobTypeDetail');
    Route::post('/editJobTypeDetail', 'HrEditDetailControler@editJobTypeDetail');

    Route::post('/addQualificationDetail', 'HrAddDetailControler@addQualificationDetail');
    Route::post('/editQualificationDetail', 'HrEditDetailControler@editQualificationDetail');

    Route::post('/addLeaveTypeDetail', 'HrAddDetailControler@addLeaveTypeDetail');
    Route::post('/editLeaveTypeDetail', 'HrEditDetailControler@editLeaveTypeDetail');

    Route::post('/addLoanTypeDetail', 'HrAddDetailControler@addLoanTypeDetail');
    Route::post('/editLoanTypeDetail', 'HrEditDetailControler@editLoanTypeDetail');

    Route::post('/addAdvanceTypeDetail', 'HrAddDetailControler@addAdvanceTypeDetail');
    Route::post('/editAdvanceTypeDetail', 'HrEditDetailControler@editAdvanceTypeDetail');

    Route::post('/addShiftTypeDetail', 'HrAddDetailControler@addShiftTypeDetail');
    Route::post('/editShiftTypeDetail', 'HrEditDetailControler@editShiftTypeDetail');

    Route::post('/addHiringRequestDetail','HrAddDetailControler@addHiringRequestDetail');
    Route::post('/editHiringRequestDetail','HrEditDetailControler@editHiringRequestDetail');

    Route::post('/addEmployeeDetail','HrAddDetailControler@addEmployeeDetail');
    Route::post('/editEmployeeDetail','HrEditDetailControler@editEmployeeDetail');
    Route::post('/editUserAccountDetail','HrEditDetailControler@editUserAccountDetail');
    Route::post('/uploadEmployeeFileDetail','HrAddDetailControler@uploadEmployeeFileDetail');
    Route::post('/editEmployeeLeavingDetail','HrEditDetailControler@editEmployeeLeavingDetail');


    Route::post('/addManageAttendenceDetail','HrAddDetailControler@addManageAttendenceDetail');
    Route::post('/editEmployeeAttendanceDetail','HrEditDetailControler@editEmployeeAttendanceDetail');
    Route::post('/addEmployeeAttendanceFileDetail','HrAddDetailControler@addEmployeeAttendanceFileDetail');

    Route::post('/addCategoryDetail','HrAddDetailControler@addCategoryDetail');
    Route::post('/editCategoryDetail', 'HrEditDetailControler@editCategoryDetail');

    Route::post('/addPayrollDetail','HrAddDetailControler@addPayrollDetail');
    Route::post('/addEmailPayslipDetail','HrAddDetailControler@addEmailPayslipDetail');
    Route::post('/addBonusEmailPayslipDetail','HrAddDetailControler@addBonusEmailPayslipDetail');

    Route::post('/addMaritalStatusDetail','HrAddDetailControler@addMaritalStatusDetail');
    Route::post('/editMaritalStatusDetail','HrEditDetailControler@editMaritalStatusDetail');

    Route::post('/editPfWithrawalDetail','HrEditDetailControler@editPfWithrawalDetail');
    Route::post('/editPfProfitDetail','HrEditDetailControler@editPfProfitDetail');

    Route::post('/addEmployeeAllowanceDetail','HrAddDetailControler@addEmployeeAllowanceDetail');
    Route::post('/editAllowanceDetail','HrEditDetailControler@editAllowanceDetail');

    Route::post('/addEmployeeDeductionDetail','HrAddDetailControler@addEmployeeDeductionDetail');
    Route::post('/editDeductionDetail','HrEditDetailControler@editDeductionDetail');

    Route::post('/addAdvanceSalaryDetail','HrAddDetailControler@addAdvanceSalaryDetail');
    Route::post('/editAdvanceSalaryDetail','HrEditDetailControler@editAdvanceSalaryDetail');

    Route::post('/addLeavesPolicyDetail','HrAddDetailControler@addLeavesPolicyDetail');
    Route::post('/editLeavesPolicyDetail','HrEditDetailControler@editLeavesPolicyDetail');

    Route::post('/addVehicleTypeDetail','HrAddDetailControler@addVehicleTypeDetail');
    Route::post('/editVehicleTypeDetail','HrEditDetailControler@editVehicleTypeDetail');

    Route::post('/addCarPolicyDetail','HrAddDetailControler@addCarPolicyDetail');
    Route::post('/editCarPolicyDetail','HrEditDetailControler@editCarPolicyDetail');

    Route::post('/addLoanRequestDetail','HrAddDetailControler@addLoanRequestDetail');
    Route::post('/editLoanRequestDetail','HrEditDetailControler@editLoanRequestDetail');

    Route::post('/addEOBIDetail','HrAddDetailControler@addEOBIDetail');
    Route::post('/editEOBIDetail','HrEditDetailControler@editEOBIDetail');

    Route::post('/addTaxesDetail','HrAddDetailControler@addTaxesDetail');
    Route::post('/editTaxesDetail','HrEditDetailControler@editTaxesDetail');


    Route::post('/addBonusDetail','HrAddDetailControler@addBonusDetail');
    Route::post('/editBonusDetail','HrEditDetailControler@editBonusDetail');

    Route::post('/addEmployeeBonusDetail','HrAddDetailControler@addEmployeeBonusDetail');
    //Route::post('/editBonusDetail','HrEditDetailControler@editBonusDetail');


    Route::post('/AssignWorkingHoursPolicyDetail','HrAddDetailControler@AssignWorkingHoursPolicyDetail');
    Route::post('/addWorkingHoursPolicyDetail','HrAddDetailControler@addWorkingHoursPolicyDetail');
    Route::post('/editWorkingHoursDetail','HrEditDetailControler@editWorkingHoursDetail');

    Route::get('/addHolidaysDetail','HrAddDetailControler@addHolidaysDetail');
    Route::post('/editHolidayDetail','HrEditDetailControler@editHolidayDetail');

    Route::post('/addEmployeeDepositDetail','HrAddDetailControler@addEmployeeDepositDetail');
    Route::post('/editEmployeeDepositDetail','HrEditDetailControler@editEmployeeDepositDetail');

    Route::post('/addAttendanceProgressDetail','HrAddDetailControler@addAttendanceProgressDetail');

    Route::get('/viewAttendanceProgress','HrDataCallController@viewAttendanceProgress');

    Route::post('/addManuallyLeaves', 'HrAddDetailControler@addManuallyLeaves');

    Route::post('/addEmployeeGradesDetail', 'HrAddDetailControler@addEmployeeGradesDetail');
    Route::post('/editEmployeeGradesDetail', 'HrEditDetailControler@editEmployeeGradesDetail');

    Route::post('/addLocationsDetail', 'HrAddDetailControler@addLocationsDetail');
    Route::post('/editLocationsDetail', 'HrEditDetailControler@editLocationsDetail');

    Route::post('/addEmployeeRegionsDetail', 'HrAddDetailControler@addEmployeeRegionsDetail');
    Route::post('/editEmployeeRegionsDetail', 'HrEditDetailControler@editEmployeeRegionsDetail');

    Route::post('/addEmployeeDegreeTypeDetail', 'HrAddDetailControler@addEmployeeDegreeTypeDetail');
    Route::post('/editEmployeeDegreeTypeDetail', 'HrEditDetailControler@editEmployeeDegreeTypeDetail');

    Route::post('/addEmployeeExitClearanceDetail','HrAddDetailControler@addEmployeeExitClearanceDetail');
    Route::post('/editEmployeeExitClearanceDetail', 'HrEditDetailControler@editEmployeeExitClearanceDetail');

    Route::post('/addEmployeeIdCardRequestDetail','HrAddDetailControler@addEmployeeIdCardRequestDetail');
    Route::post('/editEmployeeIdCardRequestDetail', 'HrEditDetailControler@editEmployeeIdCardRequestDetail');

    Route::post('/addEmployeePromotionDetail', 'HrAddDetailControler@addEmployeePromotionDetail');
    Route::post('/editEmployeePromotionDetail', 'HrEditDetailControler@editEmployeePromotionDetail');

    Route::post('/addProjectsDetail','HrAddDetailControler@addProjectsDetail');
    Route::post('/editProjectsDetail', 'HrEditDetailControler@editProjectsDetail');

    Route::post('/addEmployeeTransferDetail', 'HrAddDetailControler@addEmployeeTransferDetail');
    Route::post('/editEmployeeTransferDetail', 'HrEditDetailControler@editEmployeeTransferDetail');

    Route::post('/addEmployeeFuelDetail', 'HrAddDetailControler@addEmployeeFuelDetail');
    Route::post('/editEmployeeFuelDetail', 'HrEditDetailControler@editEmployeeFuelDetail');

    Route::post('/addEmployeeGsspVeriDetail', 'HrAddDetailControler@addEmployeeGsspVeriDetail');

    Route::post('/updateLabourSalary', 'HrEditDetailControler@updateLabourSalary');

    Route::post('/addHrLetters', 'HrAddDetailControler@addHrLetters');
    Route::post('/AddLettersFile', 'HrAddDetailControler@AddLettersFile');


    Route::post('/addEquipmentDetail', 'HrAddDetailControler@addEquipmentDetail');
    Route::post('/editEquipmentsDetail', 'HrEditDetailControler@editEquipmentsDetail');

    Route::post('/addEmployeeEquipmentDetail', 'HrAddDetailControler@addEmployeeEquipmentDetail');
    Route::post('/editEmployeeEquipmentDetail', 'HrEditDetailControler@editEmployeeEquipmentDetail');

    Route::post('/addDiseaseDetail', 'HrAddDetailControler@addDiseaseDetail');
    Route::post('/editDiseaseTypeDetail', 'HrEditDetailControler@editDiseaseTypeDetail');

    Route::post('/addEmployeeMedicalDetail', 'HrAddDetailControler@addEmployeeMedicalDetail');
    Route::post('/editEmployeeMedicalDetail', 'HrEditDetailControler@editEmployeeMedicalDetail');

    Route::post('/addTrainingDetail', 'HrAddDetailControler@addTrainingDetail');
    Route::post('/editTrainingDetail', 'HrEditDetailControler@editTrainingDetail');

    Route::post('/editFinalSettlementDetail', 'HrEditDetailControler@editFinalSettlementDetail');

    Route::post('/uploadOvertimeAndFuelFile', 'HrAddDetailControler@uploadOvertimeAndFuelFile');
    Route::post('/addManualyAttendances', 'HrAddDetailControler@addManualyAttendances');
    Route::post('/uploadAttendanceFile', 'HrAddDetailControler@uploadAttendanceFile');
    Route::post('/addEmployeeDeductionDays','HrAddDetailControler@addEmployeeDeductionDays');

    Route::post('/addEmployeeOfTheMonthDetail','HrAddDetailControler@addEmployeeOfTheMonthDetail');

    Route::post('/uploadPolicyFileDetail','HrAddDetailControler@uploadPolicyFileDetail');

    Route::post('/addProvidentFundDetail','HrAddDetailControler@addProvidentFundDetail');
    Route::post('/editProvidentFundDetail','HrEditDetailControler@editProvidentFundDetail');

    Route::post('/addProvidentFundDisburse','HrAddDetailControler@addProvidentFundDisburse');
    Route::post('/editWorkingHoursPolicyDetail','HrEditDetailControler@editWorkingHoursPolicyDetail');
    Route::post('/UpdateAssignWorkingHoursPolicyDetail','HrEditDetailControler@UpdateAssignWorkingHoursPolicyDetail');

    Route::post('/addChangeReportingManagerDetail','HrAddDetailControler@addChangeReportingManagerDetail');

    Route::post('/addEmployeeGratuityDetail', 'HrAddDetailControler@addEmployeeGratuityDetail');
    
    Route::post('/addTaxCertificateDetail', 'HrAddDetailControler@addTaxCertificateDetail');
    
    Route::post('/addLeavesEncashmentDetail', 'HrAddDetailControler@addLeavesEncashmentDetail');
    Route::post('/addTFAllowanceDetail', 'HrAddDetailControler@addTFAllowanceDetail');

});

Route::group(['prefix' => 'hdc','before' => 'csrf', 'middleware' => ['sanitize','noCache'] ], function (){

    Route::get('/viewTFAllowanceForm','HrDataCallController@viewTFAllowanceForm');
    Route::get('/checkLoanData','HrDataCallController@checkLoanData');
    Route::get('/viewEmployeeGratuityFormDetail','HrDataCallController@viewEmployeeGratuityFormDetail');
    Route::get('/viewReconciliationReportDetail','HrDataCallController@viewReconciliationReportDetail');
    Route::get('/viewFinalSettlementListDetail','HrDataCallController@viewFinalSettlementListDetail');
    Route::get('/viewIncrementsFormDetail','HrDataCallController@viewIncrementsFormDetail');
    Route::get('/viewEmployeePromotionsListDetail','HrDataCallController@viewEmployeePromotionsListDetail');
    Route::get('/viewEmploymentCertificate/{id}','HrDataCallController@viewEmploymentCertificate');
    Route::get('/viewEmployeeTaxCertificateDetail','HrDataCallController@viewEmployeeTaxCertificateDetail');

    // final settlement
    Route::get('/fetchAttendance','HrDataCallController@fetchAttendance');
    Route::get('/viewFinalSettlementForm','HrDataCallController@viewFinalSettlementForm');
    Route::get('/viewFinalSettlementDetail/{id}','HrDataCallController@viewFinalSettlementDetail');
    Route::get('/viewFinalSettlementSalaryForm','HrDataCallController@viewFinalSettlementSalaryForm');
    Route::get('/viewFinalSettlementReportDetail','HrDataCallController@viewFinalSettlementReportDetail');
    Route::get('/viewFilteredFinalSettlementList','HrDataCallController@viewFilteredFinalSettlementList');


    Route::get('/calculateSalaryTobePaidAmount','HrDataCallController@calculateSalaryTobePaidAmount');
    Route::get('/calculateLeaveDeductionAmount','HrDataCallController@calculateLeaveDeductionAmount');
    Route::get('/viewEmployeeExitClearanceListDetail','HrDataCallController@viewEmployeeExitClearanceListDetail');


    // final settlement end

    Route::get('/viewDepartmentListDetail','HrDataCallController@viewDepartmentListDetail');
    Route::get('/viewSubDepartmentListDetail','HrDataCallController@viewSubDepartmentListDetail');
    Route::get('/viewDesignationListDetail','HrDataCallController@viewDesignationListDetail');
    Route::get('/viewJobTypeListDetail','HrDataCallController@viewJobTypeListDetail');
    Route::get('/viewLeaveTypeListDetail','HrDataCallController@viewLeaveTypeListDetail');
    Route::get('/viewLoanTypeListDetail','HrDataCallController@viewLoanTypeListDetail');
    Route::get('/viewProjectsListDetail','HrDataCallController@viewProjectsListDetail');
    Route::get('/viewLocationsListDetail','HrDataCallController@viewLocationsListDetail');
    Route::get('/viewDesignationWiseLocationsListDetail','HrDataCallController@viewDesignationWiseLocationsListDetail');
    Route::get('/viewCityListDetail','HrDataCallController@viewCityListDetail');
    Route::get('/viewCategoryListDetail','HrDataCallController@viewCategoryListDetail');
    Route::get('/viewMaritalStatusListDetail','HrDataCallController@viewMaritalStatusListDetail');
    Route::get('/viewEOBIListDetail','HrDataCallController@viewEOBIListDetail');
    Route::get('/viewEmployeeGradesListDetail','HrDataCallController@viewEmployeeGradesListDetail');
    Route::get('/viewEmployeeDegreeTypeListDetail','HrDataCallController@viewEmployeeDegreeTypeListDetail');
    Route::get('/viewEquipmentsListDetail','HrDataCallController@viewEquipmentsListDetail');
    Route::get('/viewHolidayCalender','HrDataCallController@viewHolidayCalender');
    Route::get('/viewHolidaysDetail','HrDataCallController@viewHolidaysDetail');
    Route::get('/viewAllowanceTypeListDetail','HrDataCallController@viewAllowanceTypeListDetail');

    Route::get('/viewAllowanceFormDetail','HrDataCallController@viewAllowanceFormDetail');
    Route::get('/viewAllowanceListDetail','HrDataCallController@viewAllowanceListDetail');
    Route::get('/viewAllowanceListDetailFilter','HrDataCallController@viewAllowanceListDetailFilter');

    Route::get('/viewDeductionFormDetail','HrDataCallController@viewDeductionFormDetail');
    Route::get('/viewDeductionListDetail','HrDataCallController@viewDeductionListDetail');
    Route::get('/viewDeductionListDetailFilter','HrDataCallController@viewDeductionListDetailFilter');

    Route::get('/viewEmployeeFilteredList','HrDataCallController@viewEmployeeFilteredList');
    Route::get('/viewEmployeeReportFilteredList','HrDataCallController@viewEmployeeReportFilteredList');

    Route::get('/viewFilteredLoanRequestList','HrDataCallController@viewFilteredLoanRequestList');

    Route::get('/viewEmployeeTransferFormDetail','HrDataCallController@viewEmployeeTransferFormDetail');

    Route::get('/viewFilteredLeaveRequestList','HrDataCallController@viewFilteredLeaveRequestList');

    Route::get('/viewFilteredAdvanceSalaryList', 'HrDataCallController@viewFilteredAdvanceSalaryList');

    Route::get('/viewChangeReportingManagerFormDetail','HrDataCallController@viewChangeReportingManagerFormDetail');

    Route::get('/checkPf','HrDataCallController@checkPf');
    Route::get('/viewProjectLetter','HrDataCallController@viewProjectLetter');
    Route::get('/show_permanent_form','HrDataCallController@show_permanent_form');
    Route::get('/getPendingLeaveApplicationDetail','HrDataCallController@getPendingLeaveApplicationDetail');
    Route::get('/checkManualLeaves','HrDataCallController@checkManualLeaves');
    Route::get('/getEmployeeDetail','HrDataCallController@getEmployeeDetail');
    Route::get('/viewPreviousEmployeeProject','HrDataCallController@viewPreviousEmployeeProject');
    Route::get('/viewPromotionLetter','HrDataCallController@viewPromotionLetter');
    Route::get('/viewTransferLetter','HrDataCallController@viewTransferLetter');
    Route::get('/employeeGetLeavesBalances','HrDataCallController@employeeGetLeavesBalances');
    Route::get('/filterEmployeeList','HrDataCallController@filterEmployeeList');
    Route::get('/filterWorkingHoursPolicList','HrDataCallController@filterWorkingHoursPolicList');
    Route::get('/filterAssignWorkingHoursPolicyList','HrDataCallController@filterAssignWorkingHoursPolicyList');
    Route::get('/filterAssignWorkingHoursPolicyForm','HrDataCallController@filterAssignWorkingHoursPolicyForm');
    Route::get('/filterAssignWorkingHoursPolicyFormRoster','HrDataCallController@filterAssignWorkingHoursPolicyFormRoster');
    Route::get('/displayPolicies','HrDataCallController@displayPolicies');



    Route::get('/viewDepartmentList','HrDataCallController@viewDepartmentList');
    Route::get('/viewEmployeeListManageAttendence','HrDataCallController@viewEmployeeListManageAttendence');
    Route::get('/viewAttendanceReport','HrDataCallController@viewAttendanceReport');
    Route::get('/fetchAttendanceReport','HrDataCallController@fetchAttendanceReport');
    
    Route::get('/viewAttendanceError','HrDataCallController@viewAttendanceError');
    Route::get('/fetchEmployeesAttendanceReport','HrDataCallController@fetchEmployeesAttendanceReport');
    Route::get('/viewEmployeePayrollForm','HrDataCallController@viewEmployeePayrollForm')->middleware('isAdmin');
    Route::get('/viewEmployeePfForm','HrDataCallController@viewEmployeePfForm')->middleware('isAdmin');
    Route::get('/viewEmployeeTrainingForm','HrDataCallController@viewEmployeeTrainingForm');
    Route::get('/viewEmployeePayrollList','HrDataCallController@viewEmployeePayrollList')->middleware('isAdmin');
    Route::get('/viewBankReportDetail','HrDataCallController@viewBankReportDetail')->middleware('isAdmin');
    Route::get('/viewMySalarySheetDetail','HrDataCallController@viewMySalarySheetDetail');
    Route::get('/viewEmployeeBonusReport','HrDataCallController@viewEmployeesBonusReport');
    Route::get('/getDesignations','HrDataCallController@getDesignations');
    Route::get('/getSubDepartments','HrDataCallController@getSubDepartments');
    Route::get('/viewEmployeePayslips','HrDataCallController@viewEmployeePayslips');
    Route::get('/viewBonusEmployeePayslips','HrDataCallController@viewBonusEmployeePayslips');
    Route::get('/viewLatesDetil','HrDataCallController@viewLatesDetil');

    Route::get('/viewEmployeeDetail/{id}/{m}','HrDataCallController@viewEmployeeDetail');
    Route::get('/viewUserAccountDetail','HrDataCallController@viewUserAccountDetail');
    Route::get('/viewHiringRequestDetail','HrDataCallController@viewHiringRequestDetail');
    Route::get('/viewLeavePolicyDetail','HrDataCallController@viewLeavePolicyDetail');
    Route::get('/viewCarPolicyCriteria','HrDataCallController@viewCarPolicyCriteria');
    Route::get('/viewCarPolicy','HrDataCallController@viewCarPolicy');
    Route::get('/viewLoanRequestDetail','HrDataCallController@viewLoanRequestDetail');
    Route::get('/viewLoanReportDetail','HrDataCallController@viewLoanReportDetail');

    Route::get('/viewTaxCriteria','HrDataCallController@viewTaxCriteria');
    Route::get('/viewTax','HrDataCallController@viewTax');
    Route::get('/viewEmployeesBonus','HrDataCallController@viewEmployeesBonus');
    Route::get('/viewBonusBankReportDetail','HrDataCallController@viewBonusBankReportDetail');

    Route::get('/viewLeaveApplicationDetail','HrDataCallController@viewLeaveApplicationDetail');
    Route::get('/viewLeaveApplicationRequestDetail','HrDataCallController@viewLeaveApplicationRequestDetail');
    Route::get('/filterEmployeeAttendanceList','HrDataCallController@filterEmployeeAttendanceList');
    Route::get('/viewEmployeeLeaveDetail','HrDataCallController@viewEmployeeLeaveDetail');
    Route::get('/viewAttendanceProgress','HrDataCallController@viewAttendanceProgress');
    Route::get('/refreshAttendanceProgress','HrDataCallController@refreshAttendanceProgress');
    Route::get('/viewPayrollReport','HrDataCallController@viewPayrollReport');
    Route::get('/viewConcileReport','HrDataCallController@viewConcileReport')->middleware('isAdmin');
    Route::get('/viewComparisonReport','HrDataCallController@viewComparisonReport')->middleware('isAdmin');
    Route::get('/companyWisePayrollReport','HrDataCallController@companyWisePayrollReport')->middleware('isAdmin');
    Route::get('/viewPayrollReceivingReport','HrDataCallController@viewPayrollReceivingReport');
    Route::get('/viewApplicationDateWise','HrDataCallController@viewApplicationDateWise');
    Route::get('/viewHolidayDate','HrDataCallController@viewHolidayDate');
    Route::get('/viewOverTimeDetail','HrDataCallController@viewOverTimeDetail');
    Route::get('/viewLateArrivalDetail','HrDataCallController@viewLateArrivalDetail');
    Route::get('/viewLeaveApplicationClientForm','HrDataCallController@viewLeaveApplicationClientForm');
    Route::get('/addLeaveApplicationFrom','HrDataCallController@addLeaveApplicationFrom');
    Route::get('/viewHolidaysMonthWise','HrDataCallController@viewHolidaysMonthWise');
    Route::get('/viewHalfDaysDetail','HrDataCallController@viewHalfDaysDetail');
    Route::get('/viewHalfDays','HrDataCallController@viewHalfDays');
    Route::get('/viewLates','HrDataCallController@viewLates');
    Route::get('/viewOvertimeHoursDetail','HrDataCallController@viewOvertimeHoursDetail');
    Route::get('/viewEmployeeDepositDetail','HrDataCallController@viewEmployeeDepositDetail');
    Route::get('/viewLeaveBalances','HrDataCallController@viewLeaveBalances');
    Route::get('/viewRangeWiseLeaveApplicationsRequests','HrDataCallController@viewRangeWiseLeaveApplicationsRequests');
    Route::get('/viewLeavesBalances','HrDataCallController@viewLeavesBalances');
    Route::get('/viewEmployeeExitClearanceForm', 'HrDataCallController@viewEmployeeExitClearanceForm');
    Route::get('/viewEmployeeExitClearanceDetail', 'HrDataCallController@viewEmployeeExitClearanceDetail');
    Route::get('/checkEmrNoExist','HrDataCallController@checkEmrNoExist');
    Route::get('/viewEmployeeIdCardRequest', 'HrDataCallController@viewEmployeeIdCardRequest');
    Route::get('/viewEmployeeIdCardRequestDetail', 'HrDataCallController@viewEmployeeIdCardRequestDetail');
    Route::get('/viewEmployeePreviousPromotionsDetail', 'HrDataCallController@viewEmployeePreviousPromotionsDetail');
    Route::get('/viewEmployeeDocuments', 'HrDataCallController@viewEmployeeDocuments');
    Route::get('/viewEmployeePreviousTransferDetail', 'HrDataCallController@viewEmployeePreviousTransferDetail');
    Route::get('/viewExpiryAndUpcomingAlerts', 'HrDataCallController@viewExpiryAndUpcomingAlerts');
    Route::get('/viewEmployeeFuelDetailForm', 'HrDataCallController@viewEmployeeFuelDetailForm');
    Route::get('/viewEmployeeFuelDetail', 'HrDataCallController@viewEmployeeFuelDetail');
    Route::get('/viewEmployeeFilteredFuelDetail', 'HrDataCallController@viewEmployeeFilteredFuelDetail');
    Route::get('/viewUpcomingBirthdaysDetail', 'HrDataCallController@viewUpcomingBirthdaysDetail');
    Route::get('/viewEmployeeCnicExpireDetail', 'HrDataCallController@viewEmployeeCnicExpireDetail');
    Route::get('/viewEmployeeAttendanceQueriesDetail', 'HrDataCallController@viewEmployeeAttendanceQueriesDetail');
    Route::get('/viewAbsentsDetail', 'HrDataCallController@viewAbsentsDetail');
    Route::get('/approveOrRejectAttendanceQuery', 'HrDataCallController@approveOrRejectAttendanceQuery');

    Route::get('/viewEmployeeOverAgeDetail', 'HrDataCallController@viewEmployeeOverAgeDetail');
    Route::get('/viewNonVerifiedNadraEmployeeDetail', 'HrDataCallController@viewNonVerifiedNadraEmployeeDetail');
    Route::get('/viewNonVerifiedPoliceEmployeeDetail', 'HrDataCallController@viewNonVerifiedPoliceEmployeeDetail');
    Route::get('/viewEmployeeGsspVeriDetail', 'HrDataCallController@viewEmployeeGsspVeriDetail');
    Route::get('/viewEmployeeMissingImageDetail', 'HrDataCallController@viewEmployeeMissingImageDetail');
    Route::get('/viewEmployeeWarningLetterDetail', 'HrDataCallController@viewEmployeeWarningLetterDetail');
    Route::get('/viewDemiseEmployeeDetail', 'HrDataCallController@viewDemiseEmployeeDetail');
    Route::get('/viewEmployeeProbationPeriodOverDetail', 'HrDataCallController@viewEmployeeProbationPeriodOverDetail');

    Route::get('/viewHrEmployeeAuditDetail', 'HrDataCallController@viewHrEmployeeAuditDetail');
    Route::get('/viewHrLetters','HrDataCallController@viewHrLetters');
    Route::get('/getEmployeeDateOfJoining','HrDataCallController@getEmployeeDateOfJoining');
    Route::get('/getConclusionLettersDate','HrDataCallController@getConclusionLettersDate');
    Route::get('/getWithoutIncrementLettersDate','HrDataCallController@getWithoutIncrementLettersDate');
    Route::get('/getIncrementLettersDetails','HrDataCallController@getIncrementLettersDetails');
    Route::get('/getTransferLettersDetails','HrDataCallController@getTransferLettersDetails');
    Route::get('/viewEmployeeEquipmentsForm','HrDataCallController@viewEmployeeEquipmentsForm');
    Route::get('/viewEmployeeEquipmentsDetail','HrDataCallController@viewEmployeeEquipmentsDetail');
    Route::get('/viewEmployeePreviousAllowancesDetail','HrDataCallController@viewEmployeePreviousAllowancesDetail');


    Route::get('/viewHrWarningLetter/{id}/{company_id}','HrDataCallController@viewHrWarningLetter');
    Route::get('/viewHrMfmSouthIncrementLetter/{id}/{company_id}','HrDataCallController@viewHrMfmSouthIncrementLetter');
    Route::get('/viewHrMfmSouthWithoutIncrementLetter/{id}/{company_id}','HrDataCallController@viewHrMfmSouthWithoutIncrementLetter');
    Route::get('/viewHrContractConclusionLetter/{id}/{company_id}','HrDataCallController@viewHrContractConclusionLetter');
    Route::get('/viewHrTerminationFormat1Letter/{id}/{company_id}','HrDataCallController@viewHrTerminationFormat1Letter');
    Route::get('/viewHrTerminationFormat2Letter/{id}/{company_id}','HrDataCallController@viewHrTerminationFormat2Letter');
    Route::get('/viewHrTransferLetter/{id}/{company_id}','HrDataCallController@viewHrTransferLetter');

    Route::get('/viewEmployeeCnicCopy','HrDataCallController@viewEmployeeCnicCopy');
    Route::get('/viewEmployeeExperienceDocuments','HrDataCallController@viewEmployeeExperienceDocuments');
    Route::get('/checkCnicNoExist','HrDataCallController@checkCnicNoExist');
    Route::get('/getDataByCnic','HrDataCallController@getDataByCnic');

    Route::get('/viewMasterTableForm','HrDataCallController@viewMasterTableForm');

    Route::get('/viewDayWiseAttendence','HrDataCallController@viewDayWiseAttendence');
    Route::get('/viewMonthWiseAttendence','HrDataCallController@viewMonthWiseAttendence');
    Route::get('/viewUploadFileAttendance','HrDataCallController@viewUploadFileAttendance');
    Route::get('/viewEmployeeEobiCopy','HrDataCallController@viewEmployeeEobiCopy');
    Route::get('/viewEmployeeInsuranceCopy','HrDataCallController@viewEmployeeInsuranceCopy');
    Route::get('/viewEmployeeEobiDetail','HrDataCallController@viewEmployeeEobiDetail');
    Route::get('/viewEmployeeInsuranceDetail','HrDataCallController@viewEmployeeInsuranceDetail');
    Route::get('/viewEmployeeSettlementDetail','HrDataCallController@viewEmployeeSettlementDetail');
    Route::get('/viewEmployeeProbationExpireDetail','HrDataCallController@viewEmployeeProbationExpireDetail');

    Route::get('/viewHrLetterFiles','HrDataCallController@viewHrLetterFiles');
    Route::get('/viewEmployeeMedicalDocuments','HrDataCallController@viewEmployeeMedicalDocuments');

    Route::get('/getMoreEmployeesDetail','HrDataCallController@getMoreEmployeesDetail');
    Route::get('/viewTrainingDetail','HrDataCallController@viewTrainingDetail');

    Route::get('/viewFinalSettlement','HrDataCallController@viewFinalSettlement');
    Route::get('/viewFinalSettlementDetail','HrDataCallController@viewFinalSettlementDetail');

    Route::get('/viewDashboardDetails','HrDataCallController@viewDashboardDetails');
    Route::get('/viewS2bReport','HrDataCallController@viewS2bReport');
    Route::get('/viewPermanentEmployee','HrDataCallController@viewPermanentEmployee');


    Route::get('/viewAdvanceSalaryDetail','HrDataCallController@viewAdvanceSalaryDetail');
    Route::get('/viewAllowanceDetail','HrDataCallController@viewAllowanceDetail');
    Route::get('/viewDeductionDetail','HrDataCallController@viewDeductionDetail');
    Route::get('/viewHolidaysDetail','HrDataCallController@viewHolidaysDetail');
    Route::get('/viewEmployeePromotionDetailForLog','HrDataCallController@viewEmployeePromotionDetailForLog');
    Route::get('/viewEmployeeTransferDetailForLog','HrDataCallController@viewEmployeeTransferDetailForLog');
    Route::get('/viewLeaveApplicationRequestDetailForLog','HrDataCallController@viewLeaveApplicationRequestDetailForLog');

    Route::get('/viewManualAttendanceForm','HrDataCallController@viewManualAttendanceForm');
    Route::get('/viewEmployeeManualAttendance','HrDataCallController@viewEmployeeManualAttendance');
    Route::get('/viewEmployeeManualAttendance','HrDataCallController@viewEmployeeManualAttendance');
    Route::get('/viewUploadAttendanceFileForm','HrDataCallController@viewUploadAttendanceFileForm');
    Route::get('/viewLeaveApplicationDateWise','HrDataCallController@viewLeaveApplicationDateWise');
    Route::get('/viewEarlyGoingDetail','HrDataCallController@viewEarlyGoingDetail');
    Route::get('/attendanceProgressFilteredList','HrDataCallController@attendanceProgressFilteredList');

    Route::get('/viewPendingRequests','HrDataCallController@viewPendingRequests');

    Route::get('/viewPoliciesDetail','HrDataCallController@viewPoliciesDetail');
    Route::get('/getPolicyDetail','HrDataCallController@getPolicyDetail');
    Route::get('/viewTaxesDetail','HrDataCallController@viewTaxesDetail');

    Route::get('/viewProvidentFundReport','HrDataCallController@viewProvidentFundReport');
    Route::get('/disburseProvidentFundForm','HrDataCallController@disburseProvidentFundForm');
    Route::get('/viewEmployeeProvidentFundReport','HrDataCallController@viewEmployeeProvidentFundReport');

    Route::get('/createEmployeeJoiningLetter', 'HrDataCallController@createEmployeeJoiningLetter');

    Route::get('/deleteLeavesDataPolicyRows', 'HrDataCallController@deleteLeavesDataPolicyRows');
    Route::get('/viewHolidayDetails', 'HrDataCallController@viewHolidayDetails');

    Route::get('/viewEmployeeRebateDetail', 'HrDataCallController@viewEmployeeRebateDetail');
    Route::get('/viewEmployeeRebateDocuments', 'HrDataCallController@viewEmployeeRebateDocuments');
    
    Route::get('/leavesEncashmentFormDetail', 'HrDataCallController@leavesEncashmentFormDetail');

});

Route::group(['prefix' => 'hmfal','before' => 'csrf', 'middleware' => ['sanitize','noCache'] ], function () {
    Route::get('/makeFormEmployeeDetail','HrMakeFormAjaxLoadController@makeFormEmployeeDetail');
    Route::get('/addMoreAllowancesDetailRows','HrMakeFormAjaxLoadController@addMoreAllowancesDetailRows');
    Route::get('/addMoreDeductionsDetailRows','HrMakeFormAjaxLoadController@addMoreDeductionsDetailRows');
    Route::get('/makeFormDepartmentDetail','HrMakeFormAjaxLoadController@makeFormDepartmentDetail');
    Route::get('/makeFormSubDepartmentDetail','HrMakeFormAjaxLoadController@makeFormSubDepartmentDetail');
    Route::get('/makeFormDesignationDetail','HrMakeFormAjaxLoadController@makeFormDesignationDetail');
    Route::get('/makeFormHealthInsuranceDetail','HrMakeFormAjaxLoadController@makeFormHealthInsuranceDetail');
    Route::get('/makeFormEmployeeCategoryDetail','HrMakeFormAjaxLoadController@makeFormEmployeeCategoryDetail');
    Route::get('/makeFormJobTypeDetail','HrMakeFormAjaxLoadController@makeFormJobTypeDetail');
    Route::get('/makeFormQualificationDetail','HrMakeFormAjaxLoadController@makeFormQualificationDetail');
    Route::get('/makeFormLeaveTypeDetail','HrMakeFormAjaxLoadController@makeFormLeaveTypeDetail');
    Route::get('/makeFormLoanTypeDetail','HrMakeFormAjaxLoadController@makeFormLoanTypeDetail');
    Route::get('/makeFormAdvanceTypeDetail','HrMakeFormAjaxLoadController@makeFormAdvanceTypeDetail');
    Route::get('/makeFormShiftTypeDetail','HrMakeFormAjaxLoadController@makeFormShiftTypeDetail');
    Route::get('/makeFormLoanRequestDetail','HrMakeFormAjaxLoadController@makeFormLoanRequestDetail');
    Route::get('/makeFormEOBIDetail','HrMakeFormAjaxLoadController@makeFormEOBIDetail');
    Route::get('/makeFormTaxesDetail','HrMakeFormAjaxLoadController@makeFormTaxesDetail');
    Route::get('/makeFormBonusDetail','HrMakeFormAjaxLoadController@makeFormBonusDetail');
    Route::get('/makeFormEmployeeLeaveApplicationDetailByEmployeeId','HrMakeFormAjaxLoadController@makeFormEmployeeLeaveApplicationDetailByEmployeeId');
    Route::get('/makeFormEmployeeInActive','HrMakeFormAjaxLoadController@makeFormEmployeeInActive');



});

Route::group(['prefix' => 'hadbac','before' => 'csrf', 'middleware' => ['sanitize','noCache'] ], function () {
    Route::get('/addLeaveApplicationDetail', 'HrAddDetailByAjaxController@addLeaveApplicationDetail');
    Route::get('/addLeaveApplicationDetail', 'HrAddDetailByAjaxController@addLeaveApplicationDetail');
    Route::get('/addEmployeeSixthMonthAuditDetail', 'HrAddDetailByAjaxController@addEmployeeSixthMonthAuditDetail');
    Route::get('/addEmployeeTwelfthMonthAuditDetail', 'HrAddDetailByAjaxController@addEmployeeTwelfthMonthAuditDetail');
    Route::get('/addMasterTableDetail', 'HrAddDetailByAjaxController@addMasterTableDetail');
    Route::get('/addManualyAttendance', 'HrAddDetailByAjaxController@addManualyAttendance');
    Route::get('/addUserQueryDetails', 'HrAddDetailByAjaxController@addUserQueryDetails');

    Route::get('/leaveApprovOrReject', 'HrAddDetailByAjaxController@leaveApprovOrReject');
    Route::get('/approveAndRejectAll', 'HrAddDetailByAjaxController@approveAndRejectAll');
});

Route::group(['prefix' => 'hedbac','before' => 'csrf', 'middleware' => ['sanitize','noCache'] ], function () {
    Route::get('/EditEmployeeCarPolicyDetail', 'HrEditDetailByAjaxController@EditEmployeeCarPolicyDetail');
    Route::get('/EditEmployeeTaxDetail', 'HrEditDetailByAjaxController@EditEmployeeTaxDetail');
    Route::get('/editLeaveApplicationDetail', 'HrEditDetailByAjaxController@editLeaveApplicationDetail');
    Route::get('/NeglectEmployeeAttendance', 'HrEditDetailByAjaxController@NeglectEmployeeAttendance');
    Route::get('/updateDemiseEmployeeReview', 'HrEditDetailByAjaxController@updateDemiseEmployeeReview');
    Route::get('/NeglectEarlyGoing', 'HrEditDetailByAjaxController@NeglectEarlyGoing');
    Route::get('/approveEmployeePayrollDetail', 'HrEditDetailByAjaxController@approveEmployeePayrollDetail');
    Route::get('/rejectEmployeePayrollDetail', 'HrEditDetailByAjaxController@rejectEmployeePayrollDetail');
});
//End HR
Route::get('/deleteMasterTableReceord', 'DeleteMasterTableRecordController@deleteMasterTableReceord');
Route::get('/deleteUserAccount', 'DeleteMasterTableRecordController@deleteUserAccountDetail');
//Start Company Database Record Delete
Route::group(['prefix' => 'cdOne','before' => 'csrf', 'middleware' => ['sanitize','noCache'] ], function () {
    Route::get('/deleteRowCompanyHRRecordsProjectTransfer', 'DeleteCompanyHRRecordsController@deleteRowCompanyHRRecordsProjectTransfer');
    Route::get('/deleteLoanTopUp', 'DeleteCompanyHRRecordsController@deleteLoanTopUp');
    Route::get('/deleteProjectLetter', 'DeleteCompanyHRRecordsController@deleteProjectLetter');
    Route::get('/deletePromotionLetter', 'DeleteCompanyHRRecordsController@deletePromotionLetter');
    Route::get('/deleteTransferLetter', 'DeleteCompanyHRRecordsController@deleteTransferLetter');
    Route::get('/deleteRowCompanyHRRecords', 'DeleteCompanyHRRecordsController@deleteRowCompanyHRRecords');
    Route::get('/deleteRowCompanyRecords', 'DeleteCompanyRecordsController@deleteRowCompanyRecords');
    Route::get('/repostOneTableRecords', 'DeleteCompanyHRRecordsController@repostOneTableRecords');
    Route::get('/approveOneTableRecords', 'DeleteCompanyHRRecordsController@approveOneTableRecords');
    Route::get('/rejectOneTableRecords', 'DeleteCompanyHRRecordsController@rejectOneTableRecords');
    Route::get('/repostMasterTableRecords', 'DeleteCompanyHRRecordsController@repostMasterTableRecords');
    Route::get('/approvePayroll', 'DeleteCompanyHRRecordsController@approvePayroll');
    Route::get('/deleteEmployeePayroll', 'DeleteCompanyHRRecordsController@deleteEmployeePayroll');
    Route::get('/approveAdvanceSalaryWithPaySlip', 'DeleteCompanyHRRecordsController@approveAdvanceSalaryWithPaySlip');
    Route::get('/rejectAdvanceSalaryWithPaySlip', 'DeleteCompanyHRRecordsController@rejectAdvanceSalaryWithPaySlip');
    Route::get('/deleteAdvanceSalaryWithPaySlip', 'DeleteCompanyHRRecordsController@deleteAdvanceSalaryWithPaySlip');
    Route::get('/deleteLeavesDataPolicyRows', 'DeleteCompanyHRRecordsController@deleteLeavesDataPolicyRows');
    Route::get('/approveLoanRequest', 'DeleteCompanyHRRecordsController@approveLoanRequest');
    Route::get('/rejectLoanRequest', 'DeleteCompanyHRRecordsController@rejectLoanRequest');
    Route::get('/deleteLoanRequest', 'DeleteCompanyHRRecordsController@deleteLoanRequest');
    Route::get('/deleteEmployeeBonus', 'DeleteCompanyHRRecordsController@deleteEmployeeBonus');
    Route::get('/deleteLeaveApplicationDetail', 'DeleteCompanyHRRecordsController@deleteLeaveApplicationDetail');
    Route::get('/approveAndRejectRequestHiring', 'DeleteCompanyHRRecordsController@approveAndRejectRequestHiring');
    Route::get('/deleteEmployeeAttendance', 'DeleteCompanyHRRecordsController@deleteEmployeeAttendance');
    Route::get('/deleteEmployeeDocument', 'DeleteCompanyHRRecordsController@deleteEmployeeDocument');
    Route::get('/approveAndRejectTableRecord', 'DeleteCompanyHRRecordsController@approveAndRejectTableRecord');
    Route::get('/approveAndRejectEmployeeLocationAndPromotion', 'DeleteCompanyHRRecordsController@approveAndRejectEmployeeLocationAndPromotion');
    Route::get('/deleteEmployeeLocationAndPromotion', 'DeleteCompanyHRRecordsController@deleteEmployeeLocationAndPromotion');
    Route::get('/approveMonthViseTableRecord', 'DeleteCompanyHRRecordsController@approveMonthViseTableRecord');
    Route::get('/deleteEmployeeGsspDocument', 'DeleteCompanyHRRecordsController@deleteEmployeeGsspDocument');
    Route::get('/test', 'DeleteCompanyHRRecordsController@test');
    Route::get('/deleteEmployeesBonus', 'DeleteCompanyHRRecordsController@deleteEmployeesBonus');
    Route::get('/printAndDeliverIdCard', 'DeleteCompanyHRRecordsController@printAndDeliverIdCard');
    Route::get('/deleteEmployeeSixthMonthAuditDetail', 'DeleteCompanyHRRecordsController@deleteEmployeeSixthMonthAuditDetail');
    Route::get('/deleteEmployeeTwelfthMonthAuditDetail', 'DeleteCompanyHRRecordsController@deleteEmployeeTwelfthMonthAuditDetail');
    Route::get('/deleteEmployeeCnicCopy', 'DeleteCompanyHRRecordsController@deleteEmployeeCnicCopy');
    Route::get('/deleteEmployeeExperienceDocuments', 'DeleteCompanyHRRecordsController@deleteEmployeeExperienceDocuments');
    Route::get('/approveAndRejectEmployeeExit', 'DeleteCompanyHRRecordsController@approveAndRejectEmployeeExit');
    Route::get('/deleteEmployeeEobiCopy', 'DeleteCompanyHRRecordsController@deleteEmployeeEobiCopy');
    Route::get('/deleteEmployeeInsuranceCopy', 'DeleteCompanyHRRecordsController@deleteEmployeeInsuranceCopy');
    Route::get('/deleteEmployeeEquipments', 'DeleteCompanyHRRecordsController@deleteEmployeeEquipments');
    Route::get('/deleteEmployee', 'DeleteCompanyHRRecordsController@deleteEmployee');
    Route::get('/deleteIncomeTax', 'DeleteCompanyHRRecordsController@deleteIncomeTax');

    Route::get('/restoreEmployee', 'DeleteCompanyHRRecordsController@restoreEmployee');
    Route::get('/deleteEmployeeExitClearance', 'DeleteCompanyHRRecordsController@deleteEmployeeExitClearance');
    Route::get('/customUpdate','DeleteCompanyHRRecordsController@customUpdate');
    Route::get('/change_status','DeleteCompanyHRRecordsController@change_status');
    Route::get('/deleteEmployeeRebate','DeleteCompanyHRRecordsController@deleteEmployeeRebate');
    Route::get('/deleteRebateDocument','DeleteCompanyHRRecordsController@deleteRebateDocument');
    
    Route::get('/approveAndRejectLeaveApplication2','DeleteCompanyHRRecordsController@approveAndRejectLeaveApplication2');
    Route::get('/approveAndRejectLeaveApplication', 'DeleteCompanyHRRecordsController@approveAndRejectLeaveApplication');



});