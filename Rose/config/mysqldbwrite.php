<?php
    $dbde = include_once "db.php";
	/**********************************************************************
	*  mysql 数据库初始化
	*/

    define('ROSE_WXPAY_PATH',   dirname(__FILE__) . DIRECTORY_SEPARATOR);
    define('IN_ROSE_WXPAY', true);

	// Include ezSQL core
	include_once "ez_sql_core.php";

	// Include ezSQL database specific component
	include_once "ez_sql_mysql.php";

	// Initialise database object and establish a connection
	// at the same time - db_user / db_password / db_name / db_host
	$db = new ezSQL_mysql($dbde['DB_USER'],$dbde['DB_PWD'],
        $dbde['DB_NAME'],$dbde['DB_HOST']);
    /*$db = new ezSQL_mysql('cdb_outerroot','chw2016=',
    'weiste','573bd98146cf7.gz.cdb.myqcloud.com:11781');*/
		

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