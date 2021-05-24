<?php

namespace App\Models\Collection;

use App\Models\Collection;
use Database\Factories\Collection\GoalFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Collection\Goal.
 *
 * @property int $id
 * @property int $collection_id
 * @property string $name
 * @property array $category_criteria
 * @property array $item_criteria
 * @property array $stock_criteria
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection $collection
 * @method static GoalFactory factory(...$parameters)
 * @method static Builder|Goal newModelQuery()
 * @method static Builder|Goal newQuery()
 * @method static Builder|Goal query()
 * @method static Builder|Goal whereCategoryCriteria($value)
 * @method static Builder|Goal whereCollectionId($value)
 * @method static Builder|Goal whereCreatedAt($value)
 * @method static Builder|Goal whereId($value)
 * @method static Builder|Goal whereItemCriteria($value)
 * @method static Builder|Goal whereName($value)
 * @method static Builder|Goal whereStockCriteria($value)
 * @method static Builder|Goal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Goal extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'collection_goal';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'category_criteria' => 'array',
        'item_criteria' => 'array',
        'stock_criteria' => 'array',
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
