<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PhysicalLocation\StoreRoomRequest;
use App\Http\Requests\PhysicalLocation\StoreLaneRequest;
use App\Http\Requests\PhysicalLocation\StoreStandRequest;
use App\Http\Requests\PhysicalLocation\StoreRackRequest;
use App\Models\Room;
use App\Models\Lane;
use App\Models\Stand;
use App\Models\Rack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PhysicalLocationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $rooms = Room::with(['lanes.stands.racks'])
                ->withCount('lanes')
                ->orderBy('building_name')
                ->orderBy('name')
                ->get();

            if ($request->trashed === 'only') {
                $rooms = Room::onlyTrashed()
                    ->withCount('lanes')
                    ->orderBy('building_name')
                    ->orderBy('name')
                    ->get();
            }

            return view('dashboards.admin.pages.physical-locations.index', compact('rooms'));
        } catch (\Exception $e) {
            Log::error('PhysicalLocationController@index: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء تحميل البيانات');
        }
    }

    public function storeRoom(StoreRoomRequest $request)
    {
        try {
            DB::beginTransaction();
            $room = Room::create($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الغرفة بنجاح',
                'room' => $room,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PhysicalLocationController@storeRoom: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء إضافة الغرفة'], 500);
        }
    }

    public function updateRoom(StoreRoomRequest $request, Room $room)
    {
        try {
            DB::beginTransaction();
            $room->update($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الغرفة بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PhysicalLocationController@updateRoom: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء التحديث'], 500);
        }
    }

    public function destroyRoom(Room $room)
    {
        try {
            DB::beginTransaction();
            $room->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الغرفة بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PhysicalLocationController@destroyRoom: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الحذف'], 500);
        }
    }

    public function showRoom(Room $room)
    {
        try {
            $room->load(['lanes.stands.racks' => function ($query) {
                $query->withCount('files');
            }]);

            return response()->json([
                'success' => true,
                'room' => $room,
            ]);
        } catch (\Exception $e) {
            Log::error('PhysicalLocationController@showRoom: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء تحميل البيانات'], 500);
        }
    }

    public function storeLane(StoreLaneRequest $request)
    {
        try {
            DB::beginTransaction();
            $lane = Lane::create($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الممر بنجاح',
                'lane' => $lane->load('room'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PhysicalLocationController@storeLane: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء إضافة الممر'], 500);
        }
    }

    public function updateLane(StoreLaneRequest $request, Lane $lane)
    {
        try {
            DB::beginTransaction();
            $lane->update($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الممر بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PhysicalLocationController@updateLane: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء التحديث'], 500);
        }
    }

    public function destroyLane(Lane $lane)
    {
        try {
            DB::beginTransaction();
            $lane->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الممر بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PhysicalLocationController@destroyLane: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الحذف'], 500);
        }
    }

    public function storeStand(StoreStandRequest $request)
    {
        try {
            DB::beginTransaction();
            $stand = Stand::create($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الستاند بنجاح',
                'stand' => $stand->load('lane'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PhysicalLocationController@storeStand: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء إضافة الستاند'], 500);
        }
    }

    public function updateStand(StoreStandRequest $request, Stand $stand)
    {
        try {
            DB::beginTransaction();
            $stand->update($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الستاند بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PhysicalLocationController@updateStand: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء التحديث'], 500);
        }
    }

    public function destroyStand(Stand $stand)
    {
        try {
            DB::beginTransaction();
            $stand->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الستاند بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PhysicalLocationController@destroyStand: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الحذف'], 500);
        }
    }

    public function storeRack(StoreRackRequest $request)
    {
        try {
            DB::beginTransaction();
            $rack = Rack::create($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الرف بنجاح',
                'rack' => $rack->load('stand'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PhysicalLocationController@storeRack: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء إضافة الرف'], 500);
        }
    }

    public function updateRack(StoreRackRequest $request, Rack $rack)
    {
        try {
            DB::beginTransaction();
            $rack->update($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الرف بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PhysicalLocationController@updateRack: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء التحديث'], 500);
        }
    }

    public function destroyRack(Rack $rack)
    {
        try {
            DB::beginTransaction();
            $rack->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الرف بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PhysicalLocationController@destroyRack: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الحذف'], 500);
        }
    }

    public function getRackFiles(Rack $rack)
    {
        try {
            $files = $rack->files()
                ->mainFiles()
                ->with(['client', 'land'])
                ->paginate(20);

            return response()->json([
                'html' => view('dashboards.admin.pages.physical-locations.partials.rack-files', compact('files', 'rack'))->render()
            ]);
        } catch (\Exception $e) {
            Log::error('PhysicalLocationController@getRackFiles: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function getLanes(Room $room)
    {
        return response()->json(['lanes' => $room->lanes]);
    }

    public function getStands(Lane $lane)
    {
        return response()->json(['stands' => $lane->stands]);
    }

    public function getRacks(Stand $stand)
    {
        return response()->json(['racks' => $stand->racks]);
    }
}
