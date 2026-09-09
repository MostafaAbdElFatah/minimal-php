<?php

declare(strict_types=1);

dataset('invalid emails', [
    'empty' => [''],
    'missing at sign' => ['not-an-email'],
    'missing domain' => ['a@'],
    'over 255 characters' => [str_repeat('a', 244).'@example.com'],
]);
