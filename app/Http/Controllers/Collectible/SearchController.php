<?php

namespace App\Http\Controllers\Collectible;

use App\Collectible\CriteriaFieldFactory;
use App\Collectible\Search\ItemSearcher;
use App\Http\Controllers\Controller;
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
     * @return Response|View
     * @throws \JsonException
     */
    public function search(Collectible $collectible, Request $request, CriteriaFieldFactory $fieldFactory): View
    {
        $categoryFields = $fieldFactory->getCategoryFieldInfo($collectible);
        $categoryCriteria = $this->getCriteriaFromRequest($request, 'category_criteria');

        $itemFields = $fieldFactory->getItemFieldInfo($collectible);
        $itemCriteria = $this->getCriteriaFromRequest($request, 'item_criteria');

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

        return view('collectible.search', [
            'collectible' => $collectible,
            'categoryFields' => $categoryFields,
            'categoryCriteria' => $categoryCriteria,
            'itemFields' => $itemFields,
            'itemCriteria' => $itemCriteria,
            'results' => $builder->paginate(30),
        ]);
    }

    /**
     * @param Request $request
     * @param string $requestKey
     * @return array
     * @throws \JsonException
     */
    private function getCriteriaFromRequest(Request $request, string $requestKey): array
    {
        $input = trim($request->get($requestKey));
        if (! $input) {
            return [];
        }

        return json_decode($input, true, 32, JSON_THROW_ON_ERROR);
    }
}
