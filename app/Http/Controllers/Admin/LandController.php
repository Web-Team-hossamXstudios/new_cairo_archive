<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Land\StoreLandRequest;
use App\Models\Land;
use App\Models\Client;
use App\Models\Governorate;
use App\Models\City;
use App\Models\District;
use App\Models\Zone;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LandController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Land::query()
                ->with(['client', 'governorate', 'city', 'district', 'zone', 'area'])
                ->withCount('mainFiles as files_count');

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('land_no', 'like', "%{$request->search}%")
                      ->orWhere('unit_no', 'like', "%{$request->search}%")
                      ->orWhereHas('client', fn($q2) => $q2->where('name', 'like', "%{$request->search}%"));
                });
            }

            if ($request->filled('client_id')) {
                $query->where('client_id', $request->client_id);
            }

            if ($request->filled('governorate_id')) {
                $query->where('governorate_id', $request->governorate_id);
            }

            if ($request->trashed === 'only') {
                $query->onlyTrashed();
            }

            $perPage = $request->per_page ?? 25;
            $lands = $query->latest()->paginate($perPage)->withQueryString();
            $clients = Client::orderBy('name')->get();
            $governorates = Governorate::orderBy('name')->get();

            return view('dashboards.admin.pages.lands.index', compact('lands', 'clients', 'governorates'));
        } catch (\Exception $e) {
            Log::error('LandController@index: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل البيانات');
        }
    }

    public function store(StoreLandRequest $request)
    {
        try {
            DB::beginTransaction();
            $land = Land::create($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة القطعة بنجاح',
                'land' => $land->load(['client', 'governorate', 'city']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('LandController@store: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء إضافة القطعة'], 500);
        }
    }

    public function show(Land $land)
    {
        try {
            $land->load(['client', 'governorate', 'city', 'district', 'zone', 'area', 'mainFiles.items']);
            return response()->json([
                'success' => true,
                'land' => $land
            ]);
        } catch (\Exception $e) {
            Log::error('LandController@show: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'حدث خطأ'], 500);
        }
    }

    public function update(StoreLandRequest $request, Land $land)
    {
        try {
            DB::beginTransaction();
            $land->update($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث بيانات القطعة بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('LandController@update: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء التحديث'], 500);
        }
    }

    public function destroy(Land $land)
    {
        try {
            DB::beginTransaction();
            $land->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف القطعة بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('LandController@destroy: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الحذف'], 500);
        }
    }

    public function getCities($governorateId)
    {
        $cities = City::where('governorate_id', $governorateId)->orderBy('name')->get();
        return response()->json(['cities' => $cities]);
    }

    public function getDistricts($cityId)
    {
        $districts = District::where('city_id', $cityId)->orderBy('name')->get();
        return response()->json(['districts' => $districts]);
    }

    public function getZones($districtId)
    {
        $zones = Zone::where('district_id', $districtId)->orderBy('name')->get();
        return response()->json(['zones' => $zones]);
    }

    public function getAreas($zoneId)
    {
        $areas = Area::where('zone_id', $zoneId)->orderBy('name')->get();
        return response()->json(['areas' => $areas]);
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();
            Land::withTrashed()->findOrFail($id)->restore();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم استرجاع القطعة بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('LandController@restore: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();
            Land::withTrashed()->findOrFail($id)->forceDelete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم الحذف النهائي بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('LandController@forceDelete: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:lands,id']);

            DB::beginTransaction();
            Land::whereIn('id', $request->ids)->delete();
            DB::commit();

            Log::info('Lands bulk deleted', ['ids' => $request->ids, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف القطع بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('LandController@bulkDelete: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'حدث خطأ أثناء الحذف'], 500);
        }
    }

    public function bulkRestore(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

            DB::beginTransaction();
            Land::withTrashed()->whereIn('id', $request->ids)->restore();
            DB::commit();

            Log::info('Lands bulk restored', ['ids' => $request->ids, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'تم استرجاع القطع بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('LandController@bulkRestore: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'حدث خطأ أثناء الاسترجاع'], 500);
        }
    }

    public function bulkForceDelete(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

            DB::beginTransaction();
            Land::withTrashed()->whereIn('id', $request->ids)->forceDelete();
            DB::commit();

            Log::info('Lands bulk force deleted', ['ids' => $request->ids, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'تم الحذف النهائي للقطع بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('LandController@bulkForceDelete: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'حدث خطأ أثناء الحذف النهائي'], 500);
        }
    }
}
