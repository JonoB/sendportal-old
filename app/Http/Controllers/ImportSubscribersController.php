<?php

namespace App\Http\Controllers;

use App\Services\Subscribers\ApiSubscriberService;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportSubscribersController extends Controller
{
    /**
     * @var ApiSubscriberService
     */
    protected $subscriberService;

    /**
     * ApiSubscribberService $subscriberService
     *
     * @param ApiSubscriberService $subscriberService
     */
    public function __construct(ApiSubscriberService $subscriberService)
    {
        $this->subscriberService = $subscriberService;
    }

    /**
     * Show the form for upload file
     *
     * @return View
     */
    public function show()
    {
        return view('subscribers.import');
    }

    /**
     * Store the Subscribers from the uploaded file
     *
     * @param  Request $request
     * @return Redirect
     */
    public function store(Request $request)
    {
        if ($request->file('file')->isValid())
        {
            $path = $request->file('file')->storeAs('imports', str_random(16) . '.csv');

            $subscribers = (new FastExcel)->import(storage_path('app/'. $path), function ($line)
            {
                try {
                    $this->subscriberService->store(array_only($line, ['email', 'first_name', 'last_name']));
                } catch (\Exception $e) {
                    \Log::warn($e->getMessage());
                }
            });

            return redirect()->route('subscribers.index')
                ->with('success', sprintf('Imported %d subscribers', $subscribers->count()));
        }

        return redirect()->route('subscribers.index')
            ->with('errors', $this->addToErrorsBag('file', 'The uploaded file is not valid'));
    }
}
