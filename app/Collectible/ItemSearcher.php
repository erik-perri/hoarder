<?php

namespace App\Collectible;

use App\Models\Collectible;
use Illuminate\Database\Query\Builder;

// TODO Refactor so item and category filter are not in search so this could be passed to StockSearcher and reused?
class ItemSearcher
{
    /**
     * @param Collectible $collectible
     * @param array $categoryCriteria
     * @param array $itemCriteria
     * @param Builder|null $builder
     * @return Builder|null
     */
    public function search(
        Collectible $collectible,
        array $categoryCriteria,
        array $itemCriteria,
        Builder $builder = null
    ): ?Builder {
        if ($builder === null) {
            $builder = Collectible\Item::getQuery()->where('collectible_id', '=', $collectible->id);
        }

        [$itemFields, $categoryFields] = $this->getAvailableFields($collectible);

        return $this->applyCriteriaToBuilder($builder, $categoryCriteria, $categoryFields, $itemCriteria, $itemFields);
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
     * @param array $categoryCriteria
     * @param array $categoryFields
     * @param array $itemCriteria
     * @param array $itemFields
     * @return Builder|null
     */
    private function applyCriteriaToBuilder(
        Builder $builder,
        array $categoryCriteria,
        array $categoryFields,
        array $itemCriteria,
        array $itemFields
    ): ?Builder {
        if (empty($itemCriteria) && empty($categoryCriteria)) {
            return null;
        }

        $itemBuilder = new CriteriaBuilder($itemFields);

        if (! empty($categoryCriteria)) {
            $builder->whereIn('category_id', static function (Builder $builder) use ($categoryFilter, $categoryFields) {
                $categoryBuilder = new CriteriaBuilder($categoryFields);
                $categoryBuilder->apply(
                    $builder->select('id')->from('collectible_categories'),
                    false,
                    $categoryCriteria
                );
            });
        }

        if (! empty($itemCriteria)) {
            $itemBuilder->apply($builder, false, $itemCriteria);
        }

        return $builder;
    }
}
