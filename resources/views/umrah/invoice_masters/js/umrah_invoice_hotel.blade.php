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
        getUmrahInvoiceHotelTable();
    });
</script>

<script>

                var hotelTable=''; // make it global


                $('#SaveHotel').on('click', function(e){
                   
                e.preventDefault();
                 //umrah-invoice-hotel-form

 

$('#invoice_hotel_id').val('');
 
             let formData = new FormData($('#umrah-invoice-hotel-form')[0]);
             formData.append('umrah_invoice_master_id',$('#umrah_invoice_master_id').val());
             formData.append('package_id',$('#package_id').val());
            formData.append('Date',$('#Date').val());
       
            formData.append('PartyID',$('#PartyID').val());
            formData.append('SaleCurrency',$('#SaleCurrency').val());
            formData.append('PurchaseCurrency',$('#PurchaseCurrency').val());



                   // clear first
                 
                
                
                
                    console.log($('#PartyID').val());

                submitUmrahInvoiceHotel(formData);
                // partialResetUmrahInvoiceHotelForm();
                });


                
                $('#ModifyHotel').on('click', function(e){
                e.preventDefault();
                 //umrah-invoice-hotel-form
                let formData = new FormData($('#umrah-invoice-hotel-form')[0]);
                formData.append('InvoiceMasterID', $('#umrah_invoice_master_id').val());
                formData.append('umrah_invoice_master_id', $('#umrah_invoice_master_id').val());
                formData.append('package_id',$('#package_id').val());
                formData.append('Date',$('#Date').val());


                 
                console.log(formData);

                submitUmrahInvoiceHotel(formData);
                // partialResetUmrahInvoiceHotelForm();
                });


              function submitUmrahInvoiceHotel(formData)
            {

           
               
            formData.append('umrah_invoice_master_id', $('#umrah_invoice_master_id').val());

            console.log(formData);

             var submit_btn = $('#SaveHotel');
               
            $.ajax({
                type: "POST",
                url: "{{ route('umrah-invoice-hotel.store') }}",
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
                
                if(response.success == true){

            submit_btn.prop('disabled', false).html('Save Sector');  
                partialResetUmrahInvoiceHotelForm();
                hotelTable.ajax.reload();

                
                    notyf.success({
                        message: response.message, 
                        duration: 3000
                    });
                } else {
                    notyf.error({
                        message: response.message,
                        duration: 5000
                    });

                submit_btn.prop('disabled', false).html('Save Sector');  

                }  
                

                 // assign total values to inputs

                    $('#HotelPurchaseTotal').val(response.data.HotelPayable);
                    $('#HotelPurchaseAmount').val(response.data.forex_HotelPayable);
                    
                    
                    $('#HotelSaleTotal').val(response.data.HotelReceivable);
                    $('#HotelSaleAmount').val(response.data.forex_HotelReceivable);


                     calculateNetPayable();
                     calculateNetReceivable();
                    
         
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

function getUmrahInvoiceHotelTable() {
    // Only initialize if not already done
    if (!$.fn.DataTable.isDataTable('#hotel-table')) {
        hotelTable = $('#hotel-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: false,
            paging: false,
            info: false,
            ajax: {
                url: "{{ route('umrah-invoice-hotel.index') }}",
                data: function (d) {
                    d.umrah_invoice_master_id = $('#umrah_invoice_master_id').val(); // Make sure this line is used
                }
            },
            columns: [
                { data: 'action', orderable: false, searchable: false },
                { data: 'HotelCity' },
                { data: 'CheckInDate' },
                { data: 'CheckOutDate' },
                { data: 'Nights' },
                { data: 'hotel_name.hotel_name' },
                { data: 'RoomType' },
                { data: 'RoomStatus' },
                { data: 'NoOfRooms' },
                { data: 'HotelPax' },
                { data: 'HotelPayable' },
                { data: 'HotelReceivable' },
            ],
            order: [[0, 'desc']],
        });
    }
}


        // end

    // $(document).ready(function() {

// var invoicemasterid =        4249;

 
  

        
         

        $('#AddNewHotel').on('click', function(e){
            e.preventDefault();
            let formData = new FormData($('#umrah-invoice-passenger-form')[0]);
            formData.append('umrah_invoice_master_id',$('#umrah_invoice_master_id').val());
            formData.append('package_id',$('#package_id').val());
            formData.delete('umrah_invoice_passenger_id');



            submitUmrahInvoiceHotel(formData);
            partialResetUmrahInvoiceHotelForm();
        });

    

        $('#cancel-btn').on('click', function(e){
            e.preventDefault();
            partialResetUmrahInvoiceHotelForm();
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

      

$('#DeleteHotel').click(function() {
    let id = $('#invoice_hotel_id').val();

    if (!id) {
        notyf.error("No transport selected to delete.");
        return;
    }

    if (!confirm("Are you sure you want to delete this hotel record?")) {
        return; // Exit if user cancels
    }

    var submit_btn = $('#DeleteTransport');

    $.ajax({
        type: 'DELETE',
        url: "{{ route('umrah-invoice-hotel.destroy', ':id') }}".replace(':id', id),
        data: {
            _token: "{{ csrf_token() }}"
        },
        beforeSend: function() {
            submit_btn.prop('disabled', true).html('Processing');
        },
        success: function(response) {
            submit_btn.prop('disabled', false).html('Delete');  

            if(response.success == true){
                partialResetUmrahInvoiceHotelForm(); // or clearUmrahInvoiceTransportForm();
                hotelTable.ajax.reload();

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

                $('#HotelPurchaseTotal').val(response.data.HotelPayable);
                $('#HotelPurchaseAmount').val(response.data.forex_HotelPayable);
                
                
                $('#HotelSaleTotal').val(response.data.HotelReceivable);
                $('#HotelSaleAmount').val(response.data.forex_HotelReceivable);


                calculateNetPayable();
                calculateNetReceivable();


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

    function hotelEdit(id)
    {

        
        $.get("{{ route('umrah-invoice-hotel.edit', ':id') }}".replace(':id', id), function(response) {
            
            $('#invoice_hotel_id').val(response.id);
            $('#HotelCity').val(response.HotelCity);
            $('#CheckInDate').val(response.CheckInDate);
            $('#CheckOutDate').val(response.CheckOutDate);
            $('#Nights').val(response.Nights);
            $('#hotel_id').val(response.hotel_id).trigger('change');
            $('#RoomType').val(response.RoomType);
            $('#RoomStatus').val(response.RoomStatus);
            $('#NoOfRooms').val(response.NoOfRooms);
            $('#HotelPax').val(response.HotelPax);
            $('#HotelPurchase').val(response.HotelPurchase);
            $('#HotelSale').val(response.HotelSale);
            $('#HotelSale').val(response.HotelSale);
            $('#HotelPayable').val(response.HotelPayable);
            $('#HotelReceivable').val(response.HotelReceivable);
            $('#Origin').val(response.Origin);
            $('#Destination').val(response.Destination);
            $('#SupplierID').val(response.SupplierID).trigger('change');
            $('#StockStatus').val(response.StockStatus);
            $('#ExRatePurchaseHotel').val(response.ExRatePurchaseHotel);
            $('#ExRateSaleHotel').val(response.ExRateSaleHotel);
            $('#HCN_NO').val(response.HCN_NO);

 
            $('#RoomView').val(response.RoomView).trigger('change');
            $('#MealPlan').val(response.MealPlan);

             
    
             
    

            }).fail(function(xhr) {
                alert('Error fetching details: ' + xhr.responseText);
        });

        
    setButtonState('#SaveHotel','enable');
        setButtonState('#ModifyHotel','enable');
        setButtonState('#DeleteHotel','enable');
        setButtonState('#AddNewSector','disable');
        setButtonState('#ChangeSector','disable');

    }

    function updateAllPaxRate()
    {

    }

    

    function partialResetUmrahInvoiceHotelForm(){


         
        $('#invoice_hotel_id').val('');
    $('#TransportDate').val(new Date().toISOString().slice(0, 10));
    $('#TransportCity').val(null).trigger('change');
    $('#Sector').val(null).trigger('change');
    $('#VehicleType').val(null).trigger('change');
    $('#VehicleStatus').val('');
    $('#Quantity').val('');
    $('#TransportPax').val('');
    $('#TransportPurchase').val('');
    $('#TransportSale').val('');
    $('#TransportPayable').val('');
    $('#TransportReceivable').val('');
    $('#Flight').val('');
    $('#PickupTime').val('');
    $('#PickFrom').val('');
    $('#DestinationTo').val('');
    $('#TransportBrnCode').val('');
    $('#SupplierID').val(null).trigger('change');
    $('#ExRatePurchaseTransport').val('');
    $('#Nights').val('0');

        setButtonState('#SaveHotel','enable');
        setButtonState('#ModifyHotel','disable');
        setButtonState('#DeleteHotel','disable');
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
$(document).ready(function() {

    // Function to calculate HotelPayable and HotelReceivable
    function calculateHotelAmounts() {
        const nights = parseFloat($('#Nights').val()) || 0;
        const rooms = parseFloat($('#NoOfRooms').val()) || 0;
        const purchase = parseFloat($('#HotelPurchase').val()) || 0;
        const sale = parseFloat($('#HotelSale').val()) || 0;

        const payable = nights * rooms * purchase;
        const receivable = nights * rooms * sale;

        $('#HotelPayable').val(payable);
        $('#HotelReceivable').val(receivable);
    }

    // CheckInDate change
    $('#CheckInDate').on('change', function() {
        const checkIn = $(this).val();

        if (checkIn) {
            // Set min for CheckOutDate to disable previous dates
            $('#CheckOutDate').attr('min', checkIn);

            // Optionally set CheckOutDate to next day
            const nextDay = new Date(checkIn);
            nextDay.setDate(nextDay.getDate() + 1);
            const nextDayStr = nextDay.toISOString().split('T')[0];
            $('#CheckOutDate').val(nextDayStr);

            // Update Nights field
            $('#Nights').val(1);

            // Recalculate amounts
            calculateHotelAmounts();
        }
    });

    // CheckOutDate change
    $('#CheckOutDate').on('change', function() {
        const checkIn = $('#CheckInDate').val();
        const checkOut = $(this).val();

        if (checkIn && checkOut) {
            const startDate = new Date(checkIn);
            const endDate = new Date(checkOut);
            const diffTime = endDate - startDate;
            const diffDays = diffTime / (1000 * 60 * 60 * 24);

            $('#Nights').val(diffDays > 0 ? diffDays : 0);

            // Recalculate amounts
            calculateHotelAmounts();
        }
    });

    // Other fields that affect amounts
    $('#NoOfRooms, #HotelPurchase, #HotelSale, #Nights').on('input', function() {
        calculateHotelAmounts();
    });

});
</script>


<script>
    $('#Nights').on('input', function() {
    const nights = parseInt($(this).val()) || 0;
    const checkIn = $('#CheckInDate').val();

    if (checkIn && nights > 0) {
        const newCheckOut = new Date(checkIn);
        newCheckOut.setDate(newCheckOut.getDate() + nights);
        const newCheckOutStr = newCheckOut.toISOString().split('T')[0];

        $('#CheckOutDate').val(newCheckOutStr);
    }

    calculateHotelAmounts();
   
});

</script>


<script>
$(document).ready(function () {

  $('#HotelCity, #RoomType, #RoomStatus, #hotel_id, #SupplierID, #CheckInDate, #CheckOutDate').on('change', function () {
    let start_date = $('#CheckInDate').val();
    let end_date = $('#CheckOutDate').val();
    let location = $('#HotelCity').val();
    let room_type = $('#RoomType').val();
    let room_status = $('#RoomStatus').val();
    let hotel_id = $('#hotel_id').val();
    let supplier_id = $('#SupplierID').val();
    let party_id = $('#PartyID').val() || '';

    if (!start_date || !end_date || !location || !room_type || !room_status || !hotel_id || !supplier_id) return;

    let url = "{{ route('getHotelRates', [':start_date', ':end_date', ':location', ':roomt_type', ':room_status', ':hotel_id', ':supplier_id', ':party_id']) }}"
      .replace(':start_date', encodeURIComponent(start_date))
      .replace(':end_date', encodeURIComponent(end_date))
      .replace(':location', encodeURIComponent(location))
      .replace(':roomt_type', encodeURIComponent(room_type))
      .replace(':room_status', encodeURIComponent(room_status))
      .replace(':hotel_id', encodeURIComponent(hotel_id))
      .replace(':supplier_id', encodeURIComponent(supplier_id))
      .replace(':party_id', encodeURIComponent(party_id));

    $.ajax({
      url: url,
      type: 'GET',
      dataType: 'json',
      beforeSend: function() {
        $('#HotelPurchase, #HotelSale').val('...');
      },
      success: function(response) {
        if (response.status === 'found') {
          $('#HotelPurchase').val(response.purchase_price);
          $('#HotelSale').val(response.sale_price);

          // Auto-calculate payable & receivable
          let nights = parseFloat($('#Nights').val() || 1);
          let pax = parseFloat($('#HotelPax').val() || 1);
          let exRatePurchase = parseFloat($('#ExRatePurchaseHotel').val() || 1);
          let exRateSale = parseFloat($('#ExRateSaleHotel').val() || 1);

          let payable = parseFloat(response.purchase_price) * nights * pax * exRatePurchase;
          let receivable = parseFloat(response.sale_price) * nights * pax * exRateSale;

          $('#HotelPayable').val(payable.toFixed(2));
          $('#HotelReceivable').val(receivable.toFixed(2));

        } else {
          $('#HotelPurchase, #HotelSale, #HotelPayable, #HotelReceivable').val('');
          toastr.warning('No matching hotel tariff found.');
        }
      },
      error: function() {
        toastr.error('Error fetching tariff data.');
      }
    });
  });

});
</script>
