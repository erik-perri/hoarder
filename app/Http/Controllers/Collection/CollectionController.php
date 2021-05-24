<?php

namespace App\Http\Controllers\Collection;

use App\Collectible\ItemSearcher;
use App\Collectible\StockSearcher;
use App\Http\Controllers\Controller;
use App\Http\Requests\Collection\CollectionCreateRequest;
use App\Http\Requests\Collection\CollectionEditRequest;
use App\Models\Collectible;
use App\Models\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectionController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Collection::class, 'collection');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response|View
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $collections = Collection::whereUserId($user->id)->latest()->paginate(30);

        return view('collection.index', ['collections' => $collections]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $collection = new Collection();
        $collectibleOptions = [];

        foreach (Collectible::all() as $collectible) {
            $collectibleOptions[$collectible->id] = $collectible->name;
        }

        return view('collection.edit', [
            'collection' => $collection,
            'collectibleOptions' => $collectibleOptions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CollectionCreateRequest $request
     * @return RedirectResponse
     */
    public function store(CollectionCreateRequest $request): RedirectResponse
    {
        $user = auth()->user();

        $collection = new Collection();
        $collection->fill($request->validated());
        $collection->user()->associate($user);
        $collection->collectible_id = $request->get('collectible_id');
        $collection->is_default = $request->has('is_default') && $request->get('is_default');

        if (! $collection->save()) {
            return redirect()->route('collections.create')
                             ->withErrors(__('collection.messages.create_failed'))
                             ->withInput();
        }

        if ($collection->is_default) {
            $this->removeOtherDefaults($collection);
        }

        return redirect()->route('collections.show', ['collection' => $collection])
                         ->with('status', __('collection.messages.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Collection $collection
     * @return View
     */
    public function show(Collection $collection): View
    {
        $goals = Collection\Goal::whereCollectionId($collection->id)->get();
        $progress = [];
        if (count($goals)) {
            $progress = $this->getGoalsProgress($goals);
        }

        return view('collection.show', [
            'collection' => $collection,
            'goals' => $goals,
            'progress' => $progress,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Collection $collection
     * @return View
     */
    public function edit(Collection $collection): View
    {
        return view('collection.edit', [
            'collection' => $collection,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CollectionEditRequest $request
     * @param Collection $collection
     * @return RedirectResponse
     */
    public function update(CollectionEditRequest $request, Collection $collection): RedirectResponse
    {
        $collection->fill($request->validated());
        $collection->is_default = $request->has('is_default') && $request->get('is_default');

        if (! $collection->save()) {
            return redirect()->route('collections.create')
                             ->withErrors(__('collection.messages.save_failed'))
                             ->withInput();
        }

        if ($collection->is_default) {
            $this->removeOtherDefaults($collection);
        }

        return redirect()->route('collections.show', ['collection' => $collection])
                         ->with('status', __('collection.messages.save_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Collection $collection
     * @return RedirectResponse
     */
    public function destroy(Collection $collection): RedirectResponse
    {
        if (! $collection->delete()) {
            return redirect()->route('collections.edit', ['collection' => $collection])
                             ->withErrors(__('collection.messages.delete_failed'));
        }

        return redirect()->route('collections.index')
                         ->with('status', __('collection.messages.delete_success'));
    }

    private function removeOtherDefaults(Collection $collection): void
    {
        foreach (Collection::where('id', '!=', $collection->id)
                           ->where('is_default', '=', true)
                           ->get() as $otherCollection) {
            $otherCollection->is_default = false;
            $otherCollection->save();
        }
    }

    /**
     * @param Collection\Goal[]|\Illuminate\Database\Eloquent\Collection $goals
     * @return array[]
     */
    private function getGoalsProgress(\Illuminate\Database\Eloquent\Collection $goals): array
    {
        $progress = [];
        foreach ($goals as $goal) {
            $itemSearcher = new ItemSearcher();
            $items = $itemSearcher->search(
                $goal->collection->collectible,
                $goal->category_criteria,
                $goal->item_criteria
            );

            $stockSearcher = new StockSearcher();
            $stock = $stockSearcher->search(
                $goal->collection,
                $goal->category_criteria,
                $goal->item_criteria,
                $goal->stock_criteria
            );

            $total = $items ? $items->count('id') : 0;
            $stocked = $stock ? $stock->distinct()->count('item_id') : 0;
            $percent = ($total && $stocked ? round(($stocked / $total) * 100) : 0);

            $progress[$goal->id] = [
                'total' => $total,
                'stocked' => $stocked,
                'percent' => $percent,
            ];
        }

        return $progress;
    }
}
