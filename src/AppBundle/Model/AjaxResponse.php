<?php

namespace AppBundle\Model;

/**
 * Class AjaxResponse.
 *
 * @category Model
 */
class AjaxResponse
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $error;

    /**
     * @var array
     */
    private $data;

    /**
     * Methods.
     */

    /**
     * AjaxResponse constructor.
     */
    public function __construct()
    {
        $this->code = 0;
        $this->error = '---';
        $this->data = [];
    }

    /**
     * @return int
     */
    public function getCode(): ?int
    {
        return $this->code;
    }

    /**
     * @param int $code
     *
     * @return $this
     */
    public function setCode(?int $code): AjaxResponse
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string $error
     *
     * @return $this
     */
    public function setError(?string $error): AjaxResponse
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(?array $data): AjaxResponse
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return false|string
     */
    public function getJsonEncodedResult()
    {
        $result = array(
            'code' => $this->getCode(),
            'error' => $this->getError(),
            'data' => $this->getData(),
        );

        return json_encode($result);
    }
}
