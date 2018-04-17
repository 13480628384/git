<?php

	/**********************************************************************
	*  mysql 数据库初始化
	*/

    define('ROSE_WXPAY_PATH',   dirname(__FILE__) . DIRECTORY_SEPARATOR);
    define('IN_ROSE_WXPAY', true);

	// Include ezSQL core
	include_once "ez_sql_core.php";

	// Include ezSQL database specific component
	include_once "ez_sql_mysql.php";

    //加载配置文件
    function load_config($file) {
        static $configs = array();
        $path = ROSE_WXPAY_PATH.$file.".php";
        if (is_file($path)) {
            $configs[$file] = include $path;
            return $configs[$file];
        }
    }
    //数据库配置
    $config  = load_config('configread');


	// Initialise database object and establish a connection
	// at the same time - db_user / db_password / db_name / db_host
	$db = new ezSQL_mysql($config['username'],$config['password'],
        $config['database_name'],$config['host']);
		

// where组装
function get_where($parms)
{
    $sql = '';
    foreach ( $parms as $field => $val )
    {
        if ( $val === 'true' ) $val = 1;
        if ( $val === 'false' ) $val = 0;

        if ( $val == 'NOW()' )
        {
            $sql .= "$field = ".$val." and ";
        }
        else
        {
            $sql .= "$field = '".$val."' and ";
        }
    }

    return substr($sql,0,-4);
}


//拼接update set语句
function get_set($params){
	$i = 0;
    $data = '';
	foreach( $params as $key=>$val )
	{
		if(isset($val))
		{
			$val = $val;
			if($i==0&&$val!==null)
			{
				$data = $key."='".$val."'";
			}
			else
			{
				$data .= ",".$key." = '".$val."'";
			}
			$i++;
		}
	} 
	
	return $data;
}





?>