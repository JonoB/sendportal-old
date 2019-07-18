<?php

namespace App\Http\Controllers;

use App\Repositories\DeliveryEloquentRepository;
use App\Services\Deliveries\DispatchDelivery;
use App\Services\Deliveries\MarkDeliverySent;
use Illuminate\Http\Request;

class DeliveriesController extends Controller
{
    /**
     * @var DeliveryEloquentRepository
     */
    protected $deliveryRepo;

    /**
     * @var DispatchDelivery
     */
    private $dispatchService;

    public function __construct(DeliveryEloquentRepository $deliveryRepo, DispatchDelivery $dispatchService)
    {
        $this->deliveryRepo = $deliveryRepo;
        $this->dispatchService = $dispatchService;
    }

    public function index()
    {
        $deliveries = $this->deliveryRepo->paginate('sent_at', [], 25, ['sent' => true]);

        return view('deliveries.index', compact('deliveries'));
    }

    public function draft()
    {
        $deliveries = $this->deliveryRepo->paginate('sent_at', [], 25, ['draft' => true]);

        return view('deliveries.index', compact('deliveries'));
    }

    public function send()
    {
        $delivery = $this->deliveryRepo->find(request('id'));

        if ($delivery->sent_at)
        {
            return redirect()->route('deliveries.index')->withErrors(['The selected message has already been sent']);
        }

        //$mailService = strtolower(str_replace(' ', '', $campaign->provider->type->name));

        $mailService = 'mailgun';

        $messageId = $this->dispatchService->handle(
            $mailService,
            $delivery->from_email,
            $delivery->recipient_email,
            $delivery->subject,
            'This is temporary email content'
        );

        (new MarkDeliverySent())->handle($delivery);

        return redirect()->route('deliveries.draft');
    }
}
