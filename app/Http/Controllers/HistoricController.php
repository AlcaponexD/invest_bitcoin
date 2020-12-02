<?php
/**
 * Created by PhpStorm.
 * User: AlcaponexD
 * Date: 26/11/2020
 * Time: 23:59
 */

namespace App\Http\Controllers;


use App\Services\HistoricService;
use Illuminate\Http\Request;

class HistoricController extends Controller
{
    private $historic;

    public function __construct(HistoricService $historicService)
    {
        $this->historic = $historicService;
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function extract(Request $request)
    {
        $this->validate($request,[
            'interval' => ['integer']
        ]);

        return response()->json($this->historic->extract($request));
    }

    public function volume()
    {
        return response()->json($this->historic->volume());
    }
}