<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemplateStoreRequest;
use App\Http\Requests\TemplateUpdateRequest;
use App\Interfaces\TemplateRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class TemplatesController extends Controller
{
    /**
     * @var TemplateRepositoryInterface
     */
    protected $templates;

    /**
     * @param TemplateRepositoryInterface $templates
     */
    public function __construct(TemplateRepositoryInterface $templates)
    {
        $this->templates = $templates;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $templates = $this->templates->paginate('name');

        return view('templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
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
     */
    public function store(TemplateStoreRequest $request)
    {
        $this->templates->store($request->all());

        return redirect()->route('templates.index');
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     *
     * @return Response
     */
    public function show($id)
    {
        return view('templates.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $template = $this->templates->find($id);

        return view('templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TemplateUpdateRequest $request
     * @param string $id
     *
     * @return RedirectResponse
     */
    public function update(TemplateUpdateRequest $request, $id)
    {
        $this->templates->update($id, $request->all());

        return redirect()->route('templates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     *
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $template = $this->templates->find((int)$id);

        if ($template->is_in_use)
        {
            return redirect()
                ->back()
                ->withErrors(['template' => 'Cannot delete a template that has been used.']);
        }

        $this->templates->destroy($template->id);

        return redirect()
            ->back()
            ->with('success', 'Template successfully deleted.');
    }
}
