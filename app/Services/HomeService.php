<?php

namespace App\Services;

use App\Models\Address;
use App\Models\IncreasedProductPrice;
use App\Models\Location;
use App\Models\LocationProducts;
use App\Models\OrderProducts;
use App\Models\Orders;
use App\Models\Organizations;
use App\Models\Permission;
use App\Models\Product;
use App\Models\ProductFile;
use App\Models\ProductPhotos;
use App\Models\ProductType;
use App\Models\ProductTypeUnit;
use App\Models\ProductWorkingTime;
use App\Models\Responsible;
use App\Models\RolePermission;
use App\Models\Roles;
use App\Models\Room;
use App\Models\RoomProducts;
use App\Models\SellProducts;
use App\Models\Unit;
use App\Models\UsedProducts;
use App\Models\User;
use App\Models\UserLocation;
use App\Models\UserOrganization;
use App\Models\UserPermission;
use App\Models\UserRole;
use App\Models\UserRoom;
use DateTime;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HomeService
{
    const SERIALNUMBERLEN = '00000000';

    public function __construct()
    {
        //
    }

    public function setLocale($lang)
    {
        App::setLocale($lang);
        session()->put('lang', $lang);
        return Response::json(['isSuccess' => true, 'redirectUrl' => route('dashboard.index', ['locale' => app()->getLocale()])]);
    }

    public function getRoles()
    {

        if (!(Auth::user() and Auth::user()->can('showroles')) and !Auth::user()->can('createusers')) {
            return abort(403);
        }
        $roles = Roles::where('slug', '<>', 'superadmin')->get();
        return Response::json($roles);

    }

    public function getPermissions()
    {
        $permissions = new Permission();
        if (!Auth::user()->isSuperAdmin()) {
            $permArr = UserPermission::where('user_id', Auth::user()->id)->pluck('permission_id');
            $userRole = UserRole::where('user_id', Auth::user()->id)->pluck('role_id');
            $arr = RolePermission::whereIn('role_id', $userRole)->pluck('permission_id');
            $permissions = $permissions->whereIn('id', $permArr)->orWhereIn('id', $arr);
        }
        $permissions = $permissions->get();
        return Response::json($permissions);
    }

    public function getUnits()
    {
        $units = Unit::get();
        return Response::json($units);
    }

    public function getUnitsByProductType($request)
    {
        $data = [];
        $productTypeId = $request->id;
        $productTId = $request->productId;
        $unitIds = ProductTypeUnit::where('product_type_id', $productTypeId)->pluck('unit_id');
        $serialNumber = $this->convertSerialNumber($productTId, $productTypeId);

        $units = Unit::whereIn('id', $unitIds);
        $units = $units->get();
        $data['units'] = $units;
        $data['serialNumber'] = $serialNumber;
        return Response::json($data);
    }

    private function convertSerialNumber($productTId, $productTypeId)
    {
        $intSerialNumberObj = null;
        if ($productTId) {
            $intSerialNumberObj = Product::where('id', $productTId)->where('product_type_id', $productTypeId)->first();
        }
        if (!$intSerialNumberObj) {
            $intSerialNumberObj = Product::where('product_type_id', $productTypeId)->selectRaw('id,CAST(serial_number AS UNSIGNED) AS serial_number')->orderBy('serial_number', 'desc')->first();

        }
        if (isset($intSerialNumberObj) && $intSerialNumberObj) {

            $intSerialNumber = $intSerialNumberObj->serial_number;

            if ($intSerialNumber) {
                $numberLen = strlen((string)$intSerialNumber);
            }

            if ($productTId && $intSerialNumberObj->id == $productTId) {
                $newIntSerialNumber = $intSerialNumber;
            } else {
                $newIntSerialNumber = $intSerialNumber + 1;
            }

        } else {
            $newIntSerialNumber = 1;
            $numberLen = 1;
        }
        $newSerialNumber = substr_replace(self::SERIALNUMBERLEN, $newIntSerialNumber, '-' . $numberLen);

        return $newSerialNumber;
    }

    public function getProductTypes()
    {
        $productTypes = ProductType::get();
        return Response::json($productTypes);
    }

    public function getOrganizations()
    {
        $organizations = new Organizations();
        if (!Auth::user()->isSuperAdmin()) {
            $orgIds = UserOrganization::where('user_id', Auth::user()->id)->pluck('organization_id');
            if ($orgIds and count($orgIds)) {
                $organizations = $organizations->whereIn('id', $orgIds);
            } else {
                $organizations = $organizations->where('id', Auth::user()->organization_id);
            }
        }
        $organizations = $organizations->get();
        return Response::json($organizations);
    }

    public function getBrands($request)
    {
        $productBrands = [];
        if ($request->has('brand') and $request->brand != '') {
            $productBrands = Product::select('brand')->where('brand', 'LIKE', trim($request->brand) . '%')->groupBy('brand')->get();
        }
        return Response::json($productBrands);
    }

    public function getModels($request)
    {
        $productModels = [];
        if ($request->has('model') and $request->model != '') {
            $productModels = Product::select('model')->where('model', 'LIKE', trim($request->model) . '%')->groupBy('model')->get();
        }
        return Response::json($productModels);
    }

    public function getRooms()
    {
        $room = new Room();
        if (!Auth::user()->isSuperAdmin()) {
            $orgIds = UserOrganization::where('user_id', Auth::user()->id)->pluck('organization_id');
            if ($orgIds and count($orgIds)) {
                $room = $room->whereIn('rooms.organization_id', $orgIds);
            } else {
                $roomIds = UserRoom::where('user_id', Auth::user()->id)->pluck('room_id');
                if ($roomIds and count($roomIds)) {
                    $room = $room->whereIn('rooms.id', $roomIds);
                } else {
                    $orgId = Auth::user()->organization_id;
                    $room = $room->where('rooms.organization_id', $orgId);
                }
            }
        }

        $room = $room->get();
        return Response::json($room);
    }

    public function getTechnicians()
    {

        $technician = User::whereHas('roles', function ($query) {
            $query->where('slug', 'technician');
        });
        if (!Auth::user()->isSuperAdmin()) {
            $orgIds = UserOrganization::where('user_id', Auth::user()->id)->pluck('organization_id');
            if ($orgIds and count($orgIds)) {
                $technician = $technician->whereIn('users.organization_id', $orgIds);
            } else {
                $technician = $technician->whereIn('users.organization_id', Auth::user()->organization_id);

            }
        }

        $technician = $technician->get();
        return Response::json($technician);
    }

    public function getLocations()
    {

        $locations = new Location();
        if (!Auth::user()->isSuperAdmin()) {
            $locations = $locations->where('organization_id', Auth::user()->organization_id);

            $orgIds = UserOrganization::where('user_id', Auth::user()->id)->pluck('organization_id');
            if ($orgIds and count($orgIds)) {
                $locations = $locations->whereIn('organization_id', $orgIds);
            } else {
                $locationIds = UserLocation::where('user_id', Auth::user()->id)->pluck('location_id');
                if ($locationIds and count($locationIds)) {
                    $locations = $locations->whereIn('id', $locationIds);
                } else {
                    $orgId = Auth::user()->organization_id;
                    $locations = $locations->where('organization_id', $orgId);
                }
            }
        }
        $locations = $locations->get();
        return Response::json($locations);
    }

    public function getAddress()
    {
        $address = new Address();
        if (!Auth::user()->isSuperAdmin()) {
            $address = $address->where('organization_id', Auth::user()->organization_id);
        }

        $address = $address->with('location')->get();
        return Response::json($address);
    }

    public function getProductSendHistory($request)
    {

        $table = 'log_send_products';
        if (!Auth::user()->isSuperAdmin()) {
            $org_id = Auth::user()->organization_id;
            if ($org_id) {
                $table = $table . '_' . $org_id;
            } else {
                return abort(403);
            }
        } else {
            $organizationId = Product::find($request->id)->getOrganizationId();
            $table = $table . '_' . $organizationId;
        }


        $send = DB::table($table)->select(
            "{$table}.date",
            "{$table}.count",
            DB::raw("CONCAT(users.name,'/',users.email) AS userName"),
            DB::raw("CASE
            WHEN {$table}.from = 'Room' THEN CONCAT(FromRoom.name,'/',FromRoom.address)
            WHEN {$table}.from = 'Address' THEN CONCAT(FromLocationAddress.address,'/',FromLocationAddress.floor,'/',FromLocationAddress.room,'/',FromLocationAddress.description,'/',FromAddress.description)
            WHEN {$table}.from = 'Location' THEN CONCAT(FromLocation.address,'/',FromLocation.floor,'/',FromLocation.room,'/',FromLocation.description)
            WHEN {$table}.from = 'Technician' THEN CONCAT(FromTechnician.name,'/',FromTechnician.email)
            WHEN {$table}.from = 'Service' THEN 'Service'
            WHEN {$table}.from = 'Completed' THEN 'Completed'
            ELSE 'Home' END AS fromR"),
            DB::raw("CASE
            WHEN {$table}.to = 'Room' THEN CONCAT(ToRoom.name,'/',ToRoom.address)
            WHEN {$table}.to = 'Address' THEN CONCAT(ToLocationAddress.address,'/',ToLocationAddress.floor,'/',ToLocationAddress.room,'/',ToLocationAddress.description,'/',ToAddress.description)
            WHEN {$table}.to = 'Location' THEN CONCAT(ToLocation.address,'/',ToLocation.floor,'/',ToLocation.room,'/',ToLocation.description)
            WHEN {$table}.to = 'Technician' THEN CONCAT(ToTechnician.name,'/',ToTechnician.email)
            WHEN {$table}.to = 'Service' THEN 'Service'
            WHEN {$table}.to = 'Completed' THEN 'Completed'
            WHEN {$table}.to = 'Used' THEN 'Used'
            WHEN {$table}.to = 'Sell' THEN 'Sell'
            ELSE 'Home' END AS toR")
        )
            ->leftJoin('rooms as FromRoom', function ($q) use ($table) {
                $q->on('FromRoom.id', "{$table}.from_id")
                    ->where("{$table}.from", 'Room');
            })
            ->leftJoin('addresses as FromAddress', function ($q) use ($table) {
                $q->on('FromAddress.id', "{$table}.from_id")
                    ->where("{$table}.from", 'Address');
            })
            ->leftJoin('locations as FromLocationAddress', 'FromLocationAddress.id', '=', 'FromAddress.location_id')
            ->leftJoin('locations as FromLocation', function ($q) use ($table) {
                $q->on('FromLocation.id', "{$table}.from_id")
                    ->where("{$table}.from", 'Location');
            })
            ->leftJoin('users as FromTechnician', function ($q) use ($table) {
                $q->on('FromTechnician.id', "{$table}.from_id")
                    ->where("{$table}.from", 'Technician');
            })
            ->leftJoin('rooms as ToRoom', function ($q) use ($table) {
                $q->on('ToRoom.id', "{$table}.to_id")
                    ->where("{$table}.to", 'Room');
            })
            ->leftJoin('addresses as ToAddress', function ($q) use ($table) {
                $q->on('ToAddress.id', "{$table}.to_id")
                    ->where("{$table}.to", 'Address');
            })
            ->leftJoin('locations as ToLocationAddress', 'ToLocationAddress.id', '=', 'ToAddress.location_id')
            ->leftJoin('locations as ToLocation', function ($q) use ($table) {
                $q->on('ToLocation.id', "{$table}.to_id")
                    ->where("{$table}.to", 'Location');
            })
            ->leftJoin('users as ToTechnician', function ($q) use ($table) {
                $q->on('ToTechnician.id', "{$table}.to_id")
                    ->where("{$table}.to", 'Technician');
            })
            ->join('users', 'users.id', '=', "{$table}.user_id");

        $send = $send->where("{$table}.product_id", $request->id);

        $send = $send->orderBy("{$table}.id")->get();
        return Response::json($send);
    }

    public function getResponsibleProducts($request)
    {
        $ids = [];
        $location = Location::where('responsible_id', $request->id)->first();
        if ($location) {
            $ids = array_merge($ids, $location->getProductIds());
            $ids = array_merge($ids, $location->getProductInAddressIds());

        }
        $address = Address::where('responsible_id', $request->id)->first();
        if ($address) {
            $ids = array_merge($ids, $address->getProductIds());
        }
        $room = Room::where('responsible_id', $request->id)->first();
        if ($room) {
            $ids = array_merge($ids, $room->getProductIds());
        }
        $products = Product::whereIn('id', $ids)->get();
        return Response::json($products);
    }

    public function getResponsible()
    {

        $responsible = new Responsible();
        if (!Auth::user()->isSuperAdmin()) {
            $responsible = $responsible->where('organization_id', Auth::user()->organization_id);
        }

        $responsible = $responsible->get();
        return Response::json($responsible);
    }

    public function getAccountingPrice($request)
    {

        if (!$request || !$request->id) {
            return Response::json(0);
        }
        $newPrice = self::accountingPrice($request->id);
        return Response::json($newPrice);
    }

    public static function accountingPrice($productId)
    {

        if (!$productId) {
            return 0;
        }
        $product = Product::where('id', $productId)->first();
        $price = $product->price;
        $start = $product->produced_date;
        $end = $product->deadline_date;
        $start = new DateTime($start);
        $end = new DateTime($end);
        $interval = $end->diff($start);
        $monthDifference = $interval->m;
        $yearDifference = $interval->y;
        $monthDifference = $monthDifference + $yearDifference * 12;
        if ($monthDifference < 0) {
            return Response::json(0);
        }
        if ($monthDifference === 0) {
            $monthDifference = 1;
        }
        $monthPrice = $price / $monthDifference;
        $end = new DateTime();
        $interval = $end->diff($start);
        $monthDifference = $interval->m;
        $yearDifference = $interval->y;
        $monthDifference = $monthDifference + $yearDifference * 12;
        if ($monthDifference === 0) {
            $monthDifference = 1;
        }
        $newPrice = $price - ($monthPrice * $monthDifference);
        if ($newPrice < 0) {
            return Response::json(0);

        }
        return $newPrice;
    }

    public function getLogicPrice($request)
    {
        if (!$request || !$request->id) {
            return Response::json(0);
        }

        $price = self::logicPrice($request->id);
        return Response::json($price);
    }

    public static function logicPrice($productId)
    {

        if (!$productId) {
            return 0;
        }

        // step 1
        $product = Product::where('id', $productId)->first();
        $price = $product->price;
        $realPrice = $product->real_price;
        $price = ($price + $realPrice) / 2;
        // step 1

        // step 2
        $start = $product->produced_date;
        $end = $product->deadline_date;
        $start = new DateTime($start);
        $end = new DateTime($end);
        $interval = $end->diff($start);
        $yearDifference = $interval->y;
        $monthDifference = $interval->m;
        $dayDifference = $interval->d;

        $dayDifference = ($monthDifference + $yearDifference * 12) * 30 + $dayDifference;

        if ($dayDifference < 0) {
            $dayDifference = 0;
        }
        $allHour = $dayDifference * 24;

        $hourPrice = $price / ($allHour != 0 ? $allHour : 1);
        // step 2

        // step 3
        $end = new DateTime();
        $interval = $end->diff($start);

        $yearDifference = $interval->y;
        $monthDifference = $interval->m;
        $dayDifference = $interval->d;

        $dayDifference = ($monthDifference + $yearDifference * 12) * 30 + $dayDifference;
        if ($dayDifference < 0) {
            $dayDifference = 0;
        }
        $inOrganizationHour = $dayDifference * 24;

        $workingTime = ProductWorkingTime::where('product_id', $productId)->first();
        if ($workingTime) {
            $workingTime = $workingTime->hours;
        } else {
            $workingTime = 0;
        }

        $usedHourPrice = $workingTime * $hourPrice;
        $freeHourPrice = ($inOrganizationHour - $workingTime) * ($hourPrice * 0.3);

        // step 3

        // step 4
        $price = $price - $usedHourPrice - $freeHourPrice;
        // step 4

        // step 5
        $addedPrice = IncreasedProductPrice::where('product_id', $productId)->get();
        foreach ($addedPrice as $obj) {
            $price += ($obj->increased_price * ($obj->coefficient / 100));
        }
        // step 5
        if ($price < 0) {
            $price = 0;
        }


        return $price;
    }

    public function getProductUsedAndSellCount($request)
    {

        if (!$request || !$request->id) {
            return Response::json(["message" => __('variable.data_error')], 400);
        }

        $data = [];
        $productUsedCount = 0;
        $productSellCount = 0;

        $usedProduct = UsedProducts::where('product_id', $request->id)->get();
        if (count($usedProduct)) {
            foreach ($usedProduct as $item) {
                $productUsedCount += $item->count;
            }
        }

        $sellProduct = SellProducts::where('product_id', $request->id)->get();

        if (count($sellProduct)) {
            foreach ($sellProduct as $item) {
                $productSellCount += $item->count;
            }
        }

        $data['used_count'] = $productUsedCount;
        $data['sell_count'] = $productSellCount;

        return Response::json($data);

    }

    public function getHomeNameId($request)
    {
        if (!$request || !$request->id) {
            return Response::json(0);
        }
        $data = Product::find($request->id);
        if ($data) {
            $data = $data->getHomeNameId();
        } else {
            $data = ["name" => "sold", "id" => null];
        }

        return Response::json($data);
    }

    public function uploadFile($request)
    {
        if (!$request || !$request->id || !$request->file) {
            return Response::json(["message" => __('variable.data_error')], 400);
        }
        $product = Product::where('id', $request->id)->first();

        if (isset($product->isAuction) && $product->isAuction) {
            return Response::json(['isSuccess' => false, 'message' => __('variable.unable_operation')]);
        }
        $validateArray = ["pdf", "doc", "docx", "xlsx"];
        if ($request->file and $request->file != 'null') {
            if (!in_array($request->file->extension(), $validateArray)) {
                return Response::json(["message" => __('variable.format_error')], 400);
            }
        }

        try {
            DB::beginTransaction();
            $file = $request->file;
            if ($file and $file != 'null') {
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('storage/files'), $filename);
                $productFile = ProductFile::where('product_id', $request->id)->first();
                if ($productFile) {
                    $oldFileName = $productFile->file;
                    if (count(ProductFile::where('file', $oldFileName)->get()) <= 1) {
                        $this->removeFiles("storage/files", $oldFileName);
                    }
                } else {
                    $productFile = new ProductFile();
                }
                $productFile->product_id = $request->id;
                $productFile->file = $filename;
                $productFile->save();
            }

            DB::commit();
            return Response::json(['isSuccess' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json(["message" => $e->getMessage()], 400);
        }


    }

    public function uploadPhoto($request)
    {
        if (!$request || !$request->id) {
            return Response::json(["message" => __('variable.data_error')], 400);
        }
        $product = Product::where('id', $request->id)->first();

        if (isset($product->isAuction) && $product->isAuction) {
            return Response::json(['isSuccess' => false, 'message' => __('variable.unable_operation')]);
        }
        if (!$request->photo and !$request->existPhotoSrc and !ProductPhotos::where('product_id', $request->id)->first()) {
            return Response::json(['isSuccess' => true]);
        }
        $count = 0;
        if ($request->photo and count($request->photo)) {
            $count += count($request->photo);
        }
        if ($request->existPhotoSrc and count($request->existPhotoSrc)) {
            $count += count($request->existPhotoSrc);
        }
        if ($count >= 10) {
            return Response::json(["message" => __('variable.max_photo_count_10')], 400);

        }

        $validateArray = ["png", "jpg", "jpeg"];
        if ($request->photo and count($request->photo)) {
            foreach ($request->photo as $obj) {
                if (!in_array($obj->extension(), $validateArray)) {
                    return Response::json(["message" => __('variable.format_error')], 400);
                }
            }
        }


        try {

            DB::beginTransaction();
            $oldPhotos = ProductPhotos::where('product_id', $request->id)->get();
            if ($request->existPhotoSrc and count($request->existPhotoSrc)) {
                foreach ($oldPhotos as $old) {
                    $oldPhoto = $old->photo;
                    if (!in_array($oldPhoto, $request->existPhotoSrc)) {
                        if (count(ProductPhotos::where('photo', $oldPhoto)->get()) <= 1) {
                            $this->removeFiles("storage/photos", $oldPhoto);
                        }
                        $old->delete();
                    }
                }
            } else {
                foreach ($oldPhotos as $old) {
                    $oldPhoto = $old->photo;
                    if (count(ProductPhotos::where('photo', $oldPhoto)->get()) <= 1) {
                        $this->removeFiles("storage/photos", $oldPhoto);
                    }
                    $old->delete();
                }
            }

            if ($request->photo and count($request->photo)) {
                if ($request->photo and count($request->photo)) {
                    foreach ($request->photo as $file) {
                        $filename = date('YmdHis') . $file->getClientOriginalName();
                        $file->move(public_path('storage/photos'), $filename);
                        $productPhotos = new ProductPhotos();
                        $productPhotos->product_id = $request->id;
                        $productPhotos->photo = $filename;
                        $productPhotos->save();
                    }
                }

            }

            DB::commit();
            return Response::json(['isSuccess' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json(["message" => $e->getMessage()], 400);
        }


    }

    public function getProductFile($request)
    {
        if (!$request || !$request->id) {
            return Response::json(["message" => __('variable.data_error')], 400);
        }
        $productFile = ProductFile::where('product_id', $request->id)->first();
        return Response::json($productFile);
    }

    public function getProductPhotos($request)
    {
        if (!$request || !$request->id) {
            return Response::json(["message" => __('variable.data_error')], 400);
        }
        $productPhotos = ProductPhotos::where('product_id', $request->id)->get();
        return Response::json($productPhotos);
    }

    private function removeFiles($path, $file)
    {

        if (\File::exists($path . '/' . $file)) {
            \File::delete($path . '/' . $file);
        }
    }

    public function printBarCode($request)
    {
        if (!$request or !$request->serial_number) {
            return Response::json(['isSuccess' => false, 'message' => __('variable.first_write_serial_number')]);
        }
        if (!$request or !$request->brand) {
            return Response::json(['isSuccess' => false, 'message' => __('variable.first_write_brand')]);
        }
        if (!$request or !$request->model) {
            return Response::json(['isSuccess' => false, 'message' => __('variable.first_write_model')]);
        }
        if (!$request or !$request->product_type_id) {
            return Response::json(['isSuccess' => false, 'message' => __('variable.first_write_product_type')]);
        }

        try {
//            $productType = ProductType::find($request->product_type_id)->eng_name;

            $brand = str_replace(' ', '_', $request->brand);
            $model = str_replace(' ', '_', $request->model);
            $qr = substr($brand, 0, 2) . "-" . substr($model, 0, 2) . "-" . $request->serial_number;
            $image = QrCode::format('png')
//            ->merge('img/t.jpg', 0.1, true)
                ->size(200)->errorCorrection('H')
                ->generate($qr);
            $output_file = 'qrCodes/' . $qr . '.png';
            Storage::disk('local')->put($output_file, $image);
        } catch (\Exception $e) {
            return Response::json(['isSuccess' => false, 'message' => $e->getMessage()]);
        }
        return Response::json(['isSuccess' => true, 'barCode' => $qr]);
    }

}
