<?php

namespace App\Http\Controllers\Collection;

use App\Collectible\CriteriaFieldFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Collection\GoalCreateRequest;
use App\Http\Requests\Collection\GoalEditRequest;
use App\Models\Collection;
use App\Models\Collection\Goal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GoalController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Goal::class, 'goal');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Collection $collection
     * @return RedirectResponse
     */
    public function index(Collection $collection): RedirectResponse
    {
        return redirect()->route('collections.show', ['collection' => $collection]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Collection $collection
     * @param CriteriaFieldFactory $fieldFactory
     * @return View
     */
    public function create(Collection $collection, CriteriaFieldFactory $fieldFactory): View
    {
        $goal = new Goal();
        $goal->collection()->associate($collection);

        return view('collection.goal.edit', [
            'goal' => $goal,
            'categoryFields' => $fieldFactory->getCategoryFieldInfo($collection->collectible),
            'categoryCriteria' => [],
            'itemFields' => $fieldFactory->getItemFieldInfo($collection->collectible),
            'itemCriteria' => [],
            'stockFields' => $fieldFactory->getStockFieldInfo($collection->collectible),
            'stockCriteria' => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Collection $collection
     * @param GoalCreateRequest $request
     * @return RedirectResponse
     * @throws \JsonException
     */
    public function store(Collection $collection, GoalCreateRequest $request): RedirectResponse
    {
        $goal = new Goal();
        $goal->collection()->associate($collection);
        $goal->fill($request->validated());
        $this->fillGoalCriteria($goal, $request);

        if (! $goal->save()) {
            return redirect()->route('collections.goals.create', ['collection' => $collection])
                             ->withErrors(__('collection.goal.messages.create_failed'))
                             ->withInput();
        }

        return redirect()->route('collections.show', ['collection' => $collection])
                         ->with('status', __('collection.goal.messages.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Collection $collection
     * @return RedirectResponse
     */
    public function show(Collection $collection): RedirectResponse
    {
        return redirect()->route('collection.show', ['collection' => $collection]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Collection $collection
     * @param Goal $goal
     * @param CriteriaFieldFactory $fieldFactory
     * @return View
     */
    public function edit(Collection $collection, Goal $goal, CriteriaFieldFactory $fieldFactory): View
    {
        return view('collection.goal.edit', [
            'goal' => $goal,
            'categoryFields' => $fieldFactory->getCategoryFieldInfo($collection->collectible),
            'categoryCriteria' => $goal->category_criteria,
            'itemFields' => $fieldFactory->getItemFieldInfo($collection->collectible),
            'itemCriteria' => $goal->item_criteria,
            'stockFields' => $fieldFactory->getStockFieldInfo($collection->collectible),
            'stockCriteria' => $goal->stock_criteria,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GoalEditRequest $request
     * @param Collection $collection
     * @param Goal $goal
     * @return RedirectResponse
     * @throws \JsonException
     */
    public function update(GoalEditRequest $request, Collection $collection, Goal $goal): RedirectResponse
    {
        $goal->fill($request->validated());
        $this->fillGoalCriteria($goal, $request);

        if (! $goal->save()) {
            return redirect()->route('collections.goals.create')
                             ->withErrors(__('collection.goal.messages.save_failed'))
                             ->withInput();
        }

        return redirect()->route('collections.show', ['collection' => $collection])
                         ->with('status', __('collection.goal.messages.save_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Collection $collection
     * @param Goal $goal
     * @return RedirectResponse
     */
    public function destroy(Collection $collection, Goal $goal): RedirectResponse
    {
        if (! $goal->delete()) {
            return redirect()->route('collections.goals.edit', ['collection' => $collection, 'goal' => $goal])
                             ->withErrors(__('collection.goal.messages.delete_failed'));
        }

        return redirect()->route('collections.show', ['collection' => $collection])
                         ->with('status', __('collection.goal.messages.delete_success'));
    }

    /**
     * @param Goal $goal
     * @param FormRequest $request
     * @throws \JsonException
     */
    private function fillGoalCriteria(Goal $goal, FormRequest $request): void
    {
        $goal->category_criteria = $request->get('category_criteria')
            ? json_decode($request->get('category_criteria'), true, 12, JSON_THROW_ON_ERROR)
            : [];
        $goal->item_criteria = $request->get('item_criteria')
            ? json_decode($request->get('item_criteria'), true, 12, JSON_THROW_ON_ERROR)
            : [];
        $goal->stock_criteria = $request->get('stock_criteria')
            ? json_decode($request->get('stock_criteria'), true, 12, JSON_THROW_ON_ERROR)
            : [];
    }
}
