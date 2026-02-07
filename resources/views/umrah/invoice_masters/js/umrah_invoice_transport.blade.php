    {{-- <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        // Create an instance of Notyf
        let notyf = new Notyf({
            duration: 3000,
            position: {
                x: 'right',
                y: 'top',
            },
        });
    </script> --}}


<script>
    $(document).ready(function () {
        getUmrahInvoiceTransportTable();
    });
</script>

<script>

    let transportTable; // make it global


                $('#SaveTransport').on('click', function(e){
                e.preventDefault();
                 //umrah-invoice-transport-form
                 $('#invoice_transport_id').val('');  // clear first
                let formData = new FormData($('#umrah-invoice-transport-form')[0]);
                
                formData.append('umrah_invoice_master_id', $('#umrah_invoice_master_id').val());
                formData.append('package_id',$('#package_id').val());
                
                formData.append('Date',$('#Date').val());
        
                formData.append('PartyID',$('#PartyID').val());
                formData.append('SaleCurrency',$('#SaleCurrency').val());
                formData.append('PurchaseCurrency',$('#PurchaseCurrency').val());


                console.log(formData);

                submitUmrahInvoiceTransport(formData);
                partialResetUmrahInvoiceTransportForm();
                });


                
                $('#ModifyTransport').on('click', function(e){
                e.preventDefault();
                  //umrah-invoice-transport-form
                let formData = new FormData($('#umrah-invoice-transport-form')[0]);
                formData.append('InvoiceMasterID', $('#umrah_invoice_master_id').val());
                formData.append('umrah_invoice_master_id', $('#umrah_invoice_master_id').val());
                formData.append('package_id',$('#package_id').val());
                 
                
                formData.append('Date',$('#Date').val());
        
                formData.append('PartyID',$('#PartyID').val());
                formData.append('SaleCurrency',$('#SaleCurrency').val());
                formData.append('PurchaseCurrency',$('#PurchaseCurrency').val());
    

                console.log(formData);

                submitUmrahInvoiceTransport(formData);
                partialResetUmrahInvoiceTransportForm();
                });


              function submitUmrahInvoiceTransport(formData)
            {


 

             var submit_btn = $('#SaveTransport');
           
             
            $.ajax({
                type: "POST",
                url: "{{ route('umrah-invoice-transport.store') }}",
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                data: formData,
                
                enctype: "multipart/form-data",
                beforeSend: function() {
                    submit_btn.prop('disabled', true);
                    submit_btn.html('Processing');
                },
               success: function(response) {
                submit_btn.prop('disabled', false).html('Save Sector');  
                partialResetUmrahInvoiceTransportForm();

     

                if(response.success == true){
                    notyf.success({
                        message: response.message, 
                        duration: 3000
                    });


                               // Reload table
                if (transportTable) {
                    transportTable.ajax.reload();
                }

                 // assign total values to inputs

                    $('#TransportPurchaseTotal').val(response.data.TransportPayable);
                    $('#TransportPurchaseAmount').val(response.data.forex_TransportPayable);
                    
                    
                    $('#TransportSaleTotal').val(response.data.TransportReceivable);
                    $('#TransportSaleAmount').val(response.data.forex_TransportReceivable);

                calculateNetPayable();
                calculateNetReceivable();



                } else {
                    notyf.error({
                        message: response.message,
                        duration: 5000
                    });
                }   
            },
                error: function(e) {
                    submit_btn.prop('disabled', false).html('Save');
                
                    notyf.error({
                        message: e.responseJSON.message,
                        duration: 5000
                    });
                }
            });
        
        }




        // db table

function getUmrahInvoiceTransportTable() {
    // Only initialize if not already done
    if (!$.fn.DataTable.isDataTable('#transport-table')) {
        transportTable = $('#transport-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            paging: false,
            info: false,
            ajax: {
                url: "{{ route('umrah-invoice-transport.index') }}",
                data: function (d) {
                    d.umrah_invoice_master_id = $('#umrah_invoice_master_id').val(); // Make sure this line is used
                }
            },
            columns: [
                { 
                    data: 'TransportDate',
                    render: function(data, type, row) {
                        if (!data) return '';
                        let dateObj = new Date(data);
                        let day = String(dateObj.getDate()).padStart(2, '0');
                        let month = String(dateObj.getMonth() + 1).padStart(2, '0');
                        let year = dateObj.getFullYear();
                        return `${day}/${month}/${year}`;
                    }
                },
                { data: 'TransportCity' },
                { data: 'Sector' },
                { data: 'VehicleType' },
                { data: 'VehicleStatus' },
                { data: 'Quantity' },
                { data: 'TransportPax' },
                { data: 'TransportPurchase' },
                { data: 'TransportSale' },
                { data: 'action', orderable: false, searchable: false },
            ],
            order: [[0, 'desc']],
        });
    }
}


        // end

    // $(document).ready(function() {

// var invoicemasterid =        4249;

 
  

        
         

        $('#save-as-umrah-invoice-transport-btn').on('click', function(e){
            e.preventDefault();
            let formData = new FormData($('#umrah-invoice-passenger-form')[0]);
            formData.append('umrah_invoice_master_id',$('#umrah_invoice_master_id').val());
            formData.append('package_id',$('#package_id').val());
            formData.delete('umrah_invoice_passenger_id');



            submitUmrahInvoiceTransport(formData);
            partialResetUmrahInvoiceTransportForm();
        });

    

        $('#cancel-btn').on('click', function(e){
            e.preventDefault();
            partialResetUmrahInvoiceTransportForm();
        });
        
        $('#submit-update-all-pax-rate').on('click', function(e){
            e.preventDefault();
            let formData = new FormData();
            formData.append('_token', $('input[name="_token"]').val()); // CSRF token
            formData.append('umrah_invoice_master_id',$('#umrah_invoice_master_id').val());
            formData.append('shirka_name', $('#shirka_name').val());
            formData.append('visa_sale', $('#visa_sale').val());
            formData.append('ticket_sale', $('#ticket_sale').val());
            formData.append('visa_purchase', $('#visa_purchase').val());
            formData.append('ticket_purchase', $('#ticket_purchase').val());
            formData.append('forex_purchase', $('#forex_purchase').val());
            formData.append('forex_sale', $('#forex_sale').val());

            var submit_btn = $('#submit-update-all-pax-rate');
            $.ajax({
                type: "POST",
                url: "{{ route('umrah-invoice-passenger.updateAllPaxRate') }}",
                dataType: 'json',
                contentType: false,
                processData: false,
                cache: false,
                data: formData,
                
                enctype: "multipart/form-data",
                beforeSend: function() {
                    submit_btn.prop('disabled', true);
                    submit_btn.html('Processing');
                },
                success: function(response) {
                    
                    submit_btn.prop('disabled', false).html('Update All Pax Rate');  
                    transportTable.ajax.reload();


                    if(response.success == true){
                        // $('#umrah-invoice-passenger-form')[0].reset();  // Reset all form data
                        
                        notyf.success({
                            message: response.message, 
                            duration: 3000
                        });
                    }else{
                        notyf.error({
                            message: response.message,
                            duration: 5000
                        });
                    }   
                },
                error: function(e) {
                    submit_btn.prop('disabled', false).html('Update All Pax Rate');
                
                    notyf.error({
                        message: e.responseJSON.message,
                        duration: 5000
                    });
                }
            });
            
            
        });

      

$('#DeleteTransport').click(function() {
    let id = $('#invoice_transport_id').val();

    if (!id) {
        notyf.error("No transport selected to delete.");
        return;
    }

    if (!confirm("Are you sure you want to delete this transport record?")) {
        return; // Exit if user cancels
    }

    var submit_btn = $('#DeleteTransport');

    $.ajax({
        type: 'DELETE',
        url: "{{ route('umrah-invoice-transport.destroy', ':id') }}".replace(':id', id),
        data: {
            _token: "{{ csrf_token() }}"
        },
        beforeSend: function() {
            submit_btn.prop('disabled', true).html('Processing');
        },
        success: function(response) {
            submit_btn.prop('disabled', false).html('Delete');  

            if(response.success == true){
                partialResetUmrahInvoiceTransportForm(); // or clearUmrahInvoiceTransportForm();
                transportTable.ajax.reload();

                    $('#TransportPurchaseTotal').val(response.data.TransportPayable);
                    $('#TransportPurchaseAmount').val(response.data.forex_TransportPayable);
                    
                    
                    $('#TransportSaleTotal').val(response.data.TransportReceivable);
                    $('#TransportSaleAmount').val(response.data.forex_TransportReceivable);

                      calculateNetPayable();
                      calculateNetReceivable();

                notyf.success({
                    message: response.message, 
                    duration: 3000
                });
            } else {
                notyf.error({
                    message: response.message,
                    duration: 5000
                });
            }

       
        },
        error: function(e) {
            submit_btn.prop('disabled', false).html('Delete');
            notyf.error({
                message: e.responseJSON.message,
                duration: 5000
            });
        }
    });
});



       

        
    // });

    function transportEdit(id)
    {

        
        $.get("{{ route('umrah-invoice-transport.edit', ':id') }}".replace(':id', id), function(response) {
            
            $('#invoice_transport_id').val(response.id);
            $('#TransportDate').val(response.TransportDate);
            $('#TransportCity').val(response.TransportCity).trigger('change');
            $('#Sector').val(response.Sector).trigger('change');
            $('#VehicleType').val(response.VehicleType).trigger('change');
            $('#VehicleStatus').val(response.VehicleStatus);
            $('#Quantity').val(response.Quantity);
            $('#TransportPax').val(response.TransportPax);
            $('#TransportPurchase').val(response.TransportPurchase);
            $('#TransportSale').val(response.TransportSale);
            $('#TransportPayable').val(response.TransportPayable);
            $('#TransportReceivable').val(response.TransportReceivable);
            $('#Flight').val(response.Flight);
            $('#PickupTime').val(response.PickupTime);
            $('#PickFrom').val(response.PickFrom);
            $('#DestinationTo').val(response.DestinationTo);
            $('#TransportBrnCode').val(response.TransportBrnCode);
            $('#TCN').val(response.TCN);
            
            $('#TransportSupplier').val(response.SupplierID).trigger('change');
            $('#ExRatePurchaseTransport').val(response.ExRatePurchaseTransport);
            $('#ExRateSaleTransport').val(response.ExRateSaleTransport);
    

            }).fail(function(xhr) {
                alert('Error fetching details: ' + xhr.responseText);
        });

        
    setButtonState('#SaveTransport','enable');
        setButtonState('#ModifyTransport','enable');
        setButtonState('#DeleteTransport','enable');
        setButtonState('#AddNewSector','disable');
        setButtonState('#ChangeSector','disable');

    }

    function updateAllPaxRate()
    {

    }

    

    function partialResetUmrahInvoiceTransportForm(){
        
        $('#invoice_transport_id').val('');
    $('#TransportDate').val(new Date().toISOString().slice(0, 10));
    $('#TransportCity').val(null).trigger('change');
    $('#Sector').val(null).trigger('change');
    $('#VehicleType').val(null).trigger('change');
    $('#VehicleStatus').val('');
    $('#Quantity').val('1');
    $('#TransportPax').val('1');
    $('#TransportPurchase').val('0');
    $('#TransportSale').val('0');
    $('#TransportPayable').val('0');
    $('#TransportReceivable').val('0');
    $('#Flight').val('');
    $('#PickupTime').val('');
    $('#PickFrom').val('');
    $('#DestinationTo').val('');
    $('#TransportBrnCode').val('');
    $('#TransportSupplier').val(null).trigger('change');
    $('#ExRatePurchaseTransport').val('1');
    $('#ExRateSaleTransport').val('1');

        setButtonState('#SaveTransport','enable');
        setButtonState('#ModifyTransport','disable');
        setButtonState('#DeleteTransport','disable');
        setButtonState('#AddNewSector','disable');
        setButtonState('#ChangeSector','disable');

    }

 
    


    // function setButtonState(btnId, value) {
    //     if (value === 'disable') {
    //         $(btnId).prop('disabled', true);  // Disable the button
    //     } else if (value === 'enable') {
    //         $(btnId).prop('disabled', false);  // Enable the button
    //     }
    // }

    // function passengerDelete() {
    //     $('#delete-umrah-invoice-passenger').modal('enable');
    // }


    

    
</script>
<script>

// Transport calculation
function calculateTransportAmounts() {
    const quantity = parseFloat($('#Quantity').val()) || 0;
    const purchase = parseFloat($('#TransportPurchase').val()) || 0;
    const sale = parseFloat($('#TransportSale').val()) || 0;

    const payable = quantity * purchase;
    const receivable = quantity * sale;

    $('#TransportPayable').val(payable);
    $('#TransportReceivable').val(receivable);
}

$('#Quantity, #TransportPurchase, #TransportSale').on('input', function() {
    calculateTransportAmounts();
});


</script>



<script>
$(document).ready(function() {

    // Trigger the rate fetch when user changes any key field
    $('#Sector, #VehicleType, #VehicleStatus, #TransportSupplier, #TransportDate').on('change', function() {
        
        let start_date = $('#TransportDate').val();
        let end_date   = $('#TransportDate').val(); // same date used for both from/to
        let sector     = $('#Sector').val();
        let vehicle_type = $('#VehicleType').val();
        let vehicle_status = $('#VehicleStatus').val();
        let supplier_id = $('#TransportSupplier').val();
        let party_id = $('#PartyID').val() || ''; // optional

        // Only proceed if all required fields are filled
        if (!start_date || !sector || !vehicle_type || !vehicle_status || !supplier_id) {
            return;
        }

        // Build AJAX URL using Laravel route
        let url = "{{ route('getTransportRates', ['start_date' => ':start_date', 'end_date' => ':end_date', 'sector' => ':sector', 'vehicle_type' => ':vehicle_type', 'vehicle_status' => ':vehicle_status', 'supplier_id' => ':supplier_id', 'party_id' => ':party_id']) }}";
        
        // Replace placeholders with real values
        url = url
            .replace(':start_date', encodeURIComponent(start_date))
            .replace(':end_date', encodeURIComponent(end_date))
            .replace(':sector', encodeURIComponent(sector))
            .replace(':vehicle_type', encodeURIComponent(vehicle_type))
            .replace(':vehicle_status', encodeURIComponent(vehicle_status))
            .replace(':supplier_id', encodeURIComponent(supplier_id))
            .replace(':party_id', encodeURIComponent(party_id));

        // Perform AJAX request
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                // Optional loader
                $('#TransportPurchase, #TransportSale, #TransportPayable, #TransportReceivable').val('...');
            },
            success: function(response) {
                if (response.status === 'found') {
                    let data = response.data;

                    // Assign prices to input fields
                    $('#TransportPurchase').val(data.purchase_price);
                    $('#TransportSale').val(data.sale_price);

                    // Calculate payable and receivable (if needed)
                    let exRatePurchase = parseFloat($('#ExRatePurchaseTransport').val() || 1);
                    let exRateSale = parseFloat($('#ExRateSaleTransport').val() || 1);
                    let qty = parseFloat($('#Quantity').val() || 1);

                    let payable = parseFloat(data.purchase_price) * qty * exRatePurchase;
                    let receivable = parseFloat(data.sale_price) * qty * exRateSale;

                    $('#TransportPayable').val(payable.toFixed(2));
                    $('#TransportReceivable').val(receivable.toFixed(2));

                } else {
                    // Not found → clear fields
                    $('#TransportPurchase, #TransportSale, #TransportPayable, #TransportReceivable').val('');
                    toastr.warning('No rate found for the selected combination.');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                toastr.error('Error fetching transport rate.');
            }
        });
    });

});
</script>


