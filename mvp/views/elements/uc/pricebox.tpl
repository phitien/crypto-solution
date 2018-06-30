<div class="pricebox {($change >= 0)?'up':'down'}">
  <div class="currency price">{number_format($price, 2)}</div>
  <div class="space"></div>
  <div class="show_chart"><i class="material-icons">trending_up</i></div>
  <div class="up_down">
    <i class="material-icons">{($change >= 0)?'arrow_upward':'arrow_downward'}</i>
    <div class="percentage change">{$change|abs}</div>
  </div>
</div>
