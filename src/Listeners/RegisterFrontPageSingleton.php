<?php

namespace KodiCMS\Pages\Listeners;

use KodiCMS\Pages\Events\FrontPageFound;

class RegisterFrontPageSingleton
{

    /**
     * @param FrontPageFound $event
     */
    public function handle(FrontPageFound $event)
    {
        app()->singleton('frontpage', function () use ($event) {
            return $event->getPage();
        });
    }
}
