<?php

namespace App\Collectible\Search;

use App\Models\Collection;
use App\Models\Collection\Stock;
use Illuminate\Database\Query\Builder;

class StockSearcher
{
    /**
     * @param Collection $collection
     * @param array $categoryCriteria
     * @param array $itemCriteria
     * @param array $stockCriteria
     * @param Builder|null $builder
     * @return Builder|null
     */
    public function search(
        Collection $collection,
        array $categoryCriteria,
        array $itemCriteria,
        array $stockCriteria,
        Builder $builder = null
    ): ?Builder {
        if (empty($categoryCriteria) && empty($itemCriteria)) {
            return null;
        }

        if ($builder === null) {
            $builder = Stock::getQuery()->where('collection_id', '=', $collection->id);
        }

        $builder->whereIn('item_id', function (Builder $builder) use ($collection, $categoryCriteria, $itemCriteria) {
            $builder->select('id')->from('collectible_items');

            $searcher = new ItemSearcher();
            $searcher->search($collection->collectible, $categoryCriteria, $itemCriteria, $builder);
        });

        // TODO Apply $stockCriteria

        return $builder;
    }
}
