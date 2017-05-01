<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterRequest;
use App\Interfaces\ContactListRepositoryInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use Illuminate\Http\Request;

class NewslettersController extends Controller
{
    /**
     * @var NewsletterRepositoryInterface
     */
    protected $contactListRepository;

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
        ContactListRepositoryInterface $contactListRepository,
        NewsletterRepositoryInterface $newsletterRepository,
        TemplateRepositoryInterface $templateRepository
    )
    {
        $this->contactListRepository = $contactListRepository;
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
        $newsletter = $this->newsletterRepository->store($request->all());

        return redirect()->route('newsletters.template', $newsletter->id);
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
        $templateId = $request->get('template_id');
        $template = $this->templateRepository->find($templateId);

        // @todo at this point we're just over-writing the newsletter
        // content with the template content, but we need to cater for the
        // case when the user doesn't actually want to overwrite the newsletter content
        $this->newsletterRepository->update($id, [
            'content' => $template->content,
            'template_id' => $templateId,
        ]);

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
        $this->newsletterRepository->update($id, $request->only('content'));

        return redirect()->route('newsletters.confirm', $id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirm($id)
    {
        $newsletter = $this->newsletterRepository->find($id);
        $template = $this->templateRepository->find($newsletter->template_id);
        $contactLists = $this->contactListRepository->all();

        return view('newsletters.confirm', compact('newsletter', 'template', 'contactLists'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request, $id)
    {
        dd($request->all());
        $this->newsletterRepository->update($id, $request->only('content'));

        return redirect()->route('newsletters.confirm', $id);
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

        // @todo we need to check newsletter status here and
        // redirect if its not in draft

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
        // @todo we need to check newsletter status here and
        // redirect if its not in draft

        $newsletter = $this->newsletterRepository->update($id, $request->all());

        return redirect()->route('newsletters.template', $newsletter->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // @todo we need to check newsletter status here and
        // redirect if its not in draft
        //
    }


}
