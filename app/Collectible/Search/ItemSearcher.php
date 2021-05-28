<?php

namespace App\Collectible\Search;

use App\Collectible\FieldFactory;
use App\Criteria\CriteriaApplier;
use App\Models\Collectible;
use Illuminate\Database\Query\Builder;

// TODO Refactor so item and category filter are not in search method so this could be passed to StockSearcher and
//      reused?
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

        $factory = new FieldFactory();
        $itemFields = $factory->createItemFields($collectible);
        $categoryFields = $factory->createCategoryFields($collectible);

        return $this->applyCriteriaToBuilder($builder, $categoryCriteria, $categoryFields, $itemCriteria, $itemFields);
    }

    /**
     * @param Builder $builder
     * @param array $categoryCriteria
     * @param array $categoryFields
     * @param array $itemCriteria
     * @param array $itemFields
     * @return Builder|null
     */
    public function applyCriteriaToBuilder(
        Builder $builder,
        array $categoryCriteria,
        array $categoryFields,
        array $itemCriteria,
        array $itemFields
    ): ?Builder {
        if (empty($itemCriteria) && empty($categoryCriteria)) {
            throw new \InvalidArgumentException('No criteria supplied');
        }

        if (! empty($categoryCriteria)) {
            $builder->whereIn('category_id',
                static function (Builder $builder) use ($categoryCriteria, $categoryFields) {
                    $applier = new CriteriaApplier($categoryFields);
                    $applier->apply(
                        $builder->select('id')->from('collectible_categories'),
                        false,
                        $categoryCriteria
                    );
                });
        }

        if (! empty($itemCriteria)) {
            $applier = new CriteriaApplier($itemFields);
            $applier->apply($builder, false, $itemCriteria);
        }

        return $builder;
    }
}
