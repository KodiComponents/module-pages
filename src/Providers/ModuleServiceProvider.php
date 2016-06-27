<?php

namespace KodiCMS\Pages\Providers;

use KodiCMS\Pages\Console\Commands\RebuildLayoutBlocksCommand;
use KodiCMS\Pages\Facades\Block as BlockFacade;
use KodiCMS\Pages\Facades\Frontpage;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Model\PagePart as PagePartModel;
use KodiCMS\Pages\Observers\PageObserver;
use KodiCMS\Pages\Observers\PagePartObserver;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Users\Model\Permission;
use KodiCMS\Widgets\WidgetType;

class ModuleServiceProvider extends ServiceProvider
{

    public function boot()
    {
        app('view')->addNamespace('layouts', layouts_path());

        Page::observe(new PageObserver);
        PagePartModel::observe(new PagePartObserver);

        $this->registerWidgets();
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

        Permission::register('pages', 'page', ['reorder', 'list', 'edit', 'create', 'delete',]);
        Permission::register('pages', 'layout', ['add', 'list', 'rebuild', 'edit', 'view', 'delete',]);
        Permission::register('pages', 'part', ['reorder', 'list', 'create', 'delete',]);
    }

    public function contextBackend()
    {
        $root = \Navigation::addPage([
            'id' => 'pages',
            'title' => 'pages::core.title.pages.list',
            'url' => route('backend.page.list'),
            'permissions' => 'page::list',
            'priority' => 100,
            'icon' => 'sitemap',
        ]);

        if ($page = \Navigation::getPages()->findById('design')) {
            $page->addPage([
                'id' => 'layouts',
                'title' => 'pages::core.title.layouts.list',
                'url' => route('backend.layout.list'),
                'permissions' => 'layout::list',
                'priority' => 100,
                'icon' => 'desktop',
            ]);
        }
    }

    private function registerWidgets()
    {
        $this->app['widget.manager']
            ->registerWidget(new WidgetType('page.menu', 'pages::widgets.page_menu.title', 'KodiCMS\Pages\Widget\PageMenu', 'Page'))
            ->registerWidget(new WidgetType('page.list', 'pages::widgets.page_list.title', 'KodiCMS\Pages\Widget\PageList', 'Page'))
            ->registerWidget(new WidgetType('page.breadcrumbs', 'pages::widgets.page_breadcrumbs.title', 'KodiCMS\Pages\Widget\PageBreadcrumbs', 'Page'));
    }
}
