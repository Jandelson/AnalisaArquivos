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
    private static $separador;

    public static function listaArquivos($arquivos, $dir = '', $separador = "\n")
    {
        try {
            self::setDir($dir);
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

    private static function setSeparador($separador)
    {
        self::$separador = $separador;

        if (empty(self::$separador)) {
            self::$separador = "\n";
        }
    }

    private static function encontrarArquivos(): void
    {
        self::$listaArquivos = scandir(self::$dir);

        foreach (self::$listaArquivos as $key => $value) {
            if (!in_array($value, [".", ".."])) {
                foreach (self::$listaArquivosEntrada as $key_ => $value_) {
                    if ($value_ == $value) {
                        self::$arquivosEncontrados[] = $value;
                        self::$arquivosEncontradosDisplay[] = $value . '     Data: ' .
                            date(
                                "d/m/Y H:i:s",
                                filemtime(self::$dir . '/' . $value)
                            );
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
            'TotalArquivosEncontrados' => $TotalArquivosEncontrados,
            'TotalArquivosNaoEncontrados' => $TotalArquivosNaoEncontrados,
            'ArquivosNaoEncontrados' => self::$arquivosNaoEncontrados,
            'ArquivosEncontradosDisplay' => self::$arquivosEncontradosDisplay,
            'ArquivosEncontrados' => self::$arquivosEncontrados
        ];
    }
}
