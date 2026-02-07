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
                                    <i class="bx bx-receipt"></i>
                                    <span key="t-calendar">Invoice</span>
                                </a>
                            </li> 
                            

                             
                            <li>
                                <a href="{{URL('/Voucher')}}" class="waves-effect">
                                    <i class="mdi mdi-receipt"></i>
                                    <span key="t-calendar">Voucher</span>
                                </a>
                            </li> 
                            
                             
                            <li>
                                <a href="{{URL('/Expense')}}" class="waves-effect">
                                    <i class="mdi mdi-account-cash-outline"></i>
                                    <span key="t-calendar">Expense</span>
                                </a>
                            </li>


                              <li>
                                <a href="{{URL('/PettyCash')}}" class="waves-effect">
                                    <i class="mdi mdi-account-cash-outline"></i>
                                    <span key="t-calendar">PettyCash</span>
                                </a>
                            </li> 


                            <li>
                                <a href="{{URL('/AdjustmentBalance')}}" class="waves-effect">
                                    <i class="mdi mdi-scale-balance"></i>
                                    <span key="t-calendar">Adjustment Balance</span>
                                </a>
                            </li> 
                           
                            <li>
                                <a href="{{URL('/ChartOfAcc')}}" class="waves-effect">
                                    <i class="mdi mdi-text-box-check-outline"></i>
                                    <span key="t-calendar">Chart of Account</span>
                                </a>
                            </li> 
                        
                        
                            <li>
                                <a href="{{URL('/Item')}}" class="waves-effect">
                                    <i class="mdi mdi-view-list-outline"></i>
                                    <span key="t-calendar">Item</span>
                                </a>
                            </li> 
                           
                            <li>
                                <a href="{{URL('/Parties')}}" class="waves-effect">
                                    <i class="bx bxs-user-plus"></i>
                                    <span key="t-calendar">Parties / Cusomters</span>
                                </a>
                            </li> 
                          
                            <li>
                                <a href="{{URL('/Supplier')}}" class="waves-effect">
                                    <i class="bx bxs-user-plus"></i>
                                    <span key="t-calendar">Supplier</span>
                                </a>
                            </li> 
                           
                           
                            <li>
                                <a href="{{URL('/User')}}" class="waves-effect">
                                    <i class="bx bxs-user-plus"></i>
                                    <span key="t-calendar">User</span>
                                </a>
                            </li> 

                              <li>
                                <a href="{{URL('/Salesman')}}" class="waves-effect">
                                    <i class="bx bxs-bank"></i>
                                    <span key="t-calendar">Salesman</span>
                                </a>
                            </li> 


 


                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-finance"></i>
                                    <span key="t-ecommerce">Party Reports</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    
                                    
                                 <li>  <a   href="{{URL('/PartyLedger')}}" key="t-products" >Party Ledger</a></li>
                                 <li><a href="{{URL('/PartyBalance')}}" key="t-products" >Party Balance</a></li>
                                 <li><a href="{{URL('/PartyYearlyBalance')}}" key="t-products" >Yearly Report</a></li>
                                 <li><a href="#" key="t-products" >Ageing Report</a></li>
                                 <li><a href="#" key="t-products" >Party Analysis</a></li>
                                 <li><a href="{{URL('/PartyList')}}" key="t-products" >Party List</a></li>
                                 <li><a href="{{URL('/PartyWiseSale')}}" key="t-products" >Partywise Sale</a></li>
                                 <li><a href="{{URL('/OutStandingInvoice')}}" key="t-products" >Outstanding Invoices</a></li>
                                    
                                 
                                </ul>
                            </li>


                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-chart-areaspline"></i>
                                    <span key="t-ecommerce">Supplier Reports</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    
                                    
                                 <li> <a  href="{{URL('/SupplierLedger')}}" key="t-products" >Supplier Ledger</a></li>
               <li> <a  href="{{URL('/SupplierBalance')}}" key="t-products" >Supplier Balance</a></li>
                <li> <a  href="{{URL('/Invoice')}}" key="t-products" >Sale Invoice</a></li>
               <li> <a  href="{{URL('/TicketRegister')}}" key="t-products" >Ticket Register</a></li>
               <li> <a  href="{{URL('/AirlineSummary')}}" key="t-products" >Airline Summary</a></li>
               <li> <a  href="{{URL('/SalemanReport')}}" key="t-products" >Sales Man Report</a></li>
               <li> <a  href="{{URL('/TaxReport')}}" key="t-products" >Tax Report</a></li>
               <li> <a  href="{{URL('/SupplierWiseSale')}}" key="t-products" >Sales Report</a></li>
                                    
                                 
                                </ul>
                            </li>


                              <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-chart-bell-curve-cumulative"></i>
                                    <span key="t-ecommerce">Account Reports</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    
                                    
                                <li><a key="t-products" href="{{URL('/CashbookReport')}}">Cash Book</a></li>
                 <li><a key="t-products" href="#">Sales man wise cash book</a></li>
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
                <li><a key="t-products" href="{{URL('/InvoiceSummary')}}">invoice summary list</a></li>
                <li><a key="t-products" href="{{URL('/TicketRegister')}}">Invoice Detail</a></li>
                                    
                                 
                                </ul>
                            </li>



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