<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Overview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', User::count())->description(' New Users that have Joined')->descriptionIcon('heroicon-o-users'),
            Stat::make('Category', Category::count())->description(' Total Categories')->descriptionIcon('heroicon-o-numbered-list'),
            Stat::make('Posts', Post::count())->description(' Total Posts')->descriptionIcon('heroicon-o-arrow-up-on-square-stack'),
            Stat::make('Comment', Comment::count())->description(' Total Comments')->descriptionIcon('heroicon-o-rectangle-stack'),
        ];
    }
}
