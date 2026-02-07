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



                {{-- <li>

                    <a href="{{URL('/Umrah')}}" class="waves-effect">

                        <i class="bx bx-receipt"></i>

                        <span key="t-calendar">Umrah Booking</span>

                    </a>

                </li> --}}


               
 
{{--    
          <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-shopping-outline"></i>
                        <span key="t-ecommerce">CRM</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li> <a href="{{ URL('/leads') }}" key="t-products">Leads</a></li>
                        <li> <a href="{{ URL('/Booking') }}" key="t-products">Bookings</a></li>
                        <li> <a href="{{ URL('/calendar') }}" key="t-products">Calendar</a></li>
                        
       

                    </ul>
                </li> --}}

  

                </li>
 


         

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-shopping-outline"></i>
                        <span key="t-ecommerce">CRM Setting</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">


                        <li> <a href="{{ URL('/campaigns') }}" key="t-products">Compaigns</a></li>
                          <!-- <li>  <a   href="{{ URL('/') }}" key="t-products" >Recurring Bills</a></li> -->
                        <li> <a href="{{ URL('/services') }}" key="t-products">Serivces</a></li>
                        <li> <a href="{{ URL('/subServices') }}" key="t-products">Sub Services</a></li>
                        <li> <a href="{{ URL('/statuses') }}" key="t-products">Leads Status</a></li>
                        <li> <a href="{{ URL('/qualifiedStatuses') }}" key="t-products">Qualified Status</a></li>
  
                    

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