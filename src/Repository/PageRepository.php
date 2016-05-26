<?php

namespace KodiCMS\Pages\Repository;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
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
     * @param Request $request
     */
    public function validateOnCreate(Request $request)
    {
        $parentId = (int) $request->get('parent_id');

        $this->validate($request, [
            'title' => 'required|max:32',
            'slug' => "max:100|unique:pages,slug,NULL,id,parent_id,{$parentId}",
            'status' => 'required|numeric',
        ]);
    }

    /**
     * @param int     $id
     * @param Request $request
     */
    public function validateOnUpdate($id, Request $request)
    {
        $parentId = (int) $request->get('parent_id');

        $validator = $this->getValidationFactory()->make($request->all(), [
            'title' => 'required|max:255',
            'slug' => "max:100|unique:pages,slug,{$id},id,parent_id,{$parentId}",
        ]);

        $validator->sometimes('status', 'required|numeric', function ($input) use ($id) {
            return $id > 1;
        });

        $this->validateWith($validator, $request);
    }

    /**
     * @param bool $includeHidden
     *
     * @return \KodiCMS\Pages\Model\PageSitemap
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

        return parent::update($id, $data);
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
     * @return Collection|Page[]
     */
    public function searchByKeyword($keyword)
    {
        /** @var Page $query */
        $query = $this->model->query();

        if (strlen($keyword) == 2 and $keyword[0] == '.') {
            $pageStatus = [
                'd' => FrontendPage::STATUS_DRAFT,
                'p' => FrontendPage::STATUS_PUBLISHED,
                'h' => FrontendPage::STATUS_HIDDEN,
            ];

            if (isset($pageStatus[$keyword[1]])) {
                $query->whereIn('status', $pageStatus[$keyword[1]]);
            }

            return $query->get();
        }

        return $query->searchByKeyword($keyword)->get();
    }

    /**
     * @return Page
     */
    public function getRootPage()
    {
        /** @var Page $page */
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
