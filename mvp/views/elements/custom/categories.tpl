<div class="categories">
  <div class="heading">{t('Categories')}</div>
  <div class="horizontal">
    <div class="items">
      {foreach from=$categories item=o}
      {assign var='url' value=Url::get($o, 'category')}
      <div class="item" data-id="{$o['id']}">
        <a href="{$url}"><div class="name">
          <span>{$o['name']}</span>
        </div></a>
      </div>
      {/foreach}
    </div>
    <div class="slide">
      <img alt="Categories Slide" src="static/images/categories.jpg"/>
    </div>
  </div>
</div>
