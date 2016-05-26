<?php

namespace KodiCMS\Pages\Contracts\Behavior;

interface BehaviorPageInterface
{
    /**
     * @return array
     */
    public function getBehaviorSettings();

    /**
     * @return null|string
     */
    public function getBehavior();

    /**
     * @return BehaviorInterface
     */
    public function getBehaviorObject();

    /**
     * @return bool
     */
    public function hasBehavior();

    /**
     * @return string
     */
    public function getBehaviorTitle();
}
