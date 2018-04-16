<?php
class AlipayConfig{
    const APPID = "2016101802223028";//生产环境
    const PID = '2088421489854786';//合作伙伴身份（PID）:
    //const PRIVEKEYFILEPATH = 'key/rsa_private_key.pem';

    const PRIVEKEYFILEPATH = '/Alipay/key/rsa_private_key.pem';
    //const ALIPAYPUBLICKEY = 'key/alipay_public_key.pem';
    const ALIPAYPUBLICKEY = '/Alipay/key/alipay_public_key.pem';
    const GETWAPURL = 'https://openapi.alipay.com/gateway.do';
}
?>