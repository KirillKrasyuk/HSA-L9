<?php

$dir = './data';

$files = scandir($dir);

$words = [];

foreach ($files as $file) {
    if (\in_array($file, ['.', '..'])) {
        continue;
    }

    $fileToRead = fopen(sprintf('%s/%s', $dir, $file), 'r');

    if($fileToRead !== false) {
        while(($data = fgetcsv($fileToRead, 100, ',')) !== false){
            for($i = 0; $i < count($data); $i++) {
                $words[] = $data[$i];
            }
        }

        fclose($fileToRead);
    }
}

$file = fopen('./data.json', 'w');

$index = 1;

foreach ($words as $word) {
    if (!$word) {
        continue;
    }

    fwrite(
        $file,
        json_encode([
            'create' => [
                '_index' => 'words',
                '_id' => $index
            ]
        ])
    );
    fwrite(
        $file,
        "\n"
    );
    fwrite(
        $file,
        json_encode([
            'id' => $index,
            'word' => trim($word)
        ])
    );
    fwrite(
        $file,
        "\n"
    );

    $index++;
}

fclose($file);