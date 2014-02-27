<?php

/**
 * Class PostRequestCmd
 * Used to perform a http post request to the given url with the given body string
 */
final class PostRequestCmd
{
    private $cUrl;
    private $cBody = "";
    private $cHttpUserAgent = "";
    private $cHeader;

    /**
     * @param string $file
     * @throws InvalidArgumentException
     * @return PostRequestCmd
     */
    public function __construct($file)
    {
        $this->cUrl = $file;
        // check if this is a real http/https url
        if (filter_var($this->cUrl, FILTER_VALIDATE_URL) === false || strpos($this->cUrl, "http") === false)
        {
            throw new InvalidArgumentException("Argument \"url\" is not valid!");
        }
        return $this;
    }

    /**
     * @param string $string
     */
    public function setBody($string)
    {
        $this->cBody = $string;
    }

    /**
     * @param string $string
     */
    public function setHttpUserAgent($string)
    {
        $this->cHttpUserAgent = $string;
    }

    protected function buildHeader()
    {
        $this->cHeader  = "";
        $this->cHeader .= "User-Agent: ".$this->cHttpUserAgent."\r\n";
        $this->cHeader .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $this->cHeader .= "Content-Length: ".strlen($this->cBody)."\r\n";
        $this->cHeader .= "Connection: Close\r\n\r\n";
        #$this->cHeader .= "{$this->cBody}\r\n";
    }

    /**
     * @return string|bool
     */
    public function doCommand()
    {
        $this->buildHeader();

        $opts = Array(
       		'http' => Array(
       			'method' => "POST",
       			'header' => $this->cHeader,
       			'content' => $this->cBody
       		)
       	);

       	$context = stream_context_create($opts);
       	return file_get_contents($this->cUrl, false, $context);
    }
}
