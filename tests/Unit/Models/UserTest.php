<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Enums\SubscriptionType;
use App\Enums\UserRole;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    private User $model;
    private int $userId;
    private UserRole $role;
    private SubscriptionType $subscriptionType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = User::factory([
            'id' => $this->userId = mt_rand(),
            'role' => $this->role = static::getRandomEnum(UserRole::class),
            'subscription_type' => $this->subscriptionType = static::getRandomEnum(SubscriptionType::class),
        ])->make();
    }

    public function testHasAccessorToId(): void
    {
        static::assertSame($this->userId, $this->model->getId());
    }

    public function testHasAccessorToRole(): void
    {
        static::assertSame($this->role, $this->model->getRole());
    }

    public function testHasAccessorToSubscriptionType(): void
    {
        static::assertSame($this->subscriptionType, $this->model->getSubscriptionType());
    }

    private static function getRandomEnum(string $enum)
    {
        return $enum::cases()[random_int(0, count($enum::cases()) - 1)];
    }
}
