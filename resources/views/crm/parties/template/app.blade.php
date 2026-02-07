<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Party Ledger - {{ $party->PartName ?? 'ABC Traders' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Optional: better fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #5181ca;
            --danger:  #dc3545;
            --success: #198754;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-600: #6c757d;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--gray-100);
            color: #212529;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .ledger-header {
            border-left: 5px solid var(--primary);
            background: white;
            border-radius: 0.5rem;
        }

        .table-ledger {
            font-size: 0.95rem;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-ledger th {
            background: #343a40;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-ledger td, .table-ledger th {
            vertical-align: middle;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
        }

        .amount-dr {
            color: var(--danger);
            font-weight: 600;
            text-align: right;
        }

        .amount-cr {
            color: var(--success);
            font-weight: 600;
            text-align: right;
        }

        .amount-balance {
            font-weight: 600;
            text-align: right;
            min-width: 140px;
        }

        .balance-positive { color: #0d6efd; }
        .balance-negative { color: var(--danger); }
        .balance-zero     { color: #6c757d; }

        .total-row {
            font-weight: 700;
            background-color: #e9ecef !important;
        }

        .narration {
            max-width: 420px;
            white-space: pre-line;
        }

        .no-data {
            text-align: center;
            color: var(--gray-600);
            padding: 3rem 1rem;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .table-responsive { font-size: 0.9rem; }
            .amount-balance { min-width: 110px; }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-xl">
        <a class="navbar-brand" href="#">Party Ledger</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <button class="btn btn-outline-light btn-sm px-4" id="logoutBtn">Logout</button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-xl py-4">

    @yield('content')

   
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

@stack('scripts')

</body>
</html>