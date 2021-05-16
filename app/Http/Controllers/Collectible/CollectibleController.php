<?php

namespace App\Http\Controllers\Collectible;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collectible\CollectibleCreateRequest;
use App\Http\Requests\Collectible\CollectibleEditRequest;
use App\Models\Collectible;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class CollectibleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response|View
     */
    public function index(): View
    {
        $collectibles = Collectible::latest()->paginate(30);

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
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CollectibleCreateRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function store(CollectibleCreateRequest $request, User $user): RedirectResponse
    {
        $collectible = new Collectible();
        $collectible->fill($request->validated());
        $collectible->createdBy()->associate($user);

        if (! $collectible->save()) {
            return redirect()->route('collectibles.create')
                             ->withErrors(__('collectible.messages.create_failed'))
                             ->withInput();
        }

        return redirect()->route('collectibles.show', ['collectible' => $collectible])
                         ->with('status', __('collectible.messages.create_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param Collectible $collectible
     * @return View
     */
    public function show(Collectible $collectible): View
    {
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
        return view('collectible.edit', [
            'collectible' => $collectible,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CollectibleEditRequest $request
     * @param Collectible $collectible
     * @return RedirectResponse
     */
    public function update(CollectibleEditRequest $request, Collectible $collectible): RedirectResponse
    {
        $collectible->fill($request->validated());

        if (! $collectible->save()) {
            return redirect()->route('collectibles.create')
                             ->withErrors(__('collectible.messages.save_failed'))
                             ->withInput();
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
                             ->withErrors('collectible.messages.delete_failed');
        }

        return redirect()->route('collectibles.index')
                         ->with('status', 'collectible.messages.delete_success');
    }
}
