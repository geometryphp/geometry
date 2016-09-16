<?php
use Octagon\Core\Registry;

$registry = Registry::getInstance();
$config = $registry->getConfig();
$siteTitle = $config->get('app_name');
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $siteTitle; ?></title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,400,700,900" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="center">
        <p class="center__line"><em class="center__emphasis">Geometry</em> is a PHP framework for web applications on apache/mysql/php stacks.</p>
    </div>
</body>
</html>
