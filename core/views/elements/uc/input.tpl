{assign var='uuid' value=uniqid()}
<div class="input-field text-field {(isset($class))?$class:''}">
{if isset($label)}<label for="{$uuid}">{$label}</label>{/if}
<input
  id="{$uuid}"
  type="{(isset($type))?$type:'text'}"
  class="text"
  name="{(isset($name))?$name:''}"
  placeholder="{(isset($placeholder))?$placeholder:''}"
  value="{(isset($value))?$value:''}"
  {(isset($disabled))?'disabled':''}
  />
</div>
