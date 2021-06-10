<?php

namespace App\Models\Collectible;

use App\Models\Collectible;
use Database\Factories\Collectible\ItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Collectible\Item.
 *
 * @property int $id
 * @property int $collectible_id
 * @property int $category_id
 * @property int|null $parent_id
 * @property string $name
 * @property array $field_values
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category $category
 * @property-read Item|null $child
 * @property-read Collectible $collectible
 * @property-read Collection|Field[] $fields
 * @property-read int|null $fields_count
 * @property-read Item|null $parent
 * @method static ItemFactory factory(...$parameters)
 * @method static Builder|Item newModelQuery()
 * @method static Builder|Item newQuery()
 * @method static Builder|Item query()
 * @method static Builder|Item whereCategoryId($value)
 * @method static Builder|Item whereCollectibleId($value)
 * @method static Builder|Item whereCreatedAt($value)
 * @method static Builder|Item whereFieldValues($value)
 * @method static Builder|Item whereId($value)
 * @method static Builder|Item whereName($value)
 * @method static Builder|Item whereParentId($value)
 * @method static Builder|Item whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Item extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'collectible_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'field_values' => 'array',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'parent',
        'child',
        'category',
        'collectible',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class);
    }

    public function child(): HasOne
    {
        return $this->hasOne(static::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function collectible(): BelongsTo
    {
        return $this->belongsTo(Collectible::class);
    }

    public function fields(): HasManyThrough
    {
        return $this->hasManyThrough(
            Field::class,
            Collectible::class,
            'id',
            'collectible_id',
            'collectible_id'
        )->where('entity_type', '=', 'item');
    }
}
