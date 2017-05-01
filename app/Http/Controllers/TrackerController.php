<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Interfaces\ContactRepositoryInterface;
use Illuminate\Http\Request;

class TrackerController extends Controller
{
    /**
     * @var ContactRepositoryInterface
     */
    protected $contactRepository;

    /**
     * ContactsController constructor.
     *
     * @param ContactRepositoryInterface $contactRepository
     */
    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * Track email opens
     *
     * @param Request $request
     * @param int $contactId
     * @param int $newsletterId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function opens(Request $request, $contactId, $newsletterId)
    {
        $contacts = $this->contactRepository->paginate('email');

        return view('contacts.index', compact('contacts'));
    }

    /**
     * Track email clicks
     *
     * @param Request $request
     * @param int $contactId
     * @param int $newsletterId
     * @param int $newsletterLinkId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clicks(Request $request, $contactId, $newsletterId, $newsletterLinkId)
    {
        return view('contacts.create');
    }
 }
