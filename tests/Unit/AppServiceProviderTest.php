<?php

use Illuminate\Database\Eloquent\Model;

test('the application prevents lazy loading', function () {
    expect(Model::preventsLazyLoading())->toBeTrue();
});
