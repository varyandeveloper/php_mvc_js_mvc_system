<?php
/**
 * Created by PhpStorm.
 * User: VarYan
 * Date: 13.12.2015
 * Time: 14:38
 */

namespace engine\traits;
use engine\objects\Collection;

/**
 * Class OutputHelper
 * @package VS\MVC\Additional
 */
trait Output
{
    /**
     * json method
     * @param stdClass /Array $data
     * @param string $status
     * @param string $message
     * @return string
     * */
    protected function json($data, $status = 'success', $message = '')
    {
        if($data instanceof Collection)
            $data = $data->getItems();

        $data = $this->jsonIndent([
            'response' => $data,
            'status' => $status,
            'message' => $message
        ]);

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json;charset=utf-8');

        return $data;
    }

    /**
     * @param array $data
     */
    protected function xls($data)
    {
        // filename for download
        $filename = "table_excel_" . date('Ymd') . ".xls";

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.ms-excel");

        $flag = false;

        foreach ($data as $item) {
            if (!$flag) {
                echo join("\t", array_keys($item)) . "\r\n";
                $flag = true;
            }
            array_walk($item, [$this, "cleanXLS"]);
            echo implode("\t", array_values($item)) . "\r\n";
        }
    }

    /**
     * csv method
     * Will convert array to csv
     * @param array $array
     * @param string $filename
     * @return void
     * */
    protected static function csv($array = [], $filename = null)
    {
        if (is_null($filename))
            $filename = uniqid() . '.csv';

        if (!empty($array)) {
            // ensure proper file extension is used
            if (!substr(strrchr($filename, '.csv'), 1))
                $filename .= '.csv';

            try {
                // set the headers for file download
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-type: text/csv");
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename={$filename}");

                $output = @fopen('php://output', 'w');

                // used to determine header row
                $header_displayed = FALSE;

                foreach ($array as $row) {
                    if (!$header_displayed) {
                        // use the array keys as the header row
                        fputcsv($output, array_keys($row));
                        $header_displayed = TRUE;
                    }

                    // clean the data
                    $allowed = '/[^a-zA-Z0-9_ %\|\[\]\.\(\)%&-]/s';
                    foreach ($row as $key => $value) {
                        $row[$key] = preg_replace($allowed, '', $value);
                    }

                    // insert the data
                    fputcsv($output, $row);
                }

                fclose($output);

            } catch (\Exception $e) {
                exit($e->getMessage());
            }
        }
    }

    /**
     * xml method
     * @param array $data
     * @param string $infoTag
     * @param boolean $numbering
     * @return string
     * */
    public function xml($data, $infoTag = 'info', $numbering = true)
    {

        if($data instanceof Collection)
            $data = $data->asArray();

        $xml = new \DOMDocument();

        $this->createNode($xml, $data, null, $infoTag, $numbering);

        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-Type:text/xml;charset=utf-8');

        return $xml->saveXML();
    }

    /**
     * create_node method
     * @param \DOMDocument $xml
     * @param array $arr
     * @param null|string $node
     * @param string $infoTag
     * @param boolean $numbering
     * */
    private function createNode(\DOMDocument $xml, $arr, $node = null, $infoTag, $numbering)
    {

        if (is_null($node)) {
            $node = $xml->appendChild($xml->createElement($infoTag));
        }
        $i = 0;
        foreach ($arr as $element => $value) {
            if (is_object($value))
                $value = get_object_vars($value);

            $element = is_numeric($element) ? (($numbering) ? 'node-' . $i : 'node') : $element;

            $child = $xml->createElement($element, ((is_array($value)) ? null : $value));
            $node->appendChild($child);

            if (is_array($value))
                $this->createNode($xml, $value, $child, $infoTag, $numbering);

            $i++;
        }
    }

    /**
     * jsonIndent method
     * Will create beautiful json data
     * @param array $array
     * @return array
     * */
    private function jsonIndent($array)
    {
        // make sure array is provided
        if (empty($array))
            return NULL;

        //Encode the string
        $json = json_encode($array);

        $result = '';
        $pos = 0;
        $str_len = strlen($json);
        $indent_str = '  ';
        $new_line = "\n";
        $prev_char = '';
        $out_of_quotes = true;

        for ($i = 0; $i <= $str_len; $i++) {
            // grab the next character in the string
            $char = substr($json, $i, 1);

            // are we inside a quoted string?
            if ($char == '"' && $prev_char != '\\') {
                $out_of_quotes = !$out_of_quotes;
            } // if this character is the end of an element, output a new line and indent the next line
            elseif (($char == '}' OR $char == ']') && $out_of_quotes) {
                $result .= $new_line;
                $pos--;

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indent_str;
                }
            }

            // add the character to the result string
            $result .= $char;

            // if the last character was the beginning of an element, output a new line and indent the next line
            if (($char == ',' OR $char == '{' OR $char == '[') && $out_of_quotes) {
                $result .= $new_line;

                if ($char == '{' OR $char == '[') {
                    $pos++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indent_str;
                }
            }

            $prev_char = $char;
        }

        // return result
        return $result . $new_line;
    }

    /**
     * @param string $str
     */
    private function cleanXLS(&$str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    }
}