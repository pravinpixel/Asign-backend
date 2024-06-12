<link rel="stylesheet" type="text/css" href="{{asset('css/jquery.uploader.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('css/jquery.viewbox.css')}}" />
<link rel="stylesheet" type="text/css" href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<form id="dynamic-form1" method="post" enctype="multipart/form-data">
    @csrf
     <input type="hidden" name="id" value="{{ $info->id ?? '' }}">
    <input type="hidden" name="title" id="title" value="{{ $title ?? '' }}">
 
    <div class="form-group mb-3 singleUploadImage">
      <label for="formFile" class="form-label"> Fair Iamge</label>
        <div class="mb-3" id="drag_upload">
            <input type="file" id="imageInput" value="" />
        </div>
    </div>
       <div class="form-group mb-3">
      <label class="form-label">From Date:</label>
      <input type="date" name="from_date" class="form-control" id="fromDate" value="{{ $info->from_date ?? '' }}"/>
      <span class="field-error" id="from_date-error"></span>
      </div>

      <div class="form-group mb-3">
      <label class="form-label">To Date:</label>
      <input type="date" name="to_date" class="form-control" id="toDate"  value="{{ $info->to_date ?? '' }}"/>
      <span class="field-error" id="to_date-error"></span>
      </div>
<div class="form-group mb-3">
    <label class="form-label">Fair:</label>
    <input type="text" class="form-control" placeholder="Enter fair" name="name" value="{{ $info->name ?? '' }}">
 <span class="field-error" id="name-error"></span>
</div>
<div class="form-group mb-3">
<div class="form-group">
      <label class="form-label">Status</label>
      <div class="w100Select">
        <select class="select2Box"  name="status">
          <option value=""></option>
          <option value="1"  @if(isset($info->status) && $info->status == 1) selected @endif>Active</option>
        <option value="0" @if(isset($info->status) && $info->status == 0) selected @endif >InActive</option>
        </select>
      </div>
    </div>
</div>
<div class="footerbtnDiv">
    <button type="button" id="dynamic-submit1" class="btn apply-btn">Save</button>
</div>
</form>
<script>
$(".select2Box").select2({
    placeholder: "Select Status",
    minimumResultsForSearch: Infinity,
});
$(document).ready(function() {

    $('input[name="to_date"]').attr('min', $('input[name="from_date"]').val());
    $('input[name="from_date"]').on('change', function() {
        var fromDate = new Date($(this).val());
        fromDate.setDate(fromDate.getDate());
        var minToDate = fromDate.toISOString().split('T')[0];
        $('input[name="to_date"]').attr('min', minToDate);
    });


    let image = [];
    var info = "{{ $info->image ?? '' }}";
    if (info) {
        $("#drag_upload").uploader({
            defaultValue: [{
                url: info
            }],
            multiple: false,
            url: "",
            ajaxConfig: {
                paramsBuilder: function(uploaderFile) {
                    if (uploaderFile && uploaderFile.name) {
                        image.push(uploaderFile.file);
                    }
                },
            },
        });
    } else {
        $("#drag_upload").uploader({
            url: "",
            multiple: false,
            ajaxConfig: {
                paramsBuilder: function(uploaderFile) {
                    if (uploaderFile && uploaderFile.name) {
                        image.push(uploaderFile.file);
                    }
                },
            },
        });
    }


    $('#dynamic-submit1').off('click').on('click', function() {
        let form_data = new FormData($("#dynamic-form1")[0]);
        form_data.append('image', image[0]);
        $.ajax({
            url: fair,
            type: "POST",
            data: form_data,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    $('.conform_header').empty();
                    $('.conform_content').empty();
                    $('.conform_header').append($('#title').val());
                    if (($('#title').val()[0]) == 'E') {
                        $('.conform_content').append('Are you sure you want to save this change? It will reflect across all Masters.');
                    } else {
                        let title_name = $('#title').val().replace('Add', '');
                        $('.conform_content').append('Are you sure you want to add this ' + title_name + ' to your Masters?');
                    }
                    $('#conform').modal('show');
                    $('#dynamic').modal('hide');
                }
            },
            error: function(response) {
                $.each(response.responseJSON.errors, function(field_name, error) {
                    $('#' + field_name + '-error').text(error[0]);
                });
            }
        });
    });


    $('#conform_save').off('click').on('click', function() {
        let form_data = new FormData($("#dynamic-form1")[0]);
        form_data.append('image', image[0]);
        $.ajax({
            url: save,
            type: "POST",
            data: form_data,
            contentType: false,
            processData: false,
            success: function(response) {
                toastr.success(response.message);
                $("#dynamic-submit1").attr("disabled", false);
                $('#dynamic-form1')[0].reset();
                $('#dynamic').modal('hide');
                $('#conform').modal('hide');
                myfunction(per_page, page, search, search_key);

            },
            error: function(response) {
                $('#conform').modal('hide');
                $("#dynamic-submit1").attr("disabled", false);
                $.each(response.responseJSON.errors, function(field_name, error) {
                    $('#' + field_name + '-error').text(error[0]);
                });
            }
        });
    });
});

function isEmpty(value) {
    return value === null || value === undefined || value === '';
}

$(document).ready(function() {
    var $form = $('#dynamic-form1');
    $('#dynamic-submit1').prop('disabled', true);

    $form.on('change keyup paste', function(e) {
        let allValues = $(this).serializeArray();
        let formType = $("#id").val();
        if (formType) {
            $('#dynamic-submit1').prop('disabled', true);
        }

        var isAnyValueEmpty = allValues.some(function(obj) {
            for (var key in obj) {
                if (obj["name"] !== "id") {
                    if (obj.hasOwnProperty(key) &&
                        isEmpty(obj[key])) {
                        return true;
                    }
                }
            }
            return false;
        });

        $('#dynamic-submit1').prop('disabled', isAnyValueEmpty);
    });
});

</script>

 <script src="{{asset('js/qcTimepicker.min.js')}}"></script>
 <script src="{{asset('js/jquery.uploader.min.js')}}"></script>
 <script src="{{asset('js/jquery.viewbox.min.js')}}"></script>



