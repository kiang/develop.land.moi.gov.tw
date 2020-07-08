<?php
$cities = array(
    'C' => '基隆市',
    'A' => '臺北市',
    'F' => '新北市',
    'H' => '桃園市',
    'O' => '新竹市',
    'J' => '新竹縣',
    'K' => '苗栗縣',
    'B' => '臺中市',
    'M' => '南投縣',
    'N' => '彰化縣',
    'P' => '雲林縣',
    'I' => '嘉義市',
    'Q' => '嘉義縣',
    'D' => '臺南市',
    'E' => '高雄市',
    'T' => '屏東縣',
    'G' => '宜蘭縣',
    'U' => '花蓮縣',
    'V' => '臺東縣',
    'X' => '澎湖縣',
    'W' => '金門縣',
    'Z' => '連江縣',
);
$rawPath = dirname(__DIR__) . '/raw';
if(!file_exists($rawPath)) {
    mkdir($rawPath, 0777);
}
$dataPath = dirname(__DIR__) . '/data';
foreach($cities AS $code => $city) {
    $rawFile = $rawPath . '/' . $city . '.html';
    $targetPath = $dataPath . '/' . $city;
    if(!file_exists($targetPath)) {
        mkdir($targetPath, 0777, true);
    }
    if(!file_exists($rawFile)) {
        file_put_contents($rawFile, file_get_contents('https://develop.land.moi.gov.tw/DevelopmentZone_ajax_list?cityCode=' . $code));
    }
    $raw = file_get_contents($rawFile);
    $raw = substr($raw, strpos($raw, '<tbody>'));
    $lines = explode('</tr>', $raw);
    foreach($lines AS $line) {
        preg_match_all('/seqatr="([^"]+)"/', $line, $matches);
        if(!empty($matches[1][0])) {
            $key = $matches[1][0];
            $rawDetailFile = $rawPath . '/' . $key . '.html';
            if(!file_exists($rawDetailFile)) {
                file_put_contents($rawDetailFile, file_get_contents('https://develop.land.moi.gov.tw/DevelopmentZone_ajax_infoULR?seqid=' . $key));
            }
            $kmlTarget = $targetPath . '/' . $key . '.kml';
            if(!file_exists($kmlTarget)) {
                file_put_contents($kmlTarget, file_get_contents('https://develop.land.moi.gov.tw/DcDl_dlDcKml?pjId=' . $key));
            }
        }
    }
    // https://develop.land.moi.gov.tw/DcDl_dlDcKml?pjId=YCX6484632077
    // https://develop.land.moi.gov.tw/DevelopmentZone_ajax_infoULR?seqid=YCX6484632077
}
