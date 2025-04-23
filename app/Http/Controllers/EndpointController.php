<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateEndpointRequest;
use App\Modules\Endpoints\Infrastructure\Persistence\Eloquent\Endpoint as EloquentEndpoint;
use App\Services\EndpointsManager;
use App\Services\TrafficControl;
use App\Support\StubFieldContextMapper;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class EndpointController extends Controller
{
    use AuthorizesRequests;
    private const int PATH_LENGTH = 20;

    public function __construct(
        private readonly EndpointsManager $endpointsManager,
        private readonly TrafficControl $trafficControlService,
    ) {
    }

    public function index(Request $request): View
    {
        $endpoints = $this->endpointsManager->getEndpointList($request->user()->id);

        return view('dashboard', compact('endpoints'));
    }

    public function show(Request $request, EloquentEndpoint $endpoint): Response
    {
        $signature = md5($request->ip() . $request->header('User-Agent') . $request->session()->getId());
        $response = $this->trafficControlService->serve($endpoint->toEntity(), $signature);

        return response($response, HttpFoundationResponse::HTTP_OK)->header('Content-Type', 'application/json');
    }

    public function create(): View
    {
        $categories = StubFieldContextMapper::categoryMap();

        return view('endpoints.create', compact('categories'));
    }

    public function store(CreateEndpointRequest $request): RedirectResponse
    {
        $uuid = Str::uuid()->toString();
        $userId = $request->user()->id;
        $name = $request->validated('name');
        $inputs = $request->validated('inputs', []);
        $path = bin2hex(random_bytes(self::PATH_LENGTH));

        $this->endpointsManager->createEndpoint($uuid, $userId, $name, $path, $inputs);

        $endpointUrl = route('traffic.serve', ['endpoint' => $uuid]);

        $successMessage = sprintf('Endpoint created successfully. <a href="%s" class="bg-green-500 text-white text-xs py-1 px-3 uppercase font-semibold rounded" target="_blank">Visit</a>', $endpointUrl);

        session()->flash('success', $successMessage);

        return redirect()->route('dashboard');
    }

    public function delete(Request $request, EloquentEndpoint $endpoint): RedirectResponse
    {
        $this->authorize('delete', $endpoint);

        $this->endpointsManager->deleteEndpoint($endpoint->id, $endpoint->path);

        session()->flash('success', 'Endpoint deleted successfully.');

        return Redirect::route('dashboard');
    }
}
