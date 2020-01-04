<?php
foreach(glob(__DIR__ . '/politics/*.csv') AS $csvFile) {
    if(strpos($csvFile, '第')) { continue; } // 不分區 / 總統副總統
    $fh = fopen($csvFile, 'r');
    $head = fgetcsv($fh, 2048);
    $pool = array();
    while($line = fgetcsv($fh, 2048)) {
        $data = array_combine($head, $line);
        $pool[$data['選區'] . '-' . $data['號次']] = $data;
    }
    $p = pathinfo($csvFile);
    if(false === strpos($csvFile, '原住民')) {
        $jsonFile = __DIR__ . '/data/區域立委/' . $p['filename'] . '.json';
        $json = json_decode(file_get_contents($jsonFile), true);
        foreach($json AS $k1 => $l1) {
            foreach($l1 AS $k2 => $l2) {
                foreach($l2['cands'] AS $k3 => $cand) {
                    $key = $l2['areaCode'] . '-' . $cand['candNo'];
                    $cand['educ'] = $pool[$key]['學歷'];
                    $cand['exp'] = $pool[$key]['經歷'];
                    $txtFile = __DIR__ . '/politics/' . $pool[$key]['縣市'] . '-' . $pool[$key]['姓名'] . '.txt';
                    if(file_exists($txtFile)) {
                        $cand['politics'] = file_get_contents($txtFile);
                    } 
                    $jpgFile = __DIR__ . '/politics/' . $pool[$key]['縣市'] . '-' . $pool[$key]['姓名'] . '.jpg';
                    if(file_exists($jpgFile)) {
                        $cand['politicsUrl'] = '/politics/' . $pool[$key]['縣市'] . '-' . $pool[$key]['姓名'] . '.jpg';
                    }
                    $json[$k1][$k2]['cands'][$k3] = $cand;
                }
            }
        }
    } else {
        $jsonFile = __DIR__ . '/data/' . $p['filename'] . '.json';
        $json = json_decode(file_get_contents($jsonFile), true);
        foreach($json AS $k1 => $l1) {
            foreach($l1 AS $k2 => $cand) {
                $key = '0-' . $cand['candNo'];
                $cand['educ'] = $pool[$key]['學歷'];
                $cand['exp'] = $pool[$key]['經歷'];
                $txtFile = __DIR__ . '/politics/' . $pool[$key]['縣市'] . '-' . $pool[$key]['姓名'] . '.txt';
                if(file_exists($txtFile)) {
                    $cand['politics'] = file_get_contents($txtFile);
                } else {
                    $jpgFile = __DIR__ . '/politics/' . $pool[$key]['縣市'] . '-' . $pool[$key]['姓名'] . '.jpg';
                    if(file_exists($jpgFile)) {
                        $cand['politicsUrl'] = '/politics/' . $pool[$key]['縣市'] . '-' . $pool[$key]['姓名'] . '.jpg';
                    }
                }
                $json[$k1][$k2] = $cand;
            }
        }
    }
    file_put_contents($jsonFile, json_encode($json,  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
