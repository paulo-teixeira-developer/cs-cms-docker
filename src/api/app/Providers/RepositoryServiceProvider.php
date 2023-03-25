<?php

namespace App\Providers;

use App\Interfaces\AuthRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\FileRepositoryInterface;
use App\Interfaces\PostRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\AuthRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\FileRepository;
use App\Repositories\PostRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
    }

    public function boot()
    {
        //
    }
}
