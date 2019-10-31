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
     * @var string
     */
    private $htmlOptionStringData;

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
        $this->htmlOptionStringData = '';
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
     * @return string
     */
    public function getHtmlOptionStringData(): string
    {
        return $this->htmlOptionStringData;
    }

    /**
     * @param string $htmlOptionStringData
     *
     * @return $this
     */
    public function setHtmlOptionStringData(?string $htmlOptionStringData): AjaxResponse
    {
        $this->htmlOptionStringData = $htmlOptionStringData;

        return $this;
    }

    /**
     * @return false|string
     */
    public function getJsonEncodedResult()
    {
        if (is_array($this->getData()) && count($this->getData()) > 0) {
            /** @var array $data */
            foreach ($this->getData() as $data) {
                if (is_array($data) && array_key_exists('id', $data) && array_key_exists('text', $data)) {
                    $this->htmlOptionStringData .= sprintf('<option value="%s">%s</option>', (string) $data['id'], $data['text']);
                }
            }
            $pos = strpos($this->getHtmlOptionStringData(), '<option value="');
            if (false !== $pos) {
                $this->htmlOptionStringData = substr_replace($this->htmlOptionStringData, '<option selected="selected" value="', $pos, 15);
            }
        }
        $result = array(
            'code' => $this->getCode(),
            'error' => $this->getError(),
            'data' => $this->getData(),
            'htmlOptionStringData' => $this->getHtmlOptionStringData(),
        );

        return json_encode($result);
    }
}
