<?php
$aHelper = array(
    'HtmlTagHelper',
    'Arr',
    'func',
    'code',
    'WifiPrint',
    'cash',
    'Shopusers',
    'Latlng',
    'Excel',
    'FileLocal',
    'Vanke',
    'DownloadFileFromServer',
    'Calender'
);
foreach($aHelper as $k => $sFile) {
    $sFileName = sprintf('%s/%s%s',dirname(__FILE__),$sFile,'.php');
    if (file_exists($sFileName)){
        include_once $sFileName;
    }
}
