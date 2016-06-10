<?php

namespace KodiCMS\Pages\Model;

use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * KodiCMS\Pages\Model\PagePart
 *
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $id
 * @property integer $page_id
 * @property string $name
 * @property string $wysiwyg
 * @property string $content
 * @property string $content_html
 * @property boolean $is_expanded
 * @property boolean $is_indexable
 * @property boolean $is_protected
 * @property integer $position
 * @property-read \KodiCMS\Pages\Model\Page $page
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PagePart whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PagePart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PagePart whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PagePart wherePageId($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PagePart whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PagePart whereWysiwyg($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PagePart whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PagePart whereContentHtml($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PagePart whereIsExpanded($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PagePart whereIsIndexable($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PagePart whereIsProtected($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PagePart wherePosition($value)
 */
class PagePart extends Model
{
    const PART_NOT_PROTECTED = 0;
    const PART_PROTECTED = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_parts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['page_id', 'name', 'wysiwyg', 'content', 'is_expanded', 'is_indexable', 'is_protected', 'position'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at', 'is_developer', 'is_protected', 'content_html'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_developer' => 'boolean',
        'is_protected' => 'boolean',
        'is_expanded' => 'boolean',
        'is_indexable' => 'boolean',
        'position' => 'integer',
        'page_id' => 'integer',
        'name' => 'string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    /**
     * TODO: сбрасывать кеширование частей страницы после сортировки.
     *
     * @param array $positions
     *
     * @return $this
     */
    public function reorder(array $positions)
    {
        foreach ($positions as $pos => $id) {
            DB::table($this->table)->where('id', $id)->update(['position' => (int) $pos]);
        }

        return $this;
    }
}
