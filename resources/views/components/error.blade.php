 @props([
    'name' => 'required',
])

 @error($name)
     <p class="mt-2 text-sm text-error">{{ $message }}</p>
 @enderror