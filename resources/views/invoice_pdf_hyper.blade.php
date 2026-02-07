<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>

          @page {
                margin-top: -0.5cm;
                margin-bottom: 0.5cm;
                margin-left: 0.0cm;
                margin-right: 0.0cm;
            }


        body {
             
            background: #fff;
            color: #000;
            font-family: 'Open Sans', 'Arial', 'Helvetica', sans-serif, 'Tahoma', 'Amiri', sans-serif;
            /* Arabic font */
            font-size: 8pt;
            line-height: 100%;
        }



        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            border: 1px solid #ddd;
            padding: 20px;
            max-width: 800px;
            margin: auto;
        }
        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .col {
            flex: 1;
        }
        .text-end {
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 1px solid white;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f9f9f9;
        }
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 10px;
            margin-top: 20px;
        }
        .text-primary {
            color: #007bff;
        }
        .text-center {
            text-align: center;
        }


        .noborder_table {
    border-left: 0;
    border-right: 0;
    border-top: 0.;
    border-bottom: 0;
    border-collapse: collapse;
}

.noborder_table  td,
.noborder_table   th {
    border-left: 0;
    border-right: 0;
    border-top: 0;
    border-bottom: 0;
}


    </style>
</head>
<body>
    <?php 
$company = DB::table('company')->first(); ?>
    
       
     

<table class="noborder_table">
    <tr>
        <td width="50%" valign="top"> <img src="{{ asset('documents/'.$company->Logo) }}" alt="Holiday Hype" style="max-height: 100px;"></td>
        <td width="50%" valign="top" style="text-align: right;">
            <p style="margin: 0;"> {{$company->Address}}<br />
        {{$company->Contact}} <br>
        {{$company->Mobile}} <br />
        {{$company->Email}}</td>
    </tr>
</table>


<table class="noborder_table">
    <tr>
        <td width="50%" valign="bottom" style="line-height: 5px;"> <p><strong>Customer:</strong> {{$invoice_mst[0]->PartyName}}  </p>
                 <p><strong>Phone:</strong>  {{$invoice_mst[0]->Phone}}</p></td>
        <td width="50%" valign="top" style="text-align: right;line-height: 5px;"><h3 class="text-primary">INVOICE</h3>
                <p><strong>INV No:</strong> {{$invoice_mst[0]->InvoiceCode}}<br>
                <p><strong>Date:</strong> {{$invoice_mst[0]->Date}}</p></td>
    </tr>
</table>

        <table style="margin-top: -5px;">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Service</th>
                    <th>Passenger</th>
                    <th>PNR / Passport#</th>
                    <th>Sector / Nationality</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>VAT</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>

<?php 

$service=0;
$taxable=0;
$total=0;

 ?>

@foreach($invoice_det as $key => $value) 

<?php 

$service=$service + $value->Service;
$taxable=$taxable + $value->Taxable;
$total=$total + $value->Total;

 ?>

                <tr>
                    <td>{{++$key}}</td>
                    <td>{{$value->ItemName}}</td>
                    <td>{{$value->PaxName}}</td>
                    <td>{{$value->PNR}}</td>
                    <td>{{$value->Sector}}</td>
                    <td>1.00</td>
                    <td>{{number_format($value->Total,2)}}</td>
                    <td>{{number_format($taxable,2)}}</td>
                    <td>{{number_format($value->Total,2)}}</td>
                </tr>

                @endforeach


 

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" style="text-align: right;"><strong>Grand Total</strong></td>
                    <td>{{number_format($total,2)}}</td>
                </tr>
                <tr>
                    <td colspan="9" class="text-center" style="text-transform: capitalize;">UAE Dirham {{convert_number_to_words($total)}} only</td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align: right;"><strong>Paid Amount</strong></td>
                    <td>{{number_format($invoice_mst[0]->Paid,2)}}</td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align: right;"><strong>Balance Amount</strong></td>
                    <td>{{number_format($invoice_mst[0]->Balance,2)}}</td>
                </tr>
            </tfoot>
        </table>

        <div class="alert">
            <strong>IMPORTANT NOTES</strong>
            <ul style="margin: 0; line-height: 20px;">
                <li>Air Line Refund Will Be Processed, If Received In Writing, Within 15 Days From The Refund Request Date.</li>
                <li>The Visa Fee Non-Refundable In Case The Visa Application Is Not Approved By The UAE Immigration Authority.</li>
                <li>This Is Computer Generated Invoice No Signature And Stamp Is Required.</li>
            </ul>
        </div>

        <p style="text-align: right; margin-top: 20px;">Prepared by: <strong>Holiday Hype</strong></p>
     
</body>
</html>
