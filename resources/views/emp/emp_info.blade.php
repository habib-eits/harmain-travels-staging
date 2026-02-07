  <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="media">
                                            <div class="me-3">

                                              <?php if($employee[0]->Picture!=null){ ?>
                                                <img src="{{URL('/emp-picture/'.$employee[0]->Picture)}}" alt="" class="avatar-md rounded  ">  
                                              <?php } 
                                              else
                                              {


                                              ?>

                                                <img src="{{ asset('assets/images/users/avatar.png')}}" alt="" class="avatar-md rounded  ">

                                            <?php } ?>

                                            </div>
                                            <div class="media-body align-self-center">
                                                <div class="text-muted">
                                                    <h5>{{$employee[0]->Title}} {{$employee[0]->FirstName}} {{$employee[0]->MiddleName}} {{$employee[0]->LastName}}</h5>
                                                    <p class="mb-1">{{$employee[0]->JobTitleName}} , {{$employee[0]->DepartmentName}},  <span class="badge badge-soft-success font-size-11 me-2 ml-5"> {{$employee[0]->StaffType}}  </span> </p>
                                                    <p class="mb-0">{{$employee[0]->BranchName}}</p>
                                                     
                                                </div>
                                            </div>
                                    

                                                        <div class="dropdown ms-2">
                                                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="bx bxs-cog align-middle me-1"></i> Manage
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end" style="margin: 0px;">
                                                            <a class="dropdown-item" href="{{URL('/EmployeeEdit/'.$employee[0]->EmployeeID)}}"><i class="mdi mdi-pencil text-secondary font-size-16 me-2"></i>Edit</a>
                                                            <a class="dropdown-item" href="#"> <i class="mdi mdi-trash-can text-secondary font-size-16 me-2"></i>Delete</a>
                                                         </div>
                                                    </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>