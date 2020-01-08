<?php
foreach(glob(__DIR__ . '/politics/*.csv') AS $csvFile) {
    $p = pathinfo($csvFile);
    $json = false;
    if(false !== strpos($csvFile, '總統副總統')) {
        $fh = fopen($csvFile, 'r');
        $head = fgetcsv($fh, 2048);
        $pool = array();
        while($line = fgetcsv($fh, 2048)) {
            $data = array_combine($head, $line);
            $pool[$data['姓名']] = $data;
        }
        $jsonFile = __DIR__ . '/data/總統副總統.json';
        $json = json_decode(file_get_contents($jsonFile), true);
        foreach($json['P1'] AS $k => $cand) {
            $cand['educ'] = $pool[$cand['name']]['選舉公報學歷'];
            $cand['exp'] = $pool[$cand['name']]['選舉公報經歷'];
            $json['P1'][$k] = $cand;
        }
    } elseif(false !== strpos($csvFile, '全國不分區')) {
        $pFh = fopen(__DIR__ . '/spreadsheets/第10屆全國不分區及僑居國外國民立法委員選舉公報_政見.csv', 'r');
        $pHead = fgetcsv($pFh, 2048);
        $politics = array();
        while($line = fgetcsv($pFh, 2048)) {
            $politics[$line[0]] = $line[2];
        }
        $fh = fopen($csvFile, 'r');
        $head = fgetcsv($fh, 2048);
        $pool = array();
        while($line = fgetcsv($fh, 2048)) {
            $data = array_combine($head, $line);
            $key = $data['政黨'] . trim($data['排序']);
            $pool[$key] = $data;
        }
        $jsonFile = __DIR__ . '/data/不分區候選人.json';
        $json = json_decode(file_get_contents($jsonFile), true);
        foreach($json['L4'] AS $k1 => $l1) {
            if(isset($politics[$l1['partyNo']])) {
                $json['L4'][$k1]['politics'] = $politics[$l1['partyNo']];
            }
            foreach($l1['cands'] AS $k2 => $cand) {
                $key = $l1['partyName'] . $cand['candNo'];
                $cand['educ'] = $pool[$key]['學歷'];
                $cand['exp'] = $pool[$key]['經歷'];
                $json['L4'][$k1]['cands'][$k2] = $cand;
            }
        }
    } elseif(false !== strpos($csvFile, '原住民')) {
        $fh = fopen($csvFile, 'r');
        $head = fgetcsv($fh, 2048);
        $pool = array();
        while($line = fgetcsv($fh, 2048)) {
            $data = array_combine($head, $line);
            $pool[$data['選區'] . '-' . $data['號次']] = $data;
        }
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
    } else {
        $fh = fopen($csvFile, 'r');
        $head = fgetcsv($fh, 2048);
        $pool = array();
        while($line = fgetcsv($fh, 2048)) {
            $data = array_combine($head, $line);
            $pool[$data['選區'] . '-' . $data['號次']] = $data;
        }
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
    }
    if(false !== $json) {
        file_put_contents($jsonFile, json_encode($json,  JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}
