<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemplateRequest;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;

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
        $newsletters = $this->newsletterRepository->paginate('created_atDesc');

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
    public function store(TemplateRequest $request)
    {
        $this->newsletterRepository->store($request->all());

        return redirect()->route('newsletters.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('newsletters.show');
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
    public function update(TemplateRequest $request, $id)
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

    public function iframe($id)
    {
        $newsletter = $this->newsletterRepository->find($id);

        return view('newsletters.partials.iframe', compact('newsletter'))->render();
    }
}
