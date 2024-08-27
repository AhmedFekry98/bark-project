<?php

namespace Modules\World\Http\Controllers;

use Graphicode\Standard\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\World\Entities\City;

class WorldController extends Controller
{
    use ApiResponses;

    public function cities(Request $request)
    {
        $query = City::select(['id', 'name']);

        // apply limit if has limit!
        if ($request->limit) {
            $query->limit($request->limit);
        }


        // apply search if has search!
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $cities = $query->get();

        return $this->okResponse(
            message: "Get cities successfuly",
            data: [
                'count' => $cities->count(),
                'items' => $cities
            ]
        );
    }
}
