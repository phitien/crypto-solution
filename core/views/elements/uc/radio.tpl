{assign var='uuid' value=uniqid()}
<div class="input-field checkbox-field radio-field {(isset($class))?$class:''} {(isset($checked))?'checked':''}">
<input
  id="{$uuid}"
  type="radio"
  class="checkbox radio"
  name="{(isset($name))?$name:''}"
  value="{(isset($value))?$value:''}"
  {(isset($checked))?'checked':''}
  {(isset($disabled))?'disabled':''}
  {(isset($group))?$group:''}
  />
{if isset($placeholder)}<label for="{$uuid}">{$placeholder}</label>{/if}
</div>
