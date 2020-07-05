<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LinkRequestLog;
use App\Services\Links\Exceptions\NotUniqueHashGenerateException;
use App\Services\Links\LinksShortService;
use App\Services\Links\LinkThrottleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use \App\Models\Links;
use \Symfony\Component\HttpFoundation\Response;

class LinksController extends Controller
{
    /**
     * @param Request $request
     * @param LinksShortService $LinksService
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request, LinksShortService $LinksService)
    {
        $validator = Validator::make($request->all(), [
            'url' => [
                'required',
                'min:10',
                'regex:/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i'
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNAUTHORIZED);
        }

        $Link = Links::where('link_hash', $linkHash = md5($request->post('url')))->first();
        if (!is_null($Link)) {
            return response()->json(["url" => route('api.link.redirect', ['hash' => $Link->link_short_shuffle])]);
        }

        try {
            $Link = Links::create([
                "link_hash" => $linkHash,
                "link_short_shuffle" => $LinksService->shortHash($request->post('url')),
                "link" => $request->post('url')
            ]);
        }catch (NotUniqueHashGenerateException $e){
            return response()->json(['error' => $e->getMessage() . ' Please, try again.'], Response::HTTP_BAD_REQUEST);
        }

        Cache::put($linkHash, "",-1);

        return response()->json(["url" => route('api.link.redirect', ['hash' => $Link->link_short_shuffle])]);
    }

    /**
     * @param $hash
     * @param Request $request
     * @param LinkThrottleRequest $LinkThrottleRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function short($hash, Request $request, LinkThrottleRequest $LinkThrottleRequest)
    {
        $Link = Cache::remember($hash, 60 * 60, function () use ($hash) {
            return Links::where('link_short_shuffle', $hash)->first();
        });

        $userAgent = $request->header('User-Agent');
        $userIp = $request->ip();

        if (is_null($Link)) {
            $LinkThrottleRequest
                ->setParam($userIp)
                ->setParam($userAgent)
                ->saveError();
            abort(Response::HTTP_NOT_FOUND);
        }

        LinkRequestLog::create([
            'referrer' => $_SERVER['HTTP_REFERER'] ?? "",
            'link_id' => $Link->id,
            'user_ip' => $userIp,
            'user_agent' => $userAgent
        ]);

        return redirect()->away($Link->link, Response::HTTP_MOVED_PERMANENTLY);
    }

}
