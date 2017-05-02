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
     * @param int $newsletterId
     * @param int $contactId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function opens(Request $request, $newsletterId, $contactId)
    {

    }

    /**
     * Track email clicks
     *
     * @param Request $request
     * @param int $newsletterId
     * @param int $linkId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function clicks(Request $request, $newsletterId, $linkId)
    {
    }
 }
