<?php
$fh = fopen(__DIR__ . '/politics/高雄市.csv', 'w');
fputcsv($fh, array('縣市', '選區', '號次', '姓名', '生日', '性別', '出生地', '政黨', '學歷', '經歷'));
$c = file_get_contents(__DIR__ . '/politics/高雄市.txt');
$parts = explode("高雄市\t\t", $c);
$zone = 0;
foreach($parts AS $part) {
    $fields = explode("\t", $part);
    if(isset($fields[5])) {
        $fields[5] = preg_replace('/( +\n)/', "\n", $fields[5]);
        $blocks = explode("\n\n", trim($fields[5]));
        if(!isset($blocks[2])) {
            $blocks[2] = '';
        }
        switch($fields[1]) {
            case '晏揚清':
                $zone = 1;
            break;
            case '黃韻涵':
                $zone = 2;
            break;
            case '柳淑芳':
                $zone = 3;
            break;
            case '林岱樺':
                $zone = 4;
            break;
            case '李佳玲':
                $zone = 5;
            break;
            case '李鎔任':
                $zone = 6;
            break;
            case '李雅靜':
                $zone = 7;
            break;
            case '敖博勝':
                $zone = 8;
            break;
        }
        fputcsv($fh, array('高雄市', $zone, $fields[0], $fields[1], $fields[2], $fields[3], $fields[4], $blocks[0], $blocks[1], $blocks[2]));
        if(isset($blocks[3])) {
            unset($blocks[0]);
            unset($blocks[1]);
            unset($blocks[2]);
            file_put_contents(__DIR__ . '/politics/高雄市-' . $fields[1] . '.txt', implode("\n\n", $blocks));
        }
    }
}