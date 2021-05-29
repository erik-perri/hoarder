<?php

namespace App\Http\Controllers\Collectible;

use App\Collectible\FieldFactory;
use App\Collectible\Search\ItemSearcher;
use App\Criteria\Comparison\Boolean;
use App\Criteria\Comparison\Text;
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
     * @param FieldFactory $fieldFactory
     * @return Response|View
     * @throws \JsonException
     */
    public function search(Collectible $collectible, Request $request, FieldFactory $fieldFactory): View
    {
        if ($collectible->id === 1) {
            $this->setupTestRequest($request);
        }

        $categoryFields = $fieldFactory->createCategoryFields($collectible);
        $categoryCriteria = $this->getCriteriaFromRequest($request, 'category_criteria');

        $itemFields = $fieldFactory->createItemFields($collectible);
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

    /**
     * TODO Remove this method, it is just to make working on the search page and filter builder easier.
     * @param Request $request
     * @throws \JsonException
     */
    private function setupTestRequest(Request $request): void
    {
        if (! $request->has('item_criteria')) {
            $request['item_criteria'] = json_encode([
                [
                    'group_type' => 'or',
                    'group_conditions' => [
                        [
                            'match_field' => 'artist',
                            'match_comparison' => Text::COMPARISON_EQUALS,
                            'match_value' => 'Rebecca Guay',
                        ],
                        [
                            'match_field' => 'artist',
                            'match_comparison' => Text::COMPARISON_EQUALS,
                            'match_value' => 'Kaja Foglio',
                        ],
                    ],
                ],
                [
                    'match_field' => 'on_reserved_list',
                    'match_comparison' => Boolean::COMPARISON_IS,
                    'match_value' => Boolean::VALUE_TRUE,
                ],
            ], JSON_THROW_ON_ERROR);

            $request['category_criteria'] = json_encode([
                [
                    'match_field' => 'digital',
                    'match_comparison' => Boolean::COMPARISON_IS,
                    'match_value' => Boolean::VALUE_FALSE,
                ],
            ], JSON_THROW_ON_ERROR);
        }
    }
}
