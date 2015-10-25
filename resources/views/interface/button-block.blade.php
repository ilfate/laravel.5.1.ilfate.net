<div class="rounded_block" >
    @if(isset($background))

    <img class="opacity-animation" src="<?= $background ?>">
    @else
    <div class="back"></div>
    @endif
  <span class="text">
   {{ $text }}
  </span>


</div>