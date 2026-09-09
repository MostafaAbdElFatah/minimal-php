<?php

use Tests\TestCase;

it('keeps the login page markup stable', function () {
    /** @var TestCase $this */
    $html = $this->get('/login')->getContent();
    $normalizedHtml = preg_replace('/value="[^"]*"/', 'value="[normalized]"', $html);

    expect($normalizedHtml)->toMatchSnapshot();
})->group('feature');
