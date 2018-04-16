<?php
/*
 *  经纬度转换
 */
class Latlng
{

    static $pi = 3.14159265358979324;
    static $a = 6378245.0;
    static $ee = 0.00669342162296594323;

    public function FunctionName()
    {
        $this->x_pi = self::$pi * 3000.0 / 180.0;
    }

    function outOfChina($lat, $lon)
    {
        if ($lon < 72.004 || $lon > 137.8347)
            return true;
        if ($lat < 0.8293 || $lat > 55.8271)
            return true;
        return false;
    }

    function transformLat($x, $y)
    {
        $ret = -100.0 + 2.0 * $x + 3.0 * $y + 0.2 * $y * $y + 0.1 * $x * $y + 0.2 * sqrt(abs($x));
        $ret += (20.0 * sin(6.0 * $x * self::$pi) + 20.0 * sin(2.0 * $x * self::$pi)) * 2.0 / 3.0;
        $ret += (20.0 * sin($y * self::$pi) + 40.0 * sin($y / 3.0 * self::$pi)) * 2.0 / 3.0;
        $ret += (160.0 * sin($y / 12.0 * self::$pi) + 320 * sin($y * self::$pi / 30.0)) * 2.0 / 3.0;
        return $ret;
    }
    function transformLon($x, $y)
    {
        $ret = 300.0 + $x + 2.0 * $y + 0.1 * $x * $x + 0.1 * $x * $y + 0.1 * sqrt(abs($x));
        $ret += (20.0 * sin(6.0 * $x * self::$pi) + 20.0 * sin(2.0 * $x * self::$pi)) * 2.0 / 3.0;
        $ret += (20.0 * sin($x * self::$pi) + 40.0 * sin($x / 3.0 * self::$pi)) * 2.0 / 3.0;
        $ret += (150.0 * sin($x / 12.0 * self::$pi) + 300.0 * sin($x / 30.0 * self::$pi)) * 2.0 / 3.0;
        return $ret;
    }

    /**
     * 地球坐标转换为火星坐标
     * World Geodetic System ==> Mars Geodetic System
     *
     * @param wgLat  地球坐标
     * @param wgLon
     *
     * mglat,mglon 火星坐标
     */
    function transform2Mars($wgLat, $wgLon)
    {
        if ($this->outOfChina($wgLat, $wgLon))
        {
            return array(
                $wgLat,
                $wgLon
            );
        }
        $dLat = $this->transformLat($wgLon - 105.0, $wgLat - 35.0);
        $dLon = $this->transformLon($wgLon - 105.0, $wgLat - 35.0);
        $radLat = $wgLat / 180.0 * self::$pi;
        $magic = sin($radLat);
        $magic = 1 - self::$ee * $magic * $magic;
        $sqrtMagic = sqrt($magic);
        $dLat = ($dLat * 180.0) / ((self::$a * (1 - self::$ee)) / ($magic * $sqrtMagic) * self::$pi);
        $dLon = ($dLon * 180.0) / (self::$a / $sqrtMagic * cos($radLat) * self::$pi);
        return array(
            $wgLat + $dLat,
            $wgLon + $dLon
        );
    }

    /**
     * 火星坐标转换为百度坐标
     * @param gg_lat
     * @param gg_lon
     */
    function bd_encrypt($gg_lat, $gg_lon,&$bd_lat,&$bd_lon)
    {
        $x = $gg_lon; $y = $gg_lat;
        $z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * $this->x_pi);
        $theta = atan2($y, $x) + 0.000003 * cos($x * $this->x_pi);
        $bd_lon = $z * cos($theta) + 0.0065;
        $bd_lat = $z * sin($theta) + 0.006;
    }

    /**
     * 百度转火星
     * @param bd_lat
     * @param bd_lon
     */
    function bd_decrypt($bd_lat, $bd_lon,&$gg_lat,&$gg_lon)
    {
        $x = $bd_lon - 0.0065; $y = $bd_lat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $this->x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $this->x_pi);
        $gg_lon = $z * cos($theta);
        $gg_lat = $z * sin($theta);
    }


}


/*
$Latlng = new LatLng();
$Latlng->transform2Mars('22.5861733333333', '113.85916333333', $lat, $lng);
echo $lat;
echo "\n";
echo $lng;
*/


/*
int main() {
    double lat = 30.227607;
    double lon = 120.036565;

    //真实的经纬度转化为百度地图上的经纬度，便于计算百度POI
    double marsLat = 0;
    double marsLon = 0;
    double resultLat = 0;
    double resultLon = 0;
    transform2Mars(lat,lon,marsLat,marsLon);
    bd_encrypt(marsLat,marsLon,resultLat,resultLon);

    //30.2193456 120.0348264
    cout<<setprecision(10)<<resultLat<<" "<<setprecision(10)<<resultLon<<endl;

}
*/
