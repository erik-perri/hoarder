<?php

namespace App\Collectible;

use App\Models\Collection;
use App\Models\Collection\Stock;
use Illuminate\Database\Query\Builder;

class StockSearcher
{
    /**
     * @param Collection $collection
     * @param array $categoryFilter
     * @param array $itemFilter
     * @param array $stockFilter
     * @param Builder|null $builder
     * @return Builder|null
     */
    public function search(
        Collection $collection,
        array $categoryFilter,
        array $itemFilter,
        array $stockFilter,
        Builder $builder = null
    ): ?Builder {
        if (empty($categoryFilter) && empty($itemFilter)) {
            return null;
        }

        if ($builder === null) {
            $builder = Stock::getQuery()->where('collection_id', '=', $collection->id);
        }

        $builder->whereIn('item_id', function (Builder $builder) use ($collection, $categoryFilter, $itemFilter) {
            $builder->select('id')->from('collectible_items');

            $searcher = new ItemSearcher();
            $searcher->search($collection->collectible, $categoryFilter, $itemFilter, $builder);
        });

        // TODO Apply $stockFilter

        return $builder;
    }
}
