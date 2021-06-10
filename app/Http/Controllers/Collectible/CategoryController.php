<?php

namespace App\Http\Controllers\Collectible;

use App\Collectible\FieldValueProcessor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Collectible\CategoryCreateRequest;
use App\Http\Requests\Collectible\CategoryEditRequest;
use App\Http\Responses\ApiResponseFactory;
use App\Models\Collectible;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Collectible $collectible
     * @param Request $request
     * @param ApiResponseFactory $responseFactory
     * @return RedirectResponse|Response
     */
    public function index(Collectible $collectible, Request $request, ApiResponseFactory $responseFactory)
    {
        if ($request->expectsJson()) {
            $categories = Collectible\Category::whereCollectibleId($collectible->id)->paginate(30);

            return $responseFactory->createListFromPaginator($categories);
        }

        return redirect()->route('collectibles.show', ['collectible' => $collectible]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Collectible $collectible
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Collectible $collectible): View
    {
        $category = new Collectible\Category();
        $category->collectible()->associate($collectible);

        return view('collectible.category.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Collectible $collectible
     * @param CategoryCreateRequest $request
     * @param FieldValueProcessor $valueProcessor
     * @return RedirectResponse
     */
    public function store(
        Collectible $collectible,
        CategoryCreateRequest $request,
        FieldValueProcessor $valueProcessor
    ): RedirectResponse {
        $category = new Collectible\Category();
        $category->collectible()->associate($collectible);
        $category->fill($request->validated());
        $category->field_values = $valueProcessor->getFieldValues(
            $category->fields,
            $request->get('field_values') ?: []
        );

        if (! $category->save()) {
            return redirect()->route('collectibles.categories.create', ['collectible' => $collectible])
                             ->withErrors(__('collectible.category.messages.create_failed'))
                             ->withInput();
        }

        return redirect()
            ->route('collectibles.categories.show', ['collectible' => $collectible, 'category' => $category])
            ->with('status', __('collectible.category.messages.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @param Request $request
     * @param ApiResponseFactory $responseFactory
     * @return View|Response
     */
    public function show(
        Collectible $collectible,
        Collectible\Category $category,
        Request $request,
        ApiResponseFactory $responseFactory
    ) {
        if ($request->expectsJson()) {
            return $responseFactory->createSuccess([
                'category' => $category->toArray(),
            ]);
        }

        $items = Collectible\Item::where([
            'collectible_id' => $collectible->id,
            'category_id' => $category->id,
        ])->orderByRaw('CAST(field_values->>\'$.collector_number\' AS UNSIGNED)')->paginate(30);

        return view('collectible.category.show', [
            'collectible' => $collectible,
            'category' => $category,
            'items' => $items,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Collectible $collectible, Collectible\Category $category): View
    {
        return view('collectible.category.edit', [
            'category' => $category,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Collectible $collectible
     * @param Collectible\Category $category
     * @param CategoryEditRequest $request
     * @param FieldValueProcessor $valueProcessor
     * @return RedirectResponse
     */
    public function update(
        Collectible $collectible,
        Collectible\Category $category,
        CategoryEditRequest $request,
        FieldValueProcessor $valueProcessor
    ): RedirectResponse {
        $category->fill($request->validated());
        $category->field_values = $valueProcessor->getFieldValues(
            $category->fields,
            $request->get('field_values') ?: []
        );

        if (! $category->save()) {
            return redirect()
                ->route('collectibles.categories.edit', ['collectible' => $collectible, 'category' => $category])
                ->withErrors(__('collectible.category.messages.save_failed'))
                ->withInput();
        }

        return redirect()
            ->route('collectibles.categories.show', ['collectible' => $collectible, 'category' => $category])
            ->with('status', __('collectible.category.messages.save_success'));
    }
}
