<?php 
  // HACER EL CONTERO DE TODOS LOS PRODUCTOS MAS VENDITOS
  $query = 'SELECT producto, COUNT( producto ) AS total FROM ventas GROUP BY producto ORDER BY total DESC';
 ?>