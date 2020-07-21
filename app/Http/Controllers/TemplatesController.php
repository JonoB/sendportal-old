<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemplateStoreRequest;
use App\Http\Requests\TemplateUpdateRequest;
use App\Repositories\TemplateTenantRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TemplatesController extends Controller
{
    /**
     * @var TemplateTenantRepository
     */
    protected $templates;

    /**
     * @param TemplateTenantRepository $templates
     */
    public function __construct(TemplateTenantRepository $templates)
    {
        $this->templates = $templates;
    }

    /**
     * Show a listing of the resource.
     *
     * @return Factory|View
     * @throws Exception
     */
    public function index()
    {
        $templates = $this->templates->paginate(currentTeamId(), 'name');

        return view('templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TemplateStoreRequest $request
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(TemplateStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['content'] = normalize_tags($data['content'], 'content');

        $this->templates->store(currentTeamId(), $data);

        return redirect()->route('templates.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Factory|View
     * @throws Exception
     */
    public function edit(int $id)
    {
        $template = $this->templates->find(currentTeamId(), $id);

        return view('templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TemplateUpdateRequest $request
     * @param int $id
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(TemplateUpdateRequest $request, int $id): RedirectResponse
    {
        $data = $request->validated();

        $data['content'] = normalize_tags($data['content'], 'content');

        $this->templates->update(currentTeamId(), $id, $data);

        return redirect()->route('templates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(int $id): RedirectResponse
    {
        $template = $this->templates->find(currentTeamId(), $id);

        if ($template->is_in_use)
        {
            return redirect()
                ->back()
                ->withErrors(['template' => 'Cannot delete a template that has been used.']);
        }

        $this->templates->destroy(currentTeamId(), $template->id);

        return redirect()
            ->back()
            ->with('success', 'Template successfully deleted.');
    }
}
