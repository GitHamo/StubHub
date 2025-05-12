<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Facades\DataContextFacade;
use App\Http\Requests\CreateEndpointRequest;
use App\Models\Eloquent\Endpoint as EloquentEndpoint;
use App\Services\EndpointsManager;
use App\Services\TrafficControl;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

final class EndpointController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private readonly EndpointsManager $endpointsManager,
        private readonly TrafficControl $trafficControlService,
    ) {
    }

    public function index(Request $request): View
    {
        /** @var \App\Models\User */
        $user = $request->user();
        $limit = $user->subscription_type->constraints()->maxEndpointsTotal();
        $endpoints = $this->endpointsManager->getEndpointList($user->id, $limit);

        $data = [
            'endpoints' => $endpoints,
            'maxTotalEndpointCount' => $limit,
        ];

        return view('dashboard', $data);
    }

    public function show(Request $request, EloquentEndpoint $endpoint): Response
    {
        $string = sprintf(
            '%s%s%s',
            (string) $request->ip(),
            (string) $request->header('User-Agent'),
            $request->session()->getId(),
        );

        $signature = md5($string);
        $response = $this->trafficControlService->serve($endpoint->toEntity(), $signature);

        return response($response, HttpFoundationResponse::HTTP_OK)->header('Content-Type', 'application/json');
    }

    public function download(EloquentEndpoint $endpoint): Response
    {
        try {
            $this->authorize('download', $endpoint);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            abort(404);
        }

        $json = $this->trafficControlService->getResponse($endpoint->toEntity());
        $filename = "stub-{$endpoint->id}.json";

        return response($json, HttpFoundationResponse::HTTP_OK, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => (new ResponseHeaderBag())
                ->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename),
        ]);
    }

    public function create(): View
    {
        $categories = DataContextFacade::categoryMap();

        return view('endpoints.create', ['categories' => $categories]);
    }

    public function store(CreateEndpointRequest $request): RedirectResponse
    {
        $uuid = Str::uuid()->toString();
        /**
         * @var \App\Models\User
         */
        $user = $request->user();

        /** @var string */
        $name = $request->validated('name');
        /** @var list<array<string, mixed>> */
        $inputs = $request->validated('inputs', []);

        $this->endpointsManager->createEndpoint($uuid, $user, $name, $inputs);

        $endpointUrl = route('traffic.serve', ['endpoint' => $uuid]);

        $successMessage = sprintf('Endpoint created successfully. <a href="%s" class="bg-green-500 text-white text-xs py-1 px-3 uppercase font-semibold rounded" target="_blank">Visit</a>', $endpointUrl);

        session()->flash('success', $successMessage);

        return redirect()->route('dashboard');
    }

    public function delete(EloquentEndpoint $endpoint): RedirectResponse
    {
        $this->authorize('deleteEndpoint', $endpoint);

        $this->endpointsManager->deleteEndpoint($endpoint->id, $endpoint->path);

        session()->flash('success', 'Endpoint deleted successfully.');

        return Redirect::route('dashboard');
    }
}
