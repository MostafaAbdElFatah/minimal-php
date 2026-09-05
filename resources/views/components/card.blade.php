@props([
    'header' => '',
    'bgColor' => '#e3e3e3',
    'textColor' => '#000000',
    'padding' => '1rem',
    'textAlign' => 'center',
    'borderRadius' => '0.5rem',
    'boxShadow' => '0 2px 4px rgba(0, 0, 0, 0.1)'
])

<div {{ $attributes->merge(['class' => 'card']) }}
     style="background-color: {{ $bgColor }}; 
     color: {{ $textColor }}; 
     text-align: {{ $textAlign }};
     padding: {{ $padding }}; 
     border-radius: {{ $borderRadius }}; 
     box-shadow: {{ $boxShadow }};
    ">
    <div class="card-header">
        {{ $header }}
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>