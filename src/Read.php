<?php

namespace CkTcp;

class Read
{
    /**
     * @var resource
     */
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getChar($n = 1)
    {
        $buffer = fread($this->conn, $n);
        if ($buffer === false || !isset($buffer[0])) {
            throw new CkException('read from fail', CkException::CODE_READ_FAIL);
        }
        if (strlen($buffer) < $n) {
            $buffer .= $this->getChar($n - strlen($buffer));
        }
        return $buffer;
    }

    /**
     * @return int
     * @throws CkException
     */
    public function number()
    {
        $r = 0;
        $b = 0;
        while (1) {
            $j = ord($this->getChar());
            $r = (($j & 127) << ($b * 7)) | $r;
            if ($j < 128) {
                return $r;
            }
            $b++;
        }
    }

    public function echo_str($n = 50)
    {
        $s = $this->getChar($n);
        echo "--- start ---\n";
        echo 'total len ' . strlen($s) . PHP_EOL;
        echo $s . PHP_EOL;
        for ($i = 0; $i < strlen($s); $i++) {
            echo $i . '=> ' . $s[$i] . '=>' . ord($s[$i]) . PHP_EOL;
        }
        echo PHP_EOL;
        echo "--- end ---\n";
    }

    /**
     * @return int
     * @throws CkException
     */
    public function int()
    {
        return unpack('l', $this->getChar(4))[1];
    }

    /**
     * @return string
     * @throws CkException
     */
    public function string()
    {
        $n = $this->number();
        return $n === 0 ? '' : $this->getChar($n);
    }
}