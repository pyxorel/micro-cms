{if !isset($doc)}{$doc=$docs|@current}{/if}
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta name="keywords" content="{if ($doc!=null)} {$doc->meta_keywords} {/if}" />
    <meta name="description" content="{if ($doc!=null)} {$doc->meta_description} {/if}"/>
    <link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700|PT+Sans+Narrow:400,700|PT+Serif:400,700&subset=latin,cyrillic'
        rel='stylesheet' type='text/css' />
{literal} 
    <script type="text/javascript">
    </script>
{/literal}
 <title>{block name=title}    
 			{if ($doc!=null)} {$doc->head} {/if}
 		{/block} 
 </title>
</head>
 
<body>
     {block name=content}{/block}
</body>
 
</html>