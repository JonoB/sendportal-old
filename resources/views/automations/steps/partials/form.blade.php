{!! Form::textField('name', 'Automation Step Name', isset($automationStep->name) ? $automationStep->name : null) !!}
{!! Form::checkboxField('use-delay', 'Delay', null, true, ['onclick' => 'toggleDelayInput()']) !!}
<div id="choose-delay" class="choose-delay hidden">
    Send email <input name="delay" type="text"
                      value="{{ isset($automationStep->delay) ? $automationStep->delay : null }}">
    <select name="delay_unit">
        @foreach($automationUnits as $index => $value)
            <option value="{{ $index }}" {{ isset($automationStep->delay_unit) && $index == $automationStep->delay_unit ? 'selected' : '' }}>{{ $value }}</option>
        @endforeach
    </select> after a contact subscribers to list one.
</div>

<script>

  window.onload = function (e) {
    var delayCheckbox = document.getElementById("id-field-use-delay");
    var delayInput = document.getElementById("choose-delay");
    if (delayCheckbox.checked) {
      delayInput.classList.remove('hidden');
    }
  }

  function toggleDelayInput() {
    var delayCheckbox = document.getElementById("id-field-use-delay");
    var delayInput = document.getElementById("choose-delay");

    if (delayCheckbox.checked == true) {
      delayInput.classList.remove('hidden');
    } else {
      delayInput.classList.add('hidden');
    }
  }
</script>