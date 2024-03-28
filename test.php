<?php
echo "■■ 階層<br>";
echo __FILE__."<br>";
echo "<br>";
echo $_SERVER['HTTP_HOST']."<br>";
echo (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST']."<br>";
echo (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."<br>";
echo $_SERVER['SCRIPT_NAME']."<br>";
echo dirname($_SERVER['SCRIPT_NAME'])."<br>";
echo $_SERVER['REQUEST_URI']."<br>";
print_r(parse_url((empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']));
echo "<br>";
echo "<br>";
echo $_SERVER["PHP_SELF"]."<br>";
echo basename($_SERVER["PHP_SELF"])."<br>";
echo $_SERVER["QUERY_STRING"]."<br>";
echo basename(pathinfo(__FILE__)['dirname'])."<br>";
echo "<br>";
echo str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"])."<br>";
echo str_replace(basename(pathinfo(__FILE__)['dirname'])."/","",str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"])."<br>")."<br>";
echo "<br>";

echo "■■ 時間<br>";
echo date('Y-m-d H:i:s');
?>