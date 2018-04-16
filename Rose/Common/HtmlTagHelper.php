<?php
/**
 *  HTML模板tag生成类
 **/
class HtmlTagHelper {
    /**
     *  生成select框
     *  @param $aOptions array  选项值
     *  @param $sName    string select框name值
     *  @param $mAttr    mixed  属性值
     *  @param $default  mixed  默认值
     *  @param $bPrint   mixed  默认值
     **/
    public static function select($aOptions, $sName, $mAttr=null, $default=null, $bPrint=true)
    {
        $sAttr = '';
        if (is_array($mAttr)) {
            foreach ($mAttr as $key => $val) {
                $sAttr .= "{$key}='{$val}' ";
            }
        }elseif($mAttr !== null) {
            $sAttr = $mAttr;
        }else{
            $sAttr = "class='{$sName}'";
        }
        $sHtml = sprintf("<select name='%s' %s>", $sName, $sAttr);
        foreach ($aOptions as $val => $name) {
            if ($val == $default) {
                $sHtml .= "<option value='{$val}' selected>{$name}</option>";
            }else {
                $sHtml .= "<option value='{$val}'>{$name}</option>";
            }
        }
        $sHtml .= "</select>";
        if (!$bPrint) return $sHtml;
        echo $sHtml;
    }
}

