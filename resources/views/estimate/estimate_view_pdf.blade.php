



<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Invoice</title>
        <style type="text/css">
            @font-face {
                font-family: 'Open Sans';
                font-style: normal;
                font-weight: normal;
             }
            @font-face {
                font-family: 'Open Sans';
                font-style: normal;
                font-weight: bold;
             }
            @font-face {
                font-family: 'Open Sans';
                font-style: italic;
                font-weight: normal;
             }
            @font-face {
                font-family: 'Open Sans';
                font-style: italic;
                font-weight: bold;
             }
            @page {
                margin-top: 0.5cm;
                margin-bottom: 0.5cm;
                margin-left: 0.5cm;
                margin-right: 0.5cm;
            }
            body {
    background: #fff;
    color: #000;
    margin: 0.02cm;
    font-family: 'Open Sans', sans-serif;
    font-size: 10pt;
    line-height: 100%;
             }
            h1, h2, h3, h4 {
                font-weight: bold;
                margin: 0;
            }
            h1 {
                font-size: 16pt;
                margin: 5mm 0;
            }
            h2 {
                font-size: 14pt;
            }
            h3, h4 {
                font-size: 9pt;
            }
            ol,
            ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }
            li,
            ul {
                margin-bottom: 0.75em;
            }
            p {
                margin: 0;
                padding: 0;
            }
            p + p {
                margin-top: 1.25em;
            }
            a {
                border-bottom: 1px solid;
                text-decoration: none;
            }
            /* Basic Table Styling */
            table {
                border-collapse: collapse;
                border-spacing: 0;
                page-break-inside: always;
                border: 0;
                margin: 0;
                padding: 0;
            }
            th, td {
                vertical-align: top;
                text-align: left;
            }
            table.container {
                width:100%;
                border: 0;
            }
            tr.no-borders,
            td.no-borders {
                border: 0 !important;
                border-top: 0 !important;
                border-bottom: 0 !important;
                padding: 0 !important;
                width: auto;
            }
            /* Header */
            table.head {
                margin-bottom: 2mm;
            }
            td.header img {
                max-height: 3cm;
                width: auto;
            }
            td.header {
                font-size: 16pt;
                font-weight: 700;
            }
            td.shop-info {
                width: 40%;
            }
            .document-type-label {
                text-transform: uppercase;
            }
            table.order-data-addresses {
                width: 100%;
                margin-bottom: 0.5mm;
            }
            td.order-data {
                width: 30%;
            }
            .invoice .shipping-address {
                width: 30%;
            }
            .packing-slip .billing-address {
                width: 30%;
            }
            td.order-data table th {
                font-weight: normal;
                padding-right: 2mm;
            }

            table.order-details {
                width:100%;
                margin-bottom: 8mm;
            }
            .quantity,
            .price {
                width: 8%;
            }
            .product {
                width: 33%;
            }
            .sno {
                width: 3%;
            }
            .order-details tr {
                page-break-inside: always;
                page-break-after: auto;
            }
            .order-details td,
            .order-details th {
                border-bottom: 1px #ccc solid;
                border-top: 1px #ccc solid;
                padding: 0.375em;
            }
            .order-details th {
                font-weight: bold;
                text-align: left;
            }
            .order-details thead th {
                color: black;
                background-color: #b8b8b8;
                border-color: #b8b8b8;

            }

            .order-details tr.bundled-item td.product {
                padding-left: 5mm;
            }
            .order-details tr.product-bundle td,
            .order-details tr.bundled-item td {
                border: 0;
            }
            dl {
                margin: 4px 0;
            }
            dt, dd, dd p {
                display: inline;
                font-size: 7pt;
                line-height: 7pt;
            }
            dd {
                margin-left: 5px;
            }
            dd:after {
                content: "\A";
                white-space: pre;
            }

            .customer-notes {
                margin-top: 5mm;
            }
            table.totals {
                width: 100%;
                margin-top: 5mm;
            }
            table.totals th,
            table.totals td {
                border: 0;
                border-top: 1px solid #ccc;
                border-bottom: 1px solid #ccc;
            }
            table.totals th.description,
            table.totals td.price {
                width: 50%;
            }
            table.totals tr:last-child td,
            table.totals tr:last-child th {
                border-top: 2px solid #000;
                border-bottom: 2px solid #000;
                font-weight: bold;
            }
            table.totals tr.payment_method {
                display: none;
            }

            #footer {
                position: absolute;
                bottom: -2cm;
                left: 0;
                right: 0;
                height: 2cm;
                text-align: center;
                border-top: 0.1mm solid gray;
                margin-bottom: 0;
                padding-top: 2mm;
            }
 
            .pagenum:before {
                content: counter(page);
            }
            .pagenum,.pagecount {
                font-family: sans-serif;
            }

            #xyz_main {
    width: 1000px;
    border: 0px solid #666666;
    text-align: center;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: -500px;
     z-index:1000;
}

            #bg_logo {
    width: 100%;
    height: 190px;
    border: 0px solid #666666;
    text-align: center;
    position: absolute;
    top: 0%;
    left: 0%;
    opacity: 100;
     z-index:-1000;
     background-repeat: no-repeat;
    background-size: cover;


     
}


        </style>
</head>
<body class="invoice"  >

<div id="bg_logo"> </div>
     
        <table class="head container">
            <tr>
                <td class="header">
                   <img src="{{ asset('documents/' . $company[0]->Logo) }}" alt="" width="{{ env('APP_LOGO_WIDTH') }}"
                    height="{{ env('APP_LOGO_HEIGHT') }}">              
                    </td>
                <td class="shop-info">
                    <div class="shop-name">
                        <div align="right"><div style="font-size: 16pt;line-height: 20pt;">
                            <!--<strong> {{$company[0]->Name}}<br>{{$company[0]->Name2}}</strong>-->
                        </div> 
      {{$company[0]->Address}}<br />
      Contact:{{$company[0]->Contact}}<br />
      TRN:{{$company[0]->TRN}}<br />
      Email:{{$company[0]->Email}}<br />
      Website:{{$company[0]->Website}}<br />
    </div>
                    </div>
                   
                    
                    <div class="shop-address"></div>                </td>
            </tr>
            <tr>
              <td colspan="2" class="header"><div align="center">
                <h2 style="margin-top:30px" ><u>Quotation</u></h2>
              </div></td>
            </tr>
</table>

 
        <table class="order-data-addresses">
            <tr>
                <td valign="bottom" class="address billing-address">
                <strong>Buyer</strong><br>
                    <strong>{{$estimate[0]->PartyName}}  {{($estimate[0]->WalkinCustomerName==1) ? ' -'.$estimate[0]->WalkinCustomerName : ''}}</strong><br />
                     @if($estimate[0]->PartyID !=1)
            
                Address: {{$estimate[0]->Address}}<br />
          Phone: {{$estimate[0]->Phone}}<br />
          TRN: {{$estimate[0]->TRN}}<br /> 
            @endif

            <br />          </td>
                <td class="order-data">
                    <table align="right">
                        <tr class="order-number">
                            <th><strong>Estimate # </strong></th>
                            <td><div align="right">{{$estimate[0]->EstimateNo}}</div></td>
                        </tr>
                        <tr class="order-date">
                            <th><strong>Date:</strong></th>
                            <td><div align="right">{{dateformatman($estimate[0]->Date)}}</div></td>
                        </tr>
                        <tr class="payment-method">
                            <th><strong>Expiry Date :</strong></th>
                            <td><div align="right">{{dateformatman($estimate[0]->ExpiryDate)}}</div></td>
                        </tr>
                      
                    </table>                </td>
            </tr>
           
        </table>
   

    <table  class="order-details" style="width:750px !important;">
        <thead>
            <tr>
                  <th width="5%" class="sno">S#</th>
                <th width="20%" class="product">Description</th>
                <th width="10%" class="quantity">Qty</th>
                <th width="10%" class="price">Rate</th>
                 <th width="10%" class="price">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($estimate_detail as $key => $value)

            <tr>
                  <td height="13px">{{$key+1}}</td>
                <td>{{$value->Description}}</td>
                <td>{{$value->Qty}}</td>
                <td>{{number_format($value->Rate,2)}}</td>
                 <td>{{number_format($value->Total,2)}}</td>
      </tr>
            @endforeach
       

         <?php  for($i = 10; $i>=count($estimate_detail);$i--) { ?>

            <tr>
                  <td height="13px"></td>
                <td></td>
                <td></td>
                <td></td>
                 <td></td>
            </tr>

            <?php } ?>   
        </tbody>
        <tfoot>
            <tr class="no-borders">
                  <td colspan="3" class="no-borders">
                  <div class="customer-notes"><strong>Customer Notes: </strong>{{$estimate[0]->CustomerNotes}}. </div>                    
                  <div class="customer-notes"><strong>Description Notes: </strong>{{$estimate[0]->DescriptionNotes}}. </div>

                  <div style="padding-top: 10px;"><strong>In words </strong>: <em>{{ucwords(convert_number_to_words($estimate[0]->GrandTotal))}} only/-</em></div>
               @if($company[0]->Signature!=null)   
              <img src="{{URL('/documents/'.$company[0]->Signature)}}" width="200"  />
              @endif
                        </td>
                <td class="no-borders" colspan="2">

                          <table class="totals">
                        <tfoot>
                          <tr class="cart_subtotal">
                              <td class="no-borders"></td>
                              <th class="description">Subtotal</th>
                              <td class="price"><span class="totals-price"><span class="amount"> {{number_format($estimate[0]->SubTotal,2)}} {{env('APP_CURRENCY')}}</span></span></td>
                          </tr>
                          @if(env('VAT_PERCENTAGE') > 0)
                          <tr class="order_total">
                              <td class="no-borders"></td>
                              <th class="description">5% VAT </th>
                              <td class="price"><span class="totals-price"><span class="amount">{{number_format($estimate[0]->Tax,2)}} {{env('APP_CURRENCY')}}</span></span></td>
                          </tr>
                          @endif
                          <tr class="order_total">
                              <td class="no-borders"></td>
                              <th class="description">Total</th>
                              <td class="price"><span class="totals-price"><span class="amount">{{number_format($estimate[0]->GrandTotal,2)}} {{env('APP_CURRENCY')}}</span></span></td>
                          </tr>
                          
                        </tfoot>
                    </table>

                               </td>
            </tr>
        </tfoot>
    </table>

   

</body>
</html>