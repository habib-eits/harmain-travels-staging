<div class="vertical-menu">



    <div data-simplebar class="h-100">



        <!--- Sidemenu -->

        <div id="sidebar-menu">

            <!-- Left Menu Start -->

            <ul class="metismenu list-unstyled" id="side-menu">





                <li>

                    <a href="{{URL('/Dashboard')}}" class="waves-effect">

                        <i class="bx bx-home-circle"></i>

                        <span key="t-dashboards">Dashboards</span>

                    </a>



                </li>





            
                <li>

                    <a href="{{URL('/Invoice')}}" class="waves-effect">

                        <i class="mdi mdi-airplane text-success "></i>

                        <span key="t-calendar">Invoice</span>

                    </a>

                </li>
                
                
                <li>

                    <a href="{{route('umrah-invoice-master.index')}}" class="waves-effect">

                        <i class="fas fa-kaaba font-size-16 text-warning "></i>

                        <span key="t-calendar">Umrah Voucher</span>

                    </a>

                </li>
                
                
                {{-- <li>

                    <a href="{{route('visa-master.index')}}" class="waves-effect">

                        <i class="mdi mdi-airplane "></i>

                        <span key="t-calendar">Visa Pro</span>

                    </a>

                </li> --}}



                {{-- <li>

                    <a href="{{URL('/Umrah')}}" class="waves-effect">

                        <i class="fas fa-kaaba font-size-16 "></i>

                        <span key="t-calendar">Umrah Booking</span>

                    </a>

                </li> --}}


                    <li>

                    <a href="{{URL('/Estimate')}}" class="waves-effect">

                        <i class="bx bx-receipt"></i>

                        <span key="t-calendar">Quotation</span>

                    </a>

                </li>
 
   
          {{-- <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-shopping-outline"></i>
                        <span key="t-ecommerce">CRM</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li> <a href="{{ URL('/AdminDashboard') }}" key="t-products">CRM Dashboard</a></li>
                        <li> <a href="{{ URL('/leads') }}" key="t-products">Leads</a></li>
                        <li> <a href="{{ URL('/Booking') }}" key="t-products">Bookings</a></li>
                        <li> <a href="{{ URL('/calendar') }}" key="t-products">Calendar</a></li>
                        
       

                    </ul>
                </li> --}}

 <li>

                    <a href="javascript: void(0);" class="has-arrow waves-effect">

                        <i class="mdi mdi-finance"></i>

                        <span key="t-ecommerce">Accounts</span>

                    </a>

                    <ul class="sub-menu" aria-expanded="false">


                        <li> <a href="{{URL('/Voucher')}}" key="t-products">Voucher</a></li>
                        <li> <a href="{{URL('/Expense')}}" key="t-products">Expenses</a></li>
                        <li> <a href="{{URL('/PettyCash')}}" key="t-products">PettyCash</a></li>
                        <li> <a href="{{URL('/ChartOfAcc')}}" key="t-products">Chart of Account</a></li>
                        <li> <a href="{{URL('/AdjustmentBalance')}}" key="t-products">Adjustment Balance</a></li>
                     



                    </ul>

                </li>


            





                <li>

                    <a href="javascript: void(0);" class="has-arrow waves-effect">

                        <i class="mdi mdi-finance"></i>

                        <span key="t-ecommerce">Party Reports</span>

                    </a>

                    <ul class="sub-menu" aria-expanded="false">





                        <li> <a href="{{URL('/PartyLedger')}}" key="t-products">Party Ledger</a></li>

                        <li><a href="{{URL('/PartyBalance')}}" key="t-products">Party Balance</a></li>

                        <li><a href="{{URL('/PartyYearlyBalance')}}" key="t-products">Yearly Report</a></li>

                        <li><a href="#" key="t-products">Ageing Report</a></li>

                        <li><a href="#" key="t-products">Party Analysis</a></li>

                        <li><a href="{{URL('/PartyList')}}" key="t-products">Party List</a></li>

                        <li><a href="{{URL('/PartyWiseSale')}}" key="t-products">Partywise Sale</a></li>

                        <li><a href="{{URL('/OutStandingInvoice')}}" key="t-products">Outstanding Invoices</a></li>





                    </ul>

                </li>





                <li>

                    <a href="javascript: void(0);" class="has-arrow waves-effect">

                        <i class="mdi mdi-chart-areaspline"></i>

                        <span key="t-ecommerce">Supplier Reports</span>

                    </a>

                    <ul class="sub-menu" aria-expanded="false">





                        <li> <a href="{{URL('/SupplierLedger')}}" key="t-products">Supplier Ledger</a></li>

                        <li> <a href="{{URL('/SupplierBalance')}}" key="t-products">Supplier Balance</a></li>

                        <li> <a href="{{URL('/Invoice')}}" key="t-products">Sale Invoice</a></li>

                        <li> <a href="{{URL('/TicketRegister')}}" key="t-products">Ticket Register</a></li>

                        <li> <a href="{{URL('/AirlineSummary')}}" key="t-products">Airline Summary</a></li>
                        <li> <a href="{{URL('/ItemWiseSale')}}" key="t-products">Itemwise Report</a></li>

                        <li> <a href="{{URL('/SalemanReport')}}" key="t-products">Sales Man Report</a></li>
                        <li> <a href="{{URL('/SalemanInvoiceBalance')}}" key="t-products">Saleman Invoice Balance</a></li>

                        <li> <a href="{{URL('/TaxReport')}}" key="t-products">Tax Report</a></li>

                        <li> <a href="{{URL('/SupplierWiseSale')}}" key="t-products">Sales Report</a></li>
                        <li> <a href="{{URL('/UmrahReport')}}" key="t-products">Umrah Report</a></li>
                        <li> <a href="{{URL('/Log')}}" key="t-products">User's Log</a></li>





                    </ul>

                </li>





                <li>

                    <a href="javascript: void(0);" class="has-arrow waves-effect">

                        <i class="mdi mdi-chart-bell-curve-cumulative"></i>

                        <span key="t-ecommerce">Account Reports</span>

                    </a>

                    <ul class="sub-menu" aria-expanded="false">





                        <li><a key="t-products" href="{{URL('/CashbookReport')}}">Cash Book</a></li>

 
                        <li><a key="t-products" href="{{URL('/DaybookReport')}}">Day book</a></li>

                        <li><a key="t-products" href="{{URL('/GeneralLedger')}}">General Ledger</a></li>

                        <li><a key="t-products" href="{{URL('/TrialBalance')}}">Trial Balance</a></li>

                        <li><a key="t-products" href="{{URL('/TrialBalanceActivity')}}">Trial with acitivity</a></li>

                        <li><a key="t-products" href="#">yearly summary</a></li>

                        <li><a key="t-products" href="{{URL('/ProfitAndLoss')}}">profit and loss</a></li>

                        <li><a key="t-products" href="{{URL('/BalanceSheet')}}">balance sheet</a></li>

                        <li><a key="t-products" href="{{URL('/PartyBalance')}}">party balance</a></li>

                        <li><a key="t-products" href="#">ageing report</a></li>

                        <li><a key="t-products" href="#">cash flow</a></li>

                        <li><a key="t-products" href="{{URL('/TaxReport')}}">tax report</a></li>
                        <li><a key="t-products" href="{{URL('/ReconcileReport')}}">Bank Reconciliation</a></li>




                        <li><a key="t-products" href="{{URL('/InvoiceSummary')}}">invoice summary list</a></li>

                        <li><a key="t-products" href="{{URL('/TicketRegister')}}">Invoice Detail</a></li>





                    </ul>

                </li>

                    <li>

                    <a href="javascript: void(0);" class="has-arrow waves-effect">

                        <i class="mdi mdi-finance"></i>

                        <span key="t-ecommerce">Settings</span>

                    </a>

                    <ul class="sub-menu" aria-expanded="false">





                        <li> <a href="{{URL('/Item')}}" key="t-products"> Item</a></li>
                        <li> <a href="{{URL('/Parties')}}" key="t-products"> Parties / Cusomters</a></li>
                        {{-- <li> <a href="{{URL('/Supplier')}}" key="t-products"> Supplier</a></li> --}}
                        <li> <a href="{{URL('/User')}}" key="t-products"> User</a></li>
                        <li><a href="{{ route('hotel.index') }}">Hotels</a></li>
                        <li><a href="{{ route('location.index') }}">Locations</a></li>
                        <li><a href="{{ route('hoteltariff.index') }}">Hotel Tariff</a></li>
                        <li><a href="{{ route('transporttariff.index') }}">Transport Tariff</a></li>
                        <li><a href="{{ route('visatariff.index') }}">Visa Tariff</a></li>
                        <li><a href="{{ route('sector.index') }}">Sector</a></li>
                        <li><a href="{{ route('shirka.index') }}">Shirka</a></li>
                        <li><a href="{{ route('packages.index') }}">Packages</a></li>
                        <li><a href="{{ route('voucher-content.index') }}">Voucher Content</a></li>






                    </ul>

                </li>


         

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-shopping-outline"></i>
                        <span key="t-ecommerce">CRM Setting</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">


                        <li> <a href="{{ URL('/campaigns') }}" key="t-products">Compaigns</a></li>
                        <li> <a href="{{ URL('/branches') }}" key="t-products">Branches</a></li>
                        <li> <a href="{{ URL('/Company') }}" key="t-products">Company</a></li>
<!--                         <li> <a href="{{ URL('/User') }}" key="t-products">Staff</a></li>
                         <li> <a href="{{ URL('/services') }}" key="t-products">Serivces</a></li>
                        <li> <a href="{{ URL('/subServices') }}" key="t-products">Sub Services</a></li>
                        <li> <a href="{{ URL('/statuses') }}" key="t-products">Leads Status</a></li>
                        <li> <a href="{{ URL('/qualifiedStatuses') }}" key="t-products">Qualified Status</a></li>
                        <li> <a href="{{ URL('/User') }}" key="t-products">User</a></li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                 <i class="mdi mdi-folder font-size-16 text-warning me-2"></i> -->
                                {{-- <span key="t-ecommerce">Documents</span>
                            
                            <ul class="sub-menu" aria-expanded="false">


                                 <li><a href="{{ URL('/DocumentCategory') }}" key="t-products">Make Folder</a></li>
                                <li><a href="{{ URL('/Document') }}" key="t-products">Documents</a></li>
                                <li><a href="{{ URL('/Backup') }}" key="t-products">DB Backup</a></li>


                            </ul> --}}
                        </li>

                    </ul>
                </li>







                {{-- <li>

                    <a href="{{URL('/Backup')}}" class="waves-effect">

                        <i class="bx bx-power-off"></i>

                        <span key="t-calendar">Backup</span>

                    </a>

                </li> --}}
                
                <li>

                    <a href="{{URL('/Logout')}}" class="waves-effect">

                        <i class="bx bx-power-off"></i>

                        <span key="t-calendar">Logout</span>

                    </a>

                </li>







            </ul>

        </div>

        <!-- Sidebar -->

    </div>

</div>