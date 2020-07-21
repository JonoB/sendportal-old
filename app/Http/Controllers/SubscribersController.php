<?php

namespace App\Http\Controllers;

use App\Events\SubscriberAddedEvent;
use App\Http\Requests\SubscriberRequest;
use App\Repositories\SubscriberTenantRepository;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscribersController extends Controller
{
    /**
     * @var SubscriberTenantRepository
     */
    protected $subscriberRepository;

    /**
     * SubscribersController constructor.
     *
     * SubscribersController constructor.
     * @param SubscriberTenantRepository $subscriberRepository
     */
    public function __construct(SubscriberTenantRepository $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
    }

    /**
     * @return Factory|View
     * @throws Exception
     */
    public function index()
    {
        $subscribers = $this->subscriberRepository->paginate(currentTeamId(), 'first_name', [], 50, request()->all());

        return view('subscribers.index', compact('subscribers'));
    }

    /**
     * @return string|StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     * @throws Exception
     */
    public function export()
    {
        $subscribers = $this->subscriberRepository->all(currentTeamId(), 'id');

        if ( ! $subscribers->count())
        {
            return redirect()->route('subscribers.index')->withErrors('There are no subscribers to export');
        }

        return (new FastExcel($subscribers))->download(sprintf('subscribers-%s.csv', date('Y-m-d-H-m-s')), function ($subscriber)
        {
            return [
                'id' => $subscriber->id,
                'hash' => $subscriber->hash,
                'email' => $subscriber->email,
                'first_name' => $subscriber->first_name,
                'last_name' => $subscriber->last_name,
                'created_at' => $subscriber->created_at,
            ];
        });
    }

    /**
     * @return Factory|View
     * @throws Exception
     */
    public function create()
    {
        return view('subscribers.create');
    }

    /**
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(SubscriberRequest $request)
    {
        $subscriber = $this->subscriberRepository->store(currentTeamId(), $request->all());

        event(new SubscriberAddedEvent($subscriber));

        return redirect()->route('subscribers.index');
    }

    /**
     * @return Factory|View
     * @throws Exception
     */
    public function show(int $id)
    {
        $subscriber = $this->subscriberRepository->find(currentTeamId(), $id);

        return view('subscribers.show', compact('subscriber'));
    }

    /**
     * @return Factory|View
     * @throws Exception
     */
    public function edit(int $id)
    {
        $subscriber = $this->subscriberRepository->find(currentTeamId(), $id);

        $data = [
            'subscriber' => $subscriber,
        ];

        return view('subscribers.edit', $data);
    }

    /**
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(SubscriberRequest $request, int $id)
    {
        $this->subscriberRepository->update(currentTeamId(), $id, $request->all());

        return redirect()->route('subscribers.index');
    }
}
