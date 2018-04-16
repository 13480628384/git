<h1>错误</h1>
<style type="text/css">
	li {margin: 20px 0;}
</style>

<ul>
<?php
echo '<li><b>错误信息：</b>' . $e['message'] . '</li>'; 
echo '<li><b>文件：</b>' . $e['file'] . '</li>'; 
echo '<li><b>行数：</b>' . $e['line'] . '</li>'; 
echo '<li><b>trace信息：</b>' . $e['trace'] . '</li>'; 
?>
</ul>
