<?php
$cities = array(
    '63000' => '臺北市',
    '65000' => '新北市',
    '68000' => '桃園市',
    '66000' => '臺中市',
    '67000' => '臺南市',
    '64000' => '高雄市',
    '10004' => '新竹縣',
    '10005' => '苗栗縣',
    '10007' => '彰化縣',
    '10008' => '南投縣',
    '10009' => '雲林縣',
    '10010' => '嘉義縣',
    '10013' => '屏東縣',
    '10002' => '宜蘭縣',
    '10015' => '花蓮縣',
    '10014' => '臺東縣',
    '10016' => '澎湖縣',
    '10017' => '基隆市',
    '10018' => '新竹市',
    '10020' => '嘉義市',
    '09020' => '金門縣',
    '09007' => '連江縣',
);

$sources = array(
    '行政區' => 'https://www.cec.gov.tw/data/json/area/<city>.json',
    '投開票所' => 'https://www.cec.gov.tw/data/json/tbox/<city>.json',
    '區域立委' => 'https://www.cec.gov.tw/data/json/cand/L1/<city>.json',
    '總統副總統' => 'https://www.cec.gov.tw/data/json/cand/P1/000.json',
    '平地原住民' => 'https://www.cec.gov.tw/data/json/cand/L2/000.json',
    '山地原住民' => 'https://www.cec.gov.tw/data/json/cand/L3/000.json',
    '不分區候選人' => 'https://www.cec.gov.tw/data/json/cand/L4/000.json',
    '政見發表會影音清單' => 'https://www.cec.gov.tw/data/json/politics.json',
    '政黨清冊' => 'https://www.cec.gov.tw/data/json/party.json',
);

$dataPath = __DIR__ . '/data';
foreach($sources AS $dir => $source) {
    if(false !== strpos($source, '<city>')) {
        $targetPath = $dataPath . '/' . $dir;
        if(!file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
        }
        foreach($cities AS $code => $city) {
            file_put_contents($targetPath . '/' . $city . '.json', file_get_contents(str_replace('<city>', $code, $source)));
        }
    } else {
        file_put_contents($dataPath . '/' . $dir . '.json', file_get_contents($source));
    }
}