<div class="marketprice">
  <div class="heading">{t('Market Price')}</div>
  <div class="items">
  {foreach from=$marketprices item=o}
    <div class="item {($o['change'] >= 0)?'up':'down'}" data-id="{$o['id']}">
      <div class="image" style="background-image: url({$o['image']})"></div>
      <div class="name">{$o['name']}</div>
      {$space}
      {Template::renderUC('pricebox', $o)}
    </div>
  {/foreach}
  </div>
</div>
