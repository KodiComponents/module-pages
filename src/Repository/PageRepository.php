<?php

namespace KodiCMS\Pages\Repository;

use Illuminate\Database\Query\Builder;
use KodiCMS\CMS\Repository\BaseRepository;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Model\PageSitemap;

class PageRepository extends BaseRepository
{
    /**
     * @param Page $model
     */
    public function __construct(Page $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnCreate(array $data = [])
    {
        $parent_id = (int) array_get($data, 'parent_id');
        $validator = $this->validator($data, [
            'title'  => 'required|max:32',
            'slug'   => "max:100|unique:pages,slug,NULL,id,parent_id,{$parent_id}",
            'status' => 'required|numeric',
        ]);

        return $this->_validate($validator);
    }

    /**
     * @param int $id
     * @param array   $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnUpdate($id, array $data = [])
    {
        $parent_id = (int) array_get($data, 'parent_id');

        $validator = $this->validator($data, [
            'title' => 'required|max:255',
            'slug'  => "max:100|unique:pages,slug,{$id},id,parent_id,{$parent_id}",
        ]);

        $validator->sometimes('status', 'required|numeric', function ($input) use ($id) {
            return $id > 1;
        });

        return $this->_validate($validator);
    }

    /**
     * @param bool $includeHidden
     *
     * @return \KodiCMS\Pages\Model\Sitemap
     */
    public function getSitemap($includeHidden = false)
    {
        return PageSitemap::get($includeHidden);
    }

    /**
     * @param int $pageId
     *
     * @return Builder
     */
    public function getChildrenByPageId($pageId)
    {
        return $this->model
            ->select()
            ->where('parent_id', (int) $pageId)
            ->orderBy('position', 'asc')
            ->orderBy('created_at', 'asc')
            ->get()
            ->keyBy('id')
            ->all();
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data = [])
    {
        return parent::create(array_only($data, [
            'title',
            'slug',
            'is_redirect',
            'breadcrumb',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'robots',
            'parent_id',
            'layout_file',
            'behavior',
            'status',
            'published_at',
            'redirect_url',
        ]));
    }

    /**
     * @param int   $id
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $data = [])
    {
        if (! isset($data['is_redirect'])) {
            $data['is_redirect'] = 0;
        }

        return parent::update($id, array_only($data, [
            'title',
            'slug',
            'is_redirect',
            'breadcrumb',
            'meta_title',
            'meta_keywords',
            'meta_description',
            'robots',
            'parent_id',
            'layout_file',
            'behavior',
            'status',
            'published_at',
            'redirect_url',
        ]));
    }

    /**
     * @param array $pages
     *
     * @return bool
     */
    public function reorder(array $pages)
    {
        return $this->model->reorder($pages);
    }

    /**
     * @param string $keyword
     *
     * @return array
     */
    public function searchByKeyword($keyword)
    {
        $pages = $this->model;

        if (strlen($keyword) == 2 and $keyword[0] == '.') {
            $page_status = [
                'd' => FrontendPage::STATUS_DRAFT,
                'p' => FrontendPage::STATUS_PUBLISHED,
                'h' => FrontendPage::STATUS_HIDDEN,
            ];

            if (isset($page_status[$keyword[1]])) {
                $pages->whereIn('status', $page_status[$keyword[1]]);
            }
        } else {
            $pages = $pages->searchByKeyword($keyword);
        }

        return $pages->get();
    }

    /**
     *
     * @return Page
     */
    public function getRootPage()
    {
        $page = $this->model->find(1);

        if (is_null($page)) {
            $page = $this->instance();
            $page->id = 1;
            $page->title = 'Root';
            $page->setIncrementing(false);

            $page->save();
        }

        return $page;
    }
}
