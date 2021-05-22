<?php

namespace App\Models\Collectible;

use App\Models\User;
use Database\Factories\Collectible\StockFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Collectible\Stock.
 *
 * @property int $id
 * @property int $user_id
 * @property int $item_id
 * @property int $count
 * @property string $condition
 * @property string $language
 * @property array $tags
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Item $item
 * @property-read User $owner
 * @method static StockFactory factory(...$parameters)
 * @method static Builder|Stock newModelQuery()
 * @method static Builder|Stock newQuery()
 * @method static Builder|Stock query()
 * @method static Builder|Stock whereCondition($value)
 * @method static Builder|Stock whereCount($value)
 * @method static Builder|Stock whereCreatedAt($value)
 * @method static Builder|Stock whereId($value)
 * @method static Builder|Stock whereItemId($value)
 * @method static Builder|Stock whereLanguage($value)
 * @method static Builder|Stock whereTags($value)
 * @method static Builder|Stock whereUpdatedAt($value)
 * @method static Builder|Stock whereUserId($value)
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
    protected $table = 'collectible_stock';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tags' => 'array',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
