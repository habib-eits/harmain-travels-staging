@if(!is_null($supplier[0]->Balance))
 

 
<div class=" mt-1 {{($supplier[0]->Balance>0) ? 'text-success' : 'text-danger'}}"><strong>Balance: {{$supplier[0]->Balance}}</strong></div>
 @else
<p class="mt-1 text-danger "><strong>No Balance Recorded</strong></p>
 @endif