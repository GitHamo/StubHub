@props([
    'backgroundColor' => '#282550',
    'bracesColor' => '#FFFFFF',
    'boltColor' => '#FFAA00',
    'dotColor' => '#3CB371',
])

<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <!-- Background rounded square -->
    <rect x="10" y="10" width="180" height="180" rx="40" ry="40" fill="{{ $backgroundColor }}" />
    <!-- Left curly brace (reduced) -->
    <path d="M60,55 C52,55 52,65 52,73 L52,85 C52,93 48,100 40,100 C48,100 52,107 52,115 L52,127 C52,135 52,145 60,145" 
        fill="none" stroke="{{ $bracesColor }}" stroke-width="8" stroke-linecap="round"/>
    <!-- Right curly brace (reduced) -->
    <path d="M140,55 C148,55 148,65 148,73 L148,85 C148,93 152,100 160,100 C152,100 148,107 148,115 L148,127 C148,135 148,145 140,145" 
        fill="none" stroke="{{ $bracesColor }}" stroke-width="8" stroke-linecap="round"/>
    <!-- Lightning bolt (enlarged) -->
    <path d="M115,50 L80,100 L100,100 L85,150 L120,95 L100,95 L115,50" 
        fill="{{ $boltColor }}" stroke="{{ $boltColor }}" stroke-width="1"/>
    <!-- Green dot (unchanged) -->
    <circle cx="170" cy="150" r="10" fill="{{ $dotColor }}"/>
</svg>