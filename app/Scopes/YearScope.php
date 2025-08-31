<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Request;


class YearScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $year = request()->input('year', now()->year);
        $builder->whereYear('created_at', $year);
    }
}
