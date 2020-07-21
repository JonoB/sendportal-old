<?php

namespace App\Http\Controllers;

use App\Repositories\MessageTenantRepository;
use App\Services\Content\MergeContent;
use App\Services\Messages\DispatchMessage;

class MessagesController extends Controller
{
    /**
     * @var MessageTenantRepository
     */
    protected $messageRepo;

    /**
     * @var DispatchMessage
     */
    protected $dispatchMessage;

    /**
     * @var MergeContent
     */
    protected $mergeContent;

    /**
     * MessagesController constructor
     *
     * @param MessageTenantRepository $messageRepo
     * @param DispatchMessage $dispatchMessage
     * @param MergeContent $mergeContent
     */
    public function __construct(
        MessageTenantRepository $messageRepo,
        DispatchMessage $dispatchMessage,
        MergeContent $mergeContent
    )
    {
        $this->messageRepo = $messageRepo;
        $this->dispatchMessage = $dispatchMessage;
        $this->mergeContent = $mergeContent;
    }

    /**
     * Show all sent messages
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index()
    {
        $messages = $this->messageRepo->paginate(currentTeamId(), 'sent_at', [], 25, ['sent' => true]);

        return view('messages.index', compact('messages'));
    }

    /**
     * Show draft messages
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function draft()
    {
        $messages = $this->messageRepo->paginate(currentTeamId(), 'sent_at', [], 25, ['draft' => true]);

        return view('messages.index', compact('messages'));
    }

    /**
     * Show a single message
     *
     * @param int $messageId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function show(int $messageId)
    {
        $message = $this->messageRepo->find(currentTeamId(), $messageId);

        $content = $this->mergeContent->handle($message);

        return view('messages.show', compact('content', 'message'));
    }

    /**
     * Send a message
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function send()
    {
        if ( ! $message = $this->messageRepo->find(currentTeamId(), request('id' ), ['subscriber']))
        {
            return redirect()->back()->withErrors('Unable to locate that message');
        }

        if ($message->sent_at)
        {
            return redirect()->back()->withErrors('The selected message has already been sent');
        }

        $content = $this->mergeContent->handle($message);

        $this->dispatchMessage->handle($message, $content);

        return redirect()->route('messages.draft')->with('success', 'The message was sent successfully.');
    }
}