<form id="dynamic-form" method="post">
    @csrf
     <input type="hidden" name="id" id="id" value="{{ $info->id ?? '' }}">
    <input type="hidden" name="title" id="title" value="{{ $title ?? '' }}">
    <div class="form-group mb-3">
        <div class="form-group">
              <label class="form-label">Question:</label>
              <div class="w100Select">
                <select class="select2Box"  name="question">
                  <option value=""></option>
                  <option value="object_match"  @if(isset($info->question) && $info->question == 'object_match') selected @endif> Object Match</option>
                <option value="damage" @if(isset($info->question) && $info->question == 'damage') selected @endif >Damage</option>
                <option value="asign_protect+" @if(isset($info->question) && $info->question == 'asign_protect+') selected @endif >Assign Protect+</option>
                <option value="surface_condition" @if(isset($info->question) && $info->question == 'surface_condition') selected @endif >Surface Condition</option>
            </select>
              </div>
            </div>
        </div>
    <div class="form-group mb-3">
        <div class="form-group">
              <label class="form-label">Answer Type:</label>
              <div class="w100Select">
                <select class="select2Box"  name="answer_type">
                  <option value=""></option>
                  <option value="yes"  @if(isset($info->answer_type) && $info->answer_type == 'yes') selected @endif>Yes</option>
                <option value="no" @if(isset($info->answer_type) && $info->answer_type == 'no') selected @endif >No</option>
                </select>
              </div>
            </div>
        </div>
<div class="form-group mb-3">
    <label class="form-label">Name :</label>
    <input type="text" class="form-control" placeholder="Enter Name" name="name" value="{{ $info->name ?? '' }}">
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