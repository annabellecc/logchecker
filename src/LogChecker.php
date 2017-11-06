<?php

namespace Annabelle\LogChecker;


class LogChecker {
    protected $configs;

    public function __construct()
    {
        $this->configs = $this->getAppConfig();
    }

    public function index()
    {
        if (isset($_GET['path'])) {
            if (isset($_GET['empty']) && $_GET['empty'] == 1) {
                $this->emptyFile($_GET['path']);
                header('Location: ' . '//' . $_SERVER['HTTP_HOST'] . '/' . $this->configs['url'] . '?path=' . $_GET['path']);
            }
            $this->render($this->getViewPath() . '/view/index.html', $this->configs);
        } else {
            $this->render($this->getViewPath() . '/view/error.html', $this->configs);
        }

    }

    public function getBasepath()
    {
        return dirname(dirname(dirname(dirname(dirname(__FILE__)))));
    }

    public function getViewPath()
    {
        return (dirname(dirname(__FILE__))) . '/resources';
    }

    public function getAppConfig()
    {
        return require($this->getBasepath() . '/config/logchecker.php');
    }

    public function getFile()
    {
        $file_path = $_GET['path'] ?? '';

        if (!$file_path) {
            throw new \Exception('');
        }

        if (!file_exists($file_path)) {

            throw new \Exception('文件不存在');
        }

        return $file_path;
    }

    public function emptyFile($filepath)
    {
        $write_handler = fopen($filepath, 'w');
        fwrite($write_handler, '');
        fclose($write_handler);
    }


    public function getContent($file_path)
    {
        $file = fopen($file_path, 'r');
        $rows = [];
        while (!feof($file)) {
            $buffer = fgets($file);
            if (preg_match('/^\[.{19}\] /', $buffer, $matches)) {
                $rows[] = $buffer;
            } else {
                if ($rows) {
                    $rows[max(array_keys($rows))] .= $buffer;
                }
            }
        }

        $contents = [];
        foreach ($rows as $k => $row) {
            preg_match_all("/(.*)\n/", $row, $ret);
            $content = '';
            $header  = $ret[1][0];
            if (count($ret[1]) > 1) {
                array_shift($ret[1]);
                $content = $ret[1];
            } else {
                preg_match_all('/(\[[\d-:\s]{19}\])(.*(?:DEBUG|INFO|NOTICE|WARNING|ERROR|CRITICAL|ALERT|EMERGENCY))([^\[{]*)\s(.*)/', $header, $matches);
                if (isset($matches[1][0]) && isset($matches[2][0]) && isset($matches[3][0]) && isset($matches[4][0])) {
                    $header  = $matches[1][0] . $matches[2][0] . $matches[3][0];
                    $content = json_decode($matches[4][0], true);
                }
            }
            $contents[] = [
                'header'  => $header,
                'content' => $content,
            ];
        }

        return $contents;
    }


    public function render($template, $variables)
    {
        $variables['base_url']    = $_SERVER['HTTP_HOST'] . '/' . $variables['url'];
        $variables['current_url'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        try {
            if ($this->getFile()) {
                $variables['contents'] = $this->getContent($this->getFile());
            }
        } catch (\Exception $e) {
            $variables['error'] = $e->getMessage();
            $template           = $this->getViewPath() . '/view/error.html';
        }
        call_user_func(function () {
            extract(func_get_arg(1));
            require func_get_arg(0);
        }, $template, $variables);
    }

}


