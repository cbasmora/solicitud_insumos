<?php
require('fpdf/fpdf.php');

class PDF extends FPDF
{
    // Función para envolver automáticamente con utf8_decode
    function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false)
    {
        parent::MultiCell($w, $h, utf8_decode($txt), $border, $align, $fill);
    }

    // Sobrescribe el método Cell para incluir utf8_decode y ser compatible con FPDF
    function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        $txt = utf8_decode($txt);
        parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }

    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 8, 'CLÍNICA MEINTEGRAL S.A.S', 0, 1, 'C');

        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'FORMATO', 0, 1, 'C');
        $this->Cell(0, 5, 'REGISTRO DE INSUMOS Y DISPOSITIVOS MÉDICOS', 0, 1, 'C');

        $this->SetDrawColor(200, 200, 200);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY(), $this->GetPageWidth() - 10, $this->GetY());

        // Ajusta la ruta de la imagen 'logo.png' según la ubicación real
        $this->Image('img/logo.png', 10, 5, 40);

        $this->SetFont('Arial', 'B', 10);
        $this->Ln(1);

        $posX = $this->GetX();
        $posY = $this->GetY();

        $anchoDoc = $this->GetPageWidth();
        $this->SetXY($anchoDoc - 40, 15);

        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 5, 'Código: M-I-FT-FIDM-TV-285', 0, 1, 'R');
        $this->Cell(0, 5, 'Versión: 1.0  Año: 2024', 0, 1, 'R');

        $this->SetXY($posX, $posY);
        $this->SetTextColor(0, 0, 0);
    }

    // Función para obtener la hora actual de Colombia
    function getServerTime()
    {
        // Definir la zona horaria de Colombia
        $timezone = 'America/Bogota';

        // Crear un objeto DateTime con la zona horaria específica
        $dateTime = new DateTime('now', new DateTimeZone($timezone));

        // Formatear la fecha y hora en el formato deseado
        return $dateTime->format('Y-m-d H:i:s');
    }

    function Footer()
    {
        // Obtener la hora actual de Colombia
        $horaActual = $this->getServerTime();

        // Generar un número único basado en la fecha y un valor aleatorio
        $numeroUnico = mt_rand(100000000000, 9000000000000);

        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo() . ' -  PACI ' . $horaActual . ' - Número único de impresión: ' . 'INS' . $numeroUnico, 0, 0, 'C');
    }

    function PrintForm($data)
    {
        // Establecer el fondo azul y texto blanco para las celdas en negrita
        $this->SetFillColor(200, 220, 255); // Azul claro
        $this->SetTextColor(0); // Texto negro

        // Títulos con fondo azul y datos correspondientes
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(12, 10, 'Fecha:', 0, 0, 'L',); // Título Fecha con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(20, 10, $data['fecha'], 0); // Fecha
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(10, 10, 'Hora:', 0, 0, 'L',); // Título Hora con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 10, $data['hora'], 0);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(50, 10, 'N° HC', 0, 0, 'R',); // Título N° HC con fondo azul
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(60, 10, $data['numeroDocumento'], 0); // Nro de HC
        $this->Ln(10);

        // Datos del paciente
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Paciente:', 1, 0, 'L', true); // Título Paciente con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(69, 5, $data['paciente'], 1); // Paciente
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Identificación:', 1, 0, 'L', true); // Título Documento con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 5, $data['tipoDocumento'] . ' - ' . $data['numeroDocumento'], 1); // Tipo de documento y número
        $this->Ln();

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Sexo:', 1, 0, 'L', true); // Título Sexo con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(69, 5, $data['sexo'], 1); // Sexo
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Edad:', 1, 0, 'L', true); // Título Edad con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 5, $data['edad'], 1); // Edad
        $this->Ln();

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Cama:', 1, 0, 'L', true); // Título Cama con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(69, 5, $data['cama'], 1); // Cama
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 5, 'Servicio:', 1, 0, 'L', true); // Título Servicio con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(60, 5, $data['servicio'], 1); // Servicio
        $this->Ln(10);

        // Restablecer colores y fuentes normales para los datos posteriores
        $this->SetFillColor(255); // Restablecer el color de fondo a blanco
        $this->SetTextColor(0); // Restablecer el color de texto a negro

        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 6, 'Entidad:', 1, 0, 'L', true); // Título Entidad con fondo azul
        $this->SetFont('Arial', '', 10);
        $this->Cell(159, 6, $data['entidad'] . ' - ' . $data['tipo'], 1, true); // Tipo de documento y número
        $this->Ln(1);



        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 15, 'INSUMOS SOLICITADOS', 0, 1, 'C');

        $header = array(
            'N°',
            'NOMBRE DEL INSUMO',
            'CANTIDAD',
            'DESP',
            'VDO',
        );
        
        $this->SetFillColor(200, 220, 255);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetFont('Arial', 'B', 7);
        
        // Ajusta los anchos de las columnas
        $w = array(20, 150, 20, 10, 10); // Ajusta los anchos según tu diseño
        
        $anchoTotal = array_sum($w);
        $anchoDisponible = $this->GetPageWidth() - $this->lMargin - $this->rMargin;
        
        // Ajusta los anchos si exceden el espacio disponible
        if ($anchoTotal > $anchoDisponible) {
            $factorAjuste = $anchoDisponible / $anchoTotal;
            foreach ($w as &$valor) {
                $valor *= $factorAjuste;
            }
            unset($valor);
        }
        
        foreach ($header as $key => $value) {
            if ($key == 0 || $key == 2) {
                $this->Cell($w[$key], 5, $value, 1, 0, 'C', true); // Centrado para 'N°' y 'CANTIDAD'
            } else {
                $this->Cell($w[$key], 5, $value, 1, 0, 'C', true); // Izquierda para los demás
            }
        }
        $this->Ln();
        
        $this->SetFont('Arial', '', 9);
        foreach ($data['medicamentos'] as $row) {
            $maxLines = 0;
            foreach ($row as $key => $value) {
                $nb = $this->NbLines($w[$key], $value);
                if ($nb > $maxLines) {
                    $maxLines = $nb;
                }
            }
        
            $alturaPorLinea = 5; // Ajusta este valor para cambiar la altura de cada línea
            $h = $alturaPorLinea * $maxLines;
            $margen = 2; // Ajusta este valor para cambiar el margen
        
            foreach ($row as $key => $value) {
                $x = $this->GetX();
                $y = $this->GetY();
                $this->Rect($x, $y, $w[$key], $h);
        
                // Guarda la posición actual
                $currentX = $this->GetX();
                $currentY = $this->GetY();
        
                // Calcula la altura del texto
                $nb = $this->NbLines($w[$key] - 2 * $margen, $value); // Ajusta el ancho por los márgenes
                $textHeight = $nb * $this->FontSize * 1;
        
                // Calcula el nuevo Y para centrar el texto verticalmente
                $newY = $currentY + ($h - $textHeight) / 2;
        
                // Ajusta la posición y agrega el texto
                if ($key == 0 || $key == 2) {
                    $this->SetXY($currentX + $margen, $newY); // Ajusta la posición X por el margen
                    $this->MultiCell($w[$key] - 2 * $margen, 3, $value, 0, 'C'); // Ajusta el ancho por los márgenes
                } else {
                    $this->SetXY($currentX + $margen, $newY); // Ajusta la posición X por el margen
                    $this->MultiCell($w[$key] - 2 * $margen, 3, $value, 0, 'L'); // Ajusta el ancho por los márgenes
                }
        
                // Restaura la posición X para la próxima celda
                $this->SetXY($x + $w[$key], $y);
            }
        
            // Agrega celdas adicionales para DESP y VDO con margen
            $x = $this->GetX();
            $this->Rect($x, $y, $w[3], $h); // Celda para DESP
            $this->SetXY($x + $margen, $y + $h / 2 - $this->FontSize / 2); // Ajusta la posición Y para centrar el texto
            $this->Cell($w[3] - 2 * $margen, 3, '', 0, 0, 'C'); // Celda vacía con margen
            $this->SetXY($x + $w[3], $y); // Mueve la posición X para la próxima celda
        
            $x = $this->GetX();
            $this->Rect($x, $y, $w[4], $h); // Celda para VDO
            $this->SetXY($x + $margen, $y + $h / 2 - $this->FontSize / 2); // Ajusta la posición Y para centrar el texto
            $this->Cell($w[4] - 2 * $margen, 3, '', 0, 0, 'C'); // Celda vacía con margen
            $this->SetXY($x + $w[4], $y); // Mueve la posición X para la próxima celda
        
            // Ajusta la posición X para la próxima fila
            $this->Ln($h);
        }
        //-------------------------------------------------------------------------   
        $this->Ln(30);

        // Obtener los valores de 'nombreSolicitante' y 'numeroSolicitante' desde el POST
        $nombreSolicitante = utf8_encode($_POST['nombreSolicitante']);
        $numeroSolicitante = utf8_encode($_POST['numeroSolicitante']);

        $nombres = array(
            array($nombreSolicitante, "Nombre:                                          ", "Nombre:                                          "),
            array("C.C:" . $numeroSolicitante, "C.C.:                                            ", "C.C.:                                            "),
        );

        // Anchuras de las celdas para las firmas
        $w_firma = array(60, 60, 60); // Ancho para cada firma

        // Calculamos el ancho total para centrar
        $ancho_total_firmas = array_sum($w_firma);

        // Calculamos la posición X para centrar
        $posicion_x = ($this->GetPageWidth() - $ancho_total_firmas) / 2;

        // Tamaño y posición de las líneas personalizables arriba de los nombres
        $tamano_linea = 0.4; // Tamaño de las líneas
        $longitud_corte = 10; // Longitud del corte en los extremos de la línea
        $posicion_y_linea_arriba = $this->GetY() - 2; // Posición Y ajustable según necesidad

        // Dibujar las líneas arriba de los nombres
        $this->SetDrawColor(0); // Color de la línea: negro
        $this->SetLineWidth($tamano_linea); // Ancho de la línea

        // Dibujar las líneas para cada firma
        foreach ($w_firma as $key => $ancho) {
            $posicion_x_linea = $posicion_x + array_sum(array_slice($w_firma, 0, $key));
            $this->Line($posicion_x_linea + $longitud_corte / 2, $posicion_y_linea_arriba, $posicion_x_linea + $ancho - $longitud_corte / 2, $posicion_y_linea_arriba);
        }

        // Imprimir nombres con celdas y separaciones
        foreach ($nombres as $row) {
            // Centrar la fila
            $this->SetX($posicion_x);

            foreach ($row as $key => $value) {
                // Si el valor está vacío, imprime una celda vacía
                if ($value === '') {
                    $this->Cell($w_firma[$key], 3, '', 0, 0, 'C');
                } else {
                    $this->Cell($w_firma[$key], 2, utf8_decode($value), 0, 0, 'C');
                }
            }
            $this->Ln(4);
        }

        // Etiquetas y números debajo de los nombres
        $this->SetX($posicion_x); // Centrar
        $this->SetFont('Arial', 'I', 8);
        $this->Cell($w_firma[0], 5, 'Firma del Solicitante', 0, 0, 'C'); // Etiqueta para la primera firma
        $this->Cell($w_firma[1], 5, 'Firma Farmacia (Dispensa)', 0, 0, 'C'); // Etiqueta para la segunda firma
        $this->Cell($w_firma[2], 5, 'Firma Recibido (Servicio)', 0, 1, 'C'); // Etiqueta para la tercera firma

        $this->Ln(4); // Espacio entre etiquetas y números

        $this->SetX($posicion_x); // Centrar nuevamente
        $this->SetFont('Arial', '', 8);
        $this->Cell($w_firma[0], 5, '', 0, 0, 'C'); // Celda vacía para la primera firma
        $this->Cell($w_firma[1], 5, '', 0, 0, 'C'); // Celda vacía para la segunda firma

        $this->Ln(15); // Espacio después de las etiquetas y números

        // No se incluyen líneas adicionales para firmas abajo




        //-------------------------------------------------------------------------------
    }


    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}

// Verifica si la solicitud es POST para procesar los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = array(
        'fecha' => utf8_encode($_POST['fecha']),
        'hora' => utf8_encode($_POST['hora']),
        'paciente' => ($_POST['paciente']),
        'sexo' => utf8_encode($_POST['sexo']),
        'tipoDocumento' => utf8_encode($_POST['tipoDocumento']),
        'tipo' => utf8_encode($_POST['tipo']),
        'numeroDocumento' => utf8_encode($_POST['numeroDocumento']),
        'edad' => ($_POST['edad']),
        'cama' => utf8_encode($_POST['cama']),
        'servicio' => ($_POST['servicio']),
        'entidad' => ($_POST['entidad']),
        'nombreSolicitante' => utf8_encode($_POST['nombreSolicitante']),
        'numeroSolicitante' => utf8_encode($_POST['numeroSolicitante']),

        'medicamentos' => array()
    );

    // Recorre los datos POST para obtener los medicamentos
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'medicamento') === 0) {
            $num = str_replace('medicamento', '', $key);
            $data['medicamentos'][] = array(
                $num,
                utf8_encode($value),
                utf8_encode($_POST['cantidad' . $num])
            );
        }
    }

    // Genera el PDF
    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);
    $pdf->PrintForm($data); // Imprime el formulario con los datos procesados
    $pdf->Output('I', 'INSUMOS-' . utf8_decode($data['paciente']) . ' - Fecha ' . $data['fecha'] . ' Hora ' . $data['hora'] . '.pdf');
    require_once('insertar_datos.php');
}
