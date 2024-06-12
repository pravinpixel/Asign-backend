<form id="dynamic-form" method="post">
    @csrf
     <input type="hidden" name="id" id="id" value="{{ $info->id ?? '' }}">
     <input type="hidden" name="title" id="title" value="{{ $title ?? '' }}">
<div class="form-group mb-3">
    <label class="form-label">Location Name:</label>
    <input type="text" class="form-control" placeholder="Location  name" name="name" value="{{ $info->name ?? '' }}">
 <span class="field-error" id="name-error"></span>
</div>
<div class="form-group mb-3">
    <label class="form-label">Status</label>
  <div>
      <select class="form-select" name="status">
        <option value="" selected>Select status</option>
        <option value="1"  @if(isset($info->status) && $info->status == 1) selected @endif>Active</option>
        <option value="0" @if(isset($info->status) && $info->status == 0) selected @endif >Inactive</option>
    </select>
<span class="field-error" id="status-error"></span>
  </div>
</div>
<div class="footerbtnDiv">
    <button type="button" id="dynamic-submit" class="btn apply-btn">Save</button>
</div>
</form>
<script>
    function isEmpty(value) {
        return value === null || value === undefined || value === '';
    }
    $(document).ready(function () {
        var $form = $('#dynamic-form');        
        $('#dynamic-submit').prop('disabled', true);
        $form.on('change keyup paste', function(e) {
            let allValues = $(this).serializeArray();
            let formType = $("#id").val();
            if(formType){
                $('#dynamic-submit').prop('disabled', true);
            }
            var isAnyValueEmpty = allValues.some(function(obj) {
                for (var key in obj) {
                    if(obj["name"]!=="id"){
                        if (obj.hasOwnProperty(key) && isEmpty(obj[key])) {
                            return true;
                        }
                    }                                     
                }
                return false;
            });
            $('#dynamic-submit').prop('disabled', isAnyValueEmpty);
        });
    });
</script>