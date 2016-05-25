<?php

namespace KodiCMS\Pages\Listeners;

use KodiCMS\Pages\Events\FrontPageFound;

class PopulateFrontPageMetadataToLayout
{

    /**
     * @param FrontPageFound $event
     */
    public function handle(FrontPageFound $event)
    {
        app('assets.meta')->setMetaData(
            $event->getPage()
        );
    }
}
