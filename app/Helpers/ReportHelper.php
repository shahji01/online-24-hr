<?php
namespace App\Helpers;
use DB;
use Config;
use App\Helpers\CommonHelper;
class ReportHelper{
    
	public static function displayLoadCategoryAndItemWisePurchaseAndStockMonitoringDetail($m,$filterRegionId,$filterCategoryId,$filterSubItemId,$fromDate,$toDate){
		CommonHelper::companyDatabaseConnection($m);
			$detailDemandAndDemandData = DB::table('demand_data')
				->select('demand_data.id','demand_data.demand_id','demand_data.category_id','demand_data.sub_item_id','demand_data.demand_no','demand_data.demand_date','demand.location_id','demand_data.demand_status_type')
				->join('demand', 'demand_data.demand_no', '=', 'demand.demand_no')
				->whereBetween('demand_data.demand_date',[$fromDate,$toDate])
				->where('demand_data.category_id','=',$filterCategoryId)
				->where('demand_data.sub_item_id','=',$filterSubItemId)
				->where('demand.region_id','=',$filterRegionId)
				->get();
		CommonHelper::reconnectMasterDatabase();
		foreach($detailDemandAndDemandData as $row){
			$locationName = CommonHelper::getCompanyDatabaseTableValueById($m,'customer_location','location_name',$row->location_id);
			if($row->demand_status_type == '1'){
				$demandStatusType = 'Pending';
			}else if($row->demand_status_type == '2'){
				$demandStatusType = 'Approved';
			}else if($row->demand_status_type == '3'){
				$demandStatusType = 'Dis-Approved';
			}else if($row->demand_status_type == '4'){
				$demandStatusType = 'Decline';
			}
			CommonHelper::companyDatabaseConnection($m);
				$detailStoreChallanAndStoreChallanData = DB::table('store_challan_data')
					->select('store_challan_data.id','store_challan_data.store_challan_id','store_challan_data.demand_id','store_challan_data.demand_data_id','store_challan_data.store_challan_no','store_challan_data.store_challan_date','store_challan_data.store_challan_status','store_challan_data.status')
					->where('store_challan_data.demand_id','=',$row->demand_id)
					->where('store_challan_data.demand_data_id','=',$row->id)
					->get();
					
				$detailPurchaseRequestAndPurchaseRequestData = DB::table('purchase_request_data')
					->select('purchase_request_data.id','purchase_request_data.purchase_request_id','purchase_request_data.demand_id','purchase_request_data.demand_data_id','purchase_request_data.purchase_request_no','purchase_request_data.purchase_request_date','purchase_request_data.purchase_request_status','purchase_request_data.status')
					->where('purchase_request_data.demand_id','=',$row->demand_id)
					->where('purchase_request_data.demand_data_id','=',$row->id)
					->get();
			CommonHelper::reconnectMasterDatabase();
			if(count($detailStoreChallanAndStoreChallanData) == '0' && count($detailPurchaseRequestAndPurchaseRequestData) == '0'){
				$addRowsSpanValue = '1';
				$abc = 'Zero-One';
			}else{
				if(count($detailStoreChallanAndStoreChallanData) > count($detailPurchaseRequestAndPurchaseRequestData)){
					$addRowsSpanValue = count($detailStoreChallanAndStoreChallanData);
					$abc = 'Store Challan Greater Than';
				}else if(count($detailStoreChallanAndStoreChallanData) < count($detailPurchaseRequestAndPurchaseRequestData)){
					$addRowsSpanValue = count($detailPurchaseRequestAndPurchaseRequestData);
					$abc = 'Store Challan Less Than';
				}else{
					$addRowsSpanValue = '1';
					$abc = 'Zero-Two';
				}
			}
			?>
			<tr>
				<td rowspan="<?php echo $addRowsSpanValue?>"><?php echo $locationName?> </td>
				<td rowspan="<?php echo $addRowsSpanValue?>"><?php echo $row->demand_no?></td>
				<td rowspan="<?php echo $addRowsSpanValue?>"><?php echo CommonHelper::changeDateFormat($row->demand_date)?></td>
				<td rowspan="<?php echo $addRowsSpanValue?>"><?php echo $demandStatusType?></td>
				<?php 
					if(count($detailStoreChallanAndStoreChallanData) == '0'){
					?>
							<td class="text-center" rowspan="<?php echo $addRowsSpanValue?>">-</td>
							<td class="text-center" rowspan="<?php echo $addRowsSpanValue?>">-</td>
							<td class="text-center" rowspan="<?php echo $addRowsSpanValue?>">-</td>
							<td class="text-center" rowspan="<?php echo $addRowsSpanValue?>">-</td>
							<td class="text-center" rowspan="<?php echo $addRowsSpanValue?>">-</td>
							<td class="text-center" rowspan="<?php echo $addRowsSpanValue?>">-</td>
					<?php	
					}else{
						foreach($detailStoreChallanAndStoreChallanData as $row2){
							$storeChallanItemStatus = StoreHelper::checkVoucherStatus($row2->store_challan_status,$row2->status);
						?>
							<td rowspan="<?php //echo $addRowsSpanValue?>"><?php echo $row2->store_challan_no?></td>
							<td rowspan="<?php //echo $addRowsSpanValue?>"><?php echo CommonHelper::changeDateFormat($row2->store_challan_date)?></td>
							<td rowspan="<?php //echo $addRowsSpanValue?>"><?php echo $storeChallanItemStatus;?></td>
							
							<?php 
								if($storeChallanItemStatus == 'Approve'){
							?>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
							<?php
								}else{
							?>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
							<?php 
								}
							?>
						<?php
						}
					}
				?>
				<?php 
					if(count($detailPurchaseRequestAndPurchaseRequestData) == '0'){
					?>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
							<td class="text-center">-</td>
						</tr>
					<?php	
					}else{
						foreach($detailPurchaseRequestAndPurchaseRequestData as $row3){
							$purchaseRequestItemStatus = StoreHelper::checkVoucherStatus($row3->purchase_request_status,$row3->status);
						?>
							<td><?php echo $row3->purchase_request_no?></td>
							<td><?php echo CommonHelper::changeDateFormat($row3->purchase_request_date)?></td>
							<td><?php echo $purchaseRequestItemStatus?></td>
							<?php 
								if($purchaseRequestItemStatus == 'Approve'){
							?>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
							<?php
								}else{
							?>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
							<?php 
								}
							?>
						</tr>
						<?php
						}
					}
				?>
			</tr>
			<?php
		}
	}

	public static function leaveBalanceReportPQ($employees_id,$startYear,$endYear)
	{
		$year = $startYear.' - '.$endYear ;

		$queryReport = " SELECT abc.id,abc.emp_id,abc.department_id,abc.emp_name,'$year' as 'year'
					,
					SUM(IF(abc.month = 1 , abc.totalleave,0 ))'jan',
					SUM(IF(abc.month = 2 , abc.totalleave,0 ))'feb',
					SUM(IF(abc.month = 3 , abc.totalleave,0 ))'mar',
					SUM(IF(abc.month = 4 , abc.totalleave,0 ))'apr',
					SUM(IF(abc.month = 5 , abc.totalleave,0 ))'may',
					SUM(IF(abc.month = 6 , abc.totalleave,0 ))'jun',
					SUM(IF(abc.month = 7 , abc.totalleave,0 ))'jul',
					SUM(IF(abc.month = 8 , abc.totalleave,0 ))'aug',
					SUM(IF(abc.month = 9 , abc.totalleave,0 ))'sep',
					SUM(IF(abc.MONTH = 10 , abc.totalleave,0 ))'oct',
					SUM(IF(abc.MONTH = 11 , abc.totalleave,0 ))'nov',
					SUM(IF(abc.MONTH = 12 , abc.totalleave,0 )) 'dec',
					SUM(IF(abc.leave_type = 3 , abc.totalleave,0 )) 'used_casual',
					SUM(IF(abc.leave_type = 2 , abc.totalleave,0 )) 'used_sick',
					SUM(IF(abc.leave_type = 1 , abc.totalleave,0 )) 'used_annual',
					10 - SUM(IF(abc.leave_type = 3 ,   abc.totalleave , 0 )) 'remaining_casual',
					8  - SUM(IF(abc.leave_type = 2 ,  abc.totalleave, 0 )) 'remaining_sick',
					14 - SUM(IF(abc.leave_type = 1 ,  abc.totalleave, 0 )) 'remaining_annual'
					FROM
					(

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-06-25'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-06-25'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-06-25' ) + 1 

							WHEN ('$startYear-07-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-07-25' = lad.from_date )
								THEN DATEDIFF('$startYear-07-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-07-25') , year('$startYear-07-25') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-07-25') , MONTH('$startYear-07-25') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-06-25' and '$startYear-07-25'   , (from_date between '$startYear-06-25' and '$startYear-07-25' or to_date between '$startYear-06-25' and '$startYear-07-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-07-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-07-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-07-26' ) + 1 

							WHEN ('$startYear-08-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-08-25' = lad.from_date )
								THEN DATEDIFF('$startYear-08-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-08-25') , year('$startYear-08-25') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-08-25') , MONTH('$startYear-08-25') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-07-26' and '$startYear-08-25'   , (from_date between '$startYear-07-26' and '$startYear-08-25' or to_date between '$startYear-07-26' and '$startYear-08-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-08-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-08-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-08-26' ) + 1 

							WHEN ('$startYear-09-24' BETWEEN lad.from_date AND lad.to_date OR '$startYear-09-24' = lad.from_date )
								THEN DATEDIFF('$startYear-09-24', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-09-24') , year('$startYear-09-24') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-09-24') , MONTH('$startYear-09-24') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-08-26' and '$startYear-09-24'   , (from_date between '$startYear-08-26' and '$startYear-09-24' or to_date between '$startYear-08-26' and '$startYear-09-24'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-09-25'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-09-25'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-09-25' ) + 1 

							WHEN ('$startYear-10-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-10-25' = lad.from_date )
								THEN DATEDIFF('$startYear-10-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-10-25') , year('$startYear-10-25') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-10-25') , MONTH('$startYear-10-25') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-09-25' and '$startYear-10-25'   , (from_date between '$startYear-09-25' and '$startYear-10-25' or to_date between '$startYear-09-25' and '$startYear-10-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-10-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-10-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-10-26' ) + 1 

							WHEN ('$startYear-11-24' BETWEEN lad.from_date AND lad.to_date OR '$startYear-11-24' = lad.from_date )
								THEN DATEDIFF('$startYear-11-24', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-11-24') , year('$startYear-11-24') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-11-24') , MONTH('$startYear-11-24') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-10-26' and '$startYear-11-24'   , (from_date between '$startYear-10-26' and '$startYear-11-24' or to_date between '$startYear-10-26' and '$startYear-11-24'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-11-25'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-11-25'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-11-25' ) + 1 

							WHEN ('$startYear-12-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-12-25' = lad.from_date )
								THEN DATEDIFF('$startYear-12-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-12-25') , year('$startYear-12-25') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-12-25') , MONTH('$startYear-12-25') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-11-25' and '$startYear-12-25'   , (from_date between '$startYear-11-25' and '$startYear-12-25' or to_date between '$startYear-11-25' and '$startYear-12-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-12-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-12-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-12-26' ) + 1 

							WHEN ('$startYear-01-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-01-25' = lad.from_date )
								THEN DATEDIFF('$startYear-01-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$endYear-01-26') , year('$endYear-01-26') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$endYear-01-26') , MONTH('$endYear-01-26') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-12-26' and '$endYear-01-25'   , (from_date between '$startYear-12-26' and '$endYear-01-25' or to_date between '$startYear-12-26' and '$endYear-01-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END


						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-01-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-01-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-01-26' ) + 1 

							WHEN ('$startYear-02-22' BETWEEN lad.from_date AND lad.to_date OR '$startYear-02-22' = lad.from_date )
								THEN DATEDIFF('$startYear-02-22', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$endYear-02-22') , year('$endYear-02-22') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$endYear-02-22') , MONTH('$endYear-02-22') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$endYear-01-26' and '$endYear-02-22'   , (from_date between '$endYear-01-26' and '$endYear-02-22' or to_date between '$endYear-01-26' and '$endYear-02-22'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-02-23'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-02-23'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-02-23' ) + 1 

							WHEN ('$startYear-03-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-03-25' = lad.from_date )
								THEN DATEDIFF('$startYear-03-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$endYear-03-25') , year('$endYear-03-25') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$endYear-03-25') , MONTH('$endYear-03-25') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$endYear-02-23' and '$endYear-03-25'   , (from_date between '$endYear-02-23' and '$endYear-03-25' or to_date between '$endYear-02-23' and '$endYear-03-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END
						
						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-03-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-03-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-03-26' ) + 1 

							WHEN ('$startYear-04-24' BETWEEN lad.from_date AND lad.to_date OR '$startYear-04-24' = lad.from_date )
								THEN DATEDIFF('$startYear-04-24', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$endYear-04-24') , year('$endYear-04-24') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$endYear-04-24') , MONTH('$endYear-04-24') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$endYear-03-26' and '$endYear-04-24'   , (from_date between '$endYear-03-26' and '$endYear-04-24' or to_date between '$endYear-03-26' and '$endYear-04-24'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-04-25'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-04-25'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-04-25' ) + 1 

							WHEN ('$startYear-05-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-05-25' = lad.from_date )
								THEN DATEDIFF('$startYear-05-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$endYear-05-25') , year('$endYear-05-25') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$endYear-05-25') , MONTH('$endYear-05-25') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$endYear-04-25' and '$endYear-05-25'   , (from_date between '$endYear-04-25' and '$endYear-05-25' or to_date between '$endYear-04-25' and '$endYear-05-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-05-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-05-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-05-26' ) + 1 

							WHEN ('$startYear-06-24' BETWEEN lad.from_date AND lad.to_date OR '$startYear-06-24' = lad.from_date )
								THEN DATEDIFF('$startYear-06-24', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$endYear-06-24') , year('$endYear-06-24') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$endYear-06-24') , MONTH('$endYear-06-24') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$endYear-05-26' and '$endYear-06-24'   , (from_date between '$endYear-05-26' and '$endYear-06-24' or to_date between '$endYear-05-26' and '$endYear-06-24'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END
						
					) abc GROUP by abc.id ";


        $employees_leave_data =  DB::select( DB::raw($queryReport));
		
		return $employees_leave_data;	
        
	}

	public static function leaveBalanceReport($employees_id,$startYear,$endYear)
	{
		$year = $startYear.' - '.$endYear ;

		$queryReport = " SELECT abc.id,abc.emp_id,abc.department_id,abc.emp_name,'$year' as 'year'
						,
						SUM(IF(abc.month = 1 , abc.totalleave,0 ))'jan',
						SUM(IF(abc.month = 2 , abc.totalleave,0 ))'feb',
						SUM(IF(abc.month = 3 , abc.totalleave,0 ))'mar',
						SUM(IF(abc.month = 4 , abc.totalleave,0 ))'apr',
						SUM(IF(abc.month = 5 , abc.totalleave,0 ))'may',
						SUM(IF(abc.month = 6 , abc.totalleave,0 ))'jun',
						SUM(IF(abc.month = 7 , abc.totalleave,0 ))'jul',
						SUM(IF(abc.month = 8 , abc.totalleave,0 ))'aug',
						SUM(IF(abc.month = 9 , abc.totalleave,0 ))'sep',
						SUM(IF(abc.MONTH = 10 , abc.totalleave,0 ))'oct',
						SUM(IF(abc.MONTH = 11 , abc.totalleave,0 ))'nov',
						SUM(IF(abc.MONTH = 12 , abc.totalleave,0 )) 'dec',
						SUM(IF(abc.leave_type = 3 , abc.totalleave,0 )) 'used_casual',
						SUM(IF(abc.leave_type = 2 , abc.totalleave,0 )) 'used_sick',
						SUM(IF(abc.leave_type = 1 , abc.totalleave,0 )) 'used_annual',
						10 - SUM(IF(abc.leave_type = 3 ,   abc.totalleave , 0 )) 'remaining_casual',
						8  - SUM(IF(abc.leave_type = 2 ,  abc.totalleave, 0 )) 'remaining_sick',
						14 - SUM(IF(abc.leave_type = 1 ,  abc.totalleave, 0 )) 'remaining_annual'
						FROM
						( SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-03-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-03-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-03-26' ) + 1 

							WHEN ('$startYear-04-24' BETWEEN lad.from_date AND lad.to_date OR '$startYear-04-24' = lad.from_date )
								THEN DATEDIFF('$startYear-04-24', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-04-24') , year('$startYear-04-24') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-04-24') , MONTH('$startYear-04-24') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-03-26' and '$startYear-04-24'   , (from_date between '$startYear-03-26' and '$startYear-04-24' or to_date between '$startYear-03-26' and '$startYear-04-24'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-04-25'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-04-25'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-04-25' ) + 1 

							WHEN ('$startYear-05-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-05-25' = lad.from_date )
								THEN DATEDIFF('$startYear-05-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-05-25') , year('$startYear-05-25') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-05-25') , MONTH('$startYear-05-25') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-04-25' and '$startYear-05-25'   , (from_date between '$startYear-04-25' and '$startYear-05-25' or to_date between '$startYear-04-25' and '$startYear-05-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-05-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-05-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-05-26' ) + 1 

							WHEN ('$startYear-06-24' BETWEEN lad.from_date AND lad.to_date OR '$startYear-06-24' = lad.from_date )
								THEN DATEDIFF('$startYear-06-24', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-06-24') , year('$startYear-06-24') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-06-24') , MONTH('$startYear-06-24') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-05-26' and '$startYear-06-24'   , (from_date between '$startYear-05-26' and '$startYear-06-24' or to_date between '$startYear-05-26' and '$startYear-06-24'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-06-25'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-06-25'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-06-25' ) + 1 

							WHEN ('$startYear-07-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-07-25' = lad.from_date )
								THEN DATEDIFF('$startYear-07-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-07-25') , year('$startYear-07-25') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-07-25') , MONTH('$startYear-07-25') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-06-25' and '$startYear-07-25'   , (from_date between '$startYear-06-25' and '$startYear-07-25' or to_date between '$startYear-06-25' and '$startYear-07-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-07-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-07-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-07-26' ) + 1 

							WHEN ('$startYear-08-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-08-25' = lad.from_date )
								THEN DATEDIFF('$startYear-08-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-08-25') , year('$startYear-08-25') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-08-25') , MONTH('$startYear-08-25') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-07-26' and '$startYear-08-25'   , (from_date between '$startYear-07-26' and '$startYear-08-25' or to_date between '$startYear-07-26' and '$startYear-08-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-08-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-08-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-08-26' ) + 1 

							WHEN ('$startYear-09-24' BETWEEN lad.from_date AND lad.to_date OR '$startYear-09-24' = lad.from_date )
								THEN DATEDIFF('$startYear-09-24', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-09-24') , year('$startYear-09-24') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-09-24') , MONTH('$startYear-09-24') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-08-26' and '$startYear-09-24'   , (from_date between '$startYear-08-26' and '$startYear-09-24' or to_date between '$startYear-08-26' and '$startYear-09-24'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-09-25'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-09-25'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-09-25' ) + 1 

							WHEN ('$startYear-10-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-10-25' = lad.from_date )
								THEN DATEDIFF('$startYear-10-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-10-25') , year('$startYear-10-25') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-10-25') , MONTH('$startYear-10-25') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-09-25' and '$startYear-10-25'   , (from_date between '$startYear-09-25' and '$startYear-10-25' or to_date between '$startYear-09-25' and '$startYear-10-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-10-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-10-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-10-26' ) + 1 

							WHEN ('$startYear-11-24' BETWEEN lad.from_date AND lad.to_date OR '$startYear-11-24' = lad.from_date )
								THEN DATEDIFF('$startYear-11-24', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-11-24') , year('$startYear-11-24') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-11-24') , MONTH('$startYear-11-24') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-10-26' and '$startYear-11-24'   , (from_date between '$startYear-10-26' and '$startYear-11-24' or to_date between '$startYear-10-26' and '$startYear-11-24'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-11-25'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-11-25'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-11-25' ) + 1 

							WHEN ('$startYear-12-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-12-25' = lad.from_date )
								THEN DATEDIFF('$startYear-12-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$startYear-12-25') , year('$startYear-12-25') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$startYear-12-25') , MONTH('$startYear-12-25') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-11-25' and '$startYear-12-25'   , (from_date between '$startYear-11-25' and '$startYear-12-25' or to_date between '$startYear-11-25' and '$startYear-12-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-12-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-12-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-12-26' ) + 1 

							WHEN ('$startYear-01-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-01-25' = lad.from_date )
								THEN DATEDIFF('$startYear-01-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$endYear-01-26') , year('$endYear-01-26') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$endYear-01-26') , MONTH('$endYear-01-26') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$startYear-12-26' and '$endYear-01-25'   , (from_date between '$startYear-12-26' and '$endYear-01-25' or to_date between '$startYear-12-26' and '$endYear-01-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END


						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-12-26'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-12-26'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-12-26' ) + 1 

							WHEN ('$startYear-01-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-01-25' = lad.from_date )
								THEN DATEDIFF('$startYear-01-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$endYear-02-22') , year('$endYear-02-22') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$endYear-02-22') , MONTH('$endYear-02-22') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$endYear-01-26' and '$endYear-02-22'   , (from_date between '$endYear-01-26' and '$endYear-02-22' or to_date between '$endYear-01-26' and '$endYear-02-22'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						UNION ALL

						SELECT

						e.emp_name,
						e.id,
						e.emp_id,
						e.department_id,
						CASE
							WHEN (first_second_half_date != '0/0/0000' OR first_second_half_date != '0000-00-00')
									THEN IFNULL(SUM(lad.no_of_days),0)
							WHEN ('$startYear-02-23'  BETWEEN lad.from_date AND lad.to_date OR '$startYear-02-23'  = lad.from_date)       	
									THEN DATEDIFF(lad.to_date, '$startYear-02-23' ) + 1 

							WHEN ('$startYear-03-25' BETWEEN lad.from_date AND lad.to_date OR '$startYear-03-25' = lad.from_date )
								THEN DATEDIFF('$startYear-03-25', lad.from_date) + 1 
							ELSE
								DATEDIFF(lad.to_date, lad.from_date) + 1 
									
						END totalleave,
						IF(first_second_half_date != '0/0/0000' ,year('$endYear-03-25') , year('$endYear-03-25') ) 'year',
						IF(first_second_half_date != '0/0/0000' ,MONTH('$endYear-03-25') , MONTH('$endYear-03-25') ) 'month',
						la.leave_type

						FROM ".CONST_COMPANY_DB.".employee e


						LEFT join ".CONST_MASTER_DB.".leave_application la
						on e.id = la.employee_id

						left join ".CONST_MASTER_DB.".leave_application_data lad
						ON la.id = lad.leave_application_id

						WHERE
						e.id in ($employees_id) and
						(la.approval_status = 2 or la.approval_status is null )
						and
						(
						IF(first_second_half_date != '0/0/0000' , first_second_half_date between '$endYear-02-23' and '$endYear-03-25'   , (from_date between '$endYear-02-23' and '$endYear-03-25' or to_date between '$endYear-02-23' and '$endYear-03-25'))


						)
						GROUP BY e.id ,
								la.leave_type,

						CASE
							WHEN first_second_half_date != '0/0/0000' THEN CONCAT(year(first_second_half_date),MONTH(first_second_half_date))
							WHEN from_date != '0/0/0000' THEN CONCAT(year(from_date),MONTH(from_date))
						END

						) abc GROUP by abc.id 
												";


        $employees_leave_data =  DB::select( DB::raw($queryReport));
		
		return $employees_leave_data;	
        
	}
}
?>