<?php

declare(strict_types=1);

foreach (glob('*.jpeg') as $filename) {
    $update = strtolower(str_replace('\'', '', str_replace(' ', '-', $filename)));
    echo "{$filename} -> {$update}\n";
    passthru("mv -i '{$filename}' '{$update}'");
}
