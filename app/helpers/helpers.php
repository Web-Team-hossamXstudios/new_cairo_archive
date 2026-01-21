<?php

use App\Models\Word;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

if (! function_exists('x_')) {
    function x_($param, $section = null)
    {
        if ($param == null || $param === '') {
            return;
        }

        try {
            $lang = App::getLocale();

            $word = null;

            $baseQuery = Word::query()
                ->where('param', $param)
                ->whereNull('wordable_id')
                ->whereNull('wordable_type');

            if ($section !== null && $section !== '') {
                $word = (clone $baseQuery)
                    ->where('field_name', $section)
                    ->first();
            }

            if (! $word) {
                $word = (clone $baseQuery)
                    ->whereNull('field_name')
                    ->first();
            }

            if (! $word) {
                DB::beginTransaction();

                $word = new Word();
                $word->param = $param;
                $word->en = $param;
                $word->ar = '';
                $word->field_name = $section;
                $word->wordable_id = null;
                $word->wordable_type = null;
                $word->save();

                DB::commit();
            }

            if ($lang === 'ar') {
                if (! empty($word->ar)) {
                    return $word->ar;
                }

                return $word->en;
            }

            return $word->en;
        } catch (\Exception $e) {
            try {
                DB::rollBack();
            } catch (\Exception $rollbackException) {
            }

            Log::error('x_() helper error: ' . $e->getMessage(), [
                'param' => $param,
                'section' => $section,
            ]);

            return $param;
        }
    }
}

if (! function_exists('x')) {
    function x($param, $section = null)
    {
        return x_($param, $section);
    }
}
