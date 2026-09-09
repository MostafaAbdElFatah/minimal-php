<?php

declare(strict_types=1);

dataset('idea page sizes', [
    'exactly one page' => [10, 1],
    'one item over a page' => [11, 2],
    'three pages' => [25, 3],
]);
