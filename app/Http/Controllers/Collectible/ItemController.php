<?php

namespace App\Http\Controllers\Collectible;

use App\Collectible\FieldValueProcessor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Collectible\ItemCreateRequest;
use App\Http\Requests\Collectible\ItemEditRequest;
use App\Http\Responses\ApiResponseFactory;
use App\Models\Collectible;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @param Request $request
     * @param ApiResponseFactory $responseFactory
     * @return RedirectResponse|Response
     */
    public function index(
        Collectible $collectible,
        Collectible\Category $category,
        Request $request,
        ApiResponseFactory $responseFactory
    ) {
        if ($request->expectsJson()) {
            $items = Collectible\Item::where([
                'collectible_id' => $collectible->id,
                'category_id' => $category->id,
            ])->orderByRaw('CAST(field_values->>\'$.collector_number\' AS UNSIGNED)')->paginate(30);

            return $responseFactory->createListFromPaginator($items);
        }

        return redirect()->route('collectibles.categories.show', [
            'collectible' => $collectible,
            'category' => $category,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Collectible $collectible, Collectible\Category $category): View
    {
        $item = new Collectible\Item();
        $item->collectible()->associate($collectible);
        $item->category()->associate($category);

        return view('collectible.item.edit', [
            'item' => $item,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @param ItemCreateRequest $request
     * @param FieldValueProcessor $valueProcessor
     * @return RedirectResponse
     */
    public function store(
        Collectible $collectible,
        Collectible\Category $category,
        ItemCreateRequest $request,
        FieldValueProcessor $valueProcessor
    ): RedirectResponse {
        $item = new Collectible\Item();
        $item->collectible()->associate($collectible);
        $item->category()->associate($category);
        $item->fill($request->validated());
        $item->field_values = $valueProcessor->getFieldValues($item->fields, $request->get('field_values') ?: []);

        if (! $item->save()) {
            return redirect()
                ->route('collectibles.categories.items.create', [
                    'collectible' => $collectible,
                    'category' => $category,
                ])
                ->withErrors(__('collectible.item.messages.create_failed'))
                ->withInput();
        }

        return redirect()
            ->route('collectibles.categories.items.show', [
                'collectible' => $collectible,
                'category' => $category,
                'item' => $item,
            ])
            ->with('status', __('collectible.item.messages.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @param Collectible\Item $item
     * @param Request $request
     * @param ApiResponseFactory $responseFactory
     * @return View|Response
     */
    public function show(
        Collectible $collectible,
        Collectible\Category $category,
        Collectible\Item $item,
        Request $request,
        ApiResponseFactory $responseFactory
    ) {
        if ($request->expectsJson()) {
            return $responseFactory->createSuccess(['item' => $item]);
        }

        return view('collectible.item.show', [
            'collectible' => $collectible,
            'category' => $category,
            'item' => $item,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @param Collectible\Item $item
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Collectible $collectible, Collectible\Category $category, Collectible\Item $item): View
    {
        return view('collectible.item.edit', [
            'item' => $item,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @param Collectible\Item $item
     * @param ItemEditRequest $request
     * @param FieldValueProcessor $valueProcessor
     * @return RedirectResponse
     */
    public function update(
        Collectible $collectible,
        Collectible\Category $category,
        Collectible\Item $item,
        ItemEditRequest $request,
        FieldValueProcessor $valueProcessor
    ): RedirectResponse {
        $item->fill($request->validated());
        $item->field_values = $valueProcessor->getFieldValues($item->fields, $request->get('field_values') ?: []);

        if (! $item->save()) {
            return redirect()
                ->route('collectibles.categories.items.edit', [
                    'collectible' => $collectible,
                    'category' => $category,
                    'item' => $item,
                ])
                ->withErrors(__('collectible.item.messages.save_failed'))
                ->withInput();
        }

        return redirect()
            ->route('collectibles.categories.items.show', [
                'collectible' => $collectible,
                'category' => $category,
                'item' => $item,
            ])
            ->with('status', __('collectible.item.messages.save_success'));
    }
}
