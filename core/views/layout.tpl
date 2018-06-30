<!DOCTYPE HTML>
<html lang="{$lang}">
<head>
<title>{$title}</title>
<meta charset="{$charset}"/>
<meta name="description" content="{$description}"/>
{$meta}
{$favicon}
{$script}
{$style}
</head>
<body>
<noscript>{$noscript}</noscript>
<div id="application" class="application page {$pageClassName}">
  <div class="container-fluid header">{$header}</div>
  <div class="container-fluid body">
    {$left}
    <div class="container content">
      {$content}
    </div>
    {$right}
  </div>
  <div class="container-fluid footer">{$footer}</div>
</div>
{$debug}
{$bottomscript}
</body>
</html>
