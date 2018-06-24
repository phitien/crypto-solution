<ul class="debug">
{foreach from=$logs key=k item=v}
  {if is_numeric($k)}
  <li class="line">{if is_string($v)}{$v}{else}<pre>{json_encode($v, 128)}</pre>{/if}</li>
  {else}
  <li class="section {$k}">
    <b>{$k|ucfirst}</b>
    <ul>
    {foreach from=$v item=v1}
      <li class="line">{if is_array($v1)}<pre>{json_encode($v1, 128)}</pre>{elseif is_string($v1)}{$v1}{/if}</li>
    {/foreach}
    </ul>
  </li>
  {/if}
{/foreach}
</ul>
