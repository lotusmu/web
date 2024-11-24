<?php


use Livewire\Volt\Component;

new class extends Component {
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

<div>
    @if(session()->has('toast'))
        @php
            $toast = session('toast');
            $this->showToast(
                $toast['text'],
                $toast['heading'],
                $toast['variant']
            );
        @endphp
    @endif
</div>
