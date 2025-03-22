<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSettingRequest;
use App\Http\Requests\UpdateSettingRequest;
use App\Models\Setting;

/**
 * @OA\Tag(
 *     name="Settings",
 *     description="API Endpoints for managing settings"
 * )
 */
class SettingController extends Controller
{
    /**
     * Display the settings.
     *
     * @OA\Get(
     *     path="/api/settings",
     *     summary="Get settings",
     *     tags={"Settings"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\Response(response=200, description="Settings retrieved successfully")
     * )
     */
    public function index()
    {
        return response()->json(Setting::firstOrFail());
    }

    /**
     * Update settings.
     *
     * @OA\Post(
     *     path="/api/settings",
     *     summary="Update settings",
     *     tags={"Settings"},
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"time_update_service", "time_update_order", "time_deny_order", "time_exchange_rate", "account_no"},
     *             @OA\Property(property="time_update_service", type="string", example="14:30:00"),
     *             @OA\Property(property="time_update_order", type="string", example="15:00:00"),
     *             @OA\Property(property="time_deny_order", type="string", example="16:00:00"),
     *             @OA\Property(property="time_exchange_rate", type="string", example="17:30:00"),
     *             @OA\Property(property="account_no", type="string", example="123456789"),
     *             @OA\Property(property="phone", type="string", example="123-456-7890"),
     *             @OA\Property(property="facebook", type="string", example="https://facebook.com/example"),
     *             @OA\Property(property="zalo", type="string", example="0987654321"),
     *             @OA\Property(property="bank_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Settings updated successfully")
     * )
     */
    public function storeOrUpdate(StoreSettingRequest $request)
    {

        $setting = Setting::findOrfail(1);
        $setting->update([
            'time_update_service' => $request->time_update_service,
            'time_update_order' => $request->time_update_order,
            'time_deny_order' => $request->time_deny_order,
            'time_exchange_rate' => $request->time_exchange_rate,
            'account_no' => $request->account_no,
            'phone' => $request->phone,
            'facebook' => $request->facebook,
            'zalo' => $request->zalo
        ]);

        return response()->json($setting, 201);
    }
}
