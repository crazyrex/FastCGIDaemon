<?php

namespace PHPFastCGI\FastCGIDaemon\Connection;

use PHPFastCGI\FastCGIDaemon\Exception\ConnectionException;

/**
 * The default implementation of the ConnectionInterface using stream socket
 * resources.
 */
class StreamSocketConnection implements ConnectionInterface
{
    /**
     * @var resource
     */
    private $socket;

    /**
     * @var bool
     */
    private $closed;

    /**
     * Constructor.
     *
     * @param resource $socket The stream socket to wrap
     */
    public function __construct($socket)
    {
        $this->socket = $socket;
        $this->closed = false;
    }

    /**
     * Creates a formatted exception from the last error that occurecd.
     *
     * @param string $function The function that failed
     *
     * @return ConnectionException
     */
    protected function createExceptionFromLastError($function)
    {
        $this->close();

        return new ConnectionException($function.' failed');
    }

    /**
     * {@inheritdoc}
     */
    public function read($length)
    {
        if ($this->isClosed()) {
            throw new ConnectionException('Connection has been closed');
        }

        if (0 === $length) {
            return '';
        }

        $buffer = @fread($this->socket, $length);

        if (empty($buffer)) {
            throw $this->createExceptionFromLastError('fread');
        }

        return $buffer;
    }

    /**
     * {@inheritdoc}
     */
    public function write($buffer)
    {
        if ($this->isClosed()) {
            throw new ConnectionException('Connection has been closed');
        }

        if (false == @fwrite($this->socket, $buffer)) {
            throw $this->createExceptionFromLastError('fwrite');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isClosed()
    {
        return $this->closed;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        if (!$this->isClosed()) {
            fclose($this->socket);

            $this->socket = null;
            $this->closed = true;
        }
    }
}
