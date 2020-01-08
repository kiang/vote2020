<?php
$keywords = array('遊具', '公園', '兒童', '共融', '罐頭', '遊戲');
$pool = array();
foreach($keywords AS $keyword) {
    $json = json_decode(file_get_contents(__DIR__ . '/data/山地原住民.json'), true);
    foreach($json['L3'] AS $candidate) {
        if(false !== strpos($candidate['politics'], $keyword)) {
            $key = "{$candidate['type']}_{$candidate['candNo']}_{$candidate['name']}";
            if(!isset($pool[$key])) {
                $pool[$key] = $candidate['politics'];
            }
            $pool[$key] = str_replace($keyword, " >>{$keyword}<< ", $pool[$key]);
        }
    }
    $json = json_decode(file_get_contents(__DIR__ . '/data/平地原住民.json'), true);
    foreach($json['L2'] AS $candidate) {
        if(false !== strpos($candidate['politics'], $keyword)) {
            $key = "{$candidate['type']}_{$candidate['candNo']}_{$candidate['name']}";
            if(!isset($pool[$key])) {
                $pool[$key] = $candidate['politics'];
            }
            $pool[$key] = str_replace($keyword, " >>{$keyword}<< ", $pool[$key]);
        }
    }
    $json = json_decode(file_get_contents(__DIR__ . '/data/不分區候選人.json'), true);
    foreach($json['L4'] AS $candidate) {
        if(false !== strpos($candidate['politics'], $keyword)) {
            $key = "不分區_{$candidate['partyNo']}_{$candidate['partyName']}";
            if(!isset($pool[$key])) {
                $pool[$key] = $candidate['politics'];
            }
            $pool[$key] = str_replace($keyword, " >>{$keyword}<< ", $pool[$key]);
        }
    }
    foreach(glob(__DIR__ . '/data/區域立委/*.json') AS $jsonFile) {
        $json = json_decode(file_get_contents($jsonFile), true);
        foreach($json['L1'] AS $zone) {
            foreach($zone['cands'] AS $candidate) {
                if(false !== strpos($candidate['politics'], $keyword)) {
                    $key = "{$candidate['area']}_{$candidate['candNo']}_{$candidate['name']}";
                    if(!isset($pool[$key])) {
                        $pool[$key] = $candidate['politics'];
                    }
                    $pool[$key] = str_replace($keyword, " >>{$keyword}<< ", $pool[$key]);
                }
            }
        }
    }
}
$fh = fopen(__DIR__ . '/filter/parks.csv', 'w');
fputcsv($fh, array('候選人', '政見'));
foreach($pool AS $candidate => $politics) {
    fputcsv($fh, array($candidate, $politics));
}