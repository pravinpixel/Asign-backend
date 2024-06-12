<form id="dynamic-form" method="post">
    @csrf
     <input type="hidden" name="id" id="id" value="{{ $info->id ?? '' }}">
    <input type="hidden" name="title" id="title" value="{{ $title ?? '' }}">
    <div class="form-group mb-3">
        <label class="form-label">Tag</label>
      <div>
          <select class="form-select" name="tag">
            <option value="" selected>Select Tag</option>
            <option value="height"  @if(isset($info->tag) && $info->tag == 'height') selected @endif>Height</option>
            <option value="width" @if(isset($info->tag) && $info->tag == 'width') selected @endif >Width</option>
            <option value="depth" @if(isset($info->tag) && $info->tag == 'depth') selected @endif >Depth</option>
        </select>
    <span class="field-error" id="tag-error"></span>
      </div>
    </div>
<div class="form-group mb-3">
    <label class="form-label">Size From:</label>
    <input type="text" class="form-control" placeholder="Enter Size From" name="size_from" value="{{ $info->size_from ?? '' }}">
 <span class="field-error" id="size_from-error"></span>
</div>
<div class="form-group mb-3">
    <label class="form-label">Size To:</label>
    <input type="text" class="form-control" placeholder="Enter Size To" name="size_to" value="{{ $info->size_to ?? '' }}">
 <span class="field-error" id="size_to-error"></span>
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
    <button type="button" id="dynamic-submit" class="btn apply-btn">Save</button>
</div>
</form>

<script>
     $(".select2Box").select2({
      placeholder: "Select Status",
      minimumResultsForSearch: Infinity,
  });
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
                        if (obj.hasOwnProperty(key)
 && isEmpty(obj[key])) {
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