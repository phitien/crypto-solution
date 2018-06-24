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
<div id="application1" class="container-fluid application">
  <div class="container page {$pageClassName}">
    {$header}
    {$left}
    {$content}
    {$right}
    {$footer}
  </div>
</div>
{$debug}
{$bottomscript}
</body>
</html>
