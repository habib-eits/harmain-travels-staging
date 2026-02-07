<div class="card shadow-sm ">

  <div class="card-header" style="background-color: rgb(222, 239, 254) !important ;">
<span class="fa fa-bed"></span> &nbsp;Hotel | Accomodation
  </div>

  <div class="card-body" style="background-color: AliceBlue !important ;"> 
    <form id="umrah-invoice-hotel-form" method="post">
      @csrf

      <input type="hidden" name="invoice_hotel_id" id="invoice_hotel_id" class="form-control">
      <div class="row ">
        <div class="col-md-2">
          <label class="form-label">City</label>
          <select class="form-select" name="HotelCity" id="HotelCity">
            @foreach($location as $row)
            <option value="{{$row->location}}">{{$row->location}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Check In</label>
          <input type="date" name="CheckInDate" id="CheckInDate" class="form-control" value="{{date('Y-m-d') }}">
        </div>
          <div class="col-md-2">
          <label class="form-label">Nights</label>
          <input type="number" name="Nights" id="Nights"  class="form-control" value="1">
        </div>
        <div class="col-md-2">
          <label class="form-label">Check Out</label>
          <input type="date" name="CheckOutDate" id="CheckOutDate"  class="form-control" value="{{date('Y-m-d') }}">
        </div>
      
        <div class="col-md-4">
          <label class="form-label">Hotel Name</label>
          <select name="hotel_id" id="hotel_id"  class="form-select select2" >
            @foreach($hotel as $row)
          <option value="{{$row->id}}">{{$row->hotel_name}}-{{$row->location}}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="row ">
        <div class="col-md-2">
          <label class="form-label">Room Type</label>
          <select name="RoomType" id="RoomType"  class="form-select">
            @foreach($room_type as $row1)
            <option value="{{$row1->room_type}}">{{$row1->room_type}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Status</label>
          <select name="RoomStatus" id="RoomStatus" class="form-select">
          <option value="SHARING">SHARING</option>
          <option value="PRIVATE">PRIVATE</option>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Room/Bed</label>
          <input type="number" name="NoOfRooms" id="NoOfRooms" class="form-control" value="1">
        </div>
        <div class="col-md-2">
          <label class="form-label">Pax</label>
          <input type="number" name="HotelPax" id="HotelPax" class="form-control" value="1">
        </div>
        <div class="col-md-2">
          <label class="form-label">Purchase</label>
          <input type="number" name="HotelPurchase" id="HotelPurchase" class="form-control" value="0">
        </div>
        <div class="col-md-2">
          <label class="form-label">Sale</label>
          <input type="number" name="HotelSale" id="HotelSale" class="form-control" value="0">
        </div>
      </div>

  

      <div class="row ">
        <div class="col-md-2">
          <label class="form-label text-danger">Payable</label>
          <input type="number" name="HotelPayable" id="HotelPayable" class="form-control text-danger fw-bold" value="0" readonly>
        </div>
        <div class="col-md-2">
          <label class="form-label text-success">Receivable</label>
          <input type="number" name="HotelReceivable" id="HotelReceivable" class="form-control text-success fw-bold" value="0" readonly>
        </div>
        <div class="col-md-2">
          <label class="form-label">Coming From</label>
          <select name="Origin" id="Origin" class="form-select">
            @foreach ($location as $item)
            <option value="{{$item->location}}">{{$item->location}}</option>
                
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">After Check Out</label>
          <select name="Destination" id="Destination" class="form-select">
            @foreach ($location as $item)
            <option value="{{$item->location}}">{{$item->location}}</option>
                
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Hotel Supplier</label>
          <select name="SupplierID" id="SupplierID" class="form-select select2">
            <option value="">select </option>
            @foreach ($supplier as $item)
            <option value="{{$item->PartyID}}">{{$item->PartyID}}-{{$item->PartyName}}</option>
                
            @endforeach

          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">Stock Status</label>
          <select name="StockStatus" id="StockStatus" class="form-select">
            <option value="Stock">Stock</option>
          </select>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-2">
          <label class="form-label">ROE Purchase</label>
          <input type="text" name="ExRatePurchaseHotel" id="ExRatePurchaseHotel" class="form-control" value="1">
        </div>
        
        <div class="col-md-2">
          <label class="form-label">ROE Sale</label>
          <input type="text" name="ExRateSaleHotel" id="ExRateSaleHotel" class="form-control" value="1">
        </div>
        
        <div class="col-md-2">
          <label class="form-label">Hotel confirmation No</label>
          <input type="text" name="HCN_NO" id="HCN_NO" class="form-control" value="">
        </div>

             <div class="col-md-2">
          <label class="form-label">Room View</label>
          <select name="RoomView" id="RoomView" class="form-select">
            @foreach ($room_view as $item)
            <option value="{{$item->room_view}}">{{$item->room_view}}</option>
            @endforeach
          </select>
        </div>
        
        
        <div class="col-md-2">
          <label class="form-label">Meal Plan</label>
          <select name="MealPlan" id="MealPlan" class="form-select">
            @foreach ($meal_plan as $item)
            <option value="{{$item->meal_plan}}">{{$item->meal_plan}}</option>
            @endforeach
          </select>
        </div>
 



      </div>
      
      
      
   
       
  




      <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-rounded btn-warning btn-block w-25" id="SaveHotel">Save Hotel</button>
        <button type="button" class="btn btn-rounded btn-success w-25" id="ModifyHotel">Modify Hotel</button>
        <button type="button" class="btn btn-rounded btn-danger w-25" id="DeleteHotel">Delete Hotel</button>
        <button type="button" class="btn btn-rounded bg-dark text-white w-25" id="AddNewHotel">Add New</button>
        <button type="button" class="btn btn-rounded btn-primary w-25" id="ChangeHotel">Change Hotel</button>



                 <div class="col-md-2 d-none" style="margin-top: -35px;">
          <label class="form-label">Nights</label>
          <input type="text" class="form-control text-center fw-bold" value="10" readonly>
        </div>
  


      
        <div class="col-md-2 d-none" style="margin-top: -35px;">
          <label class="form-label">Rooms</label>
          <input type="text" class="form-control text-center fw-bold" value="" readonly>
        </div>
      </div>



    </form>
  </div>

</div>

 


