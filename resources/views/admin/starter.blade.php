@extends('admin.layouts.admin')

@section('title', 'Dashboard | SUIT')

@push('styles')
    <!-- Custom CSS specific to this page -->
    <link href="{{ asset('assets/css/custom-starter.css') }}" rel="stylesheet">
    <style>
    body {
      background-color: #f4f6f9;
      font-family: 'Segoe UI', sans-serif;
    }
    .stat-box {
      padding: 20px;
      border-radius: 12px;
      background: #fff;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      position: relative;
    }
    .stat-box h5 {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 5px;
    }
    .stat-box h2 {
      font-size: 2rem;
      margin: 0;
    }
    .stat-box .info-link {
      position: absolute;
      right: 15px;
      bottom: 10px;
      font-size: 0.875rem;
    }
    .stat-icon {
      position: absolute;
      right: 15px;
      top: 15px;
      font-size: 1.2rem;
    }
    .chart-container {
      position: relative;
      height: 350px;
    }
    .chart-center-label {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
    }
    .chart-center-label h4 {
      margin: 0;
      font-weight: bold;
    }
    .chart-center-label span {
      font-size: 1.25rem;
      font-weight: bold;
    }
  </style>
@endpush

@section('content')
    <!-- start page title -->
 
    <!-- end page title -->
<div class="  py-4">
  <h2 class="mb-4">🎓 University Dashboard</h2>

  <!-- Stat Boxes -->
  <div class="row g-3">
    <div class="col-md-3">
      <div class="stat-box text-primary border-start border-5 border-info">
        <h5>System Users</h5>
        <h2>2</h2>
        <div class="stat-icon text-info"><i class="bi bi-arrow-right-circle"></i></div>
        <div class="info-link text-info">More Info</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-box text-primary border-start border-5 border-primary">
        <h5>Faculty</h5>
        <h2>5</h2>
        <div class="stat-icon text-primary"><i class="bi bi-arrow-right-circle"></i></div>
        <div class="info-link text-primary">More Info</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-box text-warning border-start border-5 border-warning">
        <h5>Faculty Members</h5>
        <h2>374</h2>
        <div class="stat-icon text-warning"><i class="bi bi-arrow-right-circle"></i></div>
        <div class="info-link text-warning">More Info</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-box text-success border-start border-5 border-success">
        <h5>Departments</h5>
        <h2>21</h2>
        <div class="stat-icon text-success"><i class="bi bi-arrow-right-circle"></i></div>
        <div class="info-link text-success">More Info</div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="stat-box text-danger border-start border-5 border-danger">
        <h5>Programs</h5>
        <h2>42</h2>
        <div class="stat-icon text-danger"><i class="bi bi-arrow-right-circle"></i></div>
        <div class="info-link text-danger">More Info</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-box text-dark border-start border-5 border-secondary">
        <h5>Administration Offices</h5>
        <h2>8</h2>
        <div class="stat-icon text-secondary"><i class="bi bi-arrow-right-circle"></i></div>
        <div class="info-link text-secondary">More Info</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-box text-primary border-start border-5 border-info">
        <h5>Administration Faculty Members</h5>
        <h2>35</h2>
        <div class="stat-icon text-info"><i class="bi bi-arrow-right-circle"></i></div>
        <div class="info-link text-info">More Info</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="stat-box text-light border-start border-5 border-dark bg-dark">
        <h5 class="text-white">Distance Centers</h5>
        <h2 class="text-white">91</h2>
        <div class="stat-icon text-white"><i class="bi bi-arrow-right-circle"></i></div>
        <div class="info-link text-white">More Info</div>
      </div>
    </div>
  </div>

  <!-- Charts -->
  <div class="row mt-4 g-4">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header fw-bold">Faculty Members by Department</div>
        <div class="card-body chart-container">
          <canvas id="facultyChart"></canvas>
          <div class="chart-center-label">
            <h4>Computer Science / IT</h4>
            <span>33</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header fw-bold">Distance Center By City</div>
        <div class="card-body chart-container">
          <canvas id="distanceChart"></canvas>
          <div class="chart-center-label">
            <h4>Ajk</h4>
            <span>9</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    

@endsection

@push('scripts')
    <!-- Custom JS specific to this page -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            console.log("Starter Page JS Loaded");
        });
    </script>
@endpush
