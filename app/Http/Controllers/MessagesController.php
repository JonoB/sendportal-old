<?php

namespace App\Http\Controllers;

use App\Repositories\MessageEloquentRepository;
use App\Services\Content\MergeContent;
use App\Services\Messages\DispatchMessage;
use Illuminate\Routing\Pipeline;

class MessagesController extends Controller
{
    /**
     * @var MessageEloquentRepository
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
     * @param MessageEloquentRepository $messageRepo
     * @param DispatchMessage $dispatchMessage
     * @param MergeContent $mergeContent
     */
    public function __construct(
        MessageEloquentRepository $messageRepo,
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
     */
    public function index()
    {
        $messages = $this->messageRepo->paginate('sent_at', [], 25, ['sent' => true]);

        return view('messages.index', compact('messages'));
    }

    /**
     * Show draft messages
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function draft()
    {
        $messages = $this->messageRepo->paginate('sent_at', [], 25, ['draft' => true]);

        return view('messages.index', compact('messages'));
    }

    /**
     * Send a message
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send()
    {
        if ( ! $message = $this->messageRepo->find(request('id', ), ['subscriber']))
        {
            return redirect()->back()->withErrors('Unable to locate that message');
        }

        if ($message->sent_at)
        {
            return redirect()->back()->withErrors('The selected message has already been sent');
        }

        $content = $this->mergeContent->handle($message);

        $this->dispatchMessage->handle($message, $content);

        return redirect()->route('messages.draft');
    }
}