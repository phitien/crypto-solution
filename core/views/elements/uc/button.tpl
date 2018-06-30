{assign var='uuid' value=uniqid()}
<button class="button {(isset($class))?$class:''} {(isset($disabled))?'disabled':''}">
  {if isset($icon)}<i class="material-icons">{$icon}{/if}
  {(isset($text))?$text:''}
  {if isset($iconright)}<i class="material-icons">{$iconright}{/if}
</button>
