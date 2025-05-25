<?php

namespace App\Actions\Partner;

use App\Services\PartnerAccessService;
use Illuminate\Http\Request;

class PartnerRouteHandler
{
    public function __invoke(Request $request)
    {
        $service = new PartnerAccessService(auth()->user());

        return redirect()->route($service->getRedirectRoute());
    }
}
