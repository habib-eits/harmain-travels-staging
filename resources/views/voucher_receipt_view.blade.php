<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Al Rabeh Tourism Receipt Voucher</title>
     <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 2px solid #000;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .contact-info {
            font-size: 12px;
            line-height: 1.5;
        }

        .contact-info div {
            margin-bottom: 3px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            background: #1f4788;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: right;
            color: white;
            font-weight: bold;
            font-size: 14px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1f4788;
        }

        .arabic-name {
            font-size: 32px;
            font-weight: bold;
            color: #1f4788;
            text-align: right;
        }

        .divider {
            border-top: 3px solid #1f4788;
            margin: 20px 0;
        }

        .receipt-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .receipt-number {
            font-size: 18px;
            font-weight: bold;
            color: #1f4788;
        }

        .receipt-title {
            color:  #1f4788;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 0 20px;
        }

        .date-section {
            text-align: right;
            font-size: 14px;
            color: #1f4788;
            font-weight: bold;
        }

        .currency-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            width:20%;
        }

        .currency-item {
            border: 1px solid #000;
            padding: 5px 10px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }
        .currency-title {
            padding: 0px 10px;
            text-align: center;
            font-size: 12px;
            font-weight: bold;
        }

        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .form-label {
            font-weight: bold;
            color: #1f4788;
            margin-right: 10px;
        }

        .form-value {
            border-bottom: 1px dashed #000;
            flex: 1;
            padding: 5px 0;
            min-height: 25px;
        }

        .arabic-label {
            margin-left: 10px;
            color: #1f4788;
            font-size: 16px;
            font-weight: bold;
        }

        .customer-mob {
            text-align: right;
            font-size: 14px;
            color: #1f4788;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
        }

        .signature-section {
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 200px;
            height: 40px;
            margin-bottom: 5px;
        }

        .signature-label {
            font-weight: bold;
            color: #1f4788;
            font-size: 14px;
        }

        .signature-arabic {
            color: #666;
            font-size: 12px;
            margin-top: 3px;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            
            .receipt-container {
                border: 2px solid #000;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    @php

         $voucher = DB::table('v_voucher_detail')->where('VoucherMstID',request()->id)->get();

         
        $party = DB::table('party')->where('PartyID',$voucher[0]->PartyID ?? 0)->first();

         
        $chartOfAccount = DB::table('chartofaccount')->where('ChartOfAccountID',$voucher[0]->ChOfAcc)->first();

        $bankName = $chartOfAccount->Category == 'BANK' ? $chartOfAccount->ChartOfAccountName : 'Cash Transaction';
        

    @endphp









    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="contact-info">
                @php
                    $company = DB::table('company')->first();
                @endphp
                <div>📞 {{ $company->Mobile }}</div>
                <div>📷 {{ $company->Name }}</div>
                <div>📧 {{ $company->Email }}</div>
                <div>📍 {{ $company->Address }}</div>
            </div>
            
            <div class="logo-section">
                <div class="logo">
                    <img src="{{ asset('documents/' . $company->Logo) }}" alt="">
                </div>
                
            </div>
            
           
        </div>

        <div class="divider"></div>

        <!-- Receipt Header -->
        <div class="receipt-header">
            <div class="receipt-number">{{$voucher_master[0]->Voucher}}</div>
            <div class="receipt-title">RECEIPT VOUCHER</div>
            <div class="date-section">
                <span>Date:</span>
            <span style="border-bottom:1px dashed black ">&nbsp;&nbsp;&nbsp;{{ date('d-m-Y', strtotime($voucher[0]->Date)) }}&nbsp;&nbsp;&nbsp;</span>

            </div>
           

        </div>

        <!-- Customer Mobile -->
        <div class="customer-mob">
            <span>Customer Mob.:</span>
            <span style="border-bottom:1px dashed black ">&nbsp;&nbsp;&nbsp;{{ $party->Phone ?? ''  }}&nbsp;&nbsp;&nbsp;</span>
             
        </div>

         @php
            $amount = number_format($voucher[0]->Debit, 2); // Ensures two decimal places
            [$integer, $decimal] = explode('.', $amount);
        @endphp
        <!-- Currency Box -->
        <div class="currency-box">
           
             <table border="0" style="width: 100%">
                <thead>
                    <tr>
                        <td style="width:70%"></td>
                        <td style="width:30%"></td>
                    </tr>
                </thead>
               <tbody>
                    <tr>
                        <!--<td class="currency-title">درهم</td>-->
                        <!--<td class="currency-title">فلس</td>-->
                    </tr>
                    <tr>
                        <!--<td class="currency-title">Dhs.</td>-->
                        <!--<td class="currency-title">Fils</td>-->
                    </tr>
                    <tr>
                        <td class="currency-item">{{ env('APP_CURRENCY') }}  {{ $integer }}</td>
                        <td class="currency-item">{{ $decimal }}</td>
                    </tr>
               </tbody>
            </table>
           
        </div>

        <!-- Form Fields -->
        <div class="form-row">
            <span class="form-label">Received from Mr./Ms.</span>
            <div class="form-value">{{ $party->PartyName ?? '' }}</div>
        </div>

        @php
            $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);
            $amountInWords = ucwords($formatter->format($voucher[0]->Debit)) . ' Only';

        @endphp

        <div class="form-row">
            <span class="form-label">The Sum of Dhs.</span>
            <div class="form-value">{{ $amountInWords }}</div>
        </div>

        <div class="form-row">
            <span class="form-label">Being</span>
            <div class="form-value">{{ $voucher[0]->Narration }}</div>
        </div>

        <div class="form-row">
            <span class="form-label">Cash / Cheque No:</span>
            <div class="form-value" style="text-align: center">{{ $voucher[0]->RefNo != '' ? $voucher[0]->RefNo : '-'  }}</div>
            <span class="form-label">Date</span>
            <div class="form-value" style="text-align: center">{{ date('d-m-Y', strtotime($voucher[0]->Date)) }}</div>
        </div>

        @php
            $accountName = DB::table('')
        @endphp

        <div class="form-row">
            <span class="form-label">Bank</span>
            <div class="form-value">{{ $bankName }}</div>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-section">
                <div class="signature-line"></div>
                <div class="signature-label">Accountant's Sign</div>
            </div>
            
            <div class="signature-section">
                <div class="signature-line"></div>
                <div class="signature-label">Receiver's Sign</div>
            </div>
        </div>
    </div>
</body>
</html>