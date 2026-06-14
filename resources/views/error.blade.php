<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-5 col-sm-10">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="#dc3545" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10" stroke="#dc3545" stroke-width="1.5" fill="#fff1f2" />
                            <line x1="12" y1="8" x2="12" y2="12" stroke="#dc3545" stroke-width="2" stroke-linecap="round" />
                            <circle cx="12" cy="16" r="1" fill="#dc3545" />
                        </svg>
                    </div>
                    <h5 class="fw-semibold mb-2">Payment Failed</h5>
                    <p class="text-muted mb-4">{{ $message }}</p>
                    <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">Go Back</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>

</html>
