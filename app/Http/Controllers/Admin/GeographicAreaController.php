<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeographicArea\StoreGovernorateRequest;
use App\Models\Governorate;
use App\Models\City;
use App\Models\District;
use App\Models\Zone;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeographicAreaController extends Controller
{
   public function index(Request $request)
{
    try {
        $query = Governorate::query()
            ->with([
                'cities' => function ($q) {
                    $q->withCount('districts')
                      ->with([
                          'districts' => function ($q) {
                              $q->withCount('zones')
                                ->with([
                                    'zones' => function ($q) {
                                        $q->withCount('areas');
                                    }
                                ]);
                          }
                      ]);
                }
            ])
            ->withCount('cities')
            ->orderBy('name');

        if ($request->trashed === 'only') {
            $query->onlyTrashed();
        }

        $governorates = $query->get()->map(function ($gov) {
            $gov->districts_total = $gov->cities->sum('districts_count');

            $gov->zones_total = $gov->cities
                ->flatMap->districts
                ->sum('zones_count');

            $gov->areas_total = $gov->cities
                ->flatMap->districts
                ->flatMap->zones
                ->sum('areas_count');

            return $gov;
        });

        return view(
            'dashboards.admin.pages.geographic-areas.index',
            compact('governorates')
        );

    } catch (\Exception $e) {
        Log::error('GeographicAreaController@index: ' . $e->getMessage());
        return back()->with('error', 'حدث خطأ أثناء تحميل البيانات');
    }
}

    public function storeGovernorate(StoreGovernorateRequest $request)
    {
        try {
            DB::beginTransaction();
            $governorate = Governorate::create($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المحافظة بنجاح',
                'governorate' => $governorate,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@storeGovernorate: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الإضافة'], 500);
        }
    }

    public function updateGovernorate(StoreGovernorateRequest $request, Governorate $governorate)
    {
        try {
            DB::beginTransaction();
            $governorate->update($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المحافظة بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@updateGovernorate: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء التحديث'], 500);
        }
    }

    public function destroyGovernorate(Governorate $governorate)
    {
        try {
            DB::beginTransaction();
            $governorate->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المحافظة بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@destroyGovernorate: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الحذف'], 500);
        }
    }

    public function showGovernorate(Governorate $governorate)
    {
        try {
            $governorate->load(['cities.districts.zones.areas']);

            return response()->json([
                'success' => true,
                'governorate' => $governorate,
            ]);
        } catch (\Exception $e) {
            Log::error('GeographicAreaController@showGovernorate: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ'], 500);
        }
    }

    public function storeCity(Request $request)
    {
        try {
            $request->validate([
                'governorate_id' => 'required|exists:governorates,id',
                'name' => 'required|string|max:255',
            ]);

            DB::beginTransaction();
            $city = City::create($request->all());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المدينة بنجاح',
                'city' => $city,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@storeCity: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الإضافة'], 500);
        }
    }

    public function updateCity(Request $request, City $city)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            DB::beginTransaction();
            $city->update($request->only(['name']));
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المدينة بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@updateCity: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء التحديث'], 500);
        }
    }

    public function destroyCity(City $city)
    {
        try {
            DB::beginTransaction();
            $city->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف المدينة بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@destroyCity: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الحذف'], 500);
        }
    }

    public function storeDistrict(Request $request)
    {
        try {
            $request->validate([
                'city_id' => 'required|exists:cities,id',
                'name' => 'required|string|max:255',
            ]);

            DB::beginTransaction();
            $district = District::create($request->all());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الحي بنجاح',
                'district' => $district,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@storeDistrict: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function destroyDistrict(District $district)
    {
        try {
            DB::beginTransaction();
            $district->delete();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'تم حذف الحي بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@destroyDistrict: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function storeZone(Request $request)
    {
        try {
            $validated = $request->validate([
                'district_id' => 'required|exists:districts,id',
                'name' => 'required|string|max:255',
            ]);

            DB::beginTransaction();
            $zone = Zone::create($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المنطقة بنجاح',
                'zone' => $zone,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'خطأ في البيانات المدخلة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@storeZone: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyZone(Zone $zone)
    {
        try {
            DB::beginTransaction();
            $zone->delete();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'تم حذف المنطقة بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@destroyZone: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function storeArea(Request $request)
    {
        try {
            $validated = $request->validate([
                'zone_id' => 'required|exists:zones,id',
                'name' => 'required|string|max:255',
            ]);

            DB::beginTransaction();
            $area = Area::create($validated);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة القطاع بنجاح',
                'area' => $area,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'خطأ في البيانات المدخلة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@storeArea: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyArea(Area $area)
    {
        try {
            DB::beginTransaction();
            $area->delete();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'تم حذف القطاع بنجاح']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@destroyArea: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function getAreaLands(Area $area)
    {
        try {
            $lands = $area->lands()
                ->with('client')
                ->paginate(20);

            return response()->json([
                'html' => view('dashboards.admin.pages.geographic-areas.partials.area-lands', compact('lands', 'area'))->render()
            ]);
        } catch (\Exception $e) {
            Log::error('GeographicAreaController@getAreaLands: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function getCitiesByGovernorate($governorateId)
    {
        try {
            $cities = City::where('governorate_id', $governorateId)
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json($cities);
        } catch (\Exception $e) {
            Log::error('GeographicAreaController@getCitiesByGovernorate: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function getDistrictsByCity($cityId)
    {
        try {
            $districts = District::where('city_id', $cityId)
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json($districts);
        } catch (\Exception $e) {
            Log::error('GeographicAreaController@getDistrictsByCity: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function getZonesByDistrict($districtId)
    {
        try {
            $zones = Zone::where('district_id', $districtId)
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json($zones);
        } catch (\Exception $e) {
            Log::error('GeographicAreaController@getZonesByDistrict: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function getAreasByZone($zoneId)
    {
        try {
            $areas = Area::where('zone_id', $zoneId)
                ->orderBy('name')
                ->get(['id', 'name']);

            return response()->json($areas);
        } catch (\Exception $e) {
            Log::error('GeographicAreaController@getAreasByZone: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function showCity(City $city)
    {
        try {
            $city->load(['districts']);

            return response()->json([
                'success' => true,
                'city' => $city,
            ]);
        } catch (\Exception $e) {
            Log::error('GeographicAreaController@showCity: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ'], 500);
        }
    }

    public function showDistrict(District $district)
    {
        try {
            $district->load(['zones']);

            return response()->json([
                'success' => true,
                'district' => $district,
            ]);
        } catch (\Exception $e) {
            Log::error('GeographicAreaController@showDistrict: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ'], 500);
        }
    }

    public function updateDistrict(Request $request, District $district)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            DB::beginTransaction();
            $district->update($request->only(['name']));
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الحي بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@updateDistrict: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء التحديث'], 500);
        }
    }

    public function showZone(Zone $zone)
    {
        try {
            $zone->load(['areas']);

            return response()->json([
                'success' => true,
                'zone' => $zone,
            ]);
        } catch (\Exception $e) {
            Log::error('GeographicAreaController@showZone: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ'], 500);
        }
    }

    public function updateZone(Request $request, Zone $zone)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            DB::beginTransaction();
            $zone->update($request->only(['name']));
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المنطقة بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@updateZone: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء التحديث'], 500);
        }
    }

    public function showArea(Area $area)
    {
        try {
            return response()->json([
                'success' => true,
                'area' => $area,
            ]);
        } catch (\Exception $e) {
            Log::error('GeographicAreaController@showArea: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ'], 500);
        }
    }

    public function updateArea(Request $request, Area $area)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            DB::beginTransaction();
            $area->update($request->only(['name']));
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث القطاع بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('GeographicAreaController@updateArea: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء التحديث'], 500);
        }
    }
}
