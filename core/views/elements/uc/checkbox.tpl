{assign var='uuid' value=uniqid()}
<div class="input-field checkbox-field {(isset($class))?$class:''} {(isset($checked))?'checked':''}">
<input
  id="{$uuid}"
  type="checkbox"
  class="checkbox"
  name="{(isset($name))?$name:''}"
  value="{(isset($value))?$value:''}"
  {(isset($checked))?'checked':''}
  {(isset($disabled))?'disabled':''}
  {(isset($group))?$group:''}
  />
{if isset($placeholder)}<label for="{$uuid}">{$placeholder}</label>{/if}
</div>
