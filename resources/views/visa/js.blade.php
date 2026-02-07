<script>
    var visaDetailTable = '';

    $(document).ready(function () {
        let InvoiceMasterID =  $('#InvoiceMasterID').val();
        if(InvoiceMasterID){
            $('#create-or-update-visa-master-form-btn').text("Update");
            $('#create-or-update-visa-master-form-btn')
            .removeClass('btn-warning')
            .addClass('btn-primary');
        }
    });

    $(document).ready(function () {
        visaDetailTable = $('#visa-detail-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('visa-detail.index') }}",
                data: function (d) {
                    d.InvoiceMasterID = $('#InvoiceMasterID').val();
                }
            },
            columns: [
                { data: 'InvoiceDetailID', orderable: false, searchable: false },
                { data: 'PaxName' },
                { data: 'Passport' },
                { data: 'VisaStatus' },
                { data: 'action' }
            ],
            order: [[0, 'desc']]
        });
    });

    $('#create-or-update-visa-master-form-btn').on('click', function(){
        createOrUpdateVisaMaster();
    });


    $('#create-or-update-visa-detail-form-btn').on('click', function(){
        createOrUpdateVisaDetail();
        
    });



    function createOrUpdateVisaMaster()
    {
        let formData = new FormData(document.getElementById('create-or-update-visa-master-form'));
        $.ajax({
            type: "POST",
            url: "{{ route('visa-master.store') }}",
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            enctype: "multipart/form-data",
            
            success: function(response) {
                //assiging value to InvoiceMasterID
                $('#InvoiceMasterID').val(response.InvoiceMasterID);
                //update btn name
                $('#create-or-update-visa-master-form-btn').text("Update");
                $('#create-or-update-visa-master-form-btn')
                .removeClass('btn-warning')
                .addClass('btn-primary');
                
                notyf.success({
                    message: response.message,
                    duration: 3000
                });
            },
            error: function(e) {
                notyf.error({
                    message: e.responseJSON.message,
                    duration: 5000
                });
            }
        });

    }



    function createOrUpdateVisaDetail()
    {
        let formData = new FormData(document.getElementById('create-or-update-visa-detail-form'));
        
        formData.append('InvoiceMasterID', $('#InvoiceMasterID').val());
        formData.append('PartyID', $('#PartyID').val());

        $.ajax({
            type: "POST",
            url: "{{ route('visa-detail.store') }}",
            dataType: 'json',
            contentType: false,
            processData: false,
            cache: false,
            data: formData,
            enctype: "multipart/form-data",
            
            success: function(response) {
                
                visaDetailTable.ajax.reload();

                $('#visaMasterTotal').val(response.visaMasterTotal);
                
                notyf.success({
                    message: response.message,
                    duration: 3000
                });
                resetVisaDetailForm();
            },
            error: function(e) {
                notyf.error({
                    message: e.responseJSON.message,
                    duration: 5000
                });
            }
        });

    }


     function editVisaDetailRecord(InvoiceDetailID) {
        $.get("{{ route('visa-detail.edit', ':id') }}".replace(':id', InvoiceDetailID), function(response) {
            console.log(response);
            $('#InvoiceMasterID').val(response.InvoiceMasterID);
            $('#InvoiceDetailID').val(response.InvoiceDetailID);
            $('#ItemID').val(response.ItemID).trigger('change');
            $('#SupplierID').val(response.SupplierID).trigger('change');
            $('#VisaType').val(response.VisaType).trigger('change');
            $('#PaxName').val(response.PaxName);
            $('#Passport').val(response.Passport);
            $('#Nationality').val(response.Nationality);
            $('#VisaStatus').val(response.VisaStatus).trigger('change');
            $('#VisaNo').val(response.VisaNo);
            $('#DOB').val(response.DOB);
            $('#Age').val(response.Age);
            $('#PaxType').val(response.PaxType).trigger('change');
            $('#Gender').val(response.Gender).trigger('change');
            $('#IssueDate').val(response.IssueDate);
            $('#ExpiryDate').val(response.ExpiryDate);
            $('#RelationType').val(response.RelationType).trigger('change');
            $('#Relation').val(response.Relation).trigger('change');
            $('#ShirkaID').val(response.ShirkaID).trigger('change');
            $('#PackageName').val(response.PackageName).trigger('change');
            $('#DepartureDate').val(response.DepartureDate);
            $('#VisaSaleRate').val(response.VisaSaleRate);
            $('#ExRateSale').val(response.ExRateSale);
            $('#Receivable').val(response.Receivable);
            $('#VisaPurchaseRate').val(response.VisaPurchaseRate);
            $('#ExRatePurchase').val(response.ExRatePurchase);
            $('#Payable').val(response.Payable);

        }).fail(function(xhr) {
                notyf.error({
                        message: xhr.responseJSON.message,
                        duration: 5000
                    });
            // alert('Error fetching brand details: ' + xhr.responseText);
        });
    }


    function resetVisaDetailForm()
    {
        $('#create-or-update-visa-detail-form')[0].reset();
        $('#InvoiceDetailID').val('');
        $('.select2').val('').trigger('change');
        
    }


    $(document).on('keyup','.calculation',function(){
        calculation();
    })


    function calculation()
    {
        let VisaSaleRate = parseFloat( $('#VisaSaleRate').val()) || 0;
        let ExRateSale = parseFloat( $('#ExRateSale').val()) || 1;

        let Receivable = VisaSaleRate * ExRateSale;
        $('#Receivable').val(Receivable);

        let VisaPurchaseRate = parseFloat( $('#VisaPurchaseRate').val()) || 0;
        let ExRatePurchase = parseFloat( $('#ExRatePurchase').val()) || 1;

        let Payable = VisaPurchaseRate * ExRatePurchase;
        $('#Payable').val(Payable);
       
    }


    function deleteVisaDetailRecord(id)
    {
        if (confirm("Are you sure you want to delete?")) {
            $.ajax({
                type: 'DELETE',
                url: "{{ route('visa-detail.destroy', ':id') }}".replace(':id', id),
                data: {
                    _token: "{{ csrf_token() }}" // CSRF token for Laravel
                },
                success: function(response) {
                    visaDetailTable.ajax.reload();

                    $('#visaMasterTotal').val(response.visaMasterTotal);

                    notyf.success({
                        message: response.message,
                        duration: 3000
                    });
                },
                error: function(e) {
                    notyf.error({
                        message: e.responseJSON?.message || 'An error occurred.',
                        duration: 5000
                    });
                }
            });
        }
    }


    $('#visa-detail-update-all-btn').on('click', function(){
         updateAllVisaDetailRecords();
    });


    function updateAllVisaDetailRecords() {
        let InvoiceMasterID = $('#InvoiceMasterID').val();
        
        let VisaSaleRate = parseFloat( $('#VisaSaleRate').val()) || 0;
        let ExRateSale = parseFloat( $('#ExRateSale').val()) || 1;
        let Receivable = parseFloat( $('#Receivable').val()) || 0;

        let VisaPurchaseRate = parseFloat( $('#VisaPurchaseRate').val()) || 0;
        let ExRatePurchase = parseFloat( $('#ExRatePurchase').val()) || 1;
        let Payable = parseFloat( $('#Payable').val()) || 0;


        $.ajax({
            type: "POST",
            url: "{{ route('visa-detail.updateAllRecords', ':InvoiceMasterID') }}".replace(':InvoiceMasterID', InvoiceMasterID),
            data: {
                VisaSaleRate: VisaSaleRate,
                ExRateSale: ExRateSale,
                Receivable: Receivable,
                VisaPurchaseRate: VisaPurchaseRate,
                ExRatePurchase: ExRatePurchase,
                Payable: Payable,
                _token: '{{ csrf_token() }}'  // ✅ Required for Laravel POST
            },
            dataType: 'json',
            success: function(response) {

                $('#visaMasterTotal').val(response.visaMasterTotal);
                notyf.success({
                    message: response.message,
                    duration: 3000
                });
            },
            error: function(e) {
                let message = e.responseJSON?.message || 'An error occurred.';
                notyf.error({
                    message: message,
                    duration: 5000
                });
            }
        });
    }




</script>