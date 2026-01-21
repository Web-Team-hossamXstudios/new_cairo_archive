<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Item\StoreItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        try {
            // API request - return JSON
            if ($request->wantsJson() || $request->is('api/*')) {
                $items = Item::orderBy('order')->orderBy('name')->get();
                return response()->json([
                    'success' => true,
                    'items' => $items
                ]);
            }

            // Web request - return view
            $query = Item::query()->withCount('files');

            if ($request->filled('search')) {
                $query->where('name', 'like', "%{$request->search}%");
            }

            if ($request->trashed === 'only') {
                $query->onlyTrashed();
            }

            $items = $query->orderBy('order')->orderBy('name')->paginate(25)->withQueryString();

            return view('dashboards.admin.pages.items.index', compact('items'));
        } catch (\Exception $e) {
            Log::error('ItemController@index: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'حدث خطأ'], 500);
            }
            return back()->with('error', 'حدث خطأ أثناء تحميل البيانات');
        }
    }

    public function store(StoreItemRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = Item::create($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة نوع المحتوى بنجاح',
                'item' => $item,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ItemController@store: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الإضافة'], 500);
        }
    }

    public function update(Request $request, Item $item)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255', Rule::unique('items', 'name')->ignore($item->id)],
                'description' => 'nullable|string',
                'order' => 'required|integer|min:0',
            ]);

            DB::beginTransaction();
            $item->update($request->only(['name', 'description', 'order']));
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث نوع المحتوى بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ItemController@update: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء التحديث'], 500);
        }
    }

    public function destroy(Item $item)
    {
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف نوع المحتوى بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ItemController@destroy: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء الحذف'], 500);
        }
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();
            Item::withTrashed()->findOrFail($id)->restore();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم استرجاع نوع المحتوى بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ItemController@restore: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function forceDelete($id)
    {
        try {
            DB::beginTransaction();
            Item::withTrashed()->findOrFail($id)->forceDelete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم الحذف النهائي بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ItemController@forceDelete: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ'], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:items,id']);

            DB::beginTransaction();
            Item::whereIn('id', $request->ids)->delete();
            DB::commit();

            Log::info('Items bulk deleted', ['ids' => $request->ids, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الأنواع بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ItemController@bulkDelete: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'حدث خطأ أثناء الحذف'], 500);
        }
    }

    public function bulkRestore(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

            DB::beginTransaction();
            Item::withTrashed()->whereIn('id', $request->ids)->restore();
            DB::commit();

            Log::info('Items bulk restored', ['ids' => $request->ids, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'تم استرجاع الأنواع بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ItemController@bulkRestore: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'حدث خطأ أثناء الاسترجاع'], 500);
        }
    }

    public function bulkForceDelete(Request $request)
    {
        try {
            $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);

            DB::beginTransaction();
            Item::withTrashed()->whereIn('id', $request->ids)->forceDelete();
            DB::commit();

            Log::info('Items bulk force deleted', ['ids' => $request->ids, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'تم الحذف النهائي للأنواع بنجاح',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ItemController@bulkForceDelete: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'حدث خطأ أثناء الحذف النهائي'], 500);
        }
    }
}
