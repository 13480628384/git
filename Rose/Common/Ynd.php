<?php
class Ynd
{

    const SoapUrl = 'http://sra2015.jios.org:8888/ynd5/service/boxInf?wsdl';

    function __construct()
    {
        if (extension_loaded('soap')) {
            $this->client = new SoapClient(self::SoapUrl);
        }else{
            throw new Exception('no SoapClient extend');
            return false;
        }
    }
    public function award($type, $order, $lq, $cp, $pay, $price, $pruduct_par1)
    {
        return $this->client->award(
            array(
                'type' => $type,
                'order' => $order,
                'lq' => $lq,
                'cq' => $cp,
                'pay' => $pay,
                'price' => $price,
                'pruduct_par1' => $pruduct_par1
            )
        );
    }

    public function getProductInf($lq_sum, $cq_sum, $product_list)
    {
        return $this->client->getProductInf(
            array(
                'lq_sum' => $lq_sum,
                'cq_sum' => $cq_sum,
                'product_list' => $product_list
                /*
                array(
                    array(
                        'cq'	 => 1,
                        'lq'	=> 1,
                        'no'	=> 1,
                        'price'	=> 1,
                        'product_par1'=> 1
                    ),
                )
                */
        ));
    }

}

$Ynd = new Ynd();
$Ynd->award(1, 1, 1, 1, 1, 1, 1);
