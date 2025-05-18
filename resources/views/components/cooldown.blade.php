@props([
    'progress' => 0,
])

<div class="relative w-8 h-8 rounded-full bg-zinc-500">
    <div class="absolute inset-0 rounded-full" style="background: conic-gradient(var(--color-accent) {{ $progress }}%, var(--color-zinc-500) 0%);"></div>
    <div class="absolute inset-1 bg-accent-foreground rounded-full"></div>
</div>
