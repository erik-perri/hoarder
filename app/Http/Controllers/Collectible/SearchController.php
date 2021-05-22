<?php

namespace App\Http\Controllers\Collectible;

use App\Criteria\CollectibleCriteriaBuilder;
use App\Http\Controllers\Controller;
use App\Models\Collectible;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    /**
     * @param Collectible $collectible
     * @param Request $request
     * @return Response|View
     * @throws \JsonException
     */
    public function search(Collectible $collectible, Request $request): View
    {
        if ($collectible->id === 1) {
            $this->setupTestRequest($request);
        }

        [$itemFields, $categoryFields] = $this->getCollectibleFields($collectible);

        $categoryFilter = $this->getFilterFromRequest($request, 'categoryFilter');
        $categoryIds = $categoryFilter ? $this->getCategoryIdsFromFilter($categoryFilter, $categoryFields) : null;

        $itemFilter = $this->getFilterFromRequest($request, 'itemFilter');

        if ($categoryFilter && empty($categoryIds)) {
            $items = null;
        } else {
            $items = $this->getItemsFromFilter($itemFilter, $itemFields, $categoryIds);
        }

        return view('collectible.search', [
            'collectible' => $collectible,
            'categoryFields' => $categoryFields->values()->all(),
            'categoryFilter' => $categoryFilter,
            'itemFields' => $itemFields->values()->all(),
            'itemFilter' => $itemFilter,
            'results' => $items ? $items->paginate(30) : null,
        ]);
    }

    private function getCollectibleFields(Collectible $collectible): array
    {
        $fields = $collectible->fields->map(fn ($field) => [
            'uuid' => $field->uuid,
            'name' => $field->name,
            'code' => $field->code,
            'input_type' => $field->input_type,
            'entity_type' => $field->entity_type,
        ])->groupBy('entity_type');

        return [
            $fields['item'],
            $fields['category'],
        ];
    }

    /**
     * @param Request $request
     * @param string $requestKey
     * @return array
     * @throws \JsonException
     */
    private function getFilterFromRequest(Request $request, string $requestKey): array
    {
        $filterInput = trim($request->get($requestKey));
        if (! $filterInput) {
            return [];
        }

        return json_decode($filterInput, true, 32, JSON_THROW_ON_ERROR);
    }

    /**
     * @param array $categoryFilter
     * @param Collection $categoryFields
     * @return array|null
     */
    private function getCategoryIdsFromFilter(array $categoryFilter, Collection $categoryFields): ?array
    {
        $criteria = new CollectibleCriteriaBuilder($categoryFields->pluck('code')->toArray());
        $builder = Collectible\Category::getQuery();

        $criteria->apply($builder, false, $categoryFilter);

        $builder->orderBy('name');

        return $builder->pluck('id')->toArray();
    }

    /**
     * @param array|null $itemFilter
     * @param Collection $itemFields
     * @param array|null $categoryIds
     * @return Builder|null
     */
    private function getItemsFromFilter(?array $itemFilter, Collection $itemFields, ?array $categoryIds): ?Builder
    {
        if (empty($itemFilter) && empty($categoryIds)) {
            return null;
        }

        $criteria = new CollectibleCriteriaBuilder($itemFields->pluck('code')->toArray());
        $builder = Collectible\Item::getQuery();

        if (! empty($categoryIds)) {
            $builder->whereIn('category_id', $categoryIds);
        }

        if (! empty($itemFilter)) {
            $criteria->apply($builder, false, $itemFilter);
        }

        $builder->orderBy('name');

        return $builder;
    }

    /**
     * TODO Remove this method, it is just to make working on the search page and filter builder easier.
     * @param Request $request
     * @throws \JsonException
     */
    private function setupTestRequest(Request $request): void
    {
        if (! $request->has('itemFilter')) {
            $request['itemFilter'] = json_encode([
                [
                    'group_type' => 'or',
                    'group_conditions' => [
                        [
                            'match_field' => 'artist',
                            'match_comparison' => 'text_equals',
                            'match_value' => 'Rebecca Guay',
                        ],
                        [
                            'match_field' => 'artist',
                            'match_comparison' => 'text_equals',
                            'match_value' => 'Kaja Foglio',
                        ],
                    ],
                ],
                [
                    'match_field' => 'on_reserved_list',
                    'match_comparison' => 'boolean_is',
                    'match_value' => 'true',
                ],
            ], JSON_THROW_ON_ERROR);

            $request['categoryFilter'] = json_encode([
                [
                    'match_field' => 'digital',
                    'match_comparison' => 'boolean_is',
                    'match_value' => 'false',
                ],
            ], JSON_THROW_ON_ERROR);
        }
    }
}
