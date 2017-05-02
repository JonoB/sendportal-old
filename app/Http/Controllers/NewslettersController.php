<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterRequest;
use App\Interfaces\SegmentRepositoryInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Models\NewsletterStatus;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewslettersController extends Controller
{
    /**
     * @var NewsletterRepositoryInterface
     */
    protected $segmentRepository;

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
        SegmentRepositoryInterface $segmentRepository,
        NewsletterRepositoryInterface $newsletterRepository,
        TemplateRepositoryInterface $templateRepository
    )
    {
        $this->segmentRepository = $segmentRepository;
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
        $newsletters = $this->newsletterRepository->paginate('created_atDesc', ['status', 'template']);

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
     * @param NewsletterRequest $request
     * @return RedirectResponse
     */
    public function store(NewsletterRequest $request)
    {
        $newsletter = $this->newsletterRepository->store($request->all());

        return redirect()->route('newsletters.template', $newsletter->id);
    }

    /**
     * Display a list of templates for selection.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function template($id)
    {
        $newsletter = $this->newsletterRepository->find($id);
        $templates = $this->templateRepository->pluck();

        return view('newsletters.template', compact('newsletter', 'templates'));
    }

    /**
     * Update the template.
     *
     * @param \Illuminate\Http\Request  $request
     * @param int $id
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
     * Display the template for design.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function design($id)
    {
        $newsletter = $this->newsletterRepository->find($id);
        $template = $this->templateRepository->find($newsletter->template_id);

        return view('newsletters.design', compact('newsletter', 'template'));
    }

    /**
     * Update the design.
     *
     * @param \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateDesign(Request $request, $id)
    {
        $this->newsletterRepository->update($id, $request->only('content'));

        return redirect()->route('newsletters.confirm', $id);
    }

    /**
     * Display the confirmation view.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function confirm($id)
    {
        $newsletter = $this->newsletterRepository->find($id);

        if ($newsletter->status_id > 1)
        {
            return redirect()->route('newsletters.status', $id);
        }

        $template = $this->templateRepository->find($newsletter->template_id);
        $segments = $this->segmentRepository->all('name', ['contactsCount']);

        return view('newsletters.confirm', compact('newsletter', 'template', 'segments'));
    }

    /**
     * Dispatch the newsletter.
     *
     * @param \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request, $id)
    {
        $newsletter = $this->newsletterRepository->find($id);

        if ($newsletter->status_id > 1)
        {
            return redirect()->route('newsletters.status', $id);
        }

        // @todo validation that at least one segment has been selected
        $newsletter = $this->newsletterRepository->update($id, [
            'scheduled_at' => Carbon::now(),
            'status_id' => NewsletterStatus::STATUS_QUEUED,
        ]);

        $newsletter->segments()->attach($request->get('segments'));

        return redirect()->route('newsletters.status', $id);
    }

    /**
     * Display the status view.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function status($id)
    {
        $newsletter = $this->newsletterRepository->find($id, ['status']);

        return view('newsletters.status', compact('newsletter'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
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
     * @param \Illuminate\Http\Request  $request
     * @param int  $id
     * @return RedirectResponse
     */
    public function update(NewsletterRequest $request, $id)
    {
        // @todo we need to check newsletter status here and
        // redirect if its not in draft

        $updateData = $request->only([
            'name',
            'subject',
            'from_email',
            'from_name',
        ]);

        $update['track_opens'] = $request->get('track_opens', 0);
        $update['track_clicks'] = $request->get('track_clicks', 0);

        $newsletter = $this->newsletterRepository->update($id, $updateData);

        return redirect()->route('newsletters.template', $newsletter->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // @todo we need to check newsletter status here and
        // redirect if its not in draft
        //
    }


}
