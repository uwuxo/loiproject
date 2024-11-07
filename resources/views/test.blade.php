<!DOCTYPE html>
<html>
<head>
    <title>Date Range Form</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Chọn khoảng thời gian</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <span>{{ Session::get('success') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <span>{{ Session::get('error') }}</span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
                        <form method="POST" action="{{ route('test.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Ngày bắt đầu</label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="start_date" name="start_date" placeholder="DD/MM/YYYY">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Ngày kết thúc</label>
                                <div class="input-group">
                                    <input type="text" class="form-control datepicker" id="end_date" name="end_date" placeholder="DD/MM/YYYY">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                language: 'vi'
            });

            // Thêm validation khi submit form
            // $('form').on('submit', function(e){
            //     e.preventDefault();
            //     const startDate = $('#start_date').val();
            //     const endDate = $('#end_date').val();
                
            //     if(!startDate || !endDate) {
            //         alert('Vui lòng chọn đầy đủ ngày bắt đầu và kết thúc');
            //         return;
            //     }

            //     // Convert string dates to Date objects for comparison
            //     const start = new Date(startDate.split('/').reverse().join('-'));
            //     const end = new Date(endDate.split('/').reverse().join('-'));
                
            //     if(start > end) {
            //         alert('Ngày kết thúc phải lớn hơn ngày bắt đầu');
            //         return;
            //     }

            //     // Xử lý submit form ở đây
            //     console.log('Form submitted:', {startDate, endDate});
            // });
        });
    </script>
</body>
</html>