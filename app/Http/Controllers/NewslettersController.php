<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterRequest;
use App\Interfaces\ContactNewsletterRepositoryInterface;
use App\Interfaces\SegmentRepositoryInterface;
use App\Interfaces\NewsletterRepositoryInterface;
use App\Interfaces\TemplateRepositoryInterface;
use App\Models\NewsletterStatus;
use App\Services\NewsletterReportService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewslettersController extends Controller
{
    /**
     * @var ContactNewsletterRepositoryInterface
     */
    protected $contactNewsletterRepo;

    /**
     * @var NewsletterRepositoryInterface
     */
    protected $segmentRepo;

    /**
     * @var NewsletterRepositoryInterface
     */
    protected $newsletterRepo;

    /**
     * @var TemplateRepositoryInterface
     */
    protected $templateRepo;

    /**
     * NewslettersController constructor.
     *
     * @param NewsletterRepositoryInterface $newsletterRepository
     * @param TemplateRepositoryInterface $newsletterRepository#
     */
    public function __construct(
        SegmentRepositoryInterface $segmentRepository,
        NewsletterRepositoryInterface $newsletterRepository,
        ContactNewsletterRepositoryInterface $contactNewsletterRepo,
        TemplateRepositoryInterface $templateRepository
    )
    {
        $this->contactNewsletterRepo = $contactNewsletterRepo;
        $this->segmentRepo = $segmentRepository;
        $this->newsletterRepo = $newsletterRepository;
        $this->templateRepo = $templateRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsletters = $this->newsletterRepo->paginate('created_atDesc', ['status', 'template']);

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
        $newsletter = $this->newsletterRepo->store($request->all());

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
        $newsletter = $this->newsletterRepo->find($id);

        // @todo fix the pagination in the view
        $templates = $this->templateRepo->paginate();

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
        $template = $this->templateRepo->find($templateId);

        // @todo at this point we're just over-writing the newsletter
        // content with the template content, but we need to cater for the
        // case when the user doesn't actually want to overwrite the newsletter content
        $this->newsletterRepo->update($id, [
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
        $newsletter = $this->newsletterRepo->find($id);
        $template = $this->templateRepo->find($newsletter->template_id);

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
        $this->newsletterRepo->update($id, $request->only('content'));

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
        $newsletter = $this->newsletterRepo->find($id);

        if ($newsletter->status_id > 1)
        {
            return redirect()->route('newsletters.status', $id);
        }

        $template = $this->templateRepo->find($newsletter->template_id);
        $segments = $this->segmentRepo->all('name', ['contactsCount']);

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
        $newsletter = $this->newsletterRepo->find($id);

        if ($newsletter->status_id > 1)
        {
            return redirect()->route('newsletters.status', $id);
        }

        // @todo validation that at least one segment has been selected
        $newsletter = $this->newsletterRepo->update($id, [
            'scheduled_at' => Carbon::now(),
            'status_id' => NewsletterStatus::STATUS_QUEUED,
        ]);

        $newsletter->segments()->sync($request->get('segments'));

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
        $newsletter = $this->newsletterRepo->find($id, ['status']);

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
        $newsletter = $this->newsletterRepo->find($id);

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

        $newsletter = $this->newsletterRepo->update($id, $updateData);

        return redirect()->route('newsletters.template', $newsletter->id);
    }

    /**
     * Show newsletter report view
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\View\View
     */
    public function report($id)
    {
        $newsletter = $this->newsletterRepo->find($id);

        if ($newsletter->status_id == NewsletterStatus::STATUS_DRAFT)
        {
            return redirect()->route('newsletters.edit', $id);
        }

        if ($newsletter->status_id == NewsletterStatus::STATUS_SENT)
        {

            return view('newsletters.report', compact('newsletter', 'chartData'));
        }

        return redirect()->route('newsletters.status', $id);
    }

    /**
     * Show newsletter recipients view
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\View\View
     */
    public function recipients($id)
    {
        $newsletter = $this->newsletterRepo->find($id);

        if ($newsletter->status_id == NewsletterStatus::STATUS_DRAFT)
        {
            return redirect()->route('newsletters.edit', $id);
        }

        if ($newsletter->status_id == NewsletterStatus::STATUS_SENT)
        {
            $recipients = $this->contactNewsletterRepo->paginate('created_at', [], 50, ['newsletter_id' => $id]);

            return view('newsletters.recipients', compact('newsletter', $recipients));
        }

        return redirect()->route('newsletters.status', $id);
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
