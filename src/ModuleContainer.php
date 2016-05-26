<?php

namespace KodiCMS\Pages;

use Event;
use Illuminate\Routing\Router;
use KodiCMS\Support\Loader\ModuleContainer as BaseModuleContainer;

class ModuleContainer extends BaseModuleContainer
{
    /**
     * @param Router $router
     */
    protected function loadSystemRoutes(Router $router)
    {
        if (! cms_installed()) {
            return;
        }

        Event::listen('routes.loaded', function () use ($router) {
            $router->get('{slug?}', [
                'as' => 'frontend.url',
                'middleware' => ['web', 'context:frontend'],
                'uses' => 'KodiCMS\Pages\Http\Controllers\FrontendController@run',
            ])->where('slug', '.*');
        }, 999);
    }
}
