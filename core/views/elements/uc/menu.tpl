<ul class="menu {$class}">
{foreach from=$items item=o}
  {assign var='url' value=Url::get($o, 'menu')}
  <li>
    {if $url}<a href="{Url::get($o)}">{/if}
      <span>{$o['name']}</span>
    {if $url}</a>{/if}
    {if $recursive}
      {Template::renderMenu(['parent_id' => $o['id'], 'recursive' => $recursive, 'max' => $max - 1])}
    {/if}
  </li>
{/foreach}
</ul>
