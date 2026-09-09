<?php

declare(strict_types=1);

dataset('invalid passwords', [
    'empty' => ['', 'The password field is required.'],
    'shorter than 8 characters' => ['short12', 'The password field must be at least 8 characters.'],
]);
