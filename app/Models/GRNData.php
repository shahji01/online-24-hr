<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GRNData extends Model{
    protected $table = 'grn_data';
    protected $fillable = ['grn_id','po_data_id','department_id','region_id','po_no','po_date','lot_no','grn_no','grn_date','demand_type','demand_send_type','category_id','sub_item_id','local_imported','manufacturer_name','manufacturer_lot_no','manufacturing_date','expiry_date','delivery_challan_no','loss_quantity','total_quantity_received','purchase_order_qty','physical_damaged_quantity','tqrigc','total_no_of_dcb','pack_size','purchaseRequestQty','rate','subTotal','receivedQty','status','grn_status','username','date','time','approve_username','delete_username','user_id','accounting_year'];
    protected $primaryKey = 'id';
    public $timestamps = false;
}
