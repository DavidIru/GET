<h1>
  Albaranes
  
</h1>
 
 
<ul>
  @foreach($albaranes as $albaran)
  <!-- Equivalente en Blade a <?php //foreach ($usuarios as $usuario) ?> -->
    <li>
      {{ $albaran->NumeroDocumento }} 
    </li>
    <!-- Equivalente en Blade a <?php //echo $usuario->nombre.' '.$usuario->apellido ?> -->
  @endforeach 
</ul>