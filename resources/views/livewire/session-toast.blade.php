<?php

use Livewire\Volt\Component;

new class extends Component {
    public function mount()
    {
        if (session()->has('toast')) {
            $toast = session('toast');
            $this->showToast(
                $toast['text'],
                $toast['heading'] ?? null,
                $toast['variant'] ?? 'success'
            );
        }
    }

    protected $listeners = ['showToast' => 'showToast'];

    public function showToast($text, $heading = null, $variant = 'success')
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
