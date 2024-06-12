<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Kyc Excel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets\css\style_excel.css">
</head>
<body>
    <center>
    <h1>Download Kyc Status</h1><br><br>
    <form id="customer-form" class="formField" action="{{ route('downloadExcel') }}">
      <select name="customer" id="customer-select" class="select2">
        <option value="" selected>Select Customer</option>
        @foreach($customer as $data)
          <option value="{{ $data->id }}">{{ $data->full_name }}</option>
        @endforeach
      </select>
      <label for="from" style="color: white;">From:</label>
      <input type="date" name="from" id="from">
      <label for="to" style="color: white;">To:</label>
      <input type="date" name="to" id="to"><br><br><br>
      <button type="submit" class="download-button">Download Excel</button>
    </form>
    </center>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
      $(document).ready(function() {
        $('.select2').select2();

        $('#customer-form').on('submit', function() {
          setTimeout(function() {
            $('#customer-form')[0].reset();
            $('#customer-select').val('').trigger('change');
          }, 10);
        });
      });
    </script>
</body>
</html>
