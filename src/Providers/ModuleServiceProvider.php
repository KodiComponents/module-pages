<?php

namespace KodiCMS\Pages\Providers;

use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Repository\PageRepository;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Pages\Facades\Frontpage;
use KodiCMS\Pages\Observers\PageObserver;
use KodiCMS\Pages\Observers\PagePartObserver;
use KodiCMS\Pages\Facades\Block as BlockFacade;
use KodiCMS\Pages\Model\PagePart as PagePartModel;
use KodiCMS\Pages\Console\Commands\RebuildLayoutBlocksCommand;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app('view')->addNamespace('layouts', layouts_path());

        Page::observe(new PageObserver);
        PagePartModel::observe(new PagePartObserver);
    }

    public function register()
    {
        $this->registerAliases([
            'Frontpage' => Frontpage::class,
            'Block' => BlockFacade::class,
        ]);

        $this->registerProviders([
            BladeServiceProvider::class,
            EventServiceProvider::class,
        ]);

        $this->registerConsoleCommand(RebuildLayoutBlocksCommand::class);
    }

    public function contextBackend()
    {
        $root = \Navigation::addPage([
            'id' => 'pages',
            'title' => 'pages::core.title.pages.list',
            'url' => route('backend.page.list'),
            'permissions' => 'page.index',
            'priority' => 100,
            'icon' => 'sitemap',
        ]);

        if ($page = \Navigation::getPages()->findById('design')) {
            $page->addPage([
                'id' => 'layouts',
                'title' => 'pages::core.title.layouts.list',
                'url' => route('backend.layout.list'),
                'permissions' => 'layout.index',
                'priority' => 100,
                'icon' => 'desktop',
            ]);
        }
    }
}
