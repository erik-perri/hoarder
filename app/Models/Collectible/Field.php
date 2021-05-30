<?php

namespace App\Models\Collectible;

use App\Models\Collectible;
use Database\Factories\Collectible\FieldFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Collectible\Field.
 *
 * @property int $id
 * @property int $collectible_id
 * @property string $entity_type
 * @property string $code
 * @property string $name
 * @property string $input_type
 * @property array $input_options
 * @property int $is_required
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collectible $collectible
 * @method static FieldFactory factory(...$parameters)
 * @method static Builder|Field newModelQuery()
 * @method static Builder|Field newQuery()
 * @method static Builder|Field query()
 * @method static Builder|Field whereCode($value)
 * @method static Builder|Field whereCollectibleId($value)
 * @method static Builder|Field whereCreatedAt($value)
 * @method static Builder|Field whereEntityType($value)
 * @method static Builder|Field whereId($value)
 * @method static Builder|Field whereInputOptions($value)
 * @method static Builder|Field whereInputType($value)
 * @method static Builder|Field whereIsRequired($value)
 * @method static Builder|Field whereName($value)
 * @method static Builder|Field whereUpdatedAt($value)
 * @method static Builder|Field whereUuid($value)
 * @mixin \Eloquent
 */
class Field extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'collectible_fields';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'input_options' => 'array',
        'is_required' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'collectible_id',
        'created_at',
        'updated_at',
    ];

    public function collectible(): BelongsTo
    {
        return $this->belongsTo(Collectible::class);
    }
}
