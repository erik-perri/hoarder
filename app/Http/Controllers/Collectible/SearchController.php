<?php

namespace App\Http\Controllers\Collectible;

use App\Criteria\CollectibleCriteriaBuilder;
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
     * @return Response|View
     * @throws \JsonException
     */
    public function search(Collectible $collectible, Request $request): View
    {
        $itemFields = $collectible->fields->where('entity_type', '=', 'item')->map(fn ($field) => [
            'uuid' => $field->uuid,
            'name' => $field->name,
            'code' => $field->code,
            'input_type' => $field->input_type,
        ]);
        $items = null;

        $filter = [];

        if ($request->get('filter')) {
            $filter = $request->get('filter');
            $filter = json_decode($filter, true, 32, JSON_THROW_ON_ERROR);

            $criteria = new CollectibleCriteriaBuilder($itemFields->pluck('code')->toArray());
            $builder = Collectible\Item::getQuery();

            $criteria->apply($builder, false, $filter);

            $builder->orderBy('name');

            $items = $builder->paginate(100);
        } else {
            if ($collectible->id === 1) {
                $filter = [
                    [
                        'group_type' => 'or',
                        'group_conditions' => [
                            [
                                'match_field' => 'artist',
                                'match_comparison' => 'equals',
                                'match_value' => 'Rebecca Guay',
                            ],
                            [
                                'match_field' => 'artist',
                                'match_comparison' => 'equals',
                                'match_value' => 'Kaja Foglio',
                            ],
                        ],
                    ],
                    ['match_field' => 'on_reserved_list', 'match_comparison' => 'bool', 'match_value' => 'true'],
                ];
            }
        }

        return view('collectible.search', [
            'collectible' => $collectible,
            'itemFields' => $itemFields->values()->all(),
            'results' => $items,
            'filter' => $filter,
        ]);
    }
}
