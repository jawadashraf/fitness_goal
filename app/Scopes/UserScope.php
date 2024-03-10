<?php

namespace App\Scopes;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
class UserScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where(function($query) {
            $query->where('user_id', Auth::id())
                ->orWhereNull('user_id');
        });
    }
}
