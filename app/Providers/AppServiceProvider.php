<?php

namespace App\Providers;
use View;
use Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\MenuPrivileges;
use App\Models\Menu;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('*', function ($view)
        {
            if(Auth::check()){
                $operationrights = CommonHelper::operations_rights(Auth::user()->company_id);
                $view->with('operation_rights', $operationrights);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
