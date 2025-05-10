<?php

declare(strict_types=1);

namespace App\Services;

use App\Modules\Content\Domain\Storage as ContentStorage;
use App\Modules\Endpoints\Domain\Endpoint;
use App\Modules\Hits\Domain\HitDto;
use App\Repositories\EndpointHitRepository;

final readonly class TrafficControl
{
    public function __construct(
        private ContentStorage $contentStorage,
        private EndpointHitRepository $hitRepository,
    ) {
    }

    public function serve(Endpoint $endpoint, string $signature): string
    {
        $endpointId = $endpoint->id();

        $content = $this->getResponse($endpoint);

        $this->hitRepository->create(new HitDto($endpointId, $signature));

        return $content;
    }

    public function getResponse(Endpoint $endpoint): string
    {
        return $this->contentStorage->get($endpoint->path());
    }
}
