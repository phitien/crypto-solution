{assign var='uuid' value=uniqid()}
{if isset($value)}
{assign var='value' value=$value}
{else}
{assign var='value' value=''}
{/if}
<div class="input-field select-field {(isset($class))?$class:''}">
{if isset($label)}<label for="{$uuid}">{$label}</label>{/if}
<select
  id="{$uuid}"
  class="select"
  name="{(isset($name))?$name:''}"
  {(isset($disabled))?'disabled':''}
>
  {if isset($placeholder)}<option>{$placeholder}</option>{/if}
  {foreach from=$options item=o}
    <option value="{$o['value']}" {($o['value']==$value)?'selected':''}>{$o['text']}</option>
  {/foreach}
</select>
</div>
