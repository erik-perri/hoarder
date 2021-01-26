<?php

namespace App\Models;

use App\Models\Collectible\Category;
use App\Models\Collectible\Field;
use App\Models\Collectible\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * App\Models\Collectible.
 *
 * @property int $id
 * @property int|null $created_by_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Category[] $categories
 * @property-read int|null $categories_count
 * @property-read User|null $createdBy
 * @property-read Collection|Field[] $fields
 * @property-read int|null $fields_count
 * @property-read Collection|Item[] $items
 * @property-read int|null $items_count
 * @method static Builder|Collectible newModelQuery()
 * @method static Builder|Collectible newQuery()
 * @method static Builder|Collectible query()
 * @method static Builder|Collectible whereCreatedAt($value)
 * @method static Builder|Collectible whereCreatedById($value)
 * @method static Builder|Collectible whereId($value)
 * @method static Builder|Collectible whereName($value)
 * @method static Builder|Collectible whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Collectible extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Collectible\Field::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Collectible\Category::class);
    }

    public function items(): HasManyThrough
    {
        return $this->hasManyThrough(Collectible\Item::class, Collectible\Category::class);
    }
}
