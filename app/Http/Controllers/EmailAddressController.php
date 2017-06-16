<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailAddressRequest;
use App\Interfaces\EmailAddressRepositoryInterface;
use Illuminate\Http\RedirectResponse;

class EmailAddressController extends Controller
{
    /**
     * @var EmailAddressRepositoryInterface
     */
    protected $emailAddressRepository;

    /**
     * @param EmailAddressRepositoryInterface $emailAddressRepository
     */
    public function __construct(
        EmailAddressRepositoryInterface $emailAddressRepository
    )
    {
        $this->emailAddressRepository = $emailAddressRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emails = $this->emailAddressRepository->paginate('email');

        return view('emails.index', compact('emails'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('emails.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  EmailAddressRequest $request
     * @return RedirectResponse
     */
    public function store(EmailAddressRequest $request)
    {
        $this->emailAddressRepository->store($request->all());

        return redirect()->route('emails.index');
    }
}
