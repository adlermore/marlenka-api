<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAddressRequest;
use App\Http\Requests\GetBrandModelRequest;
use App\Http\Requests\GetLocationsRequest;
use App\Http\Requests\GetPermissionsRequest;
use App\Http\Requests\GetProductSendHistoryRequest;
use App\Http\Requests\GetProductTypesRequest;
use App\Http\Requests\GetResponsibleProductsRequest;
use App\Http\Requests\GetResponsibleRequest;
use App\Http\Requests\GetRoomsRequest;
use App\Http\Requests\GetTechniciansRequest;
use App\Http\Requests\GetUnitsByProductTypeRequest;
use App\Http\Requests\GetUnitsRequest;
use App\Http\Requests\OrganizationShowRequest;
use App\Http\Requests\PrintBarCodeRequest;
use App\Services\HomeService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home');
    }

    public function abort403()
    {
        return abort(403);
    }



    // FILE SYSTEM

    public function uploadFile(Request $request)
    {
        return $this->homeService->uploadFile($request);

    }

    public function uploadPhoto(Request $request)
    {
        return $this->homeService->uploadPhoto($request);
    }

    public function getProductFile(Request $request)
    {
        return $this->homeService->getProductFile($request);
    }

    public function getProductPhotos(Request $request)
    {
        return $this->homeService->getProductPhotos($request);
    }


    // FILE SYSTEM


}
