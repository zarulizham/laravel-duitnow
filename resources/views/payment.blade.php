<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DuitNow Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        label {
            font-weight: 200;
            color: grey
        }

    </style>
</head>

<body>
    <div class="d-flex justify-content-center mt-5">
        <div class="col-md-5">
            @if (isset($errors) && $errors->any())
            <div class="alert alert-danger">
                {{ implode(',', $errors->all()) }}
            </div>
            @endif
            <div class="text-center">
                <h5>DuitNow</h5>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('duitnow.payment.auth.request') }}" method="post">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="">Amount</label>
                            <input class="form-control" type="text" name="amount" value="{{ $request->amount ?? '3.00' }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Description</label>
                            <input class="form-control" type="text" name="TXN_DESC" value="{{ $request->description ?? 'Payment for X' }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Reference ID</label>
                            <input class="form-control" type="text" name="reference_id" value="{{ $request->referece_id ?? '' }}">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Bank Type</label>
                            <select name="bank_type" id="bank_type" class="form-control">
                                <option value="RET">Retail Banking (B2C)</option>
                                <option value="COR">Corporate Banking (B2B)</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Bank</label>
                            <select name="bank" id="bank" class="form-control">
                            </select>
                        </div>
                        <button class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $("select").select2();

        $("#bank").select2({
            ajax: {
                url: function() {
                    return "{{ route('api.duitnow.bank-urls.index') }}?type=" + $("#bank_type").val();
                }
                , dataType: 'json'
                , data: function(params) {
                    var query = {
                        name: params.term
                    , }
                    return query;
                }
                , processResults: function(data) {
                    return {
                        results: $.map(data.bank_urls, function(bankUrl) {
                            return {
                                text: bankUrl.bank.name
                                , display: bankUrl.bank.name
                                , id: bankUrl.bank.id + "|" + bankUrl.type
                            , }
                        })
                    };
                }
            }
        })

    </script>
</body>

</html>
