<?php

namespace App\Models;

use App\Models\Collection\Stock;
use Database\Factories\CollectionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Collection.
 *
 * @property int $id
 * @property int $user_id
 * @property int $collectible_id
 * @property string $name
 * @property bool $is_default
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collectible $collectible
 * @property-read \Illuminate\Database\Eloquent\Collection|Stock[] $stock
 * @property-read int|null $stock_count
 * @property-read User $user
 * @method static CollectionFactory factory(...$parameters)
 * @method static Builder|Collection newModelQuery()
 * @method static Builder|Collection newQuery()
 * @method static Builder|Collection query()
 * @method static Builder|Collection whereCollectibleId($value)
 * @method static Builder|Collection whereCreatedAt($value)
 * @method static Builder|Collection whereId($value)
 * @method static Builder|Collection whereIsDefault($value)
 * @method static Builder|Collection whereName($value)
 * @method static Builder|Collection whereUpdatedAt($value)
 * @method static Builder|Collection whereUserId($value)
 * @mixin \Eloquent
 */
class Collection extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_default',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
        'collectible',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function collectible(): BelongsTo
    {
        return $this->belongsTo(Collectible::class);
    }

    public function stock(): HasMany
    {
        return $this->hasMany(Collection\Stock::class);
    }
}
