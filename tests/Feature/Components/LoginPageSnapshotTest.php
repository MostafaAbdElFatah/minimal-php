<?php

declare(strict_types=1);

it('keeps the login page markup stable', function () {
    $html = $this->get(route('login'))->getContent();
    $normalizedHtml = preg_replace('/value="[^"]*"/', 'value="[normalized]"', $html);

    expect($normalizedHtml)->toMatchSnapshot();
})->group('feature', 'components');
