<?php

namespace Modules\Auth\Http\Controllers;

use Graphicode\Standard\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\Profession;
use Modules\Auth\Transformers\ProfessionResource;

class ProfessionController extends Controller
{
    use ApiResponses;

    public function __invoke(Request $request)
    {
        $query = Profession::query();

        if ($request->limit) {
            $query->limit($request->limit);
        }

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $professions = $query->get();

        return $this->okResponse(
            message: "Get get professions successfuly",
            data: [
                'count' => $professions->count(),
                'items' => ProfessionResource::collection($professions)
            ]
        );
    }
}
