

<div class="card mt-3">
    <div class="card-body shadow-sm">
        <div class="row">

  <!-- Payable Column -->
  <div class="col-lg-6  ">
    <h5 class="text-warning mb-2 ">Payable</h5>

    <div class=" row align-items-center">
      <label class="col-4 col-form-label">Total</label>
      <div class="col-8 d-flex">
        <input type="text" class="form-control " value="Total Amount" readonly>
      </div>
    </div>

    <div class=" row align-items-center">
      <label class="col-4 col-form-label">Visa</label>
      <div class="col-8 d-flex">
        <input type="text" id="VisaPurchaseTotal" class="form-control me-2" value="{{$invoice_master->passanger->sum('visa_purchase') ?? 0}}" readonly>
        <input type="text" id="VisaPurchaseAmount" class="form-control" value="{{ $invoice_master->passanger->sum(function ($item) {
    return $item->visa_purchase * $item->forex_purchase;
}) ?? 0 }}" readonly>
      </div>
    </div>

    <div class=" row align-items-center">
      <label class="col-4 col-form-label">Ticket</label>
      <div class="col-8 d-flex">
        <input type="text" id="TicketPurchaseTotal" class="form-control me-2" value="{{$invoice_master->passanger->sum('ticket_purchase') ?? 0}}" readonly>
        <input type="text" id="TicketPurchaseAmount" class="form-control" value="{{ $invoice_master->passanger->sum(function ($item) {
    return $item->ticket_purchase * $item->forex_purchase;
}) ?? 0 }}" readonly>
      </div>
    </div>

    <div class=" row align-items-center">
      <label class="col-4 col-form-label">Accommodation / Hotel</label>
      <div class="col-8 d-flex">
        <input type="text" id="HotelPurchaseTotal" class="form-control me-2" value="{{$invoice_master->hotel->sum('HotelPayable') ?? 0}}" readonly>
        <input type="text" id="HotelPurchaseAmount" class="form-control" value="{{ $invoice_master->hotel->sum(function ($item) {
    return $item->HotelPayable * $item->ExRatePurchaseHotel;
}) ?? 0 }}" readonly>
      </div>
    </div>

    <div class=" row align-items-center">
      <label class="col-4 col-form-label">Transportation</label>
      <div class="col-8 d-flex">
        <input type="text" id="TransportPurchaseTotal" class="form-control me-2" value="{{$invoice_master->transport->sum('TransportPayable') ?? 0}}" readonly>
        <input type="text" id="TransportPurchaseAmount" class="form-control" value="{{ $invoice_master->transport->sum(function ($item) {
    return $item->TransportPayable * $item->ExRatePurchaseTransport;
}) ?? 0 }}" readonly>
      </div>
    </div>

 

    <div class=" row align-items-center">
      <label class="col-4 col-form-label fw-bold text-warning">Payable</label>
      <div class="col-8 d-flex">
        <input type="text" id="NetPayableTotal" class="form-control me-2 text-warning" value="0" readonly>
        <input type="text" id="NetPayable" class="form-control text-warning" value="0" readonly>
      </div>
    </div>
  </div>

  <!-- Receivable Column -->
  <div class="col-lg-6">
    <h5 class="text-success mb-2">Receivable</h5>

    <div class=" row align-items-center">
      <label class="col-4 col-form-label">Total</label>
      <div class="col-8 d-flex">
        <input type="text" class="form-control" value="Total Amount" readonly>
      </div>
    </div>

    <div class=" row align-items-center">
      <label class="col-4 col-form-label">Visa</label>
      <div class="col-8 d-flex">
        <input type="text" id="VisaSaleTotal" class="form-control me-2" value="{{$invoice_master->passanger->sum('visa_sale') ?? 0}}" readonly>
        <input type="text" id="VisaSaleAmount" class="form-control" value="{{ $invoice_master->passanger->sum(function ($item) {
    return $item->visa_sale * $item->forex_sale;
}) ?? 0 }}" readonly>
      </div>
    </div>
 

    <div class=" row align-items-center">
      <label class="col-4 col-form-label">Ticket</label>
      <div class="col-8 d-flex">
        <input type="text" id="TicketSaleTotal" class="form-control me-2" value="{{$invoice_master->passanger->sum('ticket_sale') ?? 0}}" readonly>
        <input type="text" id="TicketSaleAmount" class="form-control" value="{{ $invoice_master->passanger->sum(function ($item) {
    return $item->ticket_sale * $item->forex_sale;
}) ?? 0 }}" readonly>
      </div></div>



    <div class=" row align-items-center">
      <label class="col-4 col-form-label">Accommodation</label>
      <div class="col-8 d-flex">
        <input type="text" id="HotelSaleTotal" class="form-control me-2" value="{{$invoice_master->hotel->sum('HotelReceivable') ?? 0}}" readonly>
        <input type="text" id="HotelSaleAmount" class="form-control" value="{{ $invoice_master->hotel->sum(function ($item) {
    return $item->HotelReceivable * $item->ExRateSaleHotel;
}) ?? 0 }}" readonly>
      </div>
    </div>

    <div class=" row align-items-center">
      <label class="col-4 col-form-label">Transportation</label>
      <div class="col-8 d-flex">
        <input type="text" id="TransportSaleTotal" class="form-control me-2" value="{{$invoice_master->transport->sum('TransportReceivable') ?? 0}}" readonly>
        <input type="text" id="TransportSaleAmount" class="form-control" value="{{ $invoice_master->transport->sum(function ($item) {
    return $item->TransportReceivable * $item->ExRateSaleTransport;
}) ?? 0 }}" readonly>
      </div>
    </div>

 

    <div class=" row align-items-center">
      <label class="col-4 col-form-label fw-bold text-success">Receivable</label>
      <div class="col-8 d-flex">
        <input id="NetReceivableTotal" type="text" class="form-control me-2 text-success" value="0" readonly>
        <input id="NetReceivable" type="number" class="form-control text-success" value="0" min="0" step="0.01" onchange="CalculateAmount()" readonly>
      </div>
    </div>
  </div>

</div>

    </div>
</div>

 
<form id="umrah-invoice-voucher" method="POST">
  @csrf

        <div class="card shadow-sm">
        <div class="card-body">
            <div class="row mb-3">
          <div class="col-md-3">
            <label for="validity" class="form-label">Validity</label>
            <input type="date"  name="Validity" class="form-control" id="validity" placeholder="" value="{{$invoice_master->Validity ?? date('Y-m-d') }}">
          </div>
          <div class="col-md-3">
            <label for="spo" class="form-label">SPO</label>
            <select class="form-select" id="spo" name="CareOf">
            <option value="Office" {{ ($invoice_master->CareOf == 'Office') ? 'selected' : '' }}>Office</option>
            <option value="Field" {{ ($invoice_master->CareOf == 'Field') ? 'selected' : '' }}>Field</option>
          </select>

          </div>
          <div class="col-md-3">
            <label for="visaSupplier" class="form-label">Visa Supplier</label>
            <select class="form-select select2" id="VisaSupplierID" name="VisaSupplierID">
              <option value="">select</option>
              @foreach ($supplier as $item)
                 <option value="{{$item->PartyID}}" {{ $item->PartyID == ($invoice_master->VisaSupplierID ?? null) ? 'selected' : '' }}>{{$item->PartyID}}-{{$item->PartyName}}</option>
             @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label for="ticketSupplier" class="form-label">Ticket Supplier</label>
            <select class="form-select select2" id="TicketSupplierID" name="TicketSupplierID">
              <option value="">select</option>
             @foreach ($supplier as $item)
                 <option value="{{$item->PartyID}}" {{ $item->PartyID == ($invoice_master->TicketSupplierID ?? null) ? 'selected' : '' }}>{{$item->PartyID}}-{{$item->PartyName}}</option>
             @endforeach
            </select>
          </div>
        </div>
        
        <div class="row mb-3">
          <div class="col-md-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="InvoiceStatus" name="InvoiceStatus">
              @foreach ($invoice_status as $item)
                  <option value="{{$item->status}}" {{ $item->status == ($invoice_master->InvoiceStatus ?? null) ? 'selected' : '' }}>{{$item->status}}</option>
              @endforeach
              
            </select>
          </div>
          <div class="col-md-9">
            <label for="remarks" class="form-label">Remarks</label>
            <input type="text" class="form-control" id="remarks" name="Remarks" value="{{$invoice_master->Remarks ?? ''}}">
          </div>
        </div>

        <div class="row mb-4">
          <div class="col-md-3">
            <label for="revisedDate" class="form-label">Revised Date</label>
            <input type="date" class="form-control" id="revisedDate" name="RevisedDate" value="{{$invoice_master->RevisedDate ?? ''}}">
          </div>
          <div class="col-md-9">
            <label for="revisedRemarks" class="form-label">Revised Remarks</label>
            <input type="text" class="form-control" id="revisedRemarks" name="RevisedRemarks" value="{{$invoice_master->RevisedRemarks ?? ''}}">
          </div>
        </div>

        <div class="d-flex justify-content-center gap-3">
          <button type="button" class="btn btn-danger  btn-rounded px-4" id="btn-voucher-save">Save Hotel Voucher</button>
          <button type="button" class="btn btn-warning btn-rounded px-4" id="btn-voucher-delete">Delete Hotel Voucher</button>
        </div>
        </div>
      </div>

</form>


 


