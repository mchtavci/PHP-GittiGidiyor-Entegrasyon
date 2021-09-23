<?php
$appKey = "XXX";
$secretKey = "XXX";
$roleName = "XXX";
$rolePass = "XXX";

list($usec, $sec) = explode(" ", microtime());
$time = round(microtime(true) * 1000);
$sign = strtoupper(md5($appKey.$secretKey.$time));
$endDate = date('d/m/Y H:i:s');
$startDate = date("d/m/Y H:i:s", strtotime('-7 days'));

$status="V";
// S: Kargo Yapılacaklar
// C: Onay Bekleyenler
// P: Para transferleri
// O: Tamamlananlar
// V: Aktif Satışlar
// I: İptali beklenenler
// R: İptal olanlar / İade konumunda olanlar

$params = array(
"apiKey" => $appKey,
"sign" => $sign,
"time" => $time,
"withData" => true,
"byStatus" => $status,
"byUser" => "",
"orderBy" => "P",
"orderType" => "A",
"startDate" => $startDate,
"endDate" => $endDate,
"pageNumber" => 1,
"pageSize" => 100,
"lang" => 'tr'
);

$response = gg_connect($roleName,$rolePass,'getSalesByDateRange',$params,"http://dev.gittigidiyor.com:8080/listingapi/ws/IndividualSaleService?wsdl");
echo "<pre>";
print_r($response);
echo "</pre>";



function object_to_array($data)
{
	if (is_array($data) || is_object($data))
	{
		$result = array();
		foreach ($data as $key => $value)
		{
			$result[$key] = object_to_array($value);
		}
		return $result;
	}
	return $data;
}
function gg_connect($roleName,$rolePass,$action,array $params,$soap_url){
	$client = new SoapClient($soap_url, array('login' => $roleName, 'password' => $rolePass, 'authentication' => SOAP_AUTHENTICATION_BASIC));
	$response = object_to_array($client->__soapCall($action,$params));
	return $response;
}
?>