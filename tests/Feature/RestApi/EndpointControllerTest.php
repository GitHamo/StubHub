<?php

declare(strict_types=1);

namespace Tests\Feature\RestApi;

use App\Models\Endpoint;
use App\Models\StubContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class EndpointControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testDownloadsExistingEndpointByOwner(): void
    {
        $user = User::factory()->create();

        $uri = $this->createEndpointDownloadUri($user);

        $response = $this->actingAs($user)->get($uri);

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertHeader('Content-Disposition');

        // Check Content-Disposition for file
        $contentDisposition = (string) $response->headers->get('Content-Disposition');

        // Check Content-Disposition contains "attachment"
        static::assertStringContainsString(
            'attachment; filename=',
            $contentDisposition
        );
        // Check file name is valid UUID, versions (v1–v5)
        static::assertMatchesRegularExpression(
            '/attachment;\s*filename="?stub-[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}\.json"?/i',
            $contentDisposition
        );

        // Check JSON response for file
        $json = json_decode($response->getContent() ?: '', true);

        static::assertIsArray($json);
        static::assertArrayHasKey('created_at', $json);
        static::assertArrayHasKey('message', $json);
    }

    public function testDownloadsExistingEndpointByNonOwner(): void
    {
        [$owner, $user] = User::factory(2)->create();

        $uri = $this->createEndpointDownloadUri($owner);

        $response = $this->actingAs($user)->get($uri);

        $response->assertNotFound();
    }

    public function testDownloadsNonExistingEndpoint(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/endpoints/notfound/download');

        $response->assertNotFound();
    }

    private function createEndpointDownloadUri(User $user): string
    {
        $endpoint = Endpoint::factory()->for($user)->create();
        $endpointId = $endpoint->id;
        $endpointPath = $endpoint->path;

        /**
         * @var string
         */
        $secretKey = Config::get('app.key', '');
        $decodedKey = base64_decode(explode(':', $secretKey)[1] ?? $secretKey);
        $stubName = hash_hmac('sha256', $endpointPath, $decodedKey);
        $stubContent = [
            "message" => "This is stub file $endpointPath.",
            "created_at" => now(),
        ];
        StubContent::create([
            'filename' => $stubName,
            'content' => json_encode($stubContent, JSON_PRETTY_PRINT),
        ]);

        return sprintf('/endpoints/%s/download', $endpointId);
    }
}
