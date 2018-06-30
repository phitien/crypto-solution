<div class="categories_items">
  {foreach from=$categories_items item=o}
  {if count($o['items'])}
  {assign var='url' value=Url::get($o, 'category')}
  <a href="{$url}"><div class="category-image" style="background-image:url({$o['image']})"></div></a>
  <div class="category" data-id="{$o['id']}">
    <h3 class="name"><a href="{$url}"><span>{$o['name']}</span></a></h3>
    <div class="items" data-id="{$o['id']}">
      {foreach from=$o['items'] item=$i}
      <div class="item {($i['change'] >= 0)?'up':'down'}" data-id="{$i['id']}">
        <div class="image" style="background-image: url({$i['image']})"></div>
        <div class="name"><a href=""><span>{$i['name']}</span></a></div>
        {Template::renderUC('pricebox', $i)}
      </div>
      {/foreach}
    </div>
  </div>
  {/if}
  {/foreach}
</div>
