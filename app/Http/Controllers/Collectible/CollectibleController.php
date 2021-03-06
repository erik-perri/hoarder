<?php

namespace App\Http\Controllers\Collectible;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collectible\CollectibleCreateRequest;
use App\Http\Requests\Collectible\CollectibleEditRequest;
use App\Http\Responses\ApiResponseFactory;
use App\Models\Collectible;
use App\Models\User;
use App\Repository\Collectible\FieldRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CollectibleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ApiResponseFactory $responseFactory
     * @return Response|View
     */
    public function index(Request $request, ApiResponseFactory $responseFactory)
    {
        $collectibles = Collectible::latest()->paginate(30);

        if ($request->expectsJson()) {
            return $responseFactory->createListFromPaginator($collectibles);
        }

        return view('collectible.index', ['collectibles' => $collectibles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $collectible = new Collectible();

        return view('collectible.edit', [
            'collectible' => $collectible,
            'categoryFields' => [],
            'itemFields' => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CollectibleCreateRequest $request
     * @param User $user
     * @param FieldRepository $fieldRepository
     * @param ApiResponseFactory $responseFactory
     * @return RedirectResponse|Response
     */
    public function store(
        CollectibleCreateRequest $request,
        User $user,
        FieldRepository $fieldRepository,
        ApiResponseFactory $responseFactory
    ) {
        $collectible = new Collectible();
        $collectible->fill($request->validated());
        $collectible->createdBy()->associate($user);

        if (! $collectible->save()) {
            if ($request->expectsJson()) {
                return $responseFactory->createFailure(__('collectible.messages.create_failed'));
            }

            return redirect()->route('collectibles.create')
                             ->withErrors(__('collectible.messages.create_failed'))
                             ->withInput();
        }

        // This has to be after the save here so the created fields can access the collectible ID.
        $this->handleFieldChanges($request, $fieldRepository, $collectible);

        if ($request->expectsJson()) {
            return $responseFactory->createSuccess(['collectible' => $collectible],
                __('collectible.messages.create_success'));
        }

        return redirect()->route('collectibles.show', ['collectible' => $collectible])
                         ->with('status', __('collectible.messages.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Collectible $collectible
     * @param Request $request
     * @param ApiResponseFactory $responseFactory
     * @return View|Response
     */
    public function show(Collectible $collectible, Request $request, ApiResponseFactory $responseFactory)
    {
        if ($request->expectsJson()) {
            return $responseFactory->createSuccess(['collectible' => $collectible->toArray()]);
        }

        $categories = Collectible\Category::whereCollectibleId($collectible->id)->paginate(30);

        return view('collectible.show', [
            'collectible' => $collectible,
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Collectible $collectible
     * @return View
     */
    public function edit(Collectible $collectible): View
    {
        $fields = $collectible->fields
            ->map(fn (Collectible\Field $field) => $field->jsonSerialize())
            ->groupBy('entity_type');

        return view('collectible.edit', [
            'collectible' => $collectible,
            'categoryFields' => $fields['category'] ?? [],
            'itemFields' => $fields['item'] ?? [],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CollectibleEditRequest $request
     * @param Collectible $collectible
     * @param FieldRepository $fieldRepository
     * @param ApiResponseFactory $responseFactory
     * @return RedirectResponse|Response
     */
    public function update(
        CollectibleEditRequest $request,
        Collectible $collectible,
        FieldRepository $fieldRepository,
        ApiResponseFactory $responseFactory
    ) {
        $collectible->fill($request->validated());

        $this->handleFieldChanges($request, $fieldRepository, $collectible);

        if (! $collectible->save()) {
            if ($request->expectsJson()) {
                return $responseFactory->createFailure(__('collectible.messages.save_failed'));
            }

            return redirect()->route('collectibles.create')
                             ->withErrors(__('collectible.messages.save_failed'))
                             ->withInput();
        }

        if ($request->expectsJson()) {
            return $responseFactory->createSuccess(
                ['collectible' => $collectible],
                __('collectible.messages.save_success')
            );
        }

        return redirect()->route('collectibles.show', ['collectible' => $collectible])
                         ->with('status', __('collectible.messages.save_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Collectible $collectible
     * @return RedirectResponse
     */
    public function destroy(Collectible $collectible): RedirectResponse
    {
        if (! $collectible->delete()) {
            return redirect()->route('collectibles.edit', ['collectible' => $collectible])
                             ->withErrors(__('collectible.messages.delete_failed'));
        }

        return redirect()->route('collectibles.index')
                         ->with('status', __('collectible.messages.delete_success'));
    }

    /**
     * @param Request $request
     * @param FieldRepository $fieldRepository
     * @param Collectible $collectible
     */
    private function handleFieldChanges(
        Request $request,
        FieldRepository $fieldRepository,
        Collectible $collectible
    ): void {
        foreach (['category_fields', 'item_fields'] as $fieldRequestKey) {
            if (! $request->has($fieldRequestKey)) {
                continue;
            }

            foreach ($request->get($fieldRequestKey) as $fieldInfo) {
                $fieldCode = $fieldInfo['code'];

                // TODO Figure out a better way to handle the name field (or any other non-JSON fields we may add)
                //      Right now we create the fake field in Collectible->toArray and check for the code in a few
                //      places, that is not maintainable.
                if ($fieldCode === 'name') {
                    continue;
                }

                if (($fieldInfo['is_removed'] ?? 0) > 0) {
                    $fieldRepository->removeField($collectible, $fieldCode);
                    continue;
                }

                /** @var Collectible\Field $field */
                $field = $collectible->fields->firstWhere('code', '=', $fieldCode);
                if (! $field) {
                    $entityType = $fieldRequestKey === 'category_fields' ? 'category' : 'item';

                    $field = new Collectible\Field();
                    $field->collectible()->associate($collectible);
                    $field->code = \Str::slug($fieldInfo['name'], '_');
                    $field->entity_type = $entityType;
                    $field->input_options = [];

                    // Make sure code isn't already in use TODO Should we fail here?
                    $attempts = 1;
                    while ($collectible->fields->where('code', '=', $field->code)
                                               ->where('entity_type', '=', $entityType)
                                               ->count()) {
                        $field->code = \Str::slug($fieldInfo['name'], '_').'_'.$attempts++;
                    }
                }

                $field->name = $fieldInfo['name'];
                // TODO Handle input_type changes by clearing the value?
                $field->input_type = $fieldInfo['input_type'];
                $field->is_required = $fieldInfo['is_required'] ?? false;
                $field->save();
            }
        }
    }
}
