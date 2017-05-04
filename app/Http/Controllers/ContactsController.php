<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Interfaces\ContactRepositoryInterface;
use App\Interfaces\SegmentRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    /**
     * @var ContactRepositoryInterface
     */
    protected $contactRepository;

    /**
     * @var SegmentRepositoryInterface
     */
    protected $segmentRepository;

    /**
     * ContactsController constructor.
     *
     * @param ContactRepositoryInterface $contactRepository
     */
    public function __construct(
        ContactRepositoryInterface $contactRepository,
        SegmentRepositoryInterface $segmentRepository
    )
    {
        $this->contactRepository = $contactRepository;
        $this->segmentRepository = $segmentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = $this->contactRepository->paginate('email', ['segments']);

        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'segments' => $this->segmentRepository->all(),
            'selectedSegments' => [],
        ];

        return view('contacts.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ContactRequest $request
     * @return RedirectResponse
     */
    public function store(ContactRequest $request)
    {
        $contact = $this->contactRepository->store($request->all());
        $this->contactRepository->syncSegments($contact, $request->get('segments'));

        return redirect()->route('contacts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('contacts.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contact = $this->contactRepository->find($id, ['segments']);

        $data = [
            'contact' => $contact,
            'segments' => $this->segmentRepository->all(),
            'selectedSegments' => selectedOptions('segments', $contact),
        ];

        return view('contacts.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ContactRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(ContactRequest $request, $id)
    {
        $contact = $this->contactRepository->update($id, $request->all());
        $this->contactRepository->syncSegments($contact, $request->get('segments'));

        return redirect()->route('contacts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
