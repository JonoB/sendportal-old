<?php

namespace App\Http\Controllers;

use App\Repositories\DeliveryEloquentRepository;
use Illuminate\Http\Request;

class DeliveriesController extends Controller
{
    protected $deliveryRepo;

    public function __construct(DeliveryEloquentRepository $deliveryRepo)
    {
        $this->deliveryRepo = $deliveryRepo;
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
}
