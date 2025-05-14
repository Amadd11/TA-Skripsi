@props(['record'])

@php
    // Pastikan pengingat ada dan dapat diubah menjadi timestamp
    $target = isset($record->pengingat) ? \Carbon\Carbon::parse($record->pengingat)->timestamp : time();
@endphp

<div x-data="{
    remaining: {{ $target }} - Math.floor(Date.now() / 1000),
    timer: null,
    formatTime(seconds) {
        if (seconds < 0) return 'Sudah lewat';
        const hrs = String(Math.floor(seconds / 3600)).padStart(2, '0');
        const mins = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
        const secs = String(seconds % 60).padStart(2, '0');
        return `${hrs}:${mins}:${secs}`;
    },
    startCountdown() {
        this.timer = setInterval(() => {
            this.remaining--;
            if (this.remaining < 0) {
                clearInterval(this.timer); // Stop the timer when it reaches 0
            }
        }, 1000);
    }
}" x-init="startCountdown()" x-text="formatTime(remaining)" class="font-mono text-sm text-rose-600">
</div>
