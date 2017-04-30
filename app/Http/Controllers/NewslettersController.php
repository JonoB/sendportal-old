<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterRequest;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use Illuminate\Http\Request;

class NewslettersController extends Controller
{
    /**
     * @var NewsletterRepositoryInterface
     */

    protected $newsletterRepository;
    /**
     * @var TemplateRepositoryInterface
     */
    protected $templateRepository;

    /**
     * NewslettersController constructor.
     *
     * @param NewsletterRepositoryInterface $newsletterRepository
     * @param TemplateRepositoryInterface $newsletterRepository#
     */
    public function __construct(
        NewsletterRepositoryInterface $newsletterRepository,
        TemplateRepositoryInterface $templateRepository
    )
    {
        $this->newsletterRepository = $newsletterRepository;
        $this->templateRepository = $templateRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsletters = $this->newsletterRepository->paginate('created_atDesc', ['template']);

        return view('newsletters.index', compact('newsletters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('newsletters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsletterRequest $request)
    {
        $this->newsletterRepository->store($request->all());

        return redirect()->route('newsletters.template');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function template($id)
    {
        $newsletter = $this->newsletterRepository->find($id);
        $templates = $this->templateRepository->pluck();

        return view('newsletters.template', compact('newsletter', 'templates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateTemplate(Request $request, $id)
    {
        $this->newsletterRepository->update($id, $request->only('template_id'));

        return redirect()->route('newsletters.design', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function design($id)
    {
        $newsletter = $this->newsletterRepository->find($id);
        $template = $this->templateRepository->find($newsletter->template_id);

        return view('newsletters.design', compact('newsletter', 'template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDesign(Request $request, $id)
    {
        dd($request->only('content'));
        $this->newsletterRepository->update($id, $request->only('content'));

        return redirect()->route('newsletters.confirm');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $newsletter = $this->newsletterRepository->find($id);

        return view('newsletters.edit', compact('newsletter'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NewsletterRequest $request, $id)
    {
        $this->newsletterRepository->update($id, $request->all());

        return redirect()->route('newsletters.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


}
