<?php

use Illuminate\Support\Facades\Route;
use Dgtlss\Scribe\Controllers\DocController;
use Dgtlss\Scribe\Controllers\SearchController;

Route::get('docs/{path?}', [DocController::class, 'show'])
     ->where('path', '.*')
     ->name('scribe.show');

Route::get('docs/search/{path?}', [SearchController::class, 'search'])
     ->where('path', '.*')
     ->name('scribe.search');