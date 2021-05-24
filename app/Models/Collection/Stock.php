<?php

namespace App\Models\Collection;

use App\Models\Collectible\Item;
use App\Models\Collection;
use Database\Factories\Collection\StockFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Collection\Stock.
 *
 * @property int $id
 * @property int $collection_id
 * @property int $item_id
 * @property int $count
 * @property string $condition
 * @property string $language
 * @property array $tags
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection $collection
 * @property-read Item $item
 * @method static StockFactory factory(...$parameters)
 * @method static Builder|Stock newModelQuery()
 * @method static Builder|Stock newQuery()
 * @method static Builder|Stock query()
 * @method static Builder|Stock whereCollectionId($value)
 * @method static Builder|Stock whereCondition($value)
 * @method static Builder|Stock whereCount($value)
 * @method static Builder|Stock whereCreatedAt($value)
 * @method static Builder|Stock whereId($value)
 * @method static Builder|Stock whereItemId($value)
 * @method static Builder|Stock whereLanguage($value)
 * @method static Builder|Stock whereTags($value)
 * @method static Builder|Stock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Stock extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'collection_stock';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tags' => 'array',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'item',
        'item.category',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
