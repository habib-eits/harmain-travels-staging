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
                                <a href="{{URL('/Employee')}}" class="waves-effect">
                                    <i class="bx bxs-user-plus"></i>
                                    <span key="t-calendar">Employee</span>
                                </a>
                            </li> 
                            

                             <li>
                                <a href="{{URL('/FCB')}}" class="waves-effect">
                                    <i class="bx bxs-user-plus"></i>
                                    <span key="t-calendar">Desposit</span>
                                </a>
                            </li> 
                             

                                 <li>
                                <a href="{{URL('/AttendanceImport')}}" class="waves-effect">
                                    <i class="mdi mdi-database-import-outline"></i>
                                    <span key="t-calendar">Import Attendance</span>
                                </a>
                            </li> 

                           
                                
                              <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bx-dollar-circle"></i>
                                    <span key="t-ecommerce">Salary Section</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{URL('/Salary')}}" key="t-products">Make Salary</a></li>
                                    <li><a href="{{URL('/ViewSalary')}}" key="t-products">Search Salary</a></li>
                                    <li><a href="{{URL('/EU')}}" key="t-products">Operation Manager</a></li>
                                      
                                 
                                </ul>
                            </li>
                            


                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="mdi mdi-hammer-wrench"></i>
                                    <span key="t-ecommerce">Setting</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{URL('/Branches')}}" key="t-products">Branch</a></li>
                                     <li><a href="{{URL('/Departments')}}" key="t-products">Departments</a></li>
                                    <li><a href="{{URL('/JobTitle')}}" key="t-products">Job Title</a></li>
                                     <li><a href="{{URL('/Letter')}}" key="t-products">Letter Templates</a></li>
                                     <li><a href="{{URL('/Team')}}" key="t-products">Team Structure</a></li>
                                     <li><a href="{{URL('/Users')}}" key="t-products">Users</a></li> 
                                 
                                </ul>
                            </li>


                         <!--    <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bxs-bar-chart-alt-2"></i>
                                    <span key="t-ecommerce">Reports</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{URL('/inventory')}}" key="t-products">Inventory</a></li>
                                    <li><a href="{{URL('/daily_sale')}}" key="t-products">Daily Sales</a></li>
                                    <li><a href="{{URL('/over_ledger')}}" key="t-products">Overall Ledger</a></li>
                                    <li><a href="{{URL('/rep_report')}}" key="t-products">Agent Wise Sale</a></li>
                                    <li><a href="{{URL('/daily_sale')}}" key="t-products">Month Wise Sale</a></li>
                                    <li><a href="{{ url('/export/xlsx') }}" key="t-products">Alert SMS</a></li>
                                    <li><a href="#" key="t-products">Map</a></li>
                                    <li><a href="{{URL('/daywise_payment_alert')}}" key="t-products">Day Wise Payment Alert</a></li>
                                    
                                 
                                </ul>
                            </li> -->


 <li>
                                <a href="{{URL('/logout')}}" class="waves-effect">
                                    <i class="bx bx-power-off"></i>
                                    <span key="t-calendar">Logout</span>
                                </a>
                            </li>



                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>