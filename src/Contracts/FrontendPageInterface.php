<?php

namespace KodiCMS\Pages\Contracts;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Query\Builder;
use KodiCMS\Assets\Contracts\MetaDataInterface;
use KodiCMS\CMS\Breadcrumbs\Collection as Breadcrumbs;
use KodiCMS\Pages\Contracts\Behavior\BehaviorPageInterface;
use KodiCMS\Pages\Model\Layout;
use KodiCMS\Users\Model\User;

interface FrontendPageInterface extends Arrayable, Jsonable, MetaDataInterface, BehaviorPageInterface
{

    /**
     * @param FrontendPageInterface $parentPage
     *
     * @return $this
     */
    public function setParentPage(FrontendPageInterface $parentPage = null);

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int|null
     */
    public function getParentId();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getLayout();

    /**
     * @return Layout
     */
    public function getLayoutFile();

    /**
     * @return \Illuminate\View\View|null
     */
    public function getLayoutView();

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @return string
     */
    public function getBreadcrumb();

    /**
     * @param int $level
     *
     * @return Breadcrumbs
     */
    public function getBreadcrumbs($level = 0);

    /**
     * @return Carbon
     */
    public function getCreatedAt();

    /**
     * @return Carbon
     */
    public function getUpdatedAt();

    /**
     * @return Carbon
     */
    public function getPublishedAt();

    /**
     * @return User
     */
    public function getCreatedBy();

    /**
     * @return User
     */
    public function getUpdatedBy();

    /**
     * @param null|integer $level
     *
     * @return FrontendPageInterface|null
     */
    public function getParent($level = null);

    /**
     * @return string
     */
    public function getUri();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return int
     */
    public function getLevel();

    /**
     * @return string
     */
    public function getMime();

    /**
     * @param null|string $label
     * @param array|null $attributes
     * @param bool $checkCurrent
     *
     * @return string
     */
    public function getAnchor($label = null, array $attributes = null, $checkCurrent = true);

    /**
     * @return bool
     */
    public function isRedirect();

    /**
     * @return string
     */
    public function getRedirectUrl();

    /**
     * @param string|array $key
     * @param null|string $value
     * @param null|string $field
     *
     * @return $this
     */
    public function setMetaParams($key, $value = null, $field = null);

    /**
     * @return array
     */
    public function getMetaParams();

    /**
     * @param string $key
     * @param mixed $default
     *
     * @return $this
     */
    public function getMetaParam($key, $default = null);

    /**
     * @return bool
     */
    public function isActive();

    /**
     * @param bool $includeHidden
     *
     * @return int
     */
    public function childrenCount($includeHidden = false);

    /**
     * @param bool $includeHidden
     *
     * @return array
     */
    public function getChildren($includeHidden = false);

    /**
     * @param bool $includeHidden
     *
     * @return Builder
     */
    public function getChildrenQuery($includeHidden = false);

    /**
     * @return string
     */
    public function __toString();

}