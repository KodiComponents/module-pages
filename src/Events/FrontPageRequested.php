<?php

namespace KodiCMS\Pages\Events;

class FrontPageRequested
{
    /**
     * @var string
     */
    private $slug;

    /**
     * FrontPageRequested constructor.
     *
     * @param string $slug
     */
    public function __construct($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
