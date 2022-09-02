<?php

class Common
{
    public static $excel_title = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");

    function __construct()
    {
        date_default_timezone_set("Asia/Seoul");
    }

    public static function getServerPath($path)
    {
        $real_path = SERVER_PATH . $path;

        return $real_path;
    }

    public static function getFilePath($strType = "", $id = 0, $filename = "")
    {
        $path = "upload";
        if (!file_exists(Common::getServerPath($path))) {
            mkdir(Common::getServerPath($path), 0777, true);
        }

        if ($strType != "") {
            $path .= "/" . $strType;

            if (!file_exists(Common::getServerPath($path))) {
                mkdir(Common::getServerPath($path), 0777, true);
            }
        }

        if ($id != 0) {
            $path .= "/" . $id;

            if (!file_exists(Common::getServerPath($path))) {
                mkdir(Common::getServerPath($path), 0777, true);
            }
        }

        if ($filename != "") {
            $path .= "/" . $filename;
        }

        return $path;
    }

    public static function getFileExtension($path)
    {
        $arr = preg_split("/\./", basename($path));
        $len = count($arr);
        if ($len <= 1)
            return "";
        else
            return $arr[$len - 1];
    }

    public static function fileExists($file_path)
    {
        if (is_file($file_path) && file_exists($file_path)) {
            return true;
        }

        return false;
    }

    public static function dumpExcelFile($title, $data, $path)
    {
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Administrator")
            ->setLastModifiedBy("Administrator")
            ->setTitle(basename($path));

        for ($idx = 0; $idx < count($title); $idx++) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue(Common::$excel_title[$idx] . "1", $title[$idx]);
        }

        for ($idx = 0; $idx < count($data); $idx++) {
            for ($jdx = 0; $jdx < count($title); $jdx++) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue(Common::$excel_title[$jdx] . ($idx + 2), $data[$idx][$jdx]);
            }
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $objWriter->save($path);
    }

    public static function dumpMultiExcelFile($excel_data, $path)
    {
        $objPHPExcel = new PHPExcel();

        $objPHPExcel->getProperties()->setCreator("Administrator")
            ->setLastModifiedBy("Administrator")
            ->setTitle(basename($path));

        $sheet = 0;
        foreach ($excel_data as $data) {
            $objPHPExcel->setActiveSheetIndex($sheet);

            for ($idx = 0; $idx < count($data["header"]); $idx++) {
                $objPHPExcel->getActiveSheet()->setCellValue(Common::$excel_title[$idx] . "1", $data["header"][$idx]);
            }

            for ($idx = 0; $idx < count($data["data"]); $idx++) {
                for ($jdx = 0; $jdx < count($data["header"]); $jdx++) {
                    $objPHPExcel->getActiveSheet()->setCellValue(Common::$excel_title[$jdx] . ($idx + 2), $data["data"][$idx][$jdx]);
                }
            }

            $title = ($data["title"] == null || $data["title"] == "") ? $idx : $data["title"];
            $objPHPExcel->getActiveSheet()->setTitle($title);

            $objPHPExcel->createSheet();
            $sheet++;
        }

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $objWriter->save($path);
    }

    public static function getBannerPage($index)
    {
        if ($index < 4) {
            return 'Home';
        } elseif ($index < 7) {
            return 'Chatting';
        } else {
            return 'BunYang';
        }
    }

    public static function getDefaultImage()
    {
        return base_url('assets/images/img_photo_default.png');
    }
}