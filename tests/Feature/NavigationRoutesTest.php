<?php

use Illuminate\Support\Facades\Route;

describe('navigation routes', function () {
    it('registers the routes used by the navbar', function () {
        expect(Route::has('dashboard'))->toBeTrue()
            ->and(Route::has('schedule.index'))->toBeTrue()
            ->and(Route::has('todo.index'))->toBeTrue()
            ->and(Route::has('note.index'))->toBeTrue()
            ->and(Route::has('profile.edit'))->toBeTrue()
            ->and(Route::has('logout'))->toBeTrue();
    });
});
