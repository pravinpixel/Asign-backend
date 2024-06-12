<form id="dynamic-form" method="post">
    @csrf
     <input type="hidden" name="id"  id="id" value="{{ $info->id ?? '' }}">
    <input type="hidden" name="title" id="title" value="{{ $title ?? '' }}">
    <div class="form-group mb-3">
        <div class="form-group">
          <label class="form-label">Country</label>
          <div class="w100Select">
            <select class="select2Box1"  name="country_id">
              <option value=""></option>
              @foreach($countries as $country)
              <option value="{{ $country->id }}" @if($info && $info->country_id == $country->id) selected @endif>
                  {{ $country->name }}
              </option>
          @endforeach
            </select>
          </div>
        </div>
        <span class="field-error" id="country_id-error"></span>
      </div>
      <div class="form-group mb-3">
        <div class="form-group">
          <label class="form-label">State</label>
          <div class="w100Select">
            <select class="select2Box2"  name="state_id">
              <option value=""></option>
              @foreach($states as $state)
              <option value="{{ $state->id }}" @if($info && $info->state_id == $state->id) selected @endif>
                  {{ $state->name }}
              </option>
          @endforeach
            </select>
          </div>
        </div>
        <span class="field-error" id="state_id-error"></span>
    </div>
     <div class="form-group mb-3">
        <label class="form-label">City Name:</label>
        <input type="text" class="form-control" placeholder="Enter City name"   name="name" value="{{ $info->name ?? '' }}">
     <span class="field-error" id="name-error"></span>
    </div>
    <div class="form-group mb-3">
        <label class="form-label">City Code:</label>
        <input type="text" class="form-control" placeholder="Enter City code"  name="code" value="{{ $info->code ?? '' }}">
    <span class="field-error" id="code-error"></span>
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
        <span class="field-error" id="status-error"></span>
      </div>
      <div class="form-group mb-3">
        <div class="form-group  radio-chechbox">
          <label class="form-label">Serviceable</label>
          <div class="redes-checkbox">
                                <input class="form-check-input" type="radio"   name="is_serviceable" value="1" 
                                 id="flexRadioDefault1" 
                                 @if(isset($info->is_serviceable)) {{($info->is_serviceable == 1)?'checked':''}} 
                                 @endif
                                 >
                                <label class="form-check-label" for="flexRadioDefault1">
                                    Yes
                                </label>
                    </div>
          <div class="redes-checkbox">
                                <input class="form-check-input" type="radio"   name="is_serviceable" value="0"   
                                  id="flexRadioDefault2" 
                              
                              @if(isset($info->is_serviceable)) {{($info->is_serviceable == 0)?'checked':''}}
                              @else  checked @endif
                                 >
                                <label class="form-check-label" for="flexRadioDefault2" >
                                    No
                                </label>
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
  $(".select2Box1").select2({
      placeholder: "Select Country",
      minimumResultsForSearch: Infinity,
  });
  $(".select2Box2").select2({
      placeholder: "Select State",
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
        
        $('select[name="country_id"]').on('change', function () {
            var selectedCountry = $(this).val();
            $.ajax({
                url: "{{ route('city.getState') }}",
                method: 'GET',
                data: { country: selectedCountry },
                success: function (response) {
                    if (response.states) {
                        $('select[name="state_id"]').empty().append('<option value="" selected>Choose State</option>');
                        $.each(response.states, function (key, state) {
                            $('select[name="state_id"]').append('<option value="' + state.id + '">' + state.name + '</option>');
                        });
                    }
                },
                error: function (error) {
                    console.log('Error fetching states:', error);
                }
            });
        });
    });
</script>

 