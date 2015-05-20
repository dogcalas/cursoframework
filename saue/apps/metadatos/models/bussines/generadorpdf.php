<?php


define('FPDF_FONTPATH', 'font/');


class generadorpdf extends fpdf
{
    //Columna actual
    var $col = 0;
    var $y;

    function Header()
    {
        $this->SetFont('Arial', 'B', 15);
        $w = $this->GetStringWidth($this->title) + 6;
        $this->SetX((210 - $w) / 2);
        $this->Cell($w, 9, $this->title, 0, 0, 'C');
        $this->Ln(10);
        //Guardar ordenada
        $this->y0 = $this->GetY();
    }

    function Footer()
    {
        //Pie de página
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }

    function crearTabla($titulo, $cabeceras, $datos, $nuevapagina = false, $fuente, $alto)
    {
        if ($nuevapagina)
            $this->AddPage();

        $tamano = 10;
        $tipo = 'Arial';
        $estilo = '';
        if (isset($fuente['estilo']))
            $estilo = $fuente['estilo'];
        if (isset($fuente['tipo']))
            $tipo = $fuente['tipo'];
        if (isset($fuente['tamano']))
            $tamano = $fuente['tamano'];

        $this->SetFont($tipo, 'B', $tamano);

        $this->Cell($a, 6, $titulo);
        $this->Ln();
        $a = array();
        foreach ($cabeceras as $otro => $config) {
            $a[] = $this->GetStringWidth($otro) + 6;
            $b[] = $otro;
            $this->Cell($this->GetStringWidth($otro) + 6, $alto, $otro, 1, 0, 'C', 0);
        }
        $this->Ln();
        $this->SetFont($tipo, $estilo, $tamano);


        foreach ($datos as $fil) {
            $i = 0;
            foreach ($fil as $col) {
                $this->Cell($a[$i++], 6, $col, 1);
            }
            $this->Ln();
        }


    }

}

?>
