<div class="controlbar">
  {Template::renderUC('select',['name'=>'sortby','placeholder'=>'Sort by','options'=>$sortby_options])}
  {$space}
  {Template::renderUC('checkbox',['name'=>'import','value'=>'1','placeholder'=>'Import'])}
  {Template::renderUC('checkbox',['name'=>'export','value'=>'1','placeholder'=>'Export'])}
  {Template::renderUC('select',['name'=>'country','placeholder'=>'Country','options'=>$countries])}
</div>
