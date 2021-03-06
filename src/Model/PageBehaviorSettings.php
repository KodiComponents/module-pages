<?php

namespace KodiCMS\Pages\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * KodiCMS\Pages\Model\PageBehaviorSettings
 *
 * @property integer $page_id
 * @property string $settings
 * @property-read \KodiCMS\Pages\Model\Page $page
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PageBehaviorSettings wherePageId($value)
 * @method static \Illuminate\Database\Query\Builder|\KodiCMS\Pages\Model\PageBehaviorSettings whereSettings($value)
 */
class PageBehaviorSettings extends Model
{
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'page_id';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'page_behavior_settings';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['settings'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'page_id' => 'integer',
        'settings' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
