<?php

namespace app\common\library;

/**
 * Class Aes
 * @package app\common\library
 */
class Aes
{

    private $key;
    private $iv;
    private $blockSize;

    public function __construct()
    {
        // 需要小伙伴在配置文件app.php中定义aeskey
        $this->key       = config('aes.key');
        $this->iv        = config('aes.iv');
        $this->blockSize = config('aes.block_size');
    }

    public function encrypt($input)
    {
        $padding = $this->addpadding($input);
        $data    = openssl_encrypt($padding, 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $this->hexToStr($this->iv));
        $data    = base64_encode($data);
        return $data;
    }

    public function decrypt($input)
    {
        $padding = openssl_decrypt(base64_decode($input), 'AES-256-CBC', $this->key, OPENSSL_RAW_DATA, $this->hexToStr($this->iv));
        $data    = $this->strippadding($padding);
        return $data;
    }

    /**
     * 对需要加密的明文进行填充补位
     * @param $text string 需要进行填充补位操作的明文
     * @return string 补齐明文字符串
     */
    private function addpadding($text)
    {
        $text_length = strlen($text);
        //计算需要填充的位数
        $amount_to_pad = $this->blockSize - ($text_length % $this->blockSize);
        if ($amount_to_pad == 0) {
            $amount_to_pad = $this->blockSize;
        }
        //获得补位所用的字符
        $pad_chr = chr($amount_to_pad);
        $tmp     = "";
        for ($index = 0; $index < $amount_to_pad; $index++) {
            $tmp .= $pad_chr;
        }
        return $text . $tmp;
    }

    /**
     * 对解密后的明文进行补位删除
     * @param $text string 解密后的明文
     * @return string 删除填充补位后的明文
     */
    private function strippadding($text)
    {
        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > $this->blockSize) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }

    /**
     * @example $str = openssl_random_pseudo_bytes(16);$hex = $this->strToHex($str);
     * @param $x
     * @return string
     */
    private function strToHex($x)
    {
        $s = '';
        foreach (str_split($x) as $c) $s .= sprintf("%02X", ord($c));
        return ($s);
    }

    private function hexToStr($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }
}