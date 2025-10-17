<?php

return [
    App\Providers\AppServiceProvider::class,
    \App\Domain\Press\Providers\PressServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    \App\Domain\Comment\Providers\CommentServiceProvider::class,
];
