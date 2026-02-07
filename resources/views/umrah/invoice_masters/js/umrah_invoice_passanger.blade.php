<script>

    var passengerTable = '';
    $(document).ready(function() {

 
             passengerTable = $('#passenger-table').DataTable({
            processing: true,
            serverSide: true,
            searching: false,    // Disable the search bar
            lengthChange: false, // Disable the length dropdown
            paging: false,       // Disable pagination
            info: false,         // Disable the "Showing X to Y of Z entries" text
            ajax: {
                url: "{{ route('umrah-invoice-passenger.index') }}",
                data: function (d) {
                    // d.umrah_invoice_master_id = $('#umrah_invoice_master_id').val(); // Retrieve the umrah_invoice_master_id value from an input field
                    d.umrah_invoice_master_id = $('#umrah_invoice_master_id').val() || '' ;
                }
            },
            columns: [
                { data: 'passport_no' },
                { data: 'passenger_name' },
                { data: 'type' },
                { data: 'relation_type' },
                { data: 'action', orderable: false, searchable: false },
            ],
            order: [[0, 'desc']],
        });

        $('#save-umrah-invoice-passanger-btn').on('click', function(e){
                         
            e.preventDefault();

             let formData = new FormData($('#umrah-invoice-passenger-form')[0]);
             formData.append('umrah_invoice_master_id',$('#umrah_invoice_master_id').val());
             formData.append('package_id',$('#package_id').val());
            formData.append('Date',$('#Date').val());
            formData.append('PartyID',$('#PartyID').val());
            formData.append('SaleCurrency',$('#SaleCurrency').val());
            formData.append('PurchaseCurrency',$('#PurchaseCurrency').val());
            

            submitUmrahInvoicePassenger(formData);
            // partialResetUmrahInvoicePassengerForm();

        });

        $('#save-as-umrah-invoice-passanger-btn').on('click', function(e){
            e.preventDefault();
            let formData = new FormData($('#umrah-invoice-passenger-form')[0]);
            formData.append('umrah_invoice_master_id',$('#umrah_invoice_master_id').val());
            formData.append('package_id',$('#package_id').val());
            formData.append('Date',$('#Date').val());
            formData.append('PartyID',$('#PartyID').val());
            formData.append('SaleCurrency',$('#SaleCurrency').val());
            formData.append('PurchaseCurrency',$('#PurchaseCurrency').val());
            formData.delete('umrah_invoice_passenger_id');



            submitUmrahInvoicePassenger(formData);
            // partialResetUmrahInvoicePassengerForm();
        });

        $('#modify-umrah-invoice-passanger-btn').on('click', function(e){
            e.preventDefault();
            let formData = new FormData($('#umrah-invoice-passenger-form')[0]);
            formData.append('umrah_invoice_master_id',$('#umrah_invoice_master_id').val());
            formData.append('package_id',$('#package_id').val());
            formData.append('Date',$('#Date').val());
            formData.append('PartyID',$('#PartyID').val());
            formData.append('SaleCurrency',$('#SaleCurrency').val());
            formData.append('PurchaseCurrency',$('#PurchaseCurrency').val());
            submitUmrahInvoicePassenger(formData);
            // partialResetUmrahInvoicePassengerForm();
        });

        $('#cancel-btn').on('click', function(e){
            e.preventDefault();
            partialResetUmrahInvoicePassengerForm();
        });
        
        $('#submit-update-all-pax-rate').on('click', function(e){
            e.preventDefault();
            let formData = new FormData();
            formData.append('_token', $('input[name="_token"]').val()); // CSRF token
            formData.append('umrah_invoice_master_id',$('#umrah_invoice_master_id').val());
            formData.append('package_id',$('#package_id').val());
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
                    passengerTable.ajax.reload();
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

        function submitUmrahInvoicePassenger(formData)
        {
            var submit_btn = $('#create-umrah-invoice-passenger-btn');
           

            
            $.ajax({
                type: "POST",
                url: "{{ route('umrah-invoice-passenger.store') }}",
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
                        // $('#umrah-invoice-passenger-form')[0].reset();  // Reset all form data

                        submit_btn.prop('disabled', false).html('Save');  
                    passengerTable.ajax.reload();
                    partialResetUmrahInvoicePassengerForm();
                        
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

                    
                    // assign total values to inputs

                    $('#VisaPurchaseTotal').val(response.data.visa_purchase);
                    $('#VisaPurchaseAmount').val(response.data.forex_visa_purchase);
                    
                    $('#TicketPurchaseTotal').val(response.data.ticket_purchase);
                    $('#TicketPurchaseAmount').val(response.data.forex_ticket_purchase);
                    
                    
                    
                    $('#VisaSaleTotal').val(response.data.visa_sale);
                    $('#VisaSaleAmount').val(response.data.forex_visa_sale);
                    
                    $('#TicketSaleTotal').val(response.data.ticket_sale);
                    $('#TicketSaleAmount').val(response.data.forex_ticket_sale);

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

        $('#submit-umrah-invoice-passenger-destroy').click(function() {
            let id = $('#umrah_invoice_passenger_id').val();
            var submit_btn = $('#submit-umrah-invoice-passenger-destroy');

            $.ajax({
                type: 'DELETE',
                url: "{{ route('umrah-invoice-passenger.destroy', ':id') }}".replace(':id', id), // Using route name
                data: {
                    _token: "{{ csrf_token() }}" // Add CSRF token
                },
                beforeSend: function() {
                        submit_btn.prop('disabled', true);
                        submit_btn.html('Processing');
                    },
                success: function(response) {
                    
                    submit_btn.prop('disabled', false).html('Delete');  

                    if(response.success == true){
                        $('#delete-umrah-invoice-passenger').modal('hide'); 
                        passengerTable.ajax.reload();
                    
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

                    $('#VisaPurchaseTotal').val(response.data.visa_purchase);
                    $('#VisaPurchaseAmount').val(response.data.forex_visa_purchase);
                    
                    $('#TicketPurchaseTotal').val(response.data.ticket_purchase);
                    $('#TicketPurchaseAmount').val(response.data.forex_ticket_purchase);
                    
                    alert('ddd');
                    
                    $('#VisaSaleTotal').val(response.data.visa_sale);
                    $('#VisaSaleAmount').val(response.data.forex_visa_sale);
                    
                    $('#TicketSaleTotal').val(response.data.ticket_sale);
                    $('#TicketSaleAmount').val(response.data.forex_ticket_sale);

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



       

        
    });
    function passengerEdit(id)
    {
        $.get("{{ route('umrah-invoice-passenger.edit', ':id') }}".replace(':id', id), function(response) {
            $('#umrah_invoice_passenger_id').val(response.id);
            $('#pnr').val(response.pnr);
            $('#visa_no').val(response.visa_no);
            $('#visa_date').val(response.visa_date);
            $('#visa_days').val(response.visa_days);
            $('#passenger_name').val(response.passenger_name);
            $('#passport_no').val(response.passport_no);
            $('#type').val(response.type);
            $('#gender').val(response.gender);
            $('#relation_type').val(response.relation_type);
           
           
            $('#dob').val(response.dob);
            $('#nationality').val(response.nationality);
            $('#contact').val(response.contact);
            $('#visa_type').val(response.visa_type);
            $('#relation').val(response.relation);




            $('#shirka_id').val(response.shirka_id);
            $('#visa_sale').val(response.visa_sale);
            $('#ticket_sale').val(response.ticket_sale);
            $('#visa_purchase').val(response.visa_purchase);
            $('#ticket_purchase').val(response.ticket_purchase);
            $('#forex_purchase').val(response.forex_purchase);
            $('#forex_sale').val(response.forex_sale);

            }).fail(function(xhr) {
                alert('Error fetching details: ' + xhr.responseText);
        });

        
        setButtonState('#save-umrah-invoice-passanger-btn','disable');
        setButtonState('#save-as-umrah-invoice-passanger-btn','enable');
        setButtonState('#modify-umrah-invoice-passanger-btn','enable');
        setButtonState('#delete-umrah-invoice-passanger-btn','enable');

    }

    function updateAllPaxRate()
    {

    }

    

    function partialResetUmrahInvoicePassengerForm(){
        
        $('#umrah_invoice_passenger_id').val('');
        $('#pnr').val('');
        $('#visa_no').val('');
        $('#visa_date').val('');
        $('#visa_days').val('');
        $('#passenger_name').val('');
        $('#passport_no').val('');
        $('#type').val('Adult');
        $('#gender').val('Male').trigger('change');
        $('#relation_type').val('Relation').trigger('change');

         $('#dob').val('');
            $('#nationality').val('');
            $('#contact').val('');
            $('#visa_type').val('Umrah');
            $('#relation').val('Brother');



        setButtonState('#save-umrah-invoice-passanger-btn','enable');
        setButtonState('#save-as-umrah-invoice-passanger-btn','disable');
        setButtonState('#modify-umrah-invoice-passanger-btn','disable');
        setButtonState('#delete-umrah-invoice-passanger-btn','disable');

    }


    function setButtonState(btnId, value) {
        if (value === 'disable') {
            $(btnId).prop('disabled', true);  // Disable the button
        } else if (value === 'enable') {
            $(btnId).prop('disabled', false);  // Enable the button
        }
    }
 


    $('#delete-umrah-invoice-passanger-btn').click(function() {

        
    let id = $('#umrah_invoice_passenger_id').val();

    if (!id) {
        notyf.error("No passanger  selected to delete.");
        return;
    }

    if (!confirm("Are you sure you want to delete this record?")) {
        return; // Exit if user cancels
    }

    var submit_btn = $('#delete-umrah-invoice-passanger-btn');

    $.ajax({
        type: 'DELETE',
        url: "{{ route('umrah-invoice-passenger.destroy', ':id') }}".replace(':id', id),
        data: {
            _token: "{{ csrf_token() }}"
        },
        beforeSend: function() {
            submit_btn.prop('disabled', true).html('Processing');
        },
        success: function(response) {
            submit_btn.prop('disabled', false).html('Delete');  

            if(response.success == true){
                partialResetUmrahInvoicePassengerForm(); // or clearUmrahInvoiceTransportForm();
                
                passengerTable.ajax.reload();

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

                 $('#VisaPurchaseTotal').val(response.data.visa_purchase);
                    $('#VisaPurchaseAmount').val(response.data.forex_visa_purchase);
                    
                    $('#TicketPurchaseTotal').val(response.data.ticket_purchase);
                    $('#TicketPurchaseAmount').val(response.data.forex_ticket_purchase);
                    
                     
                    $('#VisaSaleTotal').val(response.data.visa_sale);
                    $('#VisaSaleAmount').val(response.data.forex_visa_sale);
                    
                    $('#TicketSaleTotal').val(response.data.ticket_sale);
                    $('#TicketSaleAmount').val(response.data.forex_ticket_sale);

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

 
 
$('#visa_no, #passport_no').on('blur', function() {
  
    let visa_no = $('#visa_no').val();
    let passport_no = $('#passport_no').val();

    if (visa_no || passport_no) {
        $.ajax({
            url: '{{ route("get.visa.detail") }}',
            type: 'GET',
            data: { visa_no: visa_no, passport_no: passport_no },
            success: function(res) {
                $('#visa_no').val(res.visa_no);
                $('#passport_no').val(res.passport_no);
                $('#passenger_name').val(res.passenger_name);
                $('#dob').val(res.dob);
                $('#nationality').val(res.nationality);
                $('#contact').val(res.contact);
                $('#visa_purchase').val(res.visa_purchase);
                $('#visa_sale').val(res.visa_sale);
                $('#visa_sale').val(res.visa_sale);
                $('#visa_type').val(res.VisaType);
                $('#gender').val(res.Gender);
                $('#type').val(res.type);
                $('#relation_type').val(res.relation_type);
                $('#shirka_id').val(res.shirka_id).trigger('change');
                $('#visa_date').val(res.visa_date);

            },
            error: function() {
                alert('No matching record found.');
            }
        });
    }
});
</script>


   
 
 

 