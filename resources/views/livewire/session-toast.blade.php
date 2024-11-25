<?php

use Livewire\Volt\Component;

new class extends Component {
    public function mount(): void
    {
        if (session()->has('toast')) {
            $toast = session('toast');
            $this->showToast(
                $toast['text'],
                $toast['heading'] ?? null,
                $toast['variant'] ?? null
            );
        }
    }

    protected $listeners = ['showToast' => 'showToast'];

    public function showToast($text, $heading = null, $variant = null): void
    {
        Flux::toast(
            text: $text,
            heading: $heading,
            variant: $variant
        );
    }
}
?>

<div></div>
