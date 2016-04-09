<?php

namespace PHPReportz;

/**
 * Class PHPReportz
 * @package PHPReportz
 */
class PHPReportz
{
    /**
     * Options to output file type selection
     */
    const OUTPUT_DOCX = 1;
    const OUTPUT_PDF = 2;

    /**
     * Options to get download URL as JSON or direct download generated report
     */
    const ACTION_GET_DOWNLOAD_URL = 1;
    const ACTION_FORCE_DOWNLOAD = 2;

    protected $api_key;
    protected $template_id;
    protected $parameters;
    protected $output_file_type;
    private $json;

    /**
     * PHPReportz constructor.
     * @param $api_key
     */
    public function __construct($api_key)
    {
        if (empty($api_key) || strlen($api_key) != 24) {
            throw new \Exception("Invalid API key");
        }

        $this->api_key = $api_key;
    }

    /**
     * @return mixed
     */
    private function getApiKey()
    {
        if (empty($this->api_key)) {
            throw new \Exception("Invalid API key");
        }

        return $this->api_key;
    }

    /**
     * @param mixed $template_id
     */
    public function setTemplateId($template_id)
    {
        if (empty($template_id) || !is_int($template_id)) {
            throw new \Exception("Invalid Template ID");
        }

        $this->template_id = $template_id;
    }

    /**
     * @return mixed
     */
    private function getTemplateİd()
    {
        if (empty($this->template_id)) {
            throw new \Exception("Invalid Template ID");
        }

        return $this->template_id;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters)
    {
        if (empty($parameters) || !is_array($parameters)) {
            throw new \Exception("Parameters must be an array");
        }

        $this->parameters = $parameters;
    }

    /**
     * @return mixed
     */
    private function getParameters()
    {
        if (empty($this->parameters)) {
            throw new \Exception("Parameters must be an array");
        }

        return $this->parameters;
    }

    /**
     * @param mixed $template_id
     */
    public function setOutputFileType($type = self::OUTPUT_PDF)
    {
        if ($type != self::OUTPUT_PDF && $type != self::OUTPUT_DOCX) {
            throw new \Exception("Invalid output file type specified");
        }

        $this->output_file_type = $type;
    }

    /**
     * @return mixed
     */
    private function getOutputFileType()
    {
        if ($this->output_file_type != self::OUTPUT_PDF && $this->output_file_type != self::OUTPUT_DOCX) {
            $this->output_file_type = self::OUTPUT_PDF;
        }

        return $this->output_file_type;
    }

    /**
     * @param int $action
     * @throws \Exception
     */
    public function generateReport($action = self::ACTION_FORCE_DOWNLOAD)
    {
        $this->json = array(
            'api_key'     => $this->getApiKey(),
            'template_id' => $this->getTemplateİd(),
            'parameters'  => $this->getParameters(),
            'output_file_type' => $this->getOutputFileType(),
            'action' => ($action != self::ACTION_FORCE_DOWNLOAD && $action != self::ACTION_GET_DOWNLOAD_URL) ? self::ACTION_FORCE_DOWNLOAD : $action
        );

        $this->json = json_encode($this->json);

        $ch = curl_init('http://127.0.0.1:8000/api');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "json={$this->json}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        echo $response;
    }
}