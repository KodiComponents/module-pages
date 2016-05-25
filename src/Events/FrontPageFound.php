<?php

namespace KodiCMS\Pages\Events;

use KodiCMS\Pages\Contracts\FrontendPageInterface;

class FrontPageFound
{

    /**
     * @var FrontendPageInterface
     */
    private $page;

    /**
     * FrontPageFound constructor.
     *
     * @param FrontendPageInterface $page
     */
    public function __construct(FrontendPageInterface $page)
    {
        $this->page = $page;
    }

    /**
     * @return FrontendPageInterface
     */
    public function getPage()
    {
        return $this->page;
    }
}
