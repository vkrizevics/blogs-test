<?php
namespace App\Http\Controllers\Traits;
use Illuminate\Support\Facades\Auth;

trait IsAuthorTrait {
    protected function isAuthor(object $model): bool
    {
        return Auth::check() && $model->user->id === Auth::id();
    }
}
