<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaDetail extends Model
{
    use HasFactory;

    protected $table = 'invoice_detail';

    protected $primaryKey = "InvoiceDetailID";

    public $timestamps = false;


    protected $fillable = [
        'InvoiceMasterID',
        'BranchID',
        'ItemID',
        'SupplierID',
        'GroupNo',
        'VisaType',
        'PaxName',
        'PaxContact',
        'Contact',
        'Passport',
        'Nationality',
        'VisaStatus',
        'VisaNo',
        'DOB',
        'Age',
        'PaxType',
        'Gender',
        'IssueDate',
        'ExpiryDate',
        'RelationType',
        'Relation',
        'ShirkaID',
        'PackageName',
        'HotelVoucher',
        'DepartureDate',
        'Fare',
        'Service',
        'Total',
        'VisaSaleRate',
        'ExRateSale',
        'Receivable',
        'VisaPurchaseRate',
        'ExRatePurchase',
        'Payable',
 
    ];


    public function master()
    {
        return $this->belongsTo(VisaMaster::class, 'InvoiceMasterID', 'InvoiceMasterID');
    }

}



