<?php

namespace App\Http\Controllers\Collectible;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collectible\ItemCreateRequest;
use App\Http\Requests\Collectible\ItemEditRequest;
use App\Models\Collectible;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @return RedirectResponse
     */
    public function index(Collectible $collectible, Collectible\Category $category): RedirectResponse
    {
        return redirect()->route('collectibles.categories.show', ['collectible' => $collectible, 'category' => $category]);
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
     * @return RedirectResponse
     */
    public function store(Collectible $collectible, Collectible\Category $category, ItemCreateRequest $request): RedirectResponse
    {
        $item = new Collectible\Item();
        $item->collectible()->associate($collectible);
        $item->category()->associate($category);
        $item->fill($request->validated());
        $item->field_values = array_filter($request->get('field_values') ?: [], static fn ($v) => $v !== null);

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
     * @return View
     */
    public function show(Collectible $collectible, Collectible\Category $category, Collectible\Item $item): View
    {
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
     * @return RedirectResponse
     */
    public function update(
        Collectible $collectible,
        Collectible\Category $category,
        Collectible\Item $item,
        ItemEditRequest $request
    ): RedirectResponse {
        $item->fill($request->validated());
        $item->field_values = array_filter($request->get('field_values') ?: [], static fn ($v) => $v !== null);

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
