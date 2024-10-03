<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsStoreRequest;
use App\Services\ContactUsService;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    private $contactUsService;

    public function __construct(ContactUsService $contactUsService)
    {
        $this->contactUsService = $contactUsService;
    }

    public function index(Request $request)
    {
        return $this->contactUsService->show($request);

    }

    public function store(ContactUsStoreRequest $request)
    {
        return $this->contactUsService->store($request);
    }
}
