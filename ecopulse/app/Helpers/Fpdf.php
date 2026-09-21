<?php
/**
 * FPDF — Simple PDF Generator Class for PHP
 */
class Fpdf {
    protected $page = 0;
    protected $n = 2;
    protected $offsets = [];
    protected $buffer = '';
    protected $pages = [];
    protected $state = 0;
    protected $compress = false;
    protected $k = 72 / 25.4;
    protected $w = 210.0;
    protected $h = 297.0;
    protected $wPt;
    protected $hPt;
    protected $lMargin = 10.0;
    protected $tMargin = 10.0;
    protected $rMargin = 10.0;
    protected $bMargin = 10.0;
    protected $x = 10.0;
    protected $y = 10.0;
    protected $lasth = 0;
    protected $LineWidth = 0.2;
    protected $fontFamily = 'helvetica';
    protected $fontStyle = '';
    protected $fontSizePt = 12;
    protected $fontSize = 12;
    protected $DrawColor = '0 G';
    protected $FillColor = '0 g';
    protected $TextColor = '0 g';
    protected $ColorFlag = false;
    protected $AutoPageBreak = true;
    protected $bMarginThresh = 20.0;

    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4') {
        $this->wPt = $this->w * $this->k;
        $this->hPt = $this->h * $this->k;
        $this->SetMargins(15, 15, 15);
        $this->SetAutoPageBreak(true, 15);
    }

    public function SetMargins($left, $top, $right = null) {
        $this->lMargin = $left;
        $this->tMargin = $top;
        $this->rMargin = ($right === null) ? $left : $right;
    }

    public function SetAutoPageBreak($auto, $margin = 0) {
        $this->AutoPageBreak = $auto;
        $this->bMarginThresh = $margin;
    }

    public function AddPage($orientation = '') {
        if ($this->state == 0) {
            $this->state = 1;
        }
        $this->page++;
        $this->pages[$this->page] = '';
        $this->x = $this->lMargin;
        $this->y = $this->tMargin;
        $this->SetFont($this->fontFamily, $this->fontStyle, $this->fontSizePt);
    }

    public function SetFont($family, $style = '', $size = 0) {
        $family = strtolower($family);
        if ($family == '' || $family == 'inter') $family = 'helvetica';
        $style = strtoupper($style);
        if (strpos($style, 'U') !== false) {
            $style = str_replace('U', '', $style);
        }
        if ($family == 'arial') $family = 'helvetica';
        $this->fontFamily = $family;
        $this->fontStyle = $style;
        if ($size > 0) {
            $this->fontSizePt = $size;
            $this->fontSize = $size / $this->k;
        }
        if ($this->page > 0) {
            $fontName = 'F1';
            if ($style == 'B') $fontName = 'F2';
            elseif ($style == 'I') $fontName = 'F3';
            elseif ($style == 'BI' || $style == 'IB') $fontName = 'F4';
            $this->_out(sprintf('BT /%s %.2F Tf ET', $fontName, $this->fontSizePt));
        }
    }

    public function SetDrawColor($r, $g = null, $b = null) {
        if (($r == 0 && $g == 0 && $b == 0) || $g === null) {
            $this->DrawColor = sprintf('%.3F G', $r / 255);
        } else {
            $this->DrawColor = sprintf('%.3F %.3F %.3F RG', $r / 255, $g / 255, $b / 255);
        }
        if ($this->page > 0) $this->_out($this->DrawColor);
    }

    public function SetFillColor($r, $g = null, $b = null) {
        if (($r == 0 && $g == 0 && $b == 0) || $g === null) {
            $this->FillColor = sprintf('%.3F g', $r / 255);
        } else {
            $this->FillColor = sprintf('%.3F %.3F %.3F rg', $r / 255, $g / 255, $b / 255);
        }
        $this->ColorFlag = true;
        if ($this->page > 0) $this->_out($this->FillColor);
    }

    public function SetTextColor($r, $g = null, $b = null) {
        if (($r == 0 && $g == 0 && $b == 0) || $g === null) {
            $this->TextColor = sprintf('%.3F g', $r / 255);
        } else {
            $this->TextColor = sprintf('%.3F %.3F %.3F rg', $r / 255, $g / 255, $b / 255);
        }
        $this->ColorFlag = true;
    }

    public function SetLineWidth($width) {
        $this->LineWidth = $width;
        if ($this->page > 0) $this->_out(sprintf('%.2F w', $width * $this->k));
    }

    public function Line($x1, $y1, $x2, $y2) {
        $this->_out(sprintf('%.2F %.2F m %.2F %.2F l S', $x1 * $this->k, ($this->h - $y1) * $this->k, $x2 * $this->k, ($this->h - $y2) * $this->k));
    }

    public function Rect($x, $y, $w, $h, $style = '') {
        $op = 'S';
        if ($style == 'F') $op = 'f';
        elseif ($style == 'FD' || $style == 'DF') $op = 'B';
        $this->_out(sprintf('%.2F %.2F %.2F %.2F re %s', $x * $this->k, ($this->h - $y - $h) * $this->k, $w * $this->k, $h * $this->k, $op));
    }

    public function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $this->Rect($x, $y, $w, $h, $style);
    }

    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false) {
        $k = $this->k;
        if ($this->y + $h > $this->h - $this->bMarginThresh && $this->AutoPageBreak) {
            $x = $this->x;
            $this->AddPage();
            $this->x = $x;
        }
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $s = '';
        if ($fill || $border == 1) {
            $op = ($fill && $border == 1) ? 'B' : ($fill ? 'f' : 'S');
            $s .= sprintf('%.2F %.2F %.2F %.2F re %s ', $this->x * $k, ($this->h - $this->y - $h) * $k, $w * $k, $h * $k, $op);
        }
        if (is_string($border)) {
            $x = $this->x * $k;
            $y = ($this->h - $this->y) * $k;
            if (strpos($border, 'L') !== false) $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x, $y, $x, $y - $h * $k);
            if (strpos($border, 'T') !== false) $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x, $y, $x + $w * $k, $y);
            if (strpos($border, 'R') !== false) $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x + $w * $k, $y, $x + $w * $k, $y - $h * $k);
            if (strpos($border, 'B') !== false) $s .= sprintf('%.2F %.2F m %.2F %.2F l S ', $x, $y - $h * $k, $x + $w * $k, $y - $h * $k);
        }
        if ($txt !== '') {
            $txt = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', (string)$txt);
            $txt = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $txt);
            $width = strlen($txt) * ($this->fontSizePt * 0.52) / $k;
            $dx = 1.5;
            if ($align == 'R') $dx = $w - $width - 2.0;
            elseif ($align == 'C') $dx = ($w - $width) / 2.0;
            $s .= sprintf('q %s BT %.2F %.2F Td (%s) Tj ET Q ', $this->TextColor, ($this->x + max(0, $dx)) * $k, ($this->h - ($this->y + 0.5 * $h + 0.3 * $this->fontSize)) * $k, $txt);
        }
        if ($s) $this->_out($s);
        $this->lasth = $h;
        if ($ln > 0) {
            $this->y += $h;
            if ($ln == 1) $this->x = $this->lMargin;
        } else {
            $this->x += $w;
        }
    }

    public function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false) {
        $lines = explode("\n", wordwrap($txt, (int)($w * 1.8), "\n"));
        foreach ($lines as $line) {
            $this->Cell($w, $h, $line, $border, 1, $align, $fill);
        }
    }

    public function Ln($h = null) {
        $this->x = $this->lMargin;
        $this->y += ($h === null) ? $this->lasth : $h;
    }

    public function GetX() { return $this->x; }
    public function SetX($x) { $this->x = ($x >= 0) ? $x : $this->w + $x; }
    public function GetY() { return $this->y; }
    public function SetY($y) { $this->y = ($y >= 0) ? $y : $this->h + $y; }
    public function SetXY($x, $y) { $this->SetY($y); $this->SetX($x); }

    protected function _out($s) {
        if ($this->state == 2) {
            $this->pages[$this->page] .= $s . "\n";
        } elseif ($this->state == 1) {
            $this->pages[$this->page] .= $s . "\n";
        } else {
            $this->buffer .= $s . "\n";
        }
    }

    public function Output($dest = '', $name = '') {
        if ($this->state < 3) {
            $this->state = 3;
            // Build PDF body
            $this->buffer = "%PDF-1.4\n";
            $this->offsets[1] = strlen($this->buffer);
            $this->buffer .= "1 0 obj\n<</Type /Catalog /Pages 2 0 R>>\nendobj\n";

            $kids = '';
            for ($i = 1; $i <= $this->page; $i++) {
                $kids .= (2 + $i) . " 0 R ";
            }
            $this->offsets[2] = strlen($this->buffer);
            $this->buffer .= "2 0 obj\n<</Type /Pages /Kids [" . trim($kids) . "] /Count " . $this->page . ">>\nendobj\n";

            for ($i = 1; $i <= $this->page; $i++) {
                $contentObj = 2 + $this->page + $i;
                $pageObj = 2 + $i;

                $this->offsets[$pageObj] = strlen($this->buffer);
                $this->buffer .= $pageObj . " 0 obj\n<</Type /Page /Parent 2 0 R /MediaBox [0 0 " . sprintf('%.2F %.2F', $this->wPt, $this->hPt) . "] /Resources <</Font <</F1 500 0 R /F2 501 0 R /F3 502 0 R /F4 503 0 R>>>> /Contents " . $contentObj . " 0 R>>\nendobj\n";

                $content = $this->pages[$i];
                $this->offsets[$contentObj] = strlen($this->buffer);
                $this->buffer .= $contentObj . " 0 obj\n<</Length " . strlen($content) . ">>\nstream\n" . $content . "endstream\nendobj\n";
            }

            // Fonts
            $fonts = [
                500 => ['Helvetica', 'Standard'],
                501 => ['Helvetica-Bold', 'Standard'],
                502 => ['Helvetica-Oblique', 'Standard'],
                503 => ['Helvetica-BoldOblique', 'Standard']
            ];
            foreach ($fonts as $oid => $finfo) {
                $this->offsets[$oid] = strlen($this->buffer);
                $this->buffer .= $oid . " 0 obj\n<</Type /Font /Subtype /Type1 /BaseFont /" . $finfo[0] . " /Encoding /WinAnsiEncoding>>\nendobj\n";
            }

            $xref = strlen($this->buffer);
            $maxObj = 503;
            $this->buffer .= "xref\n0 " . ($maxObj + 1) . "\n0000000000 65535 f \n";
            for ($i = 1; $i <= $maxObj; $i++) {
                if (isset($this->offsets[$i])) {
                    $this->buffer .= sprintf("%010d 00000 n \n", $this->offsets[$i]);
                } else {
                    $this->buffer .= "0000000000 00000 f \n";
                }
            }
            $this->buffer .= "trailer\n<</Size " . ($maxObj + 1) . " /Root 1 0 R>>\nstartxref\n" . $xref . "\n%%EOF\n";
        }

        if ($dest == 'S') {
            return $this->buffer;
        } elseif ($dest == 'F' && $name != '') {
            return file_put_contents($name, $this->buffer) !== false;
        } else {
            return $this->buffer;
        }
    }
}
