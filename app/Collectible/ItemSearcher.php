<?php

namespace App\Collectible;

use App\Models\Collectible;
use Illuminate\Database\Query\Builder;

// TODO Refactor so item and category filter are not in search so this could be passed to StockSearcher and reused?
class ItemSearcher
{
    /**
     * @param Collectible $collectible
     * @param array $categoryFilter
     * @param array $itemFilter
     * @param Builder|null $builder
     * @return Builder|null
     */
    public function search(
        Collectible $collectible,
        array $categoryFilter,
        array $itemFilter,
        Builder $builder = null
    ): ?Builder {
        if ($builder === null) {
            $builder = Collectible\Item::getQuery()->where('collectible_id', '=', $collectible->id);
        }

        [$itemFields, $categoryFields] = $this->getAvailableFields($collectible);

        return $this->applyFilterToBuilder($builder, $categoryFilter, $categoryFields, $itemFilter, $itemFields);
    }

    /**
     * @param Collectible $collectible
     * @return array
     */
    private function getAvailableFields(Collectible $collectible): array
    {
        $fields = $collectible->fields->groupBy('entity_type');

        /* @noinspection StaticInvocationViaThisInspection */
        return [
            $fields['item']->pluck('code')->toArray(),
            $fields['category']->pluck('code')->toArray(),
        ];
    }

    /**
     * @param Builder $builder
     * @param array $categoryFilter
     * @param array $categoryFields
     * @param array $itemFilter
     * @param array $itemFields
     * @return Builder|null
     */
    private function applyFilterToBuilder(
        Builder $builder,
        array $categoryFilter,
        array $categoryFields,
        array $itemFilter,
        array $itemFields
    ): ?Builder {
        if (empty($itemFilter) && empty($categoryFilter)) {
            return null;
        }

        $criteria = new CriteriaBuilder($itemFields);

        if (! empty($categoryFilter)) {
            $builder->whereIn('category_id', static function (Builder $builder) use ($categoryFilter, $categoryFields) {
                $categoryCriteria = new CriteriaBuilder($categoryFields);
                $categoryCriteria->apply(
                    $builder->select('id')->from('collectible_categories'),
                    false,
                    $categoryFilter
                );
            });
        }

        if (! empty($itemFilter)) {
            $criteria->apply($builder, false, $itemFilter);
        }

        return $builder;
    }
}
