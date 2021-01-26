<?php

namespace App\Models\Collectible;

use App\Models\Collectible;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Collectible\Category.
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int $collectible_id
 * @property string $name
 * @property array $field_values
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category|null $child
 * @property-read Collectible $collectible
 * @property-read Collection|Item[] $items
 * @property-read int|null $items_count
 * @property-read Category|null $parent
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereCollectibleId($value)
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereFieldValues($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'collectible_categories';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'field_values' => 'array',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class);
    }

    public function child(): HasOne
    {
        return $this->hasOne(static::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function collectible(): BelongsTo
    {
        return $this->belongsTo(Collectible::class);
    }
}
