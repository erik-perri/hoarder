<?php

namespace App\Http\Controllers\Collectible;

use App\Collectible\CriteriaFieldFactory;
use App\Collectible\Search\ItemSearcher;
use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponseFactory;
use App\Models\Collectible;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SearchController extends Controller
{
    /**
     * @param Collectible $collectible
     * @param Request $request
     * @param CriteriaFieldFactory $fieldFactory
     * @param ApiResponseFactory $responseFactory
     * @return Response|View
     */
    public function search(
        Collectible $collectible,
        Request $request,
        CriteriaFieldFactory $fieldFactory,
        ApiResponseFactory $responseFactory
    ) {
        $categoryFields = $fieldFactory->getCategoryFieldInfo($collectible);
        $categoryCriteria = $request->get('category_criteria');

        $itemFields = $fieldFactory->getItemFieldInfo($collectible);
        $itemCriteria = $request->get('item_criteria');

        $searcher = new ItemSearcher();

        $builder = Collectible\Item::getQuery()->where('collectible_id', '=', $collectible->id);

        if (! empty($categoryCriteria) || ! empty($itemCriteria)) {
            $searcher->applyCriteriaToBuilder(
                $builder,
                $categoryCriteria,
                $categoryFields,
                $itemCriteria,
                $itemFields
            );
        }

        if ($request->expectsJson()) {
            $items = $builder->paginate(30);

            return $responseFactory->createListFromPaginator($items);
        }

        return view('collectible.search', [
            'collectible' => $collectible,
            'categoryFields' => $categoryFields,
            'categoryCriteria' => $categoryCriteria,
            'itemFields' => $itemFields,
            'itemCriteria' => $itemCriteria,
            'results' => $builder->paginate(30),
        ]);
    }
}
