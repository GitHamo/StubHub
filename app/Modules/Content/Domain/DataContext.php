<?php

declare(strict_types=1);

namespace App\Modules\Content\Domain;

interface DataContext
{
    /**
     * @return array<string, string[]>
     */
    public function flatMap(): array;

    /**
     * @return array<string, array{
     *     label: string,
     *     inputs: list<array{
     *         label: string,
     *         value: string,
     *         input: string
     *     }>
     * }>
     */
    public function categoryMap(): array;
}
