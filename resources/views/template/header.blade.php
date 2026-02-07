<header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="{{URL('/dashboard')}}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{URL('/')}}/assets/images/square.svg" alt="" height="40">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{URL('/')}}/assets/images/square.svg" alt="" height="40"> {{env('APP_NAME')}}
                                </span>
                            </a>

                            <a href="{{URL('/Dashboard')}}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{URL('/')}}/assets/images/square.svg" alt="" height="40">
                                </span>
                                <span class="logo-lg ">
                                   <h5 class="mt-4 text-white"> {{env('APP_NAME')}}</h5>
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>

                        <!-- App Search-->
                        <form class="app-search  d-xl-block">
                            <div class="position-relative">
                               <div class="d-flex gap-2 flex-wrap">
                                           
                                         

                                            <div class="btn-group d-none d-lg-inline-block">
                                                <button type="button" class="  btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class=" text-success far fa-bookmark
 font-size-16 align-middle me-2"></i>Favourite <i class="mdi mdi-chevron-down"></i></button>
                                                <div class="dropdown-menu" style="margin: 0px;">
                                                     
 <a class="dropdown-item" href="{{URL('/InvoiceCreate')}}"><i class="bx bx-plus "></i> Invoice</a>
                                 <div class="dropdown-divider"></div>

 
 <a class="dropdown-item" href="{{URL('/VoucherCreate/BP')}}"><i class="bx bx-up-arrow-alt text-danger "></i> BP-Bank Payment</a>
   <a class="dropdown-item" href="{{URL('/VoucherCreate/BR')}}"><i class="bx bx-down-arrow-alt text-success "></i> BR-Bank Receipt</a>
    <div class="dropdown-divider"></div>
       <a class="dropdown-item" href="{{URL('/VoucherCreate/CP')}}"><i class="bx bx-up-arrow-alt text-danger "></i> CP-Cash Payment</a>
       <a class="dropdown-item" href="{{URL('/VoucherCreate/CR')}}"><i class="bx bx-down-arrow-alt text-success "></i> CR-Cash Receipt</a>
        <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{URL('/JV')}}"><i class="bx bx-plus "></i> Journal Voucher</a>


                 
                  
                                                </div>
                                            </div><!-- /btn-group -->


                                            <div class="btn-group d-none d-lg-inline-block">
                                                <button type="button" class="  btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Party Reports <i class="mdi mdi-chevron-down"></i></button>
                                                <div class="dropdown-menu" style="margin: 0px;">
                                                     <a class="dropdown-item" href="{{URL('/Invoice')}}">Invoice</a>
                 <a class="dropdown-item" href="{{URL('/PartyLedger')}}">Party Ledger</a>
                 <a class="dropdown-item" href="{{URL('/PartyBalance')}}">Party Balances</a>
                 <a class="dropdown-item" href="{{URL('/PartyYearlyBalance')}}">Yearly Report</a>
              <!--    <a class="dropdown-item" href="#">Ageing Report</a>
                 <a class="dropdown-item" href="#">Party Analysis</a> -->
                 <a class="dropdown-item" href="{{URL('/PartyList')}}">Party List</a>
                 <a class="dropdown-item" href="{{URL('/PartyWiseSale')}}">Partywise Sale</a>
                 <a class="dropdown-item" href="{{URL('/OutStandingInvoice')}}">Outstanding Invoices</a>
                                                </div>
                                            </div><!-- /btn-group -->
                                       
                                            <div class="btn-group d-none d-lg-inline-block">
                                                <button type="button" class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Inventory Reports <i class="mdi mdi-chevron-down"></i></button>
                                                <div class="dropdown-menu">
                                                   <a class="dropdown-item" href="{{URL('/SupplierLedger')}}">Supplier Ledger</a>
                <a class="dropdown-item" href="{{URL('/SupplierBalance')}}">Supplier Balance</a>
                 <a class="dropdown-item" href="{{URL('/Invoice')}}">Sale Invoice</a>
                <a class="dropdown-item" href="{{URL('/TicketRegister')}}">Ticket Register</a>
                <a class="dropdown-item" href="{{URL('/AirlineSummary')}}">Airline Summary</a>
                <a class="dropdown-item" href="{{URL('/SalemanReport')}}">Salesman Report</a>
                <a class="dropdown-item" href="{{URL('/ItemWiseSale')}}">Itemwise Report</a>
                <a class="dropdown-item" href="{{URL('/SalemanTicketRegister')}}">Saleman Ticket Register</a>

                <a class="dropdown-item" href="{{URL('/SalemanInvoiceBalance')}}">Saleman Invoice Balances</a>
                <a class="dropdown-item" href="{{URL('/TaxReport')}}">Tax Report</a>
                <a class="dropdown-item" href="{{URL('/SupplierWiseSale')}}">Sales Report</a>
                {{-- <a class="dropdown-item" href="{{URL('/UmrahReport')}}">Umrah Report</a> --}}
                <a class="dropdown-item" href="{{URL('/Log')}}">User's Log</a>
                                                </div>
                                            </div><!-- /btn-group -->
                                            <div class="btn-group d-none d-lg-inline-block">
                                                <button type="button" class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Accounts Reports <i class="mdi mdi-chevron-down"></i></button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item  " href="{{URL('/VoucherReport')}}">Vochers</a>
                <a class="dropdown-item" href="{{URL('/CashbookReport')}}">Cash Book</a>
                <div class="dropdown-divider"></div>
                <!-- <a class="dropdown-item" href="#">Sales man wise cash book</a> -->
                <a class="dropdown-item" href="{{URL('/DaybookReport')}}">Day book</a>
                 <a class="dropdown-item" href="{{URL('/GeneralLedger')}}">General Ledger</a>
                <a class="dropdown-item" href="{{URL('/TrialBalance')}}">Trial Balance</a>
                <a class="dropdown-item" href="{{URL('/TrialBalanceActivity')}}">Trial with acitivity</a>
                <!-- <a class="dropdown-item" href="#">yearly summary</a> -->
                <a class="dropdown-item" href="{{URL('/ProfitAndLoss')}}">Profit &  Loss</a>
                <a class="dropdown-item" href="{{URL('/BalanceSheet')}}">Balance Sheet</a>
                <a class="dropdown-item" href="{{URL('/PartyBalance')}}">Party Balances</a>
            <!--     <a class="dropdown-item" href="#">ageing report</a>
                <a class="dropdown-item" href="#">cash flow</a> -->
                <a class="dropdown-item" href="{{URL('/ReconcileReport')}}">Bank Reconciliation</a>
                <a class="dropdown-item" href="{{URL('/TaxReport')}}">Tax Report</a>
                <a class="dropdown-item" href="{{URL('/InvoiceSummary')}}">Invoice Summary List</a>
                <a class="dropdown-item" href="{{URL('/TicketRegister')}}">Invoice Detail</a>
                <a class="dropdown-item" href="{{URL('/paymentSummary')}}">Payment Summary</a>
                <a class="dropdown-item" href="{{URL('/ExpenseReport')}}">Expense Report</a>
                                                     
                                                </div>
                                            </div><!-- /btn-group -->
                                            
                                        </div>
                            </div>
                        </form>

                        
                    </div>

                    <div class="d-flex">

                        <div class="dropdown d-inline-block d-lg-none ms-2">
                            <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                                aria-labelledby="page-header-search-dropdown">
        
                                <form class="p-3">
                                    <div class="form-group m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                      


{{-- @if(session('UserType') == 'SuperAdmin')
    <div class="btn-group d-none" >
        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="text-danger mdi mdi-plus-circle-outline font-size-16 align-middle me-2"></i>Branch Dashboard 
            <i class="mdi mdi-chevron-down"></i>
        </button>
        <div class="dropdown-menu ">
            <a class="dropdown-item" href="{{URL('/Dashboard/1')}}">Wazirstan Branch</a>
            <a class="dropdown-item" href="{{URL('/Dashboard/2')}}">Peshawar Branch</a>
        </div>
    </div>
@endif --}}
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="{{URL('/')}}/assets/images/users/avatar-1.jpg"
                                    alt="Header Avatar">
                                <span class="d-none d-xl-inline-block ms-1 " key="t-henry">Setting</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a class="dropdown-item" href="{{URL('/UserProfile')}}">
                                    <i class="bx bx-user font-size-16 align-middle me-1"></i> 
                                    <span key="t-profile">Profile</span></a>
                                
 
                                <a class="dropdown-item d-block" href="{{URL('/ChangePassword')}}"><i class="bx bx-wrench font-size-16 align-middle me-1"></i> <span key="t-settings">Change Password</span></a>

                                
                                
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="{{URL('/Logout')}}"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Logout</span></a>
                            </div>
                        </div>

                         

                    </div>
                </div>
            </header>