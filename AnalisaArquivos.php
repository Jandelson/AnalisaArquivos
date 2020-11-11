<?php

namespace Jandelson;

class AnalisaArquivos
{
    private static $listaArquivosEntrada;
    private static $listaArquivosProcurar;
    private static $listaArquivos;
    private static $arquivosEncontrados = [];
    private static $arquivosEncontradosDisplay = [];
    private static $arquivosNaoEncontrados = [];
    private static $dir;
    private static $arquivos;
    private static $separador;

    public static function listaArquivos($arquivos, $dir = '', $separador = "\n")
    {
        try {
            if (!is_dir($dir)) {
                return [
                    'error' => 'Diretório não encontrado'
                ];
                exit;
            }
            self::setDir($dir);
            self::setArquivos($arquivos);
            self::setSeparador($separador);
            self::$listaArquivosEntrada = explode(self::$separador, $arquivos);
            self::encontrarArquivos();
            return self::dadosArquivos();
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    private static function setDir($dir)
    {
        self::$dir = $dir;
    }

    private static function setArquivos($arquivos)
    {
        self::$arquivos = $arquivos;
    }

    private static function setSeparador($separador)
    {
        self::$separador = $separador;

        if (empty(self::$separador)) {
            self::$separador = "\n";
        }
    }

    private static function encontrarArquivos(): void
    {
        $diretorios = new \RecursiveDirectoryIterator(self::$dir);
        $iterator = new \RecursiveIteratorIterator($diretorios);
        $diretorios = [];
        foreach ($iterator as $info) {
            $diretorios[] = $info->getPath();
        }
        $diretorios = array_unique($diretorios);
        foreach ($diretorios as $dir) {
            self::$listaArquivos = scandir($dir);
            foreach (self::$listaArquivos as $key => $value) {
                if (!in_array($value, [".", ".."])) {
                    foreach (self::$listaArquivosEntrada as $key_ => $value_) {
                        $x = (strrpos($value, $value_));
                        if ($x !== false) {
                            self::$arquivosEncontrados[] = $value;
                            self::$arquivosEncontradosDisplay[] = [
                                'diretorio' => $dir,
                                'nome' => $value,
                                'data' =>
                                    date(
                                        "d/m/Y H:i:s",
                                        filemtime($dir . '/' . $value)
                                    ),
                                'link' => $dir . '/' . $value
                            ];
                        }
                    }
                }
            }
        }
    }

    private function dadosArquivos(): array
    {
        $TotalArquivosEncontrados = 0;
        $TotalArquivosNaoEncontrados = 0;

        $TotalArquivosEncontrados = count(self::$arquivosEncontradosDisplay);
        /**
         * Arquivos não encontrados
         */
        self::$arquivosNaoEncontrados[] = array_diff(self::$listaArquivosEntrada, self::$arquivosEncontrados);
        self::$arquivosNaoEncontrados = implode(self::$separador, self::$arquivosNaoEncontrados[0]);
        self::$arquivosNaoEncontrados = explode(self::$separador, self::$arquivosNaoEncontrados);

        $TotalArquivosNaoEncontrados = count(self::$arquivosNaoEncontrados);

        return [
            'dir' => self::$dir,
            'arquivos' => self::$arquivos,
            'TotalArquivosEncontrados' => $TotalArquivosEncontrados,
            'TotalArquivosNaoEncontrados' => $TotalArquivosNaoEncontrados,
            'ArquivosNaoEncontrados' => self::$arquivosNaoEncontrados,
            'ArquivosEncontradosDisplay' => self::$arquivosEncontradosDisplay,
            'ArquivosEncontrados' => self::$arquivosEncontrados
        ];
    }
}
