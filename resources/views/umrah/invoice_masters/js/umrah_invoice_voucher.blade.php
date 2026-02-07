<script>

        $('#btn-voucher-save').on('click', function(e){
            e.preventDefault();
            let formData = new FormData($('#umrah-invoice-voucher')[0]);
            formData.append('umrah_invoice_master_id',$('#umrah_invoice_master_id').val());
            
            submitUmrahInvoiceVoucher(formData); 
        }); 
 
        // $('#btn-voucher-delete').on('click', function(e){
        //     e.preventDefault();
        //     let formData = new FormData($('#umrah-invoice-passenger-form')[0]);
        //     formData.append('umrah_invoice_master_id',$('#umrah_invoice_master_id').val());

        //     submitUmrahInvoiceVoucher(formData);
        // });
 
        function submitUmrahInvoiceVoucher(formData)
        {
            var submit_btn = $('#btn-voucher-save');

                         
            $.ajax({
                type: "POST",
                url: "{{ route('umrah-invoice-voucher-store') }}",
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
                    
                    submit_btn.prop('disabled', false).html('Save Hotel Voucher');  
                    passengerTable.ajax.reload();
                    partialResetUmrahInvoicePassengerForm();

               

                    if(response.success == true){
                        // $('#umrah-invoice-passenger-form')[0].reset();  // Reset all form data

                               setTimeout(function () {
        window.location.href = response.redirect_url;
    }, 1000); // Redirect after 1 second
                        
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


//     $('#delete-umrah-invoice-passanger-btn').click(function() {

        
//     let id = $('#umrah_invoice_passenger_id').val();

//     if (!id) {
//         notyf.error("No passanger  selected to delete.");
//         return;
//     }

//     if (!confirm("Are you sure you want to delete this record?")) {
//         return; // Exit if user cancels
//     }

//     var submit_btn = $('#delete-umrah-invoice-passanger-btn');

//     $.ajax({
//         type: 'DELETE',
//         url: "{{ route('umrah-invoice-passenger.destroy', ':id') }}".replace(':id', id),
//         data: {
//             _token: "{{ csrf_token() }}"
//         },
//         beforeSend: function() {
//             submit_btn.prop('disabled', true).html('Processing');
//         },
//         success: function(response) {
//             submit_btn.prop('disabled', false).html('Delete');  

//             if(response.success == true){
//                 partialResetUmrahInvoicePassengerForm(); // or clearUmrahInvoiceTransportForm();
                
//                 passengerTable.ajax.reload();

//                 notyf.success({
//                     message: response.message, 
//                     duration: 3000
//                 });
//             } else {
//                 notyf.error({
//                     message: response.message,
//                     duration: 5000
//                 });
//             }
//         },
//         error: function(e) {
//             submit_btn.prop('disabled', false).html('Delete');
//             notyf.error({
//                 message: e.responseJSON.message,
//                 duration: 5000
//             });
//         }
//     });
// });





   
</script>
 