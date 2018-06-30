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
  <div class="container-fluid body">
    <div class="container content">
      <form>
        {Template::renderUC('input', ['name' => 'email', 'type' => 'email', 'placeholder' => 'Your email'])}
        {Template::renderUC('input', ['name' => 'password', 'type' => 'password', 'placeholder' => 'Password'])}
        {Template::renderUC('button', ['text' => 'Login'])}
      </form>
    </div>
  </div>
</div>
{$debug}
{$bottomscript}
</body>
</html>
