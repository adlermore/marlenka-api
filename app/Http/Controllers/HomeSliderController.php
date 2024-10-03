<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUsStoreRequest;
use App\Http\Requests\HomeSliderStoreRequest;
use App\Services\ContactUsService;
use App\Services\HomeSliderService;
use Illuminate\Http\Request;

class HomeSliderController extends Controller
{
    private $homeSliderService;

    public function __construct(HomeSliderService $homeSliderService)
    {
        $this->homeSliderService = $homeSliderService;
    }

    public function index(Request $request)
    {
        return $this->homeSliderService->show($request);

    }

    public function store(HomeSliderStoreRequest $request)
    {
        return $this->homeSliderService->store($request);
    }
}
