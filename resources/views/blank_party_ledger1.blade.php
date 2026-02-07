 
  <?php 
            $DrTotal=0;
            $CrTotal=0;
             ?>
             @if(count($journal)>0)    
          <table class="table table-sm table-bordered  table-hover table-striped align-middle table-nowrap mb-0">
          <tbody><tr>
          <th class="col-md-1 text-center">DATE</th>
          <th class="col-md-1 text-center" >VHNO</th>
          <th class="col-md-1 text-center">TYPE</th>
          <th class="col-md-5 text-center">Description</th>
          <th class="col-md-1 text-center">DR / Invoice</th>
          <th class="col-md-1 text-center">CR / Payment</th>
          <th class="col-md-1 text-center">Balance</th>
           </tr>
          </tbody>
          <tbody>
            <tr></tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Opending Balance</td>
            <td></td>
            <td></td>
            <td class="text-danger text-end">{{$sql[0]->Balance}}</td>
          @foreach ($journal as $key =>$value)
           <tr>
           <td class="text-center">{{dateformatman($value->Date)}}</td>
           <td class="text-center">{{$value->VHNO}}</td>
           <td class="text-center">{{$value->JournalType}}</td>
           <td >{{$value->Narration}}</td>
           <td class="text-end"><div> {{($value->Dr==0) ? '' : number_format($value->Dr,2)}}</div></td>
           <td class="text-end"><div> {{($value->Cr==0) ? '' : number_format($value->Cr,2)}}</div></td>
              <td class="text-end">
               

               <?php 

if(!isset($balance)) { 

             $balance  =  $sql[0]->Balance + ($value->Dr-$value->Cr);
             $DrTotal = $DrTotal+$value->Dr;
             $CrTotal = $CrTotal+$value->Cr;
                          echo number_format($balance,2);



}
else
{
  $balance = $balance + ($value->Dr-$value->Cr);
  $DrTotal = $DrTotal+$value->Dr;
             $CrTotal = $CrTotal+$value->Cr;
              echo number_format($balance,2);

}
              ?> 
{{($balance>0) ? "DR" : "CR"}}
             </td>
           </tr>
           @endforeach   
          <tr  class="table-active">
              
           <td></td>
           <td></td>
           <td align="center"><strong> TOTAL</strong></td>
            <td class="text-end"></td>
           <td class="text-end fw-bolder">{{number_format($DrTotal,2)}}</td>
           <td class="text-end fw-bolder">{{number_format($CrTotal,2)}}</td>
            
            <td class="text-end fw-bolder"> </td>
          </tr>
           </tbody>
           </table>
           @else
             <p class=" text-danger">No data found</p>
           @endif 