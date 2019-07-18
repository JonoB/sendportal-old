<?php

namespace App\Http\Controllers;

use App\Repositories\MessageEloquentRepository;
use App\Services\Messages\DispatchMessage;
use App\Services\Messages\MarkMessageSent;
use App\Services\Messages\ResolveProvider;
use Illuminate\Http\Request;

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

    public function __construct(MessageEloquentRepository $deliveryRepo, DispatchMessage $dispatchMessage)
    {
        $this->messageRepo = $deliveryRepo;
        $this->dispatchMessage = $dispatchMessage;
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
        if ( ! $message = $this->messageRepo->find(request('id')))
        {
            return redirect()->back()->withErrors('Unable to locate that message');
        }

        if ($message->sent_at)
        {
            return redirect()->back()->withErrors('The selected message has already been sent');
        }

        $this->dispatchMessage->handle($message);

        return redirect()->route('messages.draft');
    }
}