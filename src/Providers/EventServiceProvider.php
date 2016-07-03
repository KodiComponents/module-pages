<?php

namespace KodiCMS\Pages\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use KodiCMS\Pages\Behavior\Manager as BehaviorManager;
use WYSIWYG;

class EventServiceProvider extends BaseEventServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \KodiCMS\Pages\Events\FrontPageNotFound::class => [],
        \KodiCMS\Pages\Events\FrontPageRequested::class => [],
        \KodiCMS\Pages\Events\FrontPageFound::class => [
            100 => \KodiCMS\Pages\Listeners\PlacePagePartsToBlocksEventHandler::class,
            200 => \KodiCMS\Pages\Listeners\PopulateFrontPageMetadataToLayout::class,
            10000 => \KodiCMS\Pages\Listeners\RegisterFrontPageSingleton::class,
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  DispatcherContract $events
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        foreach ($this->listens() as $event => $listeners) {
            foreach ($listeners as $priority => $listener) {
                $events->listen($event, $listener, $priority);
            }
        }

        $events->listen('config.loaded', function () {
            BehaviorManager::init();
        });

        app('view')->addNamespace('layouts', layouts_path());

        $events->listen('view.page.edit', function ($page) {
            WYSIWYG::loadAllEditors();
            echo view('pages::parts.list')->with('page', $page);
        }, 999);
    }
}
