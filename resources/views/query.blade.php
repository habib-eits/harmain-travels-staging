 @if(count($voucher_detail)>0)		
<table class="table table-sm align-middle table-nowrap mb-0">
<tbody><tr>
<th scope="col">S.No</th>
<th scope="col">VoucherMasterID</th>
<th scope="col">InvoiceNo</th>
</tr>
</tbody>
<tbody>
@foreach ($voucher_detail as $key =>$value)


<?php 

echo "update journal set InvoiceMasterID = ".$value->InvoiceNo." where VoucherMstID = ".$value->VoucherMstID."";
echo ";<br>";

 ?>
 <tr>
 <td class="col-md-1">{{$key+1}}</td>
 <td class="col-md-1">{{$value->VoucherMstID}}</td>
 <td class="col-md-1">{{$value->InvoiceNo}}</td>
 </tr>
 @endforeach   
 </tbody>
 </table>
 @else
   <p class=" text-danger">No data found</p>
 @endif   